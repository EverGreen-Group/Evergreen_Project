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
        $this->db->query("
        SELECT routes.*, vehicles.license_plate
        FROM routes
        JOIN vehicles ON routes.vehicle_id = vehicles.vehicle_id
        ");
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
                s.supplier_id,
                s.contact_number,
                s.latitude,
                s.longitude,
                u.first_name,
                u.last_name,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            LEFT JOIN route_suppliers rs ON s.supplier_id = rs.supplier_id
            WHERE rs.supplier_id IS NULL 
            AND u.approval_status = 'Approved'
            AND s.is_active = 1
            AND s.is_deleted = 0
        ");
        
        $result = $this->db->resultSet();
        error_log('Unallocated suppliers query result: ' . print_r($result, true));
        return $result;
    }

    // for the table, must recorrect the naming issue here
    public function getUnallocatedSupplierDetails() {
        $this->db->query("
            SELECT s.*,
                   CONCAT(u.first_name, ' ', u.last_name) as full_name,
                   CONCAT(s.latitude, ', ', s.longitude) as coordinates
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            WHERE s.is_active = 1
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
                s.*,
                u.first_name,
                u.last_name,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates,
                rs.stop_order
            FROM route_suppliers rs
            JOIN suppliers s ON rs.supplier_id = s.supplier_id
            JOIN users u ON s.user_id = u.user_id
            WHERE rs.route_id = :route_id
            AND rs.is_deleted = 0
            AND s.is_deleted = 0
            ORDER BY rs.stop_order ASC
        ");
        
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }

    public function getRouteById($routeId) {
        $this->db->query("
            SELECT 
                r.*,
                COUNT(rs.supplier_id) as supplier_count
            FROM routes r
            LEFT JOIN route_suppliers rs ON r.route_id = rs.route_id
            WHERE r.route_id = :route_id
            AND r.is_deleted = 0
            GROUP BY r.route_id
        ");
        
        $this->db->bind(':route_id', $routeId);
        return $this->db->single();
    }

    public function updateRouteOrder($routeId, $supplierId, $order) {
        $this->db->query("UPDATE route_suppliers 
                          SET supplier_order = :order 
                          WHERE route_id = :route_id 
                          AND supplier_id = :supplier_id");
        
        $this->db->bind(':order', $order);
        $this->db->bind(':route_id', $routeId);
        $this->db->bind(':supplier_id', $supplierId);
        
        return $this->db->execute();
    }

    public function createRouteWithSuppliers($data) {
        try {
            $this->db->beginTransaction();

            // Create route with all required fields
            $this->db->query("INSERT INTO routes (
                route_name, 
                vehicle_id, 
                date,
                number_of_suppliers,
                status,
                is_deleted
            ) VALUES (
                :route_name,
                :vehicle_id,
                CURRENT_DATE(),
                :number_of_suppliers,
                :status,
                0
            )");

            $this->db->bind(':route_name', $data['route_name']);
            $this->db->bind(':vehicle_id', $data['vehicle_id']);
            $this->db->bind(':number_of_suppliers', count($data['suppliers']));
            $this->db->bind(':status', $data['status']);
            $this->db->execute();
            
            $routeId = $this->db->lastInsertId();

            // Add suppliers to route
            foreach ($data['suppliers'] as $supplier) {
                $this->db->query('INSERT INTO route_suppliers (
                    route_id, 
                    supplier_id, 
                    stop_order,
                    is_active,
                    is_deleted
                ) VALUES (
                    :route_id, 
                    :supplier_id, 
                    :stop_order,
                    1,
                    0
                )');
                
                $this->db->bind(':route_id', $routeId);
                $this->db->bind(':supplier_id', $supplier->supplier_id);
                $this->db->bind(':stop_order', $supplier->stop_order);
                $this->db->execute();
            }

            $this->db->commit();
            return $routeId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateRouteWithSuppliers($data) {
        try {
            $this->db->beginTransaction();

            // Update route with all required fields
            $this->db->query("UPDATE routes SET 
                route_name = :route_name, 
                vehicle_id = :vehicle_id,
                number_of_suppliers = :number_of_suppliers,
                status = :status
                WHERE route_id = :route_id");

            $this->db->bind(':route_name', $data['route_name']);
            $this->db->bind(':vehicle_id', $data['vehicle_id']);
            $this->db->bind(':number_of_suppliers', count($data['suppliers']));
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':route_id', $data['route_id']);
            $this->db->execute();

            // Soft delete existing suppliers for this route
            $this->db->query('UPDATE route_suppliers SET is_deleted = 1 
                             WHERE route_id = :route_id');
            $this->db->bind(':route_id', $data['route_id']);
            $this->db->execute();

            // Add updated suppliers to route
            foreach ($data['suppliers'] as $supplier) {
                $this->db->query('INSERT INTO route_suppliers (
                    route_id, 
                    supplier_id, 
                    stop_order,
                    is_active,
                    is_deleted
                ) VALUES (
                    :route_id, 
                    :supplier_id, 
                    :stop_order,
                    1,
                    0
                )');
                
                $this->db->bind(':route_id', $data['route_id']);
                $this->db->bind(':supplier_id', $supplier->supplier_id);
                $this->db->bind(':stop_order', $supplier->stop_order);
                $this->db->execute();
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
?>