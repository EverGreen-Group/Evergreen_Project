<?php

class M_CollectionSchedule {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllSchedules() {
        $sql = "SELECT 
                    cs.schedule_id,
                    cs.route_id,
                    r.route_name,
                    d.driver_id,
                    CONCAT(p.first_name, ' ', p.last_name) AS driver_name,
                    cs.start_time,
                    cs.end_time,
                    cs.day,
                    v.vehicle_id,
                    v.license_plate,
                    cs.created_at,
                    cs.is_active,
                    (SELECT COUNT(*) FROM route_suppliers WHERE route_id = r.route_id) AS supplier_count
                FROM collection_schedules cs
                LEFT JOIN routes r ON cs.route_id = r.route_id
                LEFT JOIN drivers d ON cs.driver_id = d.driver_id
                LEFT JOIN profiles p ON d.profile_id = p.profile_id
                LEFT JOIN vehicles v ON r.vehicle_id = v.vehicle_id
                WHERE cs.is_deleted = 0
                ORDER BY cs.start_time ASC";

        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSchedulesByDate($startDate, $endDate) {
        try {
            $this->db->query('SELECT cs.*, 
                         t.team_name, 
                         CONCAT(ud.first_name, " ", ud.last_name) as driver_name,
                         CONCAT(up.first_name, " ", up.last_name) as partner_name,
                         r.route_name,
                         v.license_plate as vehicle_number,
                         s.shift_id,
                         s.shift_name,
                         s.start_time,
                         s.end_time
                         FROM collection_schedules cs
                         LEFT JOIN teams t ON cs.team_id = t.team_id
                         LEFT JOIN routes r ON cs.route_id = r.route_id
                         LEFT JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
                         LEFT JOIN shifts s ON cs.shift_id = s.shift_id
                         LEFT JOIN drivers d ON t.driver_id = d.driver_id
                         LEFT JOIN driving_partners p ON t.partner_id = p.partner_id
                         LEFT JOIN employees ed ON d.employee_id = ed.employee_id
                         LEFT JOIN employees ep ON p.employee_id = ep.employee_id
                         LEFT JOIN users ud ON ud.user_id = ed.user_id
                         LEFT JOIN users up ON up.user_id = ep.user_id
                         WHERE cs.collection_date BETWEEN :start_date AND :end_date
                         ORDER BY s.start_time ASC');
                         
            $this->db->bind(':start_date', $startDate);
            $this->db->bind(':end_date', $endDate);
            
            $results = $this->db->resultSet();
            
            // Organize results by shift_id and date
            $organized = [];
            foreach ($results as $schedule) {
                $date = date('Y-m-d', strtotime($schedule->collection_date));
                if (!isset($organized[$schedule->shift_id])) {
                    $organized[$schedule->shift_id] = [];
                }
                if (!isset($organized[$schedule->shift_id][$date])) {
                    $organized[$schedule->shift_id][$date] = [];
                }
                $organized[$schedule->shift_id][$date][] = $schedule;
            }
            
            return $organized;
        } catch (PDOException $e) {
            error_log("Error getting schedules by date: " . $e->getMessage());
            return [];
        }
    }

    public function getUpcomingSchedules($driverId) { // TESTED
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.day,
                r.route_name,
                v.*,
                cs.start_time
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            WHERE cs.driver_id = :driver_id
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                AND r.is_deleted = 0
            ORDER BY cs.start_time
        ");
        
        $this->db->bind(':driver_id', $driverId);
        return $this->db->resultSet();
    }
    
    
    
    

    public function getScheduleDetails($scheduleId) {   // tested
        $this->db->query("
            SELECT * FROM collection_schedules cs
            INNER JOIN routes r ON cs.route_id = r.route_id
            WHERE schedule_id = :schedule_id LIMIT 1;
        ");
    
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }

    public function getScheduleById($scheduleId) {
        $this->db->query("SELECT * FROM collection_schedules WHERE schedule_id = :schedule_id AND is_deleted = 0");
        $this->db->bind(':schedule_id', $scheduleId);
        
        return $this->db->single();
    }

    public function getCollectionScheduleById($scheduleId) {
        $this->db->query("SELECT * FROM collection_schedules WHERE schedule_id = :schedule_id");
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single(); // Return a single schedule record
    }



    public function getCollectionByScheduleId($scheduleId) {
        $this->db->query("
            SELECT * FROM collections 
            WHERE schedule_id = :schedule_id
            LIMIT 1
        ");
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }



    public function createCollectionAndSupplierRecords($scheduleId) {
        // Start transaction
        $this->db->beginTransaction();

        try {
            // Update collection start time
            $this->db->query("
                UPDATE collections 
                SET start_time = CURRENT_TIMESTAMP,
                    status = 'In Progress'
                WHERE schedule_id = :schedule_id
            ");
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->execute();

            // Get collection ID
            $this->db->query("
                SELECT collection_id, route_id 
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                WHERE cs.schedule_id = :schedule_id
            ");
            $this->db->bind(':schedule_id', $scheduleId);
            $collection = $this->db->single();

            // Get suppliers for the route
            $this->db->query("
                SELECT supplier_id 
                FROM route_suppliers 
                WHERE route_id = :route_id
            ");
            $this->db->bind(':route_id', $collection->route_id);
            $suppliers = $this->db->resultSet();

            // Create supplier records
            foreach ($suppliers as $supplier) {
                $this->db->query("
                    INSERT INTO collection_supplier_records (
                        collection_id,
                        supplier_id,
                        status,
                        is_scheduled
                    ) VALUES (
                        :collection_id,
                        :supplier_id,
                        'Added',
                        1
                    )
                ");
                $this->db->bind(':collection_id', $collection->collection_id);
                $this->db->bind(':supplier_id', $supplier->supplier_id);
                $this->db->execute();
            }

            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollBack();
            return false;
        }
    }

    public function startCollection($collectionId) {
        $this->db->query('UPDATE collections 
                          SET start_time = NOW(),
                              status = "In Progress"
                          WHERE collection_id = :collection_id');
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->execute();
    }

    public function getCollectionById($collectionId) {
        $this->db->query("
            SELECT * FROM collections 
            WHERE collection_id = :collection_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->single();
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

    public function getCollectionSupplierRecords($collectionId) {
        $this->db->query("
            SELECT 
                csr.*, 
                s.latitude, 
                s.longitude, 
                s.contact_number, 
                s.average_collection, 
                CONCAT(p.first_name, ' ', p.last_name) AS supplier_name,
                p.image_path,
                csr.arrival_time,
                rs.stop_order,
                s.address
            FROM collection_supplier_records csr
            JOIN collections c ON csr.collection_id = c.collection_id
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN route_suppliers rs ON cs.route_id = rs.route_id 
                AND csr.supplier_id = rs.supplier_id
            JOIN suppliers s ON csr.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id  -- Join profiles table for first_name and last_name
            LEFT JOIN supplier_photos sp ON s.supplier_id = sp.supplier_id
            WHERE csr.collection_id = :collection_id
            ORDER BY rs.stop_order ASC
        ");
        
        $this->db->bind(':collection_id', $collectionId);
    
        return $this->db->resultSet();
    }
    

    public function markSupplierArrival($collectionId, $supplierId) {
        $this->db->query("
            UPDATE collection_supplier_records 
            SET arrival_time = CURRENT_TIMESTAMP
            WHERE collection_id = :collection_id 
            AND supplier_id = :supplier_id
        ");
        
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);
        
        return $this->db->execute();
    }



    public function checkConflict($data) {
        // Base query to check conflicts
        $sql = 'SELECT * FROM collection_schedules 
                WHERE driver_id = :driver_id 
                AND route_id = :route_id 
                AND is_active = 1';
        
        // If this is an update (schedule_id exists), exclude the current schedule
        if (isset($data['schedule_id'])) {
            $sql .= ' AND schedule_id != :schedule_id';
        }
        
        $this->db->query($sql);
        
        // Bind parameters
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':route_id', $data['route_id']);
        
        // Only bind schedule_id if it exists (for updates)
        if (isset($data['schedule_id'])) {
            $this->db->bind(':schedule_id', $data['schedule_id']);
        }
    
        $results = $this->db->resultSet();
        return !empty($results);
    }


    public function create($data) {
        $this->db->query("INSERT INTO collection_schedules 
                         (route_id, driver_id, day, start_time, end_time, is_active) 
                         VALUES 
                         (:route_id, :driver_id, :day, :start_time, :end_time, :is_active)");
        
        $this->db->bind(':route_id', $data['route_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':day', $data['day']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':is_active', 1); // Default to active
        
        return $this->db->execute();
    }

    public function delete($schedule_id) {
        // First check if the schedule exists and is not currently in use
        $this->db->query('SELECT * FROM collection_schedules 
                          WHERE schedule_id = :schedule_id 
                          AND schedule_id NOT IN (
                              SELECT schedule_id 
                              FROM collections 
                              WHERE status = "ongoing"
                          )');
        $this->db->bind(':schedule_id', $schedule_id);
        
        $schedule = $this->db->single();
        
        if (!$schedule) {
            return false; // Schedule doesn't exist or is in use
        }

        // If schedule exists and is not in use, proceed with deletion
        $this->db->query('UPDATE collection_schedules SET is_deleted = 1 WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $schedule_id);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }



    public function toggleActive($schedule_id) {
        // First get the current status
        $this->db->query('SELECT is_active FROM collection_schedules WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $schedule_id);
        $current = $this->db->single();
        
        if (!$current) {
            return false;
        }

        // Toggle the status (if it was 1, make it 0, and vice versa)
        $newStatus = $current->is_active ? 0 : 1;
        
        // Update the status
        $this->db->query('UPDATE collection_schedules 
                          SET is_active = :is_active 
                          WHERE schedule_id = :schedule_id');
        
        $this->db->bind(':is_active', $newStatus);
        $this->db->bind(':schedule_id', $schedule_id);
        
        // Execute and return result
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function update($data) {
        $this->db->query('UPDATE collection_schedules SET
                              driver_id = :driver_id,
                              shift_id = :shift_id
                          WHERE schedule_id = :schedule_id');
    
        // Bind values
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':shift_id', $data['shift_id']);
        $this->db->bind(':schedule_id', $data['schedule_id']);
    
        // Execute
        return $this->db->execute();
    }

    public function getSchedulesByVehicleId($vehicleId) {
        $this->db->query('SELECT * FROM collection_schedules WHERE vehicle_id = :vehicle_id');
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->resultSet();
    }


    public function getSchedulesByShiftIdAndDate($shiftId, $startDate, $endDate) {
        $this->db->query("
            SELECT cs.*, r.route_name, v.license_plate, t.team_name
            FROM collection_schedules cs
            LEFT JOIN routes r ON cs.route_id = r.route_id
            LEFT JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
            LEFT JOIN teams t ON cs.team_id = t.team_id
            WHERE cs.shift_id = :shift_id 
            AND cs.created_at BETWEEN :start_date AND :end_date
        ");
        $this->db->bind(':shift_id', $shiftId);
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        return $this->db->resultSet(); // This will now include route_name and vehicle_number
    }

    private function getCurrentLocation() {
        // For now, returning a default location (you should implement proper location tracking)
        return [
            'lat' => 6.927079, // Default latitude for Colombo
            'lng' => 79.861244  // Default longitude for Colombo
        ];
    }


    public function assignBagsToCollection($collectionId, $bags) {
        $this->db->beginTransaction();
        
        try {
            // First, update the bags count in collections table
            $this->db->query('UPDATE collections 
                             SET bags = :bags_count 
                             WHERE collection_id = :collection_id');
            $this->db->bind(':bags_count', count($bags));
            $this->db->bind(':collection_id', $collectionId);
            $this->db->execute();
            
            // Then, insert bag assignments
            foreach ($bags as $bagToken) {
                $this->db->query('INSERT INTO collection_bags (collection_id, bag_id) 
                                 SELECT :collection_id, bag_id 
                                 FROM bags WHERE token = :token');
                $this->db->bind(':collection_id', $collectionId);
                $this->db->bind(':token', $bagToken);
                $this->db->execute();
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }



    public function getCollectionBags($collectionId) {
        $this->db->query('
            SELECT 
                cb.*,
                b.token,
                b.status as bag_status,
                CONCAT(u.first_name, " ", u.last_name) as supplier_name,
                CASE 
                    WHEN cb.supplier_id IS NULL THEN "Unassigned"
                    WHEN csr.collection_time IS NOT NULL THEN "Collected"
                    WHEN cb.supplier_id IS NOT NULL THEN "Assigned"
                    ELSE "Available"
                END as collection_status
            FROM collection_bags cb
            JOIN bags b 
                ON cb.bag_id = b.bag_id
            LEFT JOIN collection_supplier_records csr 
                ON cb.supplier_id = csr.supplier_id 
                AND csr.collection_id = cb.collection_id
            LEFT JOIN suppliers s 
                ON cb.supplier_id = s.supplier_id
            LEFT JOIN users u 
                ON s.user_id = u.user_id
            WHERE cb.collection_id = :collection_id
            ORDER BY cb.collection_bag_id ASC
        ');
        
        $this->db->bind(':collection_id', $collectionId);
        
        return $this->db->resultSet();
    }

    public function assignBagWithStatus($collectionBagId, $supplierId, $collectionId, $bagData) {
        try {
            $this->db->beginTransaction();

            // First verify the bag exists
            $this->db->query('
                SELECT cb.bag_id 
                FROM collection_bags cb
                WHERE cb.collection_bag_id = :collection_bag_id 
                AND cb.collection_id = :collection_id
                AND cb.supplier_id IS NULL
            ');
            
            $this->db->bind(':collection_bag_id', $collectionBagId);
            $this->db->bind(':collection_id', $collectionId);
            
            $bagResult = $this->db->single();
            
            if (!$bagResult) {
                throw new Exception('Bag not found or already assigned');
            }

            // Update collection_bags record without setting supplier_id
            $this->db->query('
                UPDATE collection_bags 
                SET 
                    gross_weight_kg = :total_weight,
                    actual_weight_kg = :actual_weight,
                    leaf_type = :leaf_type,
                    leaf_age = :leaf_age,
                    moisture_level = :moisture_level
                WHERE collection_bag_id = :collection_bag_id 
                AND collection_id = :collection_id
                AND supplier_id IS NULL
            ');

            // Bind parameters
            $this->db->bind(':total_weight', $bagData['total_weight_kg']);
            $this->db->bind(':actual_weight', $bagData['actual_weight_kg']);
            $this->db->bind(':leaf_type', $bagData['leaf_type']);
            $this->db->bind(':leaf_age', $bagData['leaf_age']);
            $this->db->bind(':moisture_level', $bagData['moisture_level']);
            $this->db->bind(':collection_bag_id', $collectionBagId);
            $this->db->bind(':collection_id', $collectionId);

            $result1 = $this->db->execute();

            if (!$result1) {
                throw new Exception('Failed to update collection_bags record');
            }

            // Update bag status using the bag_id we got earlier
            $this->db->query('
                UPDATE bags 
                SET status = "In Use"
                WHERE bag_id = :bag_id
                AND status = "Available"
            ');

            $this->db->bind(':bag_id', $bagResult->bag_id);

            $result2 = $this->db->execute();

            if (!$result2) {
                throw new Exception('Failed to update bag status');
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in assignBagWithStatus: " . $e->getMessage());
            throw $e;
        }
    }

    // Add new method for confirming collection
    public function confirmCollection($collectionId, $supplierId) {
        try {
            $this->db->beginTransaction();

            // Update collection_bags with supplier_id
            $this->db->query('
                UPDATE collection_bags 
                SET supplier_id = :supplier_id
                WHERE collection_id = :collection_id
                AND supplier_id IS NULL
            ');

            $this->db->bind(':supplier_id', $supplierId);
            $this->db->bind(':collection_id', $collectionId);
            
            $result1 = $this->db->execute();

            // Update collection_supplier_records
            $this->db->query('
                UPDATE collection_supplier_records 
                SET 
                    collection_time = CURRENT_TIMESTAMP,
                    status = "Collected"
                WHERE collection_id = :collection_id 
                AND supplier_id = :supplier_id
            ');

            $this->db->bind(':collection_id', $collectionId);
            $this->db->bind(':supplier_id', $supplierId);
            
            $result2 = $this->db->execute();

            // Check if all suppliers are collected
            $this->db->query('
                SELECT COUNT(*) as remaining
                FROM collection_supplier_records
                WHERE collection_id = :collection_id
                AND (status != "Collected" OR status IS NULL)
            ');
            
            $this->db->bind(':collection_id', $collectionId);
            $remaining = $this->db->single();

            // If no remaining uncollected suppliers, update collection status
            if ($remaining->remaining == 0) {
                $this->db->query('
                    UPDATE collections 
                    SET status = "Completed"
                    WHERE collection_id = :collection_id
                ');
                
                $this->db->bind(':collection_id', $collectionId);
                $result3 = $this->db->execute();
                
                if (!$result3) {
                    throw new Exception('Failed to update collection status');
                }
            }

            if ($result1 && $result2) {
                $this->db->commit();
                return [
                    'success' => true,
                    'isCompleted' => ($remaining->remaining == 0)
                ];
            }

            throw new Exception('Failed to confirm collection');

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error in confirmCollection: " . $e->getMessage());
            throw $e;
        }
    }

    public function getSchedulesForNextWeek() {
        $schedules = [];
        $today = date('Y-m-d');
        
        // Get the next 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("+$i days"));
            $dayOfWeek = date('l', strtotime($date)); // Get the full name of the day (e.g., "Monday")

            // Fetch schedules for the specific day
            $sql = "SELECT * FROM collection_schedules
            INNER JOIN routes on collection_schedules.route_id = routes.route_id
            WHERE collection_schedules.day = :day AND collection_schedules.is_active = 1 AND collection_schedules.is_deleted = 0";
            $this->db->query($sql);
            $this->db->bind(':day', $dayOfWeek);
            $schedules[$date] = $this->db->resultSet(); // Store schedules by date
        }

        return $schedules;
    }

    public function getUpcomingSchedulesBySupplierId($supplierId) { // NOT IN USE ANYMORE,
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.day,
                cs.driver_id,
                CONCAT(p.first_name, ' ', p.last_name) AS driver_name,
                r.route_name,
                r.route_id,
                v.vehicle_type,
                v.license_plate,
                v.vehicle_id,
                cs.start_time,
                cs.end_time,
                CASE 
                    WHEN cs.day = DATE_FORMAT(CURDATE(), '%W') THEN 'today'
                    WHEN FIELD(cs.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') 
                        > FIELD(DATE_FORMAT(CURDATE(), '%W'), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
                    THEN 'upcoming'
                    ELSE 'upcoming_next_week'
                END AS schedule_status,
                CASE 
                    WHEN cs.day = DATE_FORMAT(CURDATE(), '%W') THEN 1 
                    ELSE 0 
                END AS is_today,
                (SELECT COUNT(*) 
                 FROM collections c 
                 WHERE c.schedule_id = cs.schedule_id 
                 AND DATE(c.created_at) = CURDATE()
                ) as collection_exists
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN route_suppliers rs ON r.route_id = rs.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d on d.driver_id = cs.driver_id
            JOIN profiles p on d.profile_id = p.profile_id
            WHERE rs.supplier_id = :supplier_id
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                AND r.is_deleted = 0
            ORDER BY 
                FIELD(schedule_status, 'today', 'upcoming', 'upcoming_next_week'),
                FIELD(cs.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
                cs.start_time
        ");
    
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }

    public function getTodayScheduleBySupplierId($supplierId) { // NEW IMPLEMENTATION, TESTED
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.day,
                cs.driver_id,
                CONCAT(p.first_name, ' ', p.last_name) AS driver_name,
                r.route_name,
                r.route_id,
                v.vehicle_type,
                v.license_plate,
                v.vehicle_id,
                cs.start_time,
                p.image_path AS driver_image,
                cs.end_time,
                (SELECT COUNT(*) 
                 FROM collections c 
                 WHERE c.schedule_id = cs.schedule_id 
                 AND DATE(c.created_at) = CURDATE()
                ) as collection_exists
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN route_suppliers rs ON r.route_id = rs.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d on d.driver_id = cs.driver_id
            JOIN profiles p on d.profile_id = p.profile_id
            WHERE rs.supplier_id = :supplier_id
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                AND r.is_deleted = 0
                AND cs.day = DATE_FORMAT(CURDATE(), '%W')  
        ");
    
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single(); // Use single() to return one schedule or null
    }

    public function getTodaysScheduleByDriverId($driverId) { // NEW IMPLEMENTATION, TESTED
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.day,
                cs.driver_id,
                r.route_name,
                r.route_id,
                v.vehicle_type,
                v.license_plate,
                v.vehicle_id,
                cs.start_time,
                cs.end_time,
                (SELECT COUNT(*) 
                 FROM collections c 
                 WHERE c.schedule_id = cs.schedule_id 
                 AND DATE(c.created_at) = CURDATE()
                ) as collection_exists
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d on d.driver_id = cs.driver_id
            WHERE d.driver_id = :driver_id
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                AND r.is_deleted = 0
                AND cs.day = DATE_FORMAT(CURDATE(), '%W')  
        ");
    
        $this->db->bind(':driver_id', $driverId);
        return $this->db->single(); // Use single() to return one schedule or null
    }


    public function getAllAssignedSchedulesByDriverId($driverId) { // NEW IMPLEMENTATION
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.day,
                cs.driver_id,
                r.route_name,
                r.route_id,
                v.vehicle_type,
                v.license_plate,
                v.vehicle_id,
                cs.start_time,
                cs.end_time,
                (SELECT COUNT(*) 
                 FROM collections c 
                 WHERE c.schedule_id = cs.schedule_id 
                 AND DATE(c.created_at) = CURDATE()
                ) as collection_exists
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN drivers d on d.driver_id = cs.driver_id
            WHERE d.driver_id = :driver_id
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                AND r.is_deleted = 0
        ");
    
        $this->db->bind(':driver_id', $driverId); 
        return $this->db->resultSet(); 
    }

    public function checkEndedScheduleCollection($scheduleId) { // CHECKED
        $this->db->query("
            SELECT COUNT(*) as collection_count 
            FROM collections 
            WHERE schedule_id = :schedule_id 
              AND status IN ('Completed', 'Awaiting Inventory Addition')
              AND DATE(end_time) = CURDATE() 
        ");
        
        $this->db->bind(':schedule_id', $scheduleId);
        $result = $this->db->single();
        
        return $result->collection_count > 0;
    }

    
    
    
    public function getSubscribedSchedules($supplierId) {
        $sql = "SELECT 
                    cs.schedule_id,
                    cs.route_id,
                    r.route_name,
                    cs.day,
                    CONCAT(cs.start_time, ' - ', cs.end_time) AS shift_time,
                    v.license_plate,
                    v.capacity AS remaining_capacity,
                    1 AS is_subscribed
                FROM collection_schedules cs
                INNER JOIN routes r ON cs.route_id = r.route_id
                INNER JOIN vehicles v ON r.vehicle_id = v.vehicle_id
                INNER JOIN route_suppliers rs ON r.route_id = rs.route_id
                WHERE cs.is_deleted = 0 
                  AND cs.is_active = 1
                  AND rs.is_deleted = 0
                  AND rs.supplier_id = :supplier_id
                  AND rs.is_active = 1
                ORDER BY 
                    CASE cs.day
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                        WHEN 'Saturday' THEN 6
                        WHEN 'Sunday' THEN 7
                    END,
                    cs.start_time";
    
        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }
    

    public function getAvailableSchedules($supplierId) {
        $sql = "SELECT 
                    cs.schedule_id,
                    cs.route_id,
                    r.route_name,
                    cs.day,
                    CONCAT(cs.start_time, ' - ', cs.end_time) AS shift_time,
                    v.license_plate,
                    v.capacity AS remaining_capacity,
                    0 AS is_subscribed
                FROM collection_schedules cs
                INNER JOIN routes r ON cs.route_id = r.route_id
                INNER JOIN vehicles v ON r.vehicle_id = v.vehicle_id
                WHERE cs.is_deleted = 0
                  AND r.is_locked = 0 
                  AND cs.is_active = 1
                  AND r.route_id NOT IN (
                        SELECT route_id 
                        FROM route_suppliers 
                        WHERE supplier_id = :supplier_id 
                          AND is_deleted = 0
                          AND is_active = 1
                  )
                ORDER BY 
                    CASE cs.day
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                        WHEN 'Saturday' THEN 6
                        WHEN 'Sunday' THEN 7
                    END,
                    cs.start_time";
    
        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }
    

    /**
     need this in creating collection schedules
     **/
    public function checkDriverScheduleConflict($driverId, $day, $startTime, $endTime) {
        // Convert times for comparison
        $newStartTime = strtotime("2000-01-01 " . $startTime);
        $newEndTime = strtotime("2000-01-01 " . $endTime);
        
        // If end time is earlier than start time, assume it's the next day
        if ($newEndTime < $newStartTime) {
            $newEndTime = strtotime("2000-01-02 " . $endTime);
        }
        
        // Get all schedules for this driver on this day
        $this->db->query("SELECT * FROM collection_schedules 
                         WHERE driver_id = :driver_id 
                         AND day = :day 
                         AND is_deleted = 0");
        
        $this->db->bind(':driver_id', $driverId);
        $this->db->bind(':day', $day);
        
        $schedules = $this->db->resultSet();
        
        // Check each schedule for time conflicts
        foreach ($schedules as $schedule) {
            $existingStartTime = strtotime("2000-01-01 " . $schedule->start_time);
            $existingEndTime = strtotime("2000-01-01 " . $schedule->end_time);
            
            // If existing end time is earlier than existing start time, assume it's the next day
            if ($existingEndTime < $existingStartTime) {
                $existingEndTime = strtotime("2000-01-02 " . $schedule->end_time);
            }
            
            // Check for overlap
            // New start time falls within existing schedule
            // or new end time falls within existing schedule
            // or new schedule completely contains existing schedule
            if (($newStartTime >= $existingStartTime && $newStartTime < $existingEndTime) ||
                ($newEndTime > $existingStartTime && $newEndTime <= $existingEndTime) ||
                ($newStartTime <= $existingStartTime && $newEndTime >= $existingEndTime)) {
                return true; 
            }
        }
        
        return false; // No conflict
    }


    public function checkRouteScheduleConflict($routeId, $day, $startTime = null, $endTime = null, $checkTimeConflict = false) {
        // First check if the route is already scheduled on this day
        $this->db->query("SELECT COUNT(*) as count FROM collection_schedules 
                         WHERE route_id = :route_id 
                         AND day = :day 
                         AND is_deleted = 0");
        
        $this->db->bind(':route_id', $routeId);
        $this->db->bind(':day', $day);
        
        $result = $this->db->single();
        
        // If the route is already scheduled on this day, return conflict
        if ($result->count > 0) {
            return true;
        }
        
        // If we're not checking time conflicts or times aren't provided, we're done
        if (!$checkTimeConflict || !$startTime || !$endTime) {
            return false;
        }
        
        // Otherwise, proceed with time conflict check (which is now redundant since we're checking day-level)
        // This is kept for future flexibility if requirements change
        $newStartTime = strtotime("2000-01-01 " . $startTime);
        $newEndTime = strtotime("2000-01-01 " . $endTime);
        
        // If end time is earlier than start time, assume it's the next day
        if ($newEndTime < $newStartTime) {
            $newEndTime = strtotime("2000-01-02 " . $endTime);
        }
        
        // Get all schedules for this route on this day
        $this->db->query("SELECT * FROM collection_schedules 
                         WHERE route_id = :route_id 
                         AND day = :day 
                         AND is_deleted = 0");
        
        $this->db->bind(':route_id', $routeId);
        $this->db->bind(':day', $day);
        
        $schedules = $this->db->resultSet();
        
        // Check each schedule for time conflicts
        foreach ($schedules as $schedule) {
            $existingStartTime = strtotime("2000-01-01 " . $schedule->start_time);
            $existingEndTime = strtotime("2000-01-01 " . $schedule->end_time);
            
            // If existing end time is earlier than existing start time, assume it's the next day
            if ($existingEndTime < $existingStartTime) {
                $existingEndTime = strtotime("2000-01-02 " . $schedule->end_time);
            }
            
            // Check for overlap
            if (($newStartTime >= $existingStartTime && $newStartTime < $existingEndTime) ||
                ($newEndTime > $existingStartTime && $newEndTime <= $existingEndTime) ||
                ($newStartTime <= $existingStartTime && $newEndTime >= $existingEndTime)) {
                return true; // Conflict found
            }
        }
        
        return false; // No conflict
    }


    public function checkDriverScheduleConflictExcludingCurrent($driverId, $day, $startTime, $endTime, $currentScheduleId) {
        // Convert times for comparison
        $newStartTime = strtotime("2000-01-01 " . $startTime);
        $newEndTime = strtotime("2000-01-01 " . $endTime);
        
        // If end time is earlier than start time, assume it's the next day
        if ($newEndTime < $newStartTime) {
            $newEndTime = strtotime("2000-01-02 " . $endTime);
        }
        
        // Get all schedules for this driver on this day, excluding the current one
        $this->db->query("SELECT * FROM collection_schedules 
                         WHERE driver_id = :driver_id 
                         AND day = :day 
                         AND schedule_id != :current_schedule_id
                         AND is_deleted = 0");
        
        $this->db->bind(':driver_id', $driverId);
        $this->db->bind(':day', $day);
        $this->db->bind(':current_schedule_id', $currentScheduleId);
        
        $schedules = $this->db->resultSet();
        
        // Check each schedule for time conflicts
        foreach ($schedules as $schedule) {
            $existingStartTime = strtotime("2000-01-01 " . $schedule->start_time);
            $existingEndTime = strtotime("2000-01-01 " . $schedule->end_time);
            
            // If existing end time is earlier than existing start time, assume it's the next day
            if ($existingEndTime < $existingStartTime) {
                $existingEndTime = strtotime("2000-01-02 " . $schedule->end_time);
            }
            
            // Check for overlap
            if (($newStartTime >= $existingStartTime && $newStartTime < $existingEndTime) ||
                ($newEndTime > $existingStartTime && $newEndTime <= $existingEndTime) ||
                ($newStartTime <= $existingStartTime && $newEndTime >= $existingEndTime)) {
                return true; // Conflict found
            }
        }
        
        return false; // No conflict
    }
    public function checkRouteScheduleConflictExcludingCurrent($routeId, $day, $startTime, $endTime, $currentScheduleId) {
        // Convert times for comparison
        $newStartTime = strtotime("2000-01-01 " . $startTime);
        $newEndTime = strtotime("2000-01-01 " . $endTime);
        
        // If end time is earlier than start time, assume it's the next day
        if ($newEndTime < $newStartTime) {
            $newEndTime = strtotime("2000-01-02 " . $endTime);
        }
        
        // Get all schedules for this route on this day, excluding the current one
        $this->db->query("SELECT * FROM collection_schedules 
                         WHERE route_id = :route_id 
                         AND day = :day 
                         AND schedule_id != :current_schedule_id
                         AND is_deleted = 0");
        
        $this->db->bind(':route_id', $routeId);
        $this->db->bind(':day', $day);
        $this->db->bind(':current_schedule_id', $currentScheduleId);
        
        $schedules = $this->db->resultSet();
        
        // Check each schedule for time conflicts
        foreach ($schedules as $schedule) {
            $existingStartTime = strtotime("2000-01-01 " . $schedule->start_time);
            $existingEndTime = strtotime("2000-01-01 " . $schedule->end_time);
            
            // If existing end time is earlier than existing start time, assume it's the next day
            if ($existingEndTime < $existingStartTime) {
                $existingEndTime = strtotime("2000-01-02 " . $schedule->end_time);
            }
            
            // Check for overlap
            if (($newStartTime >= $existingStartTime && $newStartTime < $existingEndTime) ||
                ($newEndTime > $existingStartTime && $newEndTime <= $existingEndTime) ||
                ($newStartTime <= $existingStartTime && $newEndTime >= $existingEndTime)) {
                return true; // Conflict found
            }
        }
        
        return false; // No conflict
    }


    public function updateSchedule($data) {
        $this->db->query("UPDATE collection_schedules 
                         SET route_id = :route_id, 
                             driver_id = :driver_id, 
                             day = :day, 
                             start_time = :start_time, 
                             end_time = :end_time
                         WHERE schedule_id = :schedule_id");
        
        $this->db->bind(':schedule_id', $data['schedule_id']);
        $this->db->bind(':route_id', $data['route_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':day', $data['day']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        
        return $this->db->execute();
    }

    public function getTotalSchedules() {
        $this->db->query("SELECT COUNT(*) as total FROM collection_schedules WHERE is_deleted = 0");
        return $this->db->single()->total; 
    }

    public function getActiveSchedulesCount() { // get the schedule if its in the current day and shift time
        $currentDay = date('l'); 
        $currentTime = date('H:i:s'); 

        $this->db->query("
            SELECT COUNT(*) as total 
            FROM collection_schedules 
            WHERE day = :currentDay 
            AND is_active = 1 
            AND is_deleted = 0 
            AND start_time <= :currentTime 
            AND end_time >= :currentTime
        ");


        $this->db->bind(':currentDay', $currentDay);
        $this->db->bind(':currentTime', $currentTime);
        return $this->db->single()->total; 
    }

    public function getSchedulesByDriverId($driverId) {
        $this->db->query('SELECT cs.*, r.route_name, 
                          CONCAT(p.first_name, " ", p.last_name) as driver_name
                          FROM collection_schedules cs
                          LEFT JOIN routes r ON cs.route_id = r.route_id
                          LEFT JOIN drivers d ON cs.driver_id = d.driver_id
                          LEFT JOIN profiles p ON d.profile_id = p.profile_id
                          WHERE cs.driver_id = :driver_id
                          AND cs.is_active = 1
                          AND cs.is_deleted = 0
                          ');
        
        $this->db->bind(':driver_id', $driverId);
        
        return $this->db->resultSet();
    }

} 