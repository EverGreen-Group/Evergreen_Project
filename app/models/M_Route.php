<?php
// app/models/M_Route.php

class M_Route {
    /* ========================================================================
       Constructor & Class Constants
       ======================================================================== */
    private $db;

    // Class constants for factory location
    const FACTORY_LAT = 6.2173037;
    const FACTORY_LONG = 80.2538636;

    public function __construct() {
        $this->db = new Database();
    }


    /* ========================================================================
       CRUD Methods (Create, Update, Delete)
       ======================================================================== */

    /**
     * Create a new route.
     */
    public function createRoute($routeName, $routeDay, $vehicleId) {
        // Step 1: Get the capacity of the vehicle
        $this->db->query("SELECT capacity FROM vehicles WHERE vehicle_id = :vehicle_id");
        $this->db->bind(':vehicle_id', $vehicleId);
        $vehicle = $this->db->single(); // Fetch the single vehicle record

        // Check if the vehicle exists and has a capacity
        if ($vehicle) {
            $remainingCapacity = $vehicle->capacity; // Assuming 'capacity' is the column name
        } else {
            // Handle the case where the vehicle does not exist
            return false; // Or throw an exception, or handle as needed
        }

        // Step 2: Insert the new route with the remaining capacity
        $sql = "INSERT INTO routes (route_name, day, vehicle_id, number_of_suppliers, remaining_capacity) VALUES (:route_name, :day, :vehicle_id, 0, :remaining_capacity)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_name', $routeName);
        $stmt->bindParam(':day', $routeDay);
        $stmt->bindParam(':vehicle_id', $vehicleId);
        $stmt->bindParam(':remaining_capacity', $remainingCapacity); // Bind the remaining capacity

        return $stmt->execute(); // Return true on success, false on failure
    }

    /**
     * Update the order of a supplier within a route.
     */
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

