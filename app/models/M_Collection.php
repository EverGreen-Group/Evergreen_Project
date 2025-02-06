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
                    c.* FROM collections c
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
        $this->db->query("
            SELECT 
                csr.*,
                s.*,
                CONCAT(u.first_name, ' ', u.last_name) as supplier_name
            FROM collection_supplier_records csr
            JOIN suppliers s ON csr.supplier_id = s.supplier_id
            JOIN users u ON s.user_id = u.user_id
            WHERE csr.collection_id = :collection_id
            ORDER BY csr.record_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet();
    }

    public function getCollectionSuppliersCount($collectionId) {
        $this->db->query("
            SELECT 
                COUNT(*) as total_suppliers,
                SUM(CASE WHEN approval_status = 'APPROVED' THEN 1 ELSE 0 END) as collected_count,
                SUM(CASE WHEN approval_status = 'PENDING' THEN 1 ELSE 0 END) as remaining_count
            FROM collection_supplier_records csr
            JOIN suppliers s ON csr.supplier_id = s.supplier_id
            WHERE csr.collection_id = :collection_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->single();
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
        $this->db->beginTransaction();
        try {
            // Update the collection status to "In Progress" or similar
            $this->db->query('UPDATE collections SET status = "In Progress" WHERE collection_id = :collection_id');
            $this->db->bind(':collection_id', $collectionId);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to start collection');
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            error_log('Error in startCollection: ' . $e->getMessage());
            $this->db->rollBack();
            return false;
        }
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

    public function createCollection($scheduleId) {
        $this->db->beginTransaction();
        try {
            // Check for collection exceptions
            $this->db->query('SELECT new_time FROM collection_exceptions WHERE schedule_id = :schedule_id AND exception_date = CURDATE()');
            $this->db->bind(':schedule_id', $scheduleId);
            $exception = $this->db->single(); // Fetch the single result

            // Determine the start time
            if ($exception) {
                // Use new_time if an exception exists
                $startTime = $exception->new_time;
            } else {
                // If no exception, get the normal start_time from collection_schedules
                $this->db->query('SELECT cs_shift.start_time 
                                  FROM collection_schedules cs
                                  JOIN collection_shifts cs_shift ON cs.shift_id = cs_shift.shift_id
                                  WHERE cs.schedule_id = :schedule_id');
                $this->db->bind(':schedule_id', $scheduleId);
                $schedule = $this->db->single(); // Fetch the single result

                // Use the start_time from the schedule
                $startTime = $schedule ? $schedule->start_time : null; // Handle case where no schedule is found
            }

            // Format the start time
            $formattedStartTime = date('Y-m-d H:i:s', strtotime($startTime));

            // Create collection entry
            $this->db->query('INSERT INTO collections (schedule_id, status, start_time) 
                              VALUES (:schedule_id, "Pending", :start_time)');
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->bind(':start_time', $formattedStartTime);

            if (!$this->db->execute()) {
                throw new Exception('Failed to create collection');
            }

            $collectionId = $this->db->lastInsertId();

            // Now, insert records for each supplier
            $this->db->query('
                SELECT 
                    rs.supplier_id,
                    rs.stop_order,
                    s.latitude,
                    s.longitude
                FROM collection_schedules cs
                JOIN routes r ON cs.route_id = r.route_id
                JOIN route_suppliers rs ON r.route_id = rs.route_id
                JOIN suppliers s ON rs.supplier_id = s.supplier_id
                WHERE cs.schedule_id = :schedule_id
                AND rs.is_active = 1
                AND rs.is_deleted = 0
                AND s.is_active = 1
                AND s.is_deleted = 0
                ORDER BY rs.stop_order ASC
            ');

            $this->db->bind(':schedule_id', $scheduleId);
            $suppliers = $this->db->resultSet();

            // Insert records for each supplier
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

            // Select fertilizer order items that are scheduled and match the route_id
            $this->db->query('
                SELECT 
                    fo.supplier_id,
                    fo.item_id,
                    fo.quantity
                FROM fertilizer_order_items fo
                JOIN collection_schedules cs ON fo.route_id = cs.route_id
                WHERE cs.schedule_id = :schedule_id
                AND fo.is_schedule = 1
            ');

            $this->db->bind(':schedule_id', $scheduleId);
            $fertilizerOrders = $this->db->resultSet();

            // Insert records with item_id into collection_fertilizer_records
            if (!empty($fertilizerOrders)) {
                $this->db->query('
                    INSERT INTO collection_fertilizer_records 
                    (
                        collection_id,
                        supplier_id,
                        item_id,
                        quantity,
                        status,
                        is_scheduled
                    ) 
                    VALUES 
                    (
                        :collection_id,
                        :supplier_id,
                        :item_id,
                        :quantity,
                        "Pending",
                        1
                    )
                ');

                foreach ($fertilizerOrders as $order) {
                    $this->db->bind(':collection_id', $collectionId);
                    $this->db->bind(':supplier_id', $order->supplier_id);
                    $this->db->bind(':item_id', $order->item_id);
                    $this->db->bind(':quantity', $order->quantity);
                    $this->db->execute();
                }
            }

            // Calculate total quantity from fertilizer orders
            $totalQuantity = array_sum(array_column($fertilizerOrders, 'quantity'));

            // Update total_quantity in collections entry
            $this->db->query('
                UPDATE collections 
                SET total_quantity = :total_quantity 
                WHERE collection_id = :collection_id
            ');

            $this->db->bind(':total_quantity', $totalQuantity);
            $this->db->bind(':collection_id', $collectionId);
            $this->db->execute();

            $this->db->commit(); // Commit the transaction
            return true; // Return true if everything is successful
        } catch (Exception $e) {
            error_log('Error in createCollection: ' . $e->getMessage());
            $this->db->rollBack();
            return false; // Return false if there was an error
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
                          WHERE schedule_id = :schedule_id'
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

// NEW STUFF NEED TO TEST IT

    public function addBagUsageHistory($data) {
        try {
            // First check if bag is available
            $this->db->query('SELECT status FROM collection_bags WHERE bag_id = :bag_id');
            $this->db->bind(':bag_id', $data->bag_id);
            $bagStatus = $this->db->single();

            if (!$bagStatus || $bagStatus->status !== 'active') {
                return [
                    'success' => false,
                    'message' => 'Bag is not available for use'
                ];
            }

            // Begin transaction
            $this->db->beginTransaction();

            // Insert into bag_usage_history
            $this->db->query('INSERT INTO bag_usage_history (
                bag_id,
                supplier_id,
                actual_weight_kg,
                leaf_type_id,
                leaf_age,
                moisture_level,
                deduction_notes,
                action,
                timestamp,
                collection_id
            ) VALUES (
                :bag_id,
                :supplier_id,
                :actual_weight_kg,
                :leaf_type_id,
                :leaf_age,
                :moisture_level,
                :notes,
                :action,
                :timestamp,
                :collection_id
            )');

            $this->db->bind(':bag_id', $data->bag_id);
            $this->db->bind(':supplier_id', $data->supplier_id);
            $this->db->bind(':actual_weight_kg', $data->actual_weight_kg);
            $this->db->bind(':leaf_type_id', $data->leaf_type_id);
            $this->db->bind(':leaf_age', $data->leaf_age);
            $this->db->bind(':moisture_level', $data->moisture_level);
            $this->db->bind(':notes', $data->notes);
            $this->db->bind(':action', 'added');
            $this->db->bind(':timestamp', date('Y-m-d H:i:s', strtotime($data->timestamp)));
            $this->db->bind(':collection_id', $data->collection_id);

            $this->db->execute();

            // Update bag status to indicate it's in use
            $this->db->query('UPDATE collection_bags SET status = "inactive" WHERE bag_id = :bag_id');
            $this->db->bind(':bag_id', $data->bag_id);
            $this->db->execute();

            // Commit transaction
            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Bag assigned successfully'
            ];

        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to assign bag: ' . $e->getMessage()
            ];
        }
    }

    public function finalizeSupplierCollection($data) {
        try {
            // Begin transaction
            $this->db->beginTransaction();

            // Get total weight from assigned bags
            $this->db->query('SELECT COALESCE(SUM(actual_weight_kg), 0) as total_weight 
                FROM bag_usage_history 
                WHERE supplier_id = :supplier_id 
                AND action = "added" 
                AND collection_id = :collection_id');
            $this->db->bind(':supplier_id', $data->supplier_id);
            $this->db->bind(':collection_id', $data->collection_id);
            $result = $this->db->single();
            $totalWeight = $result->total_weight;

            // Update collection_supplier_records
            $this->db->query('UPDATE collection_supplier_records SET
                status = :status,
                quantity = :quantity,
                collection_time = :collection_time,
                notes = :notes
            WHERE supplier_id = :supplier_id AND collection_id = :collection_id');

            $this->db->bind(':supplier_id', $data->supplier_id);
            $this->db->bind(':status', 'Collected');
            $this->db->bind(':quantity', $totalWeight);
            $this->db->bind(':collection_time', date('Y-m-d H:i:s', strtotime($data->collection_time)));
            $this->db->bind(':notes', $data->notes ?? null);
            $this->db->bind(':collection_id', $data->collection_id);

            if (!$this->db->execute()) {
                error_log('Failed to update collection supplier record: ' . implode(', ', $this->db->errorInfo()));
                throw new Exception('Failed to update collection supplier record');
            }

            // Update total_quantity in collections table
            $this->db->query('UPDATE collections SET
                total_quantity = total_quantity + :totalWeight
            WHERE collection_id = :collection_id');

            $this->db->bind(':totalWeight', $totalWeight);
            $this->db->bind(':collection_id', $data->collection_id);

            if (!$this->db->execute()) {
                error_log('Failed to update total quantity in collections: ' . implode(', ', $this->db->errorInfo()));
                throw new Exception('Failed to update total quantity in collections');
            }

            // Commit transaction
            $this->db->commit();

            return [
                'success' => true,
                'message' => 'Collection finalized successfully',
                'data' => [
                    'total_weight' => $totalWeight
                ]
            ];

        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to finalize collection: ' . $e->getMessage()
            ];
        }
    }

    public function getAssignedBags($supplierId) {
        try {
            $this->db->query('SELECT 
                buh.bag_id,
                buh.actual_weight_kg,
                buh.leaf_type,
                buh.leaf_age,
                buh.moisture_level,
                COALESCE(csr.status, "Pending") as status
                FROM bag_usage_history buh
                LEFT JOIN collection_supplier_records csr ON buh.collection_id = csr.record_id
                WHERE buh.supplier_id = :supplier_id
                AND buh.action = "added"
                ORDER BY buh.timestamp DESC');
            
            $this->db->bind(':supplier_id', $supplierId);
            $bags = $this->db->resultSet();

            return [
                'success' => true,
                'bags' => $bags
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch assigned bags: ' . $e->getMessage()
            ];
        }
    }

    public function finalizeCollection($collectionId) {
        try {
            // Get the number of bags used for the given collection_id
            $this->db->query('SELECT COUNT(*) as bags_used FROM bag_usage_history WHERE collection_id = :collection_id AND action = "added"');
            $this->db->bind(':collection_id', $collectionId);
            $result = $this->db->single();
            $bagsUsed = $result->bags_used;

            // Update the collection status, end_time, and bags used
            $this->db->query('UPDATE collections SET 
                status = "Completed", 
                end_time = NOW(), 
                bags = :bags_used 
                WHERE collection_id = :collection_id');
            
            $this->db->bind(':bags_used', $bagsUsed);
            $this->db->bind(':collection_id', $collectionId);

            if ($this->db->execute()) {
                return ['success' => true];
            } else {
                return ['success' => false];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to finalize collection: ' . $e->getMessage()
            ];
        }
    }

    public function getCollectionTeaLeafTypes() {
        try {
            // Query to fetch all active leaf types
            $this->db->query('SELECT leaf_type_id, name FROM leaf_types WHERE is_active = 1');
            
            $leafTypes = $this->db->resultSet(); // Fetch the results

            return [
                'success' => true,
                'leafTypes' => $leafTypes // Return the leaf types
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch leaf types: ' . $e->getMessage()
            ];
        }
    }

    public function getCollectionsByDate($date) {
        // Ensure the date is formatted correctly (YYYY-MM-DD)
        $sql = "
        SELECT * FROM collections c
        LEFT JOIN collection_schedules cs ON
        c.schedule_id = cs.schedule_id
        WHERE DATE(c.created_at) = :date"; // Check against created_at
        $this->db->query($sql);
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }


    public function checkCollectionExists($driverId) {
        // Check for collections that are either "Pending" or "In Progress" for the given driver
        $this->db->query('
            SELECT c.collection_id 
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            WHERE cs.driver_id = :driver_id 
            AND c.status IN ("Pending", "In Progress") 
            LIMIT 1
        '); // Limit to 1 to get the first matching collection

        $this->db->bind(':driver_id', $driverId);
        $result = $this->db->single();

        return $result ? $result->collection_id : null; // Return collection_id or null if not found
    }


} 