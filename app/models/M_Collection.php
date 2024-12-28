<?php

class M_Collection {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    public function getOngoingCollections() {
        $sql = "SELECT 
                    c.*,
                    cs.schedule_id,
                    r.route_name,
                    t.team_name,
                    v.license_plate,
                    s.shift_name
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                JOIN routes r ON cs.route_id = r.route_id
                JOIN teams t ON cs.team_id = t.team_id
                JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
                JOIN collection_shifts s ON cs.shift_id = s.shift_id
                WHERE c.status IN ('Pending', 'In Progress')
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                ORDER BY c.start_time ASC";
        
        $this->db->query($sql);
        $result = $this->db->resultSet();
        
        // test debug
        // var_dump($sql);
        // var_dump($result);
        
        return $result;
    }

    public function getCollectionById($collectionId) {
        $sql = "SELECT 
                    c.*,
                    cs.schedule_id,
                    r.route_name,
                    t.team_name,
                    v.license_plate,
                    s.shift_name
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                JOIN routes r ON cs.route_id = r.route_id
                JOIN teams t ON cs.team_id = t.team_id
                JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
                JOIN collection_shifts s ON cs.shift_id = s.shift_id
                WHERE c.collection_id = :collection_id";
        
        $this->db->query($sql);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->single();
    }

