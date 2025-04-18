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
    public function createRoute($routeName, $vehicleId) {   //tested
        // Check for duplicate route name
        if ($this->isDuplicateRouteName($routeName)) {
            return false; 
        }

        $this->db->query("SELECT capacity FROM vehicles WHERE vehicle_id = :vehicle_id");
        $this->db->bind(':vehicle_id', $vehicleId);
        $vehicle = $this->db->single();

        if ($vehicle) {
            $remainingCapacity = $vehicle->capacity; 
        } else {
            return false;
        }

        $sql = "INSERT INTO routes (route_name, vehicle_id, number_of_suppliers, remaining_capacity) VALUES (:route_name, :vehicle_id, 0, :remaining_capacity)";
        $this->db->query($sql);
        $this->db->bind(':route_name', $routeName);
        $this->db->bind(':vehicle_id', $vehicleId);
        $this->db->bind(':remaining_capacity', $remainingCapacity); 

        return $this->db->execute();
    }

    public function editRoute($routeId, $routeName, $vehicleId) {
        // Get current route information
        $this->db->query("SELECT vehicle_id, remaining_capacity FROM routes WHERE route_id = :route_id");
        $this->db->bind(':route_id', $routeId);
        $currentRoute = $this->db->single();
        
        if ($currentRoute->vehicle_id != $vehicleId) {
            $this->db->query("SELECT capacity FROM vehicles WHERE vehicle_id = :vehicle_id");
            $this->db->bind(':vehicle_id', $currentRoute->vehicle_id);
            $currentVehicle = $this->db->single();
            
            // the new vehicles capacity
            $this->db->query("SELECT capacity FROM vehicles WHERE vehicle_id = :vehicle_id");
            $this->db->bind(':vehicle_id', $vehicleId);
            $newVehicle = $this->db->single();
            
            // we can get the routes filled kg with current vehicle capacity - remaining capacity
            $suppliersCapacity = $currentVehicle->capacity - $currentRoute->remaining_capacity;
            
            if ($newVehicle->capacity < $suppliersCapacity) {
                return false;
            }

            $newRemainingCapacity = $newVehicle->capacity - $suppliersCapacity;
        } else {
            $newRemainingCapacity = $currentRoute->remaining_capacity;
        }
        
        // Update the route
        $this->db->query("UPDATE routes SET route_name = :route_name, vehicle_id = :vehicle_id, remaining_capacity = :remaining_capacity WHERE route_id = :route_id");
        $this->db->bind(':route_name', $routeName);
        $this->db->bind(':vehicle_id', $vehicleId);
        $this->db->bind(':remaining_capacity', $newRemainingCapacity);
        $this->db->bind(':route_id', $routeId);
        
        return $this->db->execute();
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
        // Check if the route is in any active collection schedules
        $this->db->query("SELECT COUNT(*) as count, schedule_id FROM collection_schedules WHERE route_id = :route_id AND collection_schedules.is_deleted = 0");
        $this->db->bind(':route_id', $route_id);
        $result = $this->db->single();

        if ($result->count > 0) {
            // cannot delete if route is associated with active schedule, 
            setFlashMessage("This route is currently set to the schedule: " . $result->schedule_id, 'error');
            return false; 
        }

        $this->db->beginTransaction();
        
        try {
            $this->db->query('UPDATE route_suppliers SET is_deleted = 1 WHERE route_id = :route_id');
            $this->db->bind(':route_id', $route_id);
            $this->db->execute();
            $this->db->query('UPDATE routes SET is_deleted = 1 WHERE route_id = :route_id');
            $this->db->bind(':route_id', $route_id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Add a supplier to a route.
     */
    public function addSupplierToRoute($routeId, $supplierId, $stopOrder) { // tested
        $sql = "INSERT INTO route_suppliers (route_id, supplier_id, stop_order) VALUES (:route_id, :supplier_id, :stop_order)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        $stmt->bindParam(':stop_order', $stopOrder);
        
        return $stmt->execute(); 
    }

    /**
     * Update the remaining capacity of a route based on the vehicle capacity and total average collection.
     */
    public function updateRemainingCapacity($routeId, $action = 'add') {    // tested
        // Get the vehicle capacity
        $vehicleCapacity = $this->getVehicleCapacityByRouteId($routeId);    // tested

        // Get the total average collection for the route
        $totalSum = $this->getTotalRouteSuppliersSum($routeId); // tested

        // Calculate remaining capacity
        $remainingCapacity = $vehicleCapacity - $totalSum;

        // Determine the adjustment for the number of suppliers
        $supplierAdjustment = ($action === 'add') ? 1 : -1;

        // Update the remaining capacity and adjust supplier count in the database
        $sql = "UPDATE routes SET remaining_capacity = :remaining_capacity, number_of_suppliers = number_of_suppliers + :supplier_adjustment WHERE route_id = :route_id";
        $this->db->query($sql);
        $this->db->bind(':remaining_capacity', $remainingCapacity);
        $this->db->bind(':supplier_adjustment', $supplierAdjustment);
        $this->db->bind(':route_id', $routeId);
        
        return $this->db->execute();
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

    public function getAllUnAssignedRoutes() {  // tested
        $this->db->query("
            SELECT r.* 
            FROM routes r
            LEFT JOIN collection_schedules cs ON r.route_id = cs.route_id AND cs.is_deleted = 0
            WHERE cs.route_id IS NULL AND r.is_deleted = 0
        ");
        return $this->db->resultSet();
    }

    //  gtting all the undeleted routes

    public function getAllUndeletedRoutes($limit = 5, $offset = 0) {    // tested
        $this->db->query("
            SELECT r.*, 
                v.*, 
                (SELECT COUNT(*) FROM route_suppliers WHERE route_id = r.route_id) AS supplier_count,
                COALESCE(assigned.is_assigned, 0) AS is_assigned
            FROM routes r 
            INNER JOIN vehicles v ON r.vehicle_id = v.vehicle_id 
            LEFT JOIN (
                SELECT cs.route_id, 
                    CASE 
                        WHEN cs.route_id IS NOT NULL AND cs.is_deleted = 0 THEN 1 
                        ELSE 0 
                    END AS is_assigned
                FROM collection_schedules cs 
                WHERE cs.is_deleted = 0
            ) AS assigned ON r.route_id = assigned.route_id
            WHERE r.is_deleted = 0
            GROUP BY r.route_id
            ORDER BY r.route_id ASC 
            LIMIT :limit OFFSET :offset
        ");
        
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);

        return $this->db->resultSet();
    }

    /**
     * Get the total count of undeleted routes.
     */
    public function getTotalRoutes() {
        $this->db->query("SELECT COUNT(*) as totalRoutes FROM routes WHERE is_deleted = 0");
        $result = $this->db->single();
        return $result ? $result->totalRoutes : 0;
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
                p.first_name,
                p.last_name,
                CONCAT(p.first_name, ' ', p.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates,
                rs.*
            FROM route_suppliers rs
            JOIN suppliers s ON rs.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id
            WHERE rs.route_id = :route_id
            AND rs.is_deleted = 0
            AND s.is_deleted = 0
            ORDER BY rs.stop_order
        ");
        
        $this->db->bind(':route_id', $routeId);
        return $this->db->resultSet();
    }


    public function getSupplierCountByScheduleId($scheduleId) { // TESTED
        $this->db->query("
            SELECT 
                COUNT(*)
                FROM route_suppliers rs
                INNER JOIN routes r ON r.route_id = rs.route_id
                INNER JOIN collection_schedules cs ON cs.route_id = rs.route_id
                WHERE schedule_id = :schedule_id
        ");
        
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }
    

    /**
     * Get routes for a specific day that are not already assigned in collection schedules.
     */
    public function getRoutesByDay($day) {
        $this->db->query("
            SELECT r.* 
            FROM routes r
            WHERE r.day = :day 
            AND r.is_deleted = 0 
            AND r.route_id NOT IN (
                SELECT cs.route_id 
                FROM collection_schedules cs 
                WHERE cs.day = :day
                AND cs.is_deleted = 0
                AND cs.is_active = 1
            )
        ");
        
        $this->db->bind(':day', $day);
        return $this->db->resultSet();
    }

    /**
     * Get route details based on a collection ID.
     */
    public function getRouteDetailsByCollection($collectionId){         // tested but  date is null
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
     * Get the last stop order number for a given route.
     */
    // public function getLastStopOrder($routeId) {
    //     $sql = "SELECT MAX(stop_order) AS last_stop_order 
    //             FROM route_suppliers 
    //             WHERE route_id = :route_id AND is_deleted = 0";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->bindParam(':route_id', $routeId);
        
    //     if ($stmt->execute()) {
    //         $result = $stmt->fetch(PDO::FETCH_ASSOC);
    //         // Explicitly check if last_stop_order is NULL and set it to 0
    //         return isset($result['last_stop_order']) ? (int)$result['last_stop_order'] : 0;
    //     }
    //     return 0;
    // }

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
    public function getUnallocatedSuppliers() { // actually should call it unassigned, tested
        $this->db->query("
            SELECT DISTINCT
                s.*,
                p.first_name,
                p.last_name,
                CONCAT(p.first_name, ' ', p.last_name) as full_name,
                CONCAT(s.latitude, ', ', s.longitude) as coordinates
            FROM suppliers s
            JOIN profiles p ON s.profile_id = p.profile_id
            LEFT JOIN route_suppliers rs ON s.supplier_id = rs.supplier_id AND rs.is_deleted = 0  -- check active only
            LEFT JOIN routes r ON rs.route_id = r.route_id AND r.is_deleted = 0  -- taking only active route
            WHERE rs.supplier_id IS NULL  -- Supplier is not in any active route
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

    /**
     * Calculate the total average collection for a given route.
     */
    private function getTotalRouteSuppliersSum($routeId) {

        $this->db->query(        "
        SELECT SUM(s.average_collection) AS sum 
        FROM route_suppliers rs
        JOIN suppliers s ON rs.supplier_id = s.supplier_id
        WHERE rs.route_id = :route_id AND rs.is_deleted = 0 AND s.is_deleted = 0
        ");
        $this->db->bind(':route_id', $routeId);

        $result = $this->db->single();
        if($result) {
            return (float)$result->sum;
        }

        return 0;
        
    }

    /**
     * Retrieve the vehicle capacity associated with a route.
     */
    private function getVehicleCapacityByRouteId($routeId) {    // tested
        $sql = "SELECT v.capacity 
                FROM routes r 
                JOIN vehicles v ON r.vehicle_id = v.vehicle_id 
                WHERE r.route_id = :route_id";
                
        $this->db->query($sql);
        $this->db->bind(':route_id', $routeId);
        
        $result = $this->db->single();
        
        // Check if result exists and has the capacity property
        return $result->capacity;
    }



    public function getSupplierCurrentRoute($supplierId) {
        $sql = "SELECT route_id FROM route_suppliers 
                WHERE supplier_id = :supplier_id 
                AND is_active = 1 
                AND is_deleted = 0";
        
        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single();
    }


    public function getRouteIdByScheduleId($scheduleId) {
        $sql = "SELECT r.route_id 
                FROM collection_schedules cs 
                JOIN routes r ON cs.route_id = r.route_id 
                WHERE cs.schedule_id = :schedule_id 
                AND r.is_deleted = 0";  // Ensure the route is not deleted

        $this->db->query($sql);
        $this->db->bind(':schedule_id', $scheduleId);
        
        // Execute the query and return the route_id
        $result = $this->db->single();
        
        // Check if result is valid and return the route_id or null
        return ($result && isset($result->route_id)) ? $result->route_id : null;
    }



    /**
     * Get the total count of unassigned routes.
     */
    public function getUnassignedRoutesCount() {    //fixed and tested
        $this->db->query("
            SELECT COUNT(*) as totalUnassigned 
            FROM routes r 
            LEFT JOIN collection_schedules cs ON r.route_id = cs.route_id AND cs.is_deleted = 0
            WHERE r.is_deleted = 0 AND cs.route_id IS NULL
        ");
        $result = $this->db->single();
        return $result ? $result->totalUnassigned : 0; 
    }


    // I MADE THIS FOR OPTIMIZATIOn, REMOVED THE SEQUENTIAL LINKED LIST LIKE APPROACH!!

    public function optimizeRouteStopOrders($routeId) {
        $suppliers = $this->getRouteSuppliersByRouteId($routeId);
        
        // if empty nothing to update
        if (empty($suppliers)) {
            return true;
        }
        
        // if only 1 supplier then its simply set to 1
        if (count($suppliers) == 1) {
            // Assuming $suppliers is an array of objects
            $sql = "UPDATE route_suppliers SET stop_order = 1 
                    WHERE route_id = :route_id 
                      AND supplier_id = :supplier_id";
            $this->db->query($sql);
            $this->db->bind(':route_id', $routeId);
            $this->db->bind(':supplier_id', $suppliers[0]->supplier_id);
            return $this->db->execute();
        }
        
        
        $factory = $this->getFactoryLocation();
        $optimizedOrder = $this->calculateOptimalOrder($factory, $suppliers);

        $this->db->beginTransaction();
        try {
            foreach ($optimizedOrder as $index => $supplierId) {
                $stopOrder = $index + 1; 
                $sql = "UPDATE route_suppliers SET stop_order = :stop_order 
                        WHERE route_id = :route_id AND supplier_id = :supplier_id";
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(':stop_order', $stopOrder);
                $stmt->bindParam(':route_id', $routeId);
                $stmt->bindParam(':supplier_id', $supplierId);
                $stmt->execute();
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    
    
    private function calculateOptimalOrder($factory, $suppliers) {
        $currentPoint = $factory;
        $remaining = $suppliers; 
        $route = [];
        
        while (count($remaining) > 0) {
            // Find the closest supplier from the current point.
            $nearestIndex = $this->findNearest($currentPoint, $remaining);
            $nearest = $remaining[$nearestIndex];
            
            $route[] = $nearest->supplier_id;

            unset($remaining[$nearestIndex]);
            // re-iindexing the array so that the index are ordered again
            $remaining = array_values($remaining);
        }
        
        return $route;
    }
    
    private function findNearest($point, $suppliers) {
        $minDistance=PHP_FLOAT_MAX;
        $nearestIndex=0;

        // simple short value finding
        
        foreach ($suppliers as $index =>$supplier){
            // frp, current point to the supplier
            $distance = $this->calculateDistance($point->latitude, $point->longitude,$supplier->latitude, $supplier->longitude
            );
            
            if ($distance <$minDistance) {
                $minDistance=$distance;
                $nearestIndex =$index;
            }
        }
        return $nearestIndex;
    }
    


    public function removeSupplierFromRoute($routeId, $supplierId) {

        $sql = "DELETE FROM route_suppliers WHERE route_id = :route_id AND supplier_id = :supplier_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':route_id', $routeId);
        $stmt->bindParam(':supplier_id', $supplierId);
        return $stmt->execute();
        

    }
    


    private function getFactoryLocation() {
        $this->db->query("SELECT latitude, longitude FROM factory_location LIMIT 1");
        $result = $this->db->single();
        
        // use default if nt available
        if (!$result) {
            return (object)[
                'latitude' => 6.9497,
                'longitude' => 80.7891,
                'supplier_id' => 0 
            ];
        }
        
        return $result;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        // Convert degrees to radians
        $lat1 = $lat1 * (pi() / 180);
        $lon1 = $lon1 * (pi() / 180);
        $lat2 = $lat2 * (pi() / 180);
        $lon2 = $lon2 * (pi() / 180);
    
        // Earth's radius in kilometers
        $earthRadius = 6371;
    
        // Differences in coordinates
        $diffLat = $lat2 - $lat1;
        $diffLon = $lon2 - $lon1;
    
        // Haversine formula
        $a = sin($diffLat / 2) * sin($diffLat / 2) +
             cos($lat1) * cos($lat2) *
             sin($diffLon / 2) * sin($diffLon / 2);
    
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        // Final distance
        $distance = $earthRadius * $c;
    
        return $distance;
    }
    

    public function isDuplicateRouteName($routeName) {
        $this->db->query("SELECT COUNT(*) as count FROM routes WHERE route_name = :route_name AND is_deleted = 0");
        $this->db->bind(':route_name', $routeName);
        $result = $this->db->single();
        
        return $result->count > 0;
    }

}
?>
