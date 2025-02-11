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

    public function getAllUndeletedRoutes() {
        $this->db->query("SELECT * FROM routes r WHERE r.is_deleted = 0");
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
                status,
                vehicle_id,
                day
            ) VALUES (
                :name,
                :factory_lat,
                :factory_long,
                :factory_lat,
                :factory_long,
                CURRENT_DATE(),
                :num_suppliers,
                :status,
                :vehicle_id,
                :day
            )");
            
            $this->db->bind(':name', $routeData->name);
            $this->db->bind(':factory_lat', self::FACTORY_LAT);
            $this->db->bind(':factory_long', self::FACTORY_LONG);
            $this->db->bind(':num_suppliers', count($routeData->stops));
            $this->db->bind(':status', $routeData->status);
            $this->db->bind(':vehicle_id', $routeData->vehicle_id);
            $this->db->bind(':day', $routeData->day);
            
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
            SELECT DISTINCT
                s.*,
                u.first_name,
                u.last_name,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            LEFT JOIN route_suppliers rs ON s.supplier_id = rs.supplier_id
            LEFT JOIN routes r ON rs.route_id = r.route_id
            WHERE (rs.supplier_id IS NULL OR r.route_id IS NULL OR r.is_deleted = 1)
            AND s.is_active = 1
            AND s.is_deleted = 0;

        ");
        
        $result = $this->db->resultSet();
        error_log('Unallocated suppliers query result: ' . print_r($result, true));
        return $result;
    }


    public function getSuppliersInCollection($collectionId) {
        $this->db->query("
            SELECT 
                s.*,
                u.first_name,
                u.last_name,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates,
                csr.approval_status
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            LEFT JOIN collection_supplier_records csr ON s.supplier_id = csr.supplier_id
            JOIN collections c ON c.collection_id = csr.collection_id
            WHERE c.collection_id = :collection_id
            AND s.is_active = 1
            AND s.is_deleted = 0
        ");
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->resultSet();
        error_log('Unallocated suppliers query result: ' . print_r($result, true));
        return $result;
    }

    public function getUnallocatedSuppliersByDay($preferredDay) {
        $this->db->query("
            SELECT 
                s.*,
                u.first_name,
                u.last_name,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates
            FROM suppliers s
            JOIN users u ON s.user_id = u.user_id
            LEFT JOIN route_suppliers rs ON s.supplier_id = rs.supplier_id
            WHERE rs.supplier_id IS NULL 
            AND s.is_active = 1
            AND s.is_deleted = 0
            AND s.preferred_day = :preferred_day
        ");
        
        $this->db->bind(':preferred_day', $preferredDay);
        $result = $this->db->resultSet();
        error_log('Unallocated suppliers query result for day ' . $preferredDay . ': ' . print_r($result, true));
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

    public function getRouteSuppliersByRouteId($routeId) {
        $this->db->query("
            SELECT 
                s.*,
                u.first_name,
                u.last_name,
                CONCAT(u.first_name, ' ', u.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates,
                rs.*
            FROM route_suppliers rs
            JOIN suppliers s ON rs.supplier_id = s.supplier_id
            JOIN users u ON s.user_id = u.user_id
            WHERE rs.route_id = :route_id
            AND rs.is_deleted = 0
            AND s.is_deleted = 0
        ");
        
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }

    public function getRouteById($routeId) {
        $this->db->query("
            SELECT * FROM routes WHERE route_id = :route_id;
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


    public function deleteRoute($route_id) {
        // Start a transaction
        $this->db->beginTransaction();
        
        try {
            // Delete from route_suppliers
            // $this->db->query('DELETE FROM route_suppliers WHERE route_id = :route_id');
            // $this->db->bind(':route_id', $route_id);
            // $this->db->execute();

            // Soft Delete from routes
            $this->db->query('UPDATE routes SET is_deleted = 1 WHERE route_id = :route_id');
            $this->db->bind(':route_id', $route_id);
            $this->db->execute();

            // Commit the transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction if something failed
            $this->db->rollBack();
            return false;
        }
    }

    public function getRoutesByDay($day) {
        $this->db->query("
            SELECT r.* 
            FROM routes r
            LEFT JOIN collection_schedules cs ON r.route_id = cs.route_id 
                AND cs.day = :day 
                AND cs.is_active = 1
            WHERE r.day = :day 
            AND r.is_deleted = 0 
            AND cs.route_id IS NULL
        ");
        
        $this->db->bind(':day', $day);
        return $this->db->resultset();
    }

    public function getRouteDetailsByCollection($collectionId){
        $sql = "
        SELECT r.* FROM collection_schedules cs
        JOIN collections c ON c.schedule_id = cs.schedule_id
        JOIN routes r on cs.route_id = r.route_id
        WHERE c.collection_id = :collection_id;
        ";
        $this->db->query($sql);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->single();
    }

    public function getTodayAssignedRoutes() {
        $currentDay = date('l'); // Get the current day (e.g., 'Tuesday')
    
        $this->db->query("
            SELECT r.*, v.license_plate 
            FROM routes r
            JOIN collection_schedules cs ON r.route_id = cs.route_id 
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id 
            WHERE cs.day = :day 
            AND cs.is_active = 1 
            AND r.is_deleted = 0
        ");
        
        $this->db->bind(':day', $currentDay);
        return $this->db->resultSet(); // Use resultSet() to fetch the results
    }

    public function getRouteSuppliers($routeId) {
        $this->db->query("
            SELECT 
                s.supplier_id, 
                CONCAT(u.first_name, ' ', u.last_name) AS full_name, 
                s.latitude, 
                s.longitude 
            FROM 
                route_suppliers rs 
            JOIN 
                suppliers s ON rs.supplier_id = s.supplier_id 
            JOIN 
                users u ON s.user_id = u.user_id 
            WHERE 
                rs.route_id = :route_id 
                AND rs.is_active = 1 
                AND rs.is_deleted = 0
        ");
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet(); // Assuming this method fetches the results as an array of objects
    }


    public function addSupplierToRoute($routeId, $supplierId, $stopOrder) {
        $sql = "INSERT INTO route_suppliers (route_id, supplier_id, stop_order) VALUES (:route_id, :supplier_id, :stop_order)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        $stmt->bindParam(':stop_order', $stopOrder);
        
        return $stmt->execute(); // Return true on success, false on failure
    }

    public function getLastStopOrder($routeId) {
        $sql = "SELECT MAX(stop_order) AS last_stop_order FROM route_suppliers WHERE route_id = :route_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['last_stop_order'] ? (int)$result['last_stop_order'] : 0; // Return the last stop order or 0 if none
        }
        return 0; // Return 0 if query fails
    }

    public function updateRemainingCapacity($routeId) {
        // Get the vehicle capacity
        $vehicleCapacity = $this->getVehicleCapacityByRouteId($routeId); // Create this method to fetch vehicle capacity

        // Get the total average collection for the route
        $totalAverageCollection = $this->getTotalAverageCollection($routeId);

        // Calculate remaining capacity
        $remainingCapacity = $vehicleCapacity - $totalAverageCollection;

        // Update the remaining capacity in the database
        $sql = "UPDATE routes SET remaining_capacity = :remaining_capacity , number_of_suppliers = number_of_suppliers + 1 WHERE route_id = :route_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':remaining_capacity', $remainingCapacity);
        $stmt->bindParam(':route_id', $routeId);
        
        return $stmt->execute(); // Return true on success, false on failure
    }

    private function getTotalAverageCollection($routeId) {
        $sql = "
            SELECT SUM(s.average_collection) AS total_average 
            FROM route_suppliers rs
            JOIN suppliers s ON rs.supplier_id = s.supplier_id
            WHERE rs.route_id = :route_id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total_average'] ? (float)$result['total_average'] : 0; // Return the total average or 0 if none
        }
        return 0; // Return 0 if query fails
    }

    private function getVehicleCapacityByRouteId($routeId) {
        $sql = "SELECT v.capacity FROM routes r JOIN vehicles v ON r.vehicle_id = v.vehicle_id WHERE r.route_id = :route_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['capacity'] ? (float)$result['capacity'] : 0; // Return the vehicle capacity or 0 if none
        }
        return 0; // Return 0 if query fails
    }

    public function removeSupplierFromRoute($routeId, $supplierId) {
        // First, get the stop_order of the supplier being removed
        $stopOrder = $this->getStopOrder($routeId, $supplierId);

        // Remove the supplier from the route
        $sql = "DELETE FROM route_suppliers WHERE route_id = :route_id AND supplier_id = :supplier_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        $stmt->execute();

        // Adjust the stop orders for remaining suppliers
        $this->adjustStopOrders($routeId, $stopOrder);

        // Decrement the number of suppliers in the routes table
        $sql = "UPDATE routes SET number_of_suppliers = number_of_suppliers - 1 WHERE route_id = :route_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->execute();

        return true; // Return true on success
    }

    private function getStopOrder($routeId, $supplierId) {
        $sql = "SELECT stop_order FROM route_suppliers WHERE route_id = :route_id AND supplier_id = :supplier_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['stop_order'] ? (int)$result['stop_order'] : 0; // Return the stop order or 0 if none
        }
        return 0; // Return 0 if query fails
    }

    private function adjustStopOrders($routeId, $removedStopOrder) {
        // Update the stop_order for suppliers with a higher stop_order
        $sql = "UPDATE route_suppliers SET stop_order = stop_order - 1 WHERE route_id = :route_id AND stop_order > :removed_stop_order";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':removed_stop_order', $removedStopOrder);
        
        return $stmt->execute(); // Return true on success, false on failure
    }

}
?>