    /**
     * Soft delete a route and its associated route_suppliers.
     */
    public function deleteRoute($route_id) {
        // Start a transaction
        $this->db->beginTransaction();
        
        try {
            // Soft delete from route_suppliers
            $this->db->query('UPDATE route_suppliers SET is_deleted = 1 WHERE route_id = :route_id');
            $this->db->bind(':route_id', $route_id);
            $this->db->execute();

            // Soft delete from routes
            $this->db->query('UPDATE routes SET is_deleted = 1 WHERE route_id = :route_id');
            $this->db->bind(':route_id', $route_id);
            $this->db->execute();

            // Commit the transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback if something goes wrong
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Add a supplier to a route.
     */
    public function addSupplierToRoute($routeId, $supplierId, $stopOrder) {
        $sql = "INSERT INTO route_suppliers (route_id, supplier_id, stop_order) VALUES (:route_id, :supplier_id, :stop_order)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        $stmt->bindParam(':stop_order', $stopOrder);
        
        return $stmt->execute(); // Return true on success, false on failure
    }

    /**
     * Update the remaining capacity of a route based on the vehicle capacity and total average collection.
     */
    public function updateRemainingCapacity($routeId, $action = 'add') {
        // Get the vehicle capacity
        $vehicleCapacity = $this->getVehicleCapacityByRouteId($routeId);

        // Get the total average collection for the route
        $totalAverageCollection = $this->getTotalAverageCollection($routeId);

        // Calculate remaining capacity
        $remainingCapacity = $vehicleCapacity - $totalAverageCollection;

        // Determine the adjustment for the number of suppliers
        $supplierAdjustment = ($action === 'add') ? 1 : -1;

        // Update the remaining capacity and adjust supplier count in the database
        $sql = "UPDATE routes SET remaining_capacity = :remaining_capacity, number_of_suppliers = number_of_suppliers + :supplier_adjustment WHERE route_id = :route_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':remaining_capacity', $remainingCapacity);
        $stmt->bindParam(':supplier_adjustment', $supplierAdjustment);
        $stmt->bindParam(':route_id', $routeId);
        
        return $stmt->execute();
    }

    /**
     * Remove a supplier from a route and adjust the supplier stop orders accordingly.
     */
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

        return true; // Return true on success
    }


    /* ========================================================================
       Fetch Methods (General Data Retrieval)
       ======================================================================== */

    /**
     * Get all routes (including deleted).
     */
    public function getAllRoutes() {
        $this->db->query("SELECT * FROM routes");
        return $this->db->resultset();
    }

    /**
     * Get all undeleted routes.
     */
    public function getAllUndeletedRoutes() {
        $this->db->query("SELECT * FROM routes r WHERE r.is_deleted = 0");
        return $this->db->resultset();
    }

    /**
     * Get the total count of undeleted routes.
     */
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
    
    /**
     * Get the total count of active routes.
     */
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
    
    /**
     * Get the total count of inactive routes.
     */
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

    /**
     * Get route details by its ID.
     */
    public function getRouteById($routeId) {
        $this->db->query("SELECT * FROM routes WHERE route_id = :route_id;");
        $this->db->bind(':route_id', $routeId);
        return $this->db->single();
    }

    /**
     * Get detailed supplier information for a specific route.
     */
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

    /**
     * Get routes for a specific day that are not already assigned in collection schedules.
     */
    public function getRoutesByDay($day) {
        $this->db->query("
            SELECT r.* 
            FROM routes r
            LEFT JOIN collection_schedules cs ON r.route_id = cs.route_id 
                AND cs.day = :day 
                AND cs.is_active = 1
            WHERE r.day = :day 
            AND r.is_deleted = 0 
            AND (cs.route_id IS NULL OR cs.is_deleted = 1)
        ");
        
        $this->db->bind(':day', $day);
        return $this->db->resultset();
    }

    /**
     * Get route details based on a collection ID.
     */
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

    /**
     * Get the routes assigned for today.
     */
    public function getTodayAssignedRoutes() {
        $currentDay = date('l'); // Get the current day (e.g., 'Tuesday')
    
        $this->db->query("
            SELECT DISTINCT r.*, v.license_plate 
            FROM routes r
            JOIN collection_schedules cs ON r.route_id = cs.route_id 
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id 
            WHERE cs.day = :day 
            AND cs.is_active = 1 
            AND r.is_deleted = 0
        ");
        
        $this->db->bind(':day', $currentDay);
        return $this->db->resultSet();
    }

    /**
     * Get basic supplier details for a route (for maps or lists).
     */
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
        return $this->db->resultSet();
    }

    /**
     * Get the last stop order number for a given route.
     */
    public function getLastStopOrder($routeId) {
        $sql = "SELECT MAX(stop_order) AS last_stop_order 
                FROM route_suppliers 
                WHERE route_id = :route_id AND is_deleted = 0";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            // Explicitly check if last_stop_order is NULL and set it to 0
            return isset($result['last_stop_order']) ? (int)$result['last_stop_order'] : 0;
        }
        return 0;
    }

    /**
     * Get supplier count grouped by day.
     */
    public function getSupplierCountByDay() {
        $this->db->query("
            SELECT 
                r.day, 
                COUNT(rs.supplier_id) AS supplier_count 
            FROM routes r
            LEFT JOIN route_suppliers rs ON r.route_id = rs.route_id AND rs.is_deleted = 0
            GROUP BY r.day
        ");
        
        return $this->db->resultSet();
    }


    /* ========================================================================
       AJAX Methods (Data Retrieval for AJAX Calls)
       ======================================================================== */

    /**
     * Get unallocated suppliers (those not assigned to any active route).
     */
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

    /**
     * Get suppliers associated with a specific collection.
     */
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

    /**
     * Get unallocated suppliers filtered by their preferred day.
     */
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
            LEFT JOIN route_suppliers rs ON s.supplier_id = rs.supplier_id AND rs.is_deleted = 0
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

    /**
     * Get unallocated supplier details including user info and coordinates.
     */
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


    /* ========================================================================
       Helper Methods (Internal Utility Functions)
       ======================================================================== */

    /**
     * Calculate the total average collection for a given route.
     */
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
            return $result['total_average'] ? (float)$result['total_average'] : 0;
        }
        return 0;
    }

    /**
     * Retrieve the vehicle capacity associated with a route.
     */
    private function getVehicleCapacityByRouteId($routeId) {
        $sql = "SELECT v.capacity FROM routes r JOIN vehicles v ON r.vehicle_id = v.vehicle_id WHERE r.route_id = :route_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['capacity'] ? (float)$result['capacity'] : 0;
        }
        return 0;
    }

    /**
     * Get the stop order for a supplier within a route.
     */
    private function getStopOrder($routeId, $supplierId) {
        $sql = "SELECT stop_order FROM route_suppliers WHERE route_id = :route_id AND supplier_id = :supplier_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        
        if ($stmt->execute()) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['stop_order'] ? (int)$result['stop_order'] : 0;
        }
        return 0;
    }

    /**
     * Adjust the stop orders for remaining suppliers after one is removed.
     */
    private function adjustStopOrders($routeId, $removedStopOrder) {
        // Update the stop_order for suppliers with a higher stop_order
        $sql = "UPDATE route_suppliers SET stop_order = stop_order - 1 WHERE route_id = :route_id AND stop_order > :removed_stop_order";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':removed_stop_order', $removedStopOrder);
        
        return $stmt->execute();
    }
}
?>
