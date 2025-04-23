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
                v.license_plate,
                CONCAT(p.first_name, ' ', p.last_name) as driver_name
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d ON cs.driver_id = d.driver_id
            JOIN profiles p ON d.profile_id = p.profile_id
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

    public function getAllCollections() {
        $sql = "SELECT 
                c.*,
                cs.schedule_id,
                r.route_name,
                v.license_plate,
                CONCAT(p.first_name, ' ', p.last_name) as driver_name
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d ON cs.driver_id = d.driver_id
            JOIN profiles p ON d.profile_id = p.profile_id
            WHERE cs.is_active = 1
            -- AND cs.is_deleted = 0
            ORDER BY c.start_time ASC";
        
        $this->db->query($sql);
        $result = $this->db->resultSet();
        
        // test debug
        // var_dump($sql);
        // var_dump($result);
        
        return $result;
    }

    public function getCollectionById($collectionId) { // repeated in collectionDetails ill check later
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
                p.*
            FROM collection_supplier_records csr
            JOIN suppliers s ON csr.supplier_id = s.supplier_id
            JOIN profiles p on s.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE csr.collection_id = :collection_id
            ORDER BY csr.record_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet();
    }

    public function getCollectionSuppliersStatus($collectionId, $supplierId) {  // tested
        $this->db->query("
            SELECT DISTINCT
                csr.status
            FROM collection_supplier_records csr
            WHERE csr.collection_id = :collection_id AND csr.supplier_id = :supplier_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single();
    }


    


    public function getSupplierCollections($supplierId) {
        $this->db->query("
            SELECT DISTINCT
                c.collection_id,
                c.status,
                c.created_at,
                cs.driver_id,
                r.vehicle_id,
                csr.quantity,
                csr.notes
            FROM collection_supplier_records csr
            JOIN collections c ON csr.collection_id = c.collection_id
            JOIN collection_schedules cs on c.schedule_id = cs.schedule_id
            JOIN routes r on cs.route_id = r.route_id
            WHERE csr.supplier_id = :supplier_id
            AND c.status = 'Completed' OR c.status = 'Pending'
            ORDER BY c.collection_id DESC
        ");
        
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }

    public function getSupplierBagsForCollection($supplier_id, $collection_id) {
        $this->db->query('SELECT bag_id, actual_weight_kg, leaf_age, moisture_level, 
                          deduction_notes, timestamp, leaf_type_id, is_finalized 
                          FROM bag_usage_history 
                          WHERE collection_id = :collection_id 
                          AND supplier_id = :supplier_id 
                          ORDER BY timestamp DESC');
        
        $this->db->bind(':collection_id', $collection_id);
        $this->db->bind(':supplier_id', $supplier_id);
        
        return $this->db->resultSet();
    }
    
    

    public function getCollectionSuppliersCount($collectionId) {    // tested
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


    public function setDriverReady($collectionId) {
        $this->db->beginTransaction();
        try {
            $this->db->query('UPDATE collections 
                             SET driver_approved = 1,
                                 start_time = NOW() 
                             WHERE collection_id = :collection_id');
            $this->db->bind(':collection_id', $collectionId);
            if (!$this->db->execute()) {
                throw new Exception('Failed to update collection');
            }

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

    public function createCollection($scheduleId) { // TESTED
        $this->db->beginTransaction();
        try {

            //getting all the suppliers from the route suppliers and then adding them to the collection supplier records

                $this->db->query('SELECT cs.start_time 
                                  FROM collection_schedules cs
                                  WHERE cs.schedule_id = :schedule_id');
                $this->db->bind(':schedule_id', $scheduleId);
                $schedule = $this->db->single(); 

                $startTime = $schedule ? $schedule->start_time : null; 
            

            $formattedStartTime = date('Y-m-d H:i:s', strtotime($startTime));

            $this->db->query('INSERT INTO collections (schedule_id, status, start_time) 
                              VALUES (:schedule_id, "Pending", :start_time)');
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->bind(':start_time', $formattedStartTime);

            if (!$this->db->execute()) {
                throw new Exception('Failed to create collection');
            }

            $collectionId = $this->db->lastInsertId();


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


            $this->db->commit(); 
            return $collectionId; 
        } catch (Exception $e) {
            $this->db->rollBack();
            return false; 
        }
    }



    public function getCollectionByScheduleId($scheduleId) {
        $this->db->query('SELECT * 
                          FROM collections 
                          WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();

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
        $this->db->query('SELECT cb.*, 
                          (SELECT buh.collection_id 
                           FROM bag_usage_history buh 
                           JOIN collections c ON buh.collection_id = c.collection_id
                           WHERE buh.bag_id = cb.bag_id 
                           AND buh.action = "added" 
                           AND c.status IN ("Pending", "In Progress", "Awaiting Inventory Addition")
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
            // Set flash message for occupied bag
            $_SESSION['flash'] = 'This bag is currently in use in collection #' . $bag->active_collection_id;
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


    public function saveBag($bagData) { // TESTED
        $this->db->beginTransaction();
        
        try {
            $this->db->query('SELECT * FROM collection_bags WHERE bag_id = :bag_id');
            $this->db->bind(':bag_id', $bagData['bag_id']);
            $existingBag = $this->db->single();
            
            if ($existingBag) {
                // Check if bag is active
                if ($existingBag->status === 'active') {
                    // Check if actual weight exceeds capacity
                    if ($bagData['actual_weight'] > $existingBag->capacity_kg) {
                        $this->db->rollBack();
                        return [
                            'success' => false,
                            'message' => 'Actual weight exceeds bag capacity'
                        ];
                    }
                    
                    $this->db->query('UPDATE collection_bags SET status = "inactive" WHERE bag_id = :bag_id');
                    $this->db->bind(':bag_id', $bagData['bag_id']);
                    
                    if (!$this->db->execute()) {
                        $this->db->rollBack();
                        return [
                            'success' => false,
                            'message' => 'Failed to update bag status'
                        ];
                    }
                } else {
                    $this->db->rollBack();
                    return [
                        'success' => false,
                        'message' => 'Bag is already in use'
                    ];
                }
            } else {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Bag not found in system'
                ];
            }
            
            $this->db->query('INSERT INTO bag_usage_history 
                             (bag_id, collection_id, capacity_kg, actual_weight_kg, leaf_age, 
                             moisture_level, deduction_notes, supplier_id, action, leaf_type_id) 
                             VALUES 
                             (:bag_id, :collection_id, :capacity_kg, :actual_weight_kg, :leaf_age, 
                             :moisture_level, :deduction_notes, :supplier_id, :action, :leaf_type_id)');
            
            $this->db->bind(':bag_id', $bagData['bag_id']);
            $this->db->bind(':collection_id', $bagData['collection_id']);
            $this->db->bind(':capacity_kg', $existingBag->capacity_kg);
            $this->db->bind(':actual_weight_kg', $bagData['actual_weight']);
            $this->db->bind(':leaf_age', $bagData['leaf_age']);
            $this->db->bind(':moisture_level', $bagData['moisture_level']);
            $this->db->bind(':deduction_notes', $bagData['notes']);
            $this->db->bind(':supplier_id', $bagData['supplier_id']);
            $this->db->bind(':action', 'added');
            $this->db->bind(':leaf_type_id', $bagData['leaf_type_id']);
            
            if (!$this->db->execute()) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Failed to record bag usage history'
                ];
            }
            
            $this->db->commit();
            return [
                'success' => true,
                'message' => 'Bag added successfully'
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error in saveBag: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'System error occurred'
            ];
        }
    }


    // public function getCollectionDetails($supplier_id) {
    //     $this->db->query("
    //         SELECT csr.* , c.status AS collection_status
    //         FROM collection_supplier_records csr
    //         JOIN collections c ON csr.collection_id = c.collection_id
    //         WHERE csr.supplier_id = :supplier_id
    //         ORDER BY csr.collection_time DESC
    //     ");
        
    //     $this->db->bind(':supplier_id', $supplier_id);
    //     return $this->db->resultSet();
    // }


    public function getCollectionDetails($id) { // TESTED
        $this->db->query("
            SELECT 
                c.collection_id,
                c.status AS collection_status,
                c.created_at,
                c.start_time,
                c.end_time,
                c.total_quantity,
                c.bags,
                
                cs.schedule_id,
                cs.day,
                cs.start_time AS schedule_start,
                cs.end_time AS schedule_end,
                
                r.*,
                
                d.driver_id,
                d.status AS driver_status,
                
                p.first_name,
                p.last_name,
    
                v.*
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN drivers d ON cs.driver_id = d.driver_id
            JOIN profiles p ON d.profile_id = p.profile_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            WHERE c.collection_id = :id
              AND cs.is_deleted = 0
              AND cs.is_active = 1
        ");
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    

// NEW STUFF NEED TO TEST IT

    public function getCollectionSupplierRecordDetails($collectionId, $supplierId) {
        $sql = "SELECT * FROM collection_supplier_records WHERE collection_id = :collection_id AND supplier_id = :supplier_id";
        $this->db->query($sql);
        $this->db->bind(":collection_id", $collectionId);
        $this->db->bind(":supplier_id", $supplierId);
        $this->db->single();
    }



    public function finalizeSupplierCollection($collectionId, $supplierId) { // TESTED
        try {
            $this->db->beginTransaction();

            $this->db->query('SELECT COALESCE(SUM(actual_weight_kg), 0) as total_weight 
                FROM bag_usage_history 
                WHERE supplier_id = :supplier_id 
                AND collection_id = :collection_id');
            $this->db->bind(':supplier_id', $supplierId);
            $this->db->bind(':collection_id', $collectionId);
            $result = $this->db->single();
            $totalWeight = $result->total_weight;


            $this->db->query('UPDATE collection_supplier_records SET
                status = :status,
                quantity = :quantity,
                collection_time = NOW()
            WHERE supplier_id = :supplier_id AND collection_id = :collection_id');

            $this->db->bind(':supplier_id', $supplierId);
            $this->db->bind(':status', 'Collected');
            $this->db->bind(':quantity', $totalWeight);
            $this->db->bind(':collection_id', $collectionId);

            $this->db->execute();

            $this->db->query('UPDATE collections SET
                total_quantity = total_quantity + :totalWeight
            WHERE collection_id = :collection_id');

            $this->db->bind(':totalWeight', $totalWeight);
            $this->db->bind(':collection_id', $collectionId);

            $this->db->execute();

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error in finalizeSupplierCollection: ' . $e->getMessage());
            return false;
        }
    }

    // public function getAssignedBags($supplierId, $collectionId) { COULD BE ANOTHER DUP
    //     try {
    //         $this->db->query('
    //             SELECT 
    //             buh.bag_id,
    //             buh.actual_weight_kg,
    //             buh.leaf_type_id,
    //             buh.leaf_age,
    //             buh.moisture_level,
    //             COALESCE(csr.status, "Pending") as status
    //             FROM bag_usage_history buh
    //             LEFT JOIN collection_supplier_records csr ON buh.collection_id = csr.record_id
    //             WHERE buh.supplier_id = :supplier_id
    //             AND buh.collection_id = :collection_id
    //             AND buh.action = "added"
    //             ORDER BY buh.timestamp DESC
    //             ');
            
    //         $this->db->bind(':supplier_id', $supplierId);
    //         $this->db->bind(':collection_id', $collectionId);
    //         $bags = $this->db->resultSet();

    //         return [
    //             'success' => true,
    //             'bags' => $bags
    //         ];

    //     } catch (Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => 'Failed to fetch assigned bags: ' . $e->getMessage()
    //         ];
    //     }
    // }

    // public function finalizeCollection($collectionId) {
    //     try {
    //         // Get the number of bags used for the given collection_id
    //         $this->db->query('SELECT COUNT(*) as bags_used FROM bag_usage_history WHERE collection_id = :collection_id AND action = "added"');
    //         $this->db->bind(':collection_id', $collectionId);
    //         $result = $this->db->single();
    //         $bagsUsed = $result->bags_used;

    //         // Update the collection status, end_time, and bags used
    //         $this->db->query('UPDATE collections SET 
    //             status = "Awaiting Inventory Addition", 
    //             end_time = NOW(), 
    //             bags = :bags_used 
    //             WHERE collection_id = :collection_id');
            
    //         $this->db->bind(':bags_used', $bagsUsed);
    //         $this->db->bind(':collection_id', $collectionId);

    //         // Execute the update for the collections table
    //         if ($this->db->execute()) {
    //             // Check if there are any fertilizer records for this collection
    //             // $this->db->query('SELECT COUNT(*) as count FROM collection_fertilizer_records WHERE collection_id = :collection_id');
    //             // $this->db->bind(':collection_id', $collectionId);
    //             // $fertilizerCount = $this->db->single()->count;

    //             // Update the status of fertilizer items to "Delivered" if records exist
    //             // if ($fertilizerCount > 0) {
    //             //     $this->db->query('UPDATE collection_fertilizer_records SET status = "Delivered" WHERE collection_id = :collection_id');
    //             //     $this->db->bind(':collection_id', $collectionId);
    //             //     $this->db->execute(); // Execute the update for fertilizer items
    //             // }

    //             // Update the status of fertilizer orders to "Delivered"
    //             // $this->db->query('UPDATE fertilizer_order_items SET status = "Delivered" WHERE item_id IN (SELECT item_id FROM collection_fertilizer_records WHERE collection_id = :collection_id)');
    //             // $this->db->bind(':collection_id', $collectionId);
    //             // $this->db->execute(); // Execute the update for fertilizer orders

    //             return ['success' => true];
    //         } else {
    //             return ['success' => false];
    //         }
    //     } catch (Exception $e) {
    //         return [
    //             'success' => false,
    //             'message' => 'Failed to finalize collection: ' . $e->getMessage()
    //         ];
    //     }
    // }

    public function getCollectionTeaLeafTypes() { // TESTED
        try {
            // Query to fetch all active leaf types
            $this->db->query('SELECT leaf_type_id, name FROM leaf_types WHERE is_active = 1');
            
            $leafTypes = $this->db->resultSet();

            return [
                'success' => true,
                'leafTypes' => $leafTypes 
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch leaf types: ' . $e->getMessage()
            ];
        }
    }

    // public function getCollectionsByDate($date) {   // OLD IMPLEMENTATION
    //     // Ensure the date is formatted correctly (YYYY-MM-DD)
    //     $sql = "
    //     SELECT * FROM collections c
    //     LEFT JOIN collection_schedules cs ON
    //     c.schedule_id = cs.schedule_id
    //     WHERE DATE(c.created_at) = :date"; // Check against created_at
    //     $this->db->query($sql);
    //     $this->db->bind(':date', $date);
    //     return $this->db->resultSet();
    // }


    public function checkCollectionExists($driverId) { // TESTED
        // Check if it's pending or in progress
        $this->db->query("
            SELECT c.collection_id 
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            WHERE cs.driver_id = :driver_id 
              AND c.status IN ('Pending', 'In Progress') 
            LIMIT 1
        "); // Limit to 1 
    
        $this->db->bind(':driver_id', $driverId);
        $result = $this->db->single();
    
        return $result ? $result->collection_id : null; 
    }
    

    public function checkCollectionExistsUsingSupplierId($supplierId) { // TESTED
        $this->db->query("
            SELECT c.collection_id 
            FROM collections c
            JOIN collection_supplier_records csr ON csr.collection_id = c.collection_id
            WHERE csr.supplier_id = :supplier_id
              AND c.status IN ('Pending', 'In Progress') 
            LIMIT 1
        "); 
    
        $this->db->bind(':supplier_id', $supplierId);
        $result = $this->db->single();
    
        return $result ? $result->collection_id : null; 
    }
    


    // get collection bags method

    public function getCollectionBags($collectionId, $supplierId) { //tested
        $this->db->query('SELECT * FROM bag_usage_history WHERE supplier_id = :supplier_id AND collection_id = :collection_id');
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet(); 
    }

    public function getBagCapacity($bagId) {        // TESTED
        $this->db->query('SELECT capacity_kg FROM collection_bags WHERE bag_id = :bag_id');
        $this->db->bind(':bag_id', $bagId);
        $result = $this->db->single();
        
        if ($result) {
            return [
                'success' => true,
                'capacity' => $result->capacity_kg
            ];
        } else {
            return [
                'success' => false,
                'capacity' => 50.00 
            ];
        }
    }


    public function getBagById($bagId, $collectionId) { // TESTED
        $this->db->query('SELECT * FROM bag_usage_history 
                         WHERE bag_id = :bag_id 
                         AND collection_id = :collection_id 
                         ORDER BY timestamp DESC 
                         LIMIT 1');
        
        $this->db->bind(':bag_id', $bagId);
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->single();
        
        if ($result) {
            return $result;
        } else {
            return false;
        }
    }


    public function updateBag($bagData) {   // tested
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            $this->db->query('SELECT * FROM bag_usage_history WHERE history_id = :history_id');
            $this->db->bind(':history_id', $bagData['history_id']);
            $existingBag = $this->db->single();
            
            if (!$existingBag) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Bag record not found'
                ];
            }
            
            // Check if the actual weight exceeds capacity
            if ($bagData['actual_weight'] > $existingBag->capacity_kg) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Actual weight exceeds bag capacity'
                ];
            }
            
            // Update the bag record
            $this->db->query('UPDATE bag_usage_history SET 
                             actual_weight_kg = :actual_weight_kg,
                             leaf_type_id = :leaf_type_id,
                             leaf_age = :leaf_age,
                             moisture_level = :moisture_level,
                             deduction_notes = :deduction_notes
                             WHERE history_id = :history_id');
            
            $this->db->bind(':actual_weight_kg', $bagData['actual_weight']);
            $this->db->bind(':leaf_type_id', $bagData['leaf_type']);
            $this->db->bind(':leaf_age', $bagData['leaf_age']);
            $this->db->bind(':moisture_level', $bagData['moisture_level']);
            $this->db->bind(':deduction_notes', $bagData['notes']);
            $this->db->bind(':history_id', $bagData['history_id']);
            
            if (!$this->db->execute()) {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'message' => 'Failed to update bag record'
                ];
            }
            
            // Commit the transaction
            $this->db->commit();
            return [
                'success' => true,
                'message' => 'Bag updated successfully'
            ];
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error in updateBag: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'System error occurred'
            ];
        }
    }


    public function deleteBag($bagId, $collectionId) { // TESTED
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Get the bag record using bag_id and collection_id
            $this->db->query('SELECT * FROM bag_usage_history WHERE bag_id = :bag_id AND collection_id = :collection_id');
            $this->db->bind(':bag_id', $bagId);
            $this->db->bind(':collection_id', $collectionId);
            $bagRecord = $this->db->single();
            
            if (!$bagRecord) {
                $this->db->rollBack();
                return false;
            }
            
            // Delete the bag record from bag_usage_history
            $this->db->query('DELETE FROM bag_usage_history WHERE history_id = :history_id');
            $this->db->bind(':history_id', $bagRecord->history_id);
            
            if (!$this->db->execute()) {
                $this->db->rollBack();
                return false;
            }
            
            // Update the bag status in collection_bags back to active
            $this->db->query('UPDATE collection_bags SET status = "active" WHERE bag_id = :bag_id');
            $this->db->bind(':bag_id', $bagId);
            
            if (!$this->db->execute()) {
                $this->db->rollBack();
                return false;
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function completeCollection($collectionId) { // TESTED
        try {
            $this->db->query('UPDATE collections SET status = :status, end_time = NOW() WHERE collection_id = :collection_id');
            $this->db->bind(':status', 'Awaiting Inventory Addition');
            $this->db->bind(':collection_id', $collectionId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // public function getCollectionSupplierRecordById($recordId) {
    //     $this->db->query("SELECT * FROM collection_supplier_records WHERE record_id = :record_id");
    //     $this->db->bind(':record_id', $recordId);
    //     return $this->db->single();
    // }
    
    public function getBagsByCollectionAndSupplier($collectionId, $supplierId) { // TESTED
        $this->db->query("
            SELECT * FROM bag_usage_history 
            WHERE collection_id = :collection_id 
            AND supplier_id = :supplier_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }

    public function getFilteredCollections($collection_id = null, $schedule_id = null, $status = null,$start_date = null, $end_date = null) {
        // TESTED
        $sql = "SELECT 
                c.*,
                cs.schedule_id,
                r.route_name,
                v.license_plate,
                CONCAT(p.first_name, ' ', p.last_name) as driver_name
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d ON cs.driver_id = d.driver_id
            JOIN profiles p ON d.profile_id = p.profile_id
            WHERE 1=1
            ";
        $this->db->query($sql);    
        return $this->db->resultSet();
    }

    public function getTotalCollections() { // tested
        $sql = "SELECT COUNT(*) as total FROM collections";
        $this->db->query($sql);
        $row = $this->db->single();
        return $row->total;
    }


    public function updateVehicleLocation($vehicleId, $latitude, $longitude) {  // tested
        $this->db->query('UPDATE vehicles
                          SET latitude = :latitude, longitude = :longitude
                          WHERE vehicle_id = :vehicle_id');
        
        $this->db->bind(':latitude', $latitude);
        $this->db->bind(':longitude', $longitude);
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->execute();
    }



} 