    public function updateArrivalTime($data) {
        $this->db->query('UPDATE collection_supplier_records 
                          SET arrival_time = :arrival_time 
                          WHERE collection_id = :collection_id 
                          AND supplier_id = :supplier_id');
        
        $this->db->bind(':arrival_time', $data['arrival_time']);
        $this->db->bind(':collection_id', $data['collection_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        
        return $this->db->execute();
    }

    public function getCollectionsByVehicleId($vehicleId) {
        $this->db->query('SELECT * FROM collections WHERE vehicle_id = :vehicle_id AND status != "Completed"');
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->resultSet();
    }

    public function getCollectionSuppliers($collectionId) {
        $this->db->query("SELECT 
            s.supplier_id,
            s.first_name,
            s.last_name,
            s.contact_number,
            s.coordinates,
            csr.status,
            csr.arrival_time,
            csr.quantity,
            csr.collection_time,
            csr.notes
        FROM suppliers s
        INNER JOIN collection_supplier_records csr ON s.supplier_id = csr.supplier_id
        INNER JOIN collections c ON csr.collection_id = c.collection_id
        WHERE c.collection_id = :collection_id
        ORDER BY csr.arrival_time ASC");

        $this->db->bind(':collection_id', $collectionId);

        return $this->db->resultSet();
    }

    public function updateSupplierCollectionStatus($collectionId, $supplierId, $status) {
        $this->db->query("UPDATE collection_supplier_records 
                          SET status = :status,
                              arrival_time = CASE 
                                  WHEN :status = 'Added' THEN NOW() 
                                  ELSE arrival_time 
                              END
                          WHERE collection_id = :collection_id 
                          AND supplier_id = :supplier_id");

        $this->db->bind(':status', $status);
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);

        return $this->db->execute();
    }

    public function recordSupplierCollection($collectionId, $supplierId, $quantity, $notes = '') {
        $this->db->query("UPDATE collection_supplier_records 
                          SET status = 'Collected',
                              quantity = :quantity,
                              notes = :notes,
                              collection_time = NOW()
                          WHERE collection_id = :collection_id 
                          AND supplier_id = :supplier_id");

        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);

        return $this->db->execute();
    }

    public function setPartnerApproval($collectionId, $approved) {
        $this->db->query('UPDATE collections SET partner_approved = :approved WHERE collection_id = :id');
        $this->db->bind(':approved', $approved ? 1 : 0);
        $this->db->bind(':id', $collectionId);
        return $this->db->execute();
    }

    public function assignBagsAndApprove($collectionId, $bags) {
        $this->db->beginTransaction();

        try {
            // First, store the total number of bags in collections table
            $this->db->query('UPDATE collections 
                             SET bags = :bag_count,
                                 partner_approved = 1 
                             WHERE collection_id = :collection_id');
            $this->db->bind(':bag_count', count($bags));
            $this->db->bind(':collection_id', $collectionId);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to update collection');
            }

            // Then, insert each bag into collection_bags table
            foreach ($bags as $bagToken) {
                $this->db->query('INSERT INTO collection_bags 
                                 (collection_id, bag_token) 
                                 VALUES (:collection_id, :bag_token)');
                $this->db->bind(':collection_id', $collectionId);
                $this->db->bind(':bag_token', $bagToken);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to insert bag token');
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }



    public function startCollection($collectionId) {
        $this->db->query('UPDATE collections 
                          SET status = "In Progress",
                              start_time = NOW() 
                          WHERE collection_id = :collection_id');
        
        $this->db->bind(':collection_id', $collectionId);
        $this->db->execute();
        $this->db->query('INSERT INTO collection_supplier_records (collection_id, supplier_id, status, is_scheduled) 
                          VALUES (:collection_id, :supplier_id, :status, :is_scheduled)');
    }

    public function createSupplierRecord($data) {
        $this->db->query('INSERT INTO collection_supplier_records 
                          (collection_id, supplier_id, status, is_scheduled) 
                          VALUES (:collection_id, :supplier_id, :status, :is_scheduled)');
        
        $this->db->bind(':collection_id', $data['collection_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':is_scheduled', $data['is_scheduled']);
        
        return $this->db->execute();
    }

    public function startCollectionAndCreateRecords($collectionId, $routeSuppliers) {
        $this->db->beginTransaction();

        try {
            // 1. Update collection with start time and driver approval
            $this->db->query('UPDATE collections 
                             SET start_time = NOW(),
                                 driver_approved = 1,
                                 status = "In Progress"
                             WHERE collection_id = :collection_id');
            $this->db->bind(':collection_id', $collectionId);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to update collection');
            }

            // 2. Insert records for each supplier
            foreach ($routeSuppliers as $supplier) {
                $this->db->query('INSERT INTO collection_supplier_records 
                                 (collection_id, supplier_id, status, is_scheduled) 
                                 VALUES (:collection_id, :supplier_id, :status, :is_scheduled)');
                
                $this->db->bind(':collection_id', $collectionId);
                $this->db->bind(':supplier_id', $supplier->supplier_id);
                $this->db->bind(':status', 'Added');
                $this->db->bind(':is_scheduled', 1);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to create supplier record');
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function setPartnerReady($collectionId) {
        $this->db->query('UPDATE collections 
                          SET partner_approved = 1 
                          WHERE collection_id = :collection_id');
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->execute();
    }

    public function setDriverReady($collectionId) {
        $this->db->beginTransaction();
        try {
            // Set driver approval and start time
            $this->db->query('UPDATE collections 
                             SET driver_approved = 1,
                                 start_time = NOW() 
                             WHERE collection_id = :collection_id');
            $this->db->bind(':collection_id', $collectionId);
            if (!$this->db->execute()) {
                throw new Exception('Failed to update collection');
            }

            // Insert supplier records - corrected JOIN path
            $this->db->query('INSERT INTO collection_supplier_records 
                                (collection_id, supplier_id, status, is_scheduled)
                             SELECT 
                                :collection_id,
                                rs.supplier_id,
                                "Added",
                                1
                             FROM collections c
                             JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                             JOIN route_suppliers rs ON cs.route_id = rs.route_id
                             WHERE c.collection_id = :collection_id
                             AND rs.is_active = 1
                             AND rs.is_deleted = 0');
                             
            $this->db->bind(':collection_id', $collectionId);
            if (!$this->db->execute()) {
                throw new Exception('Failed to create supplier records');
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function createCollectionWithBags($scheduleId, $bagIds) {
        $this->db->beginTransaction();
        try {
            // Create collection entry
            $this->db->query('INSERT INTO collections (schedule_id, status) 
                             VALUES (:schedule_id, "Pending")');
            $this->db->bind(':schedule_id', $scheduleId);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to create collection');
            }

            $collectionId = $this->db->lastInsertId();

            foreach ($bagIds as $bagId) {
                // First verify the bag exists in collection_bags
                $this->db->query('SELECT * FROM collection_bags WHERE bag_id = :bag_id');
                $this->db->bind(':bag_id', $bagId);
                $bagDetails = $this->db->single();

                if (!$bagDetails) {
                    throw new Exception('Bag not found in collection_bags: ' . $bagId);
                }

                // Create usage history with the bag's actual details
                $this->db->query('INSERT INTO bag_usage_history 
                                 (bag_id, collection_id, action, capacity_kg, bag_weight_kg) 
                                 VALUES (:bag_id, :collection_id, "added", :capacity_kg, :bag_weight_kg)');
                
                $this->db->bind(':bag_id', $bagId);
                $this->db->bind(':collection_id', $collectionId);
                $this->db->bind(':capacity_kg', $bagDetails->capacity_kg);
                $this->db->bind(':bag_weight_kg', $bagDetails->bag_weight_kg);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to create bag usage history');
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            error_log('Error in createCollectionWithBags: ' . $e->getMessage());
            $this->db->rollBack();
            return false;
        }
    }

    public function isDriverReady($scheduleId) {
        $this->db->query('SELECT driver_approved FROM collections WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        $result = $this->db->single();
        return $result ? $result->driver_approved : false;
    }

    public function getUpcomingCollectionIdByScheduleId($scheduleId) {
        $this->db->query('SELECT collection_id 
                          FROM collections 
                          WHERE schedule_id = :schedule_id 
                          AND status NOT IN ("Cancelled", "Completed")');
        $this->db->bind(':schedule_id', $scheduleId);
        $result = $this->db->single();
        return $result ? $result->collection_id : false;
    }

    public function getUpcomingCollectionDetailsByScheduleId($scheduleId) {
        $this->db->query('SELECT *
                          FROM collections 
                          WHERE schedule_id = :schedule_id 
                          AND status != "Completed"'
                          );
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }



    public function getCollectionBagsByCollectionId($collectionId) {
        $this->db->query('SELECT 
                            buh.*,
                            cb.status as bag_status
                          FROM bag_usage_history buh
                          JOIN collection_bags cb ON buh.bag_id = cb.bag_id
                          WHERE buh.collection_id = :collection_id
                          AND buh.action = "added"
                          ORDER BY buh.timestamp ASC');
        
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet();
    }

    public function updateCollection($data) {
        $sql = "UPDATE collections SET 
                    status = :status,
                    vehicle_manager_approved = :vehicle_manager_approved,
                    initial_weight_bridge = :initial_weight_bridge,
                    vehicle_manager_id = :vehicle_manager_id,
                    vehicle_manager_approved_at = :vehicle_manager_approved_at
                WHERE collection_id = :collection_id";

        $this->db->query($sql); // Prepare the query

        // Bind the parameters
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':vehicle_manager_approved', $data['vehicle_manager_approved']);
        $this->db->bind(':initial_weight_bridge', $data['initial_weight_bridge']);
        $this->db->bind(':vehicle_manager_id', $data['vehicle_manager_id']);
        $this->db->bind(':vehicle_manager_approved_at', $data['vehicle_manager_approved_at']);
        $this->db->bind(':collection_id', $data['collection_id']);

        return $this->db->execute(); // Execute the prepared statement
    }

    public function checkBag($bagId) {
        // First check if bag exists and get its status from collection_bags
        $this->db->query('SELECT cb.*, 
                          (SELECT buh.collection_id 
                           FROM bag_usage_history buh 
                           WHERE buh.bag_id = cb.bag_id 
                           AND buh.action = "added" 
                           AND NOT EXISTS (
                               SELECT 1 
                               FROM bag_usage_history buh2 
                               WHERE buh2.bag_id = cb.bag_id 
                               AND buh2.collection_id = buh.collection_id 
                               AND buh2.action IN ("emptied", "reused")
                           )
                           LIMIT 1) as active_collection_id
                         FROM collection_bags cb 
                         WHERE cb.bag_id = :bag_id');
        
        $this->db->bind(':bag_id', $bagId);
        $bag = $this->db->single();

        // If bag doesn't exist
        if (!$bag) {
            return [
                'success' => false,
                'message' => 'Bag not found'
            ];
        }

        // If bag is marked as inactive
        if ($bag->status === 'inactive') {
            return [
                'success' => false,
                'message' => 'This bag is marked as inactive'
            ];
        }

        // If bag is currently in use in another collection
        if ($bag->active_collection_id) {
            return [
                'success' => false,
                'message' => 'This bag is currently in use in collection #' . $bag->active_collection_id
            ];
        }

        // If all checks pass, bag is available
        return [
            'success' => true,
            'message' => 'Bag is available for use',
            'data' => [
                'bag_id' => $bag->bag_id,
                'capacity_kg' => $bag->capacity_kg,
                'bag_weight_kg' => $bag->bag_weight_kg,
                'status' => $bag->status
            ]
        ];
    }

    public function getPendingCollections() {
        $this->db->query('
            SELECT 
                c.collection_id,
                r.route_name,
                CONCAT(u.first_name, " ", u.last_name) as driver_name,
                c.status,
                c.created_at,
                c.bags,
                c.vehicle_manager_approved,
                cs.route_id,
                cs.driver_id,
                cs.day
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN users u ON cs.driver_id = u.user_id
            WHERE c.status = "Pending" 
            AND c.vehicle_manager_approved = 0
            ORDER BY c.created_at DESC
        ');
        
        return $this->db->resultSet();
    }

    public function getPendingCollectionRequests() {
        $this->db->query('
            SELECT 
                c.collection_id,
                r.route_name,
                CONCAT(u.first_name, " ", u.last_name) as driver_name,
                c.fertilizer_distributed,  -- This will be used for "Deliveries" column
                c.status,
                c.created_at,
                c.vehicle_manager_approved
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN users u ON cs.driver_id = u.user_id
            WHERE c.status = "Pending" 
            AND c.vehicle_manager_approved = 0
            AND c.bags_added = 1  -- Only show collections where bags have been added
            ORDER BY c.created_at DESC
        ');
        
        return $this->db->resultSet();
    }

    public function getCollectionDetails($id) {
        $this->db->query('
            SELECT 
                c.collection_id,
                c.status as collection_status,
                c.created_at,
                c.start_time,
                c.end_time,
                c.total_quantity,
                c.bags,
                c.fertilizer_distributed,
                
                cs.schedule_id,
                cs.day,
                cs.week_number,
                
                r.*,
                
                d.driver_id,
                d.status as driver_status,
                
                u.first_name,
                u.last_name,
                
                s.shift_id,
                s.start_time as shift_start,
                s.end_time as shift_end,
                s.shift_name
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN drivers d ON cs.driver_id = d.driver_id
            JOIN users u ON d.user_id = u.user_id
            JOIN collection_shifts s ON cs.shift_id = s.shift_id
            WHERE c.collection_id = :id
            AND cs.is_deleted = 0
            AND cs.is_active = 1
        ');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function approveCollection($collectionId) {
        try {
            $this->db->beginTransaction();

            // 1. Update the collection status
            $this->db->query('
                UPDATE collections 
                SET 
                    status = "In Progress",
                    start_time = CURRENT_TIMESTAMP(),
                    vehicle_manager_id = :vehicle_manager_id,
                    vehicle_manager_approved = 1,
                    vehicle_manager_approved_at = CURRENT_TIMESTAMP(),
                    bags_added = 1
                WHERE collection_id = :collection_id
            ');

            $this->db->bind(':vehicle_manager_id', $_SESSION['user_id']);
            $this->db->bind(':collection_id', $collectionId);
            $this->db->execute();

            // 2. Get the route ID and its suppliers with stop order
            $this->db->query('
                SELECT 
                    rs.supplier_id,
                    rs.stop_order,
                    s.latitude,
                    s.longitude
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                JOIN route_suppliers rs ON cs.route_id = rs.route_id
                JOIN suppliers s ON rs.supplier_id = s.supplier_id
                WHERE c.collection_id = :collection_id
                AND rs.is_active = 1
                AND rs.is_deleted = 0
                AND s.is_active = 1
                AND s.is_deleted = 0
                ORDER BY rs.stop_order ASC
            ');
            
            $this->db->bind(':collection_id', $collectionId);
            $suppliers = $this->db->resultSet();

            // 3. Insert records for each supplier
            $this->db->query('
                INSERT INTO collection_supplier_records 
                (
                    collection_id, 
                    supplier_id, 
                    status, 
                    quantity,
                    is_scheduled
                ) 
                VALUES 
                (
                    :collection_id, 
                    :supplier_id, 
                    "Added",
                    0.00,
                    1
                )
            ');

            foreach ($suppliers as $supplier) {
                $this->db->bind(':collection_id', $collectionId);
                $this->db->bind(':supplier_id', $supplier->supplier_id);
                $this->db->execute();
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in approveCollection: " . $e->getMessage());
            return false;
        }
    }

    public function getBagsByCollectionId($collectionId) {
        $this->db->query('
            SELECT *
            FROM bag_usage_history
            WHERE collection_id = :collection_id
        ');

        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet();  // This will return an array of bags
    }

    public function getVehicleIdFromCollection($collectionId) {
        $this->db->query('
            SELECT v.vehicle_id
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            WHERE c.collection_id = :collection_id
        ');
        
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->single();
        
        return $result ? $result->vehicle_id : null;
    }

} 
