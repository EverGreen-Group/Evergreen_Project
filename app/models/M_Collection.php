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

    public function createCollectionWithBags($scheduleId, $bags) {
        $this->db->beginTransaction();
        try {
            // 1. Create collection entry first
            $this->db->query('INSERT INTO collections (schedule_id, partner_approved, bags) 
                             VALUES (:schedule_id, 1, :bags)');
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->bind(':bags', count($bags));
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to create collection');
            }

            $collectionId = $this->db->lastInsertId();

            // 2. For each bag token
            foreach ($bags as $bagToken) {
                // First, check if bag exists, if not create it
                $this->db->query('INSERT IGNORE INTO bags (token, status) 
                                 VALUES (:token, "In Use")');
                $this->db->bind(':token', $bagToken);
                $this->db->execute();

                // Get the bag_id (whether it was just inserted or already existed)
                $this->db->query('SELECT bag_id FROM bags WHERE token = :token');
                $this->db->bind(':token', $bagToken);
                $bagResult = $this->db->single();
                
                if (!$bagResult) {
                    throw new Exception('Failed to get bag ID');
                }

                // Create collection_bags record
                $this->db->query('INSERT INTO collection_bags 
                                 (collection_id, bag_id) 
                                 VALUES (:collection_id, :bag_id)');
                $this->db->bind(':collection_id', $collectionId);
                $this->db->bind(':bag_id', $bagResult->bag_id);
                
                if (!$this->db->execute()) {
                    throw new Exception('Failed to assign bag');
                }
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
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

    public function isPartnerReady($scheduleId) {
        $this->db->query('SELECT partner_approved FROM collections WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        $result = $this->db->single();
        return $result ? $result->partner_approved : false;
    }

    public function getCollectionIdByScheduleId($scheduleId) {
        $this->db->query('SELECT collection_id FROM collections WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        $result = $this->db->single();
        return $result ? $result->collection_id : false;
    }

    public function getCollectionBags($collectionId) {
        $this->db->query('SELECT bag_token FROM collection_bags WHERE collection_id = :collection_id');
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

} 
