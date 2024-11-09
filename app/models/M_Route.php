<?php
// app/models/M_Route.php
class M_Route {
    private $db;

    // Class constants for factory location
    const FACTORY_LAT = 6.2173037;
    const FACTORY_LONG = 80.2538636;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllRoutes() {
        $this->db->query("SELECT * FROM routes");
        return $this->db->resultset();
    }

    public function getTotalRoutes() {
        $sql = "SELECT COUNT(*) as total FROM routes WHERE is_deleted = 0";
        $stmt = $this->db->query($sql);
        if ($stmt) {
            $count = $stmt->fetchColumn();
            error_log("Total routes count: " . $count);
            return (int)$count;
        }
        error_log("Error getting total routes: " . print_r($this->db->errorInfo(), true));
        return 0;
    }
    
    public function getTotalActiveRoutes() {
        $sql = "SELECT COUNT(*) as total FROM routes WHERE status = 'Active' AND is_deleted = 0";
        $stmt = $this->db->query($sql);
        if ($stmt) {
            $count = $stmt->fetchColumn();
            error_log("Total active routes count: " . $count);
            return (int)$count;
        }
        error_log("Error getting active routes: " . print_r($this->db->errorInfo(), true));
        return 0;
    }
    
    public function getTotalInactiveRoutes() {
        $sql = "SELECT COUNT(*) as total FROM routes WHERE status = 'Inactive' AND is_deleted = 0";
        $stmt = $this->db->query($sql);
        if ($stmt) {
            $count = $stmt->fetchColumn();
            error_log("Total inactive routes count: " . $count);
            return (int)$count;
        }
        error_log("Error getting inactive routes: " . print_r($this->db->errorInfo(), true));
        return 0;
    }

    public function createRoute($routeData) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // Insert into routes table
            $this->db->query("INSERT INTO routes (
                route_name, 
                start_location_lat,
                start_location_long,
                end_location_lat,
                end_location_long,
                date,
                number_of_suppliers,
                status
            ) VALUES (
                :name,
                :factory_lat,
                :factory_long,
                :factory_lat,
                :factory_long,
                CURRENT_DATE(),
                :num_suppliers,
                :status
            )");
            
            $this->db->bind(':name', $routeData->name);
            $this->db->bind(':factory_lat', self::FACTORY_LAT);
            $this->db->bind(':factory_long', self::FACTORY_LONG);
            $this->db->bind(':num_suppliers', count($routeData->stops));
            $this->db->bind(':status', $routeData->status);
            
            $this->db->execute();
            $routeId = $this->db->lastInsertId();

            // Insert route suppliers
            if (!empty($routeData->stops)) {
                $this->db->query("INSERT INTO route_suppliers (route_id, supplier_id) 
                                 VALUES (:route_id, :supplier_id)");
                
                foreach ($routeData->stops as $stop) {
                    $this->db->bind(':route_id', $routeId);
                    $this->db->bind(':supplier_id', $stop->id);
                    $this->db->execute();
                }
            }

            // Commit transaction
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            error_log("Error creating route: " . $e->getMessage());
            return false;
        }
    }


    public function getUnallocatedSuppliers() {
        $this->db->query("
            SELECT 
                s.supplier_id as supplier_id,
                CONCAT(u.first_name, ' ', u.last_name) as supplier_name,
                SUBSTRING_INDEX(s.coordinates, ',', 1) as latitude,
                SUBSTRING_INDEX(s.coordinates, ',', -1) as longitude
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            WHERE s.supplier_id NOT IN (
                SELECT DISTINCT supplier_id 
                FROM route_suppliers 
                WHERE route_id IN (
                    SELECT route_id 
                    FROM routes 
                    WHERE is_deleted = 0
                )
            )
            AND s.status = 'Active'
            AND u.approval_status = 'Approved'
        ");
        return $this->db->resultSet();
    }

    // for the table, must recorrect the naming issue here
    public function getUnallocatedSupplierDetails() {
        $this->db->query("
            SELECT s.supplier_id, 
                   CONCAT(u.first_name, ' ', u.last_name) as full_name,
                   s.street, s.city, s.coordinates
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            WHERE s.status = 'Active'
            AND s.supplier_id NOT IN (
                SELECT DISTINCT supplier_id 
                FROM route_suppliers
            )
            AND u.approval_status = 'Approved'
        ");
        return $this->db->resultSet();
    }

    public function getRouteSuppliers($routeId) {
        $this->db->query("
            SELECT 
                s.supplier_id,
                s.coordinates,
                CONCAT(u.first_name, ' ', u.last_name) as supplier_name
            FROM route_suppliers rs
            JOIN suppliers s ON rs.supplier_id = s.supplier_id
            JOIN users u ON s.user_id = u.id
            WHERE rs.route_id = :route_id
            AND rs.is_deleted = 0
        ");
        
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }
}
?>