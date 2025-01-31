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
                    CONCAT(u.first_name, ' ', u.last_name) AS driver_name,
                    cs.shift_id,
                    s.shift_name,
                    s.start_time,
                    s.end_time,
                    cs.week_number,
                    cs.day,
                    v.vehicle_id,
                    v.license_plate,
                    cs.created_at,
                    cs.is_active
                FROM collection_schedules cs
                LEFT JOIN routes r ON cs.route_id = r.route_id
                LEFT JOIN drivers d ON cs.driver_id = d.driver_id
                LEFT JOIN users u ON d.user_id = u.user_id
                LEFT JOIN collection_shifts s ON cs.shift_id = s.shift_id
                LEFT JOIN vehicles v ON r.vehicle_id = v.vehicle_id
                WHERE cs.is_deleted = 0
                ORDER BY cs.created_at DESC";

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

    public function getUpcomingSchedules($driverId) {
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.week_number,
                cs.day,
                r.route_name,
                v.vehicle_type,
                v.license_plate,
                cs_shift.shift_name,
                cs_shift.start_time,
                cs_shift.end_time
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            JOIN collection_shifts cs_shift ON cs.shift_id = cs_shift.shift_id
            WHERE cs.driver_id = :driver_id
            AND cs.is_active = 1
            AND cs.is_deleted = 0
            ORDER BY 
                cs.week_number,
                FIELD(cs.day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ");

        $this->db->bind(':driver_id', $driverId);
        return $this->db->resultSet();
    }

    public function getScheduleDetails($scheduleId) {
        $this->db->query("
            SELECT * FROM collection_schedules WHERE schedule_id = :schedule_id LIMIT 1;
        ");
    
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }

    public function getScheduleById($scheduleId) {
        $this->db->query("
            SELECT 
                r.*,
                u.*,
                cs.*,
                v.*,
                cs_shift.*
            FROM collection_schedules cs
            JOIN collection_shifts cs_shift ON cs.shift_id = cs_shift.shift_id
            LEFT JOIN drivers d ON cs.driver_id = d.driver_id
            LEFT JOIN users u ON d.user_id = u.user_id
            JOIN routes r ON cs.route_id = r.route_id
            LEFT JOIN vehicles v ON r.vehicle_id = v.vehicle_id
            WHERE cs.schedule_id = :schedule_id
            AND cs.is_active = 1
            AND cs.is_deleted = 0
            LIMIT 1;

        ");

        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }

    public function isUserReady($scheduleId, $userId) {
        $this->db->query("
            SELECT 
                c.*,
                CASE 
                    WHEN :user_id IN (
                        SELECT e.user_id 
                        FROM drivers d 
                        JOIN employees e ON d.employee_id = e.employee_id 
                        JOIN teams t ON d.driver_id = t.driver_id
                        WHERE t.team_id = cs.team_id
                    ) THEN c.driver_approved
                    ELSE c.partner_approved
                END as user_ready
            FROM collection_schedules cs
            LEFT JOIN collections c ON cs.schedule_id = c.schedule_id
            WHERE cs.schedule_id = :schedule_id
            LIMIT 1
        ");
        
        $this->db->bind(':schedule_id', $scheduleId);
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        if (!$result) {
            // Create a new collection entry if it doesn't exist
            $this->createInitialCollection($scheduleId);
            return false;
        }
        
        return (bool)$result->user_ready;
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

    public function createInitialCollection($scheduleId) {
        $this->db->query("
            INSERT INTO collections (
                schedule_id,
                status,
                driver_approved,
                partner_approved
            ) VALUES (
                :schedule_id,
                'Pending',
                0,
                0
            )
        ");
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->execute();
    }

    public function setUserReady($scheduleId, $userId) {
        try {
            $this->db->beginTransaction();

            // First check if this user is a driver or partner
            $userRole = RoleHelper::hasRole(RoleHelper::DRIVER) ? 'driver' : 
                       (RoleHelper::hasRole(RoleHelper::DRIVING_PARTNER) ? 'driving_partner' : null);

            // Get the collection record
            $collection = $this->getCollectionByScheduleId($scheduleId);
            
            if (!$collection) {
                // Create initial collection if it doesn't exist
                $this->createInitialCollection($scheduleId);
                $collection = $this->getCollectionByScheduleId($scheduleId);
            }

            // Update the appropriate approval field based on role
            if ($userRole == 'driver') {
                $sql = "UPDATE collections 
                       SET driver_approved = 1,
                           start_time = CASE 
                               WHEN partner_approved = 1 THEN NOW() 
                               ELSE start_time 
                           END
                       WHERE schedule_id = :schedule_id";
            } elseif ($userRole == 'driving_partner') {
                $sql = "UPDATE collections 
                       SET partner_approved = 1,
                           start_time = CASE 
                               WHEN driver_approved = 1 THEN NOW() 
                               ELSE start_time 
                           END
                       WHERE schedule_id = :schedule_id";
            } else {
                throw new Exception("Invalid user role");
            }

            $this->db->query($sql);
            $this->db->bind(':schedule_id', $scheduleId);
            
            if (!$this->db->execute()) {
                throw new Exception("Failed to update ready status");
            }

            // Get updated collection to check if both are ready
            $updatedCollection = $this->getCollectionByScheduleId($scheduleId);
            if ($updatedCollection->driver_approved && $updatedCollection->partner_approved) {
                // Both are ready, initialize collection records for each supplier
                $schedule = $this->getScheduleById($scheduleId);
                
                // Create model instances directly
                $routeModel = new M_Route();
                $routeSuppliers = $routeModel->getRouteSuppliers($schedule->route_id);
                
                // Create supplier records
                foreach ($routeSuppliers as $supplier) {
                    $this->db->query("INSERT INTO collection_supplier_records 
                                    (collection_id, supplier_id, status, created_at) 
                                    VALUES (:collection_id, :supplier_id, 'pending', NOW())");
                    $this->db->bind(':collection_id', $updatedCollection->collection_id);
                    $this->db->bind(':supplier_id', $supplier->supplier_id);
                    $this->db->execute();
                }

                // Optimize the route
                $route = $routeModel->getRouteById($schedule->route_id);
                $currentLocation = $this->getCurrentLocation();
                $this->optimizeRoute($route->route_id, $currentLocation['lat'], $currentLocation['lng']);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function areBothTeamMembersReady($scheduleId) {
        $this->db->query("
            SELECT 
                driver_approved,
                partner_approved,
                start_time
            FROM collections
            WHERE schedule_id = :schedule_id
            LIMIT 1
        ");
        
        $this->db->bind(':schedule_id', $scheduleId);
        $result = $this->db->single();
        
        if ($result->driver_approved && $result->partner_approved && !$result->start_time) {
            // Both are ready and collection hasn't started yet, set start time
            $this->db->query("
                UPDATE collections 
                SET start_time = CURRENT_TIMESTAMP,
                    status = 'In Progress'
                WHERE schedule_id = :schedule_id
            ");
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->execute();
        }
        
        return $result->driver_approved && $result->partner_approved;
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

    public function checkAndCreateCollection($scheduleId) {
        $this->db->query("
            SELECT 
                cs.*,
                c.collection_id,
                c.start_time,
                c.driver_approved,
                c.partner_approved
            FROM collection_schedules cs
            LEFT JOIN collections c ON cs.schedule_id = c.schedule_id
            WHERE cs.schedule_id = :schedule_id
        ");
        $this->db->bind(':schedule_id', $scheduleId);
        $schedule = $this->db->single();

        // If collection doesn't exist, create it
        if (!$schedule->collection_id) {
            $this->createInitialCollection($scheduleId);
            return;
        }

        // Check if it's time to start and both are ready
        $currentTime = new DateTime();
        $scheduleTime = new DateTime($schedule->start_time);
        
        if (($schedule->driver_approved && $schedule->partner_approved) || 
            $currentTime >= $scheduleTime) {
            
            if (!$schedule->start_time) {
                $this->startCollection($schedule->collection_id);
            }
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
                CONCAT(u.first_name, ' ', u.last_name) as supplier_name,
                COALESCE(sp.profile_image, 'default.jpg') as profile_image,
                csr.arrival_time,
                rs.stop_order
            FROM collection_supplier_records csr
            JOIN collections c ON csr.collection_id = c.collection_id
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            JOIN route_suppliers rs ON cs.route_id = rs.route_id AND csr.supplier_id = rs.supplier_id
            JOIN suppliers s ON csr.supplier_id = s.supplier_id
            JOIN users u ON s.user_id = u.user_id
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

    public function optimizeRoute($collectionId, $driverLat, $driverLng) {
        // Get route_id for this collection
        $this->db->query("
            SELECT cs.route_id
            FROM collections c
            JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
            WHERE c.collection_id = :collection_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->single();
        
        if ($result) {
            $routeModel = new M_Route();
            return $routeModel->updateRouteOrder($result->route_id, $driverLat, $driverLng);
        }
        return false;
    }

    public function checkConflict($data) {
        // Base query to check conflicts
        $sql = 'SELECT * FROM collection_schedules 
                WHERE driver_id = :driver_id 
                AND week_number = :week_number 
                AND route_id = :route_id 
                AND is_active = 1';
        
        // If this is an update (schedule_id exists), exclude the current schedule
        if (isset($data['schedule_id'])) {
            $sql .= ' AND schedule_id != :schedule_id';
        }
        
        $this->db->query($sql);
        
        // Bind parameters
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':week_number', $data['week_number']);
        $this->db->bind(':route_id', $data['route_id']);
        
        // Only bind schedule_id if it exists (for updates)
        if (isset($data['schedule_id'])) {
            $this->db->bind(':schedule_id', $data['schedule_id']);
        }
    
        $results = $this->db->resultSet();
        return !empty($results);
    }

    public function create($data) {
        $this->db->query('INSERT INTO collection_schedules (
            driver_id, 
            route_id, 
            -- vehicle_id, 
            shift_id, 
            week_number, 
            day,
            is_active,
            created_at
        ) VALUES (
            :driver_id, 
            :route_id, 
            -- :vehicle_id, 
            :shift_id, 
            :week_number, 
            :day,
            1,
            CURRENT_TIMESTAMP
        )');

        // Bind values
        $this->db->bind(':driver_id', $data['driver_id']);
        $this->db->bind(':route_id', $data['route_id']);
        // $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':shift_id', $data['shift_id']);
        $this->db->bind(':week_number', $data['week_number']);
        $this->db->bind(':day', $data['day']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
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

    public function getTeamAssignmentCount($team_id) {
        $this->db->query('SELECT COUNT(DISTINCT days_of_week) as day_count 
                          FROM collection_schedules 
                          WHERE team_id = :team_id 
                          AND is_active = 1');
        $this->db->bind(':team_id', $team_id);
        $result = $this->db->single();
        return $result->day_count ?? 0;
    }

    public function getVehicleAssignmentCount($vehicle_id) {
        $this->db->query('SELECT COUNT(DISTINCT days_of_week) as day_count 
                          FROM collection_schedules 
                          WHERE vehicle_id = :vehicle_id 
                          AND is_active = 1');
        $this->db->bind(':vehicle_id', $vehicle_id);
        $result = $this->db->single();
        return $result->day_count ?? 0;
    }

    public function getTeamAssignedDays($team_id) {
        $this->db->query('SELECT days_of_week 
                          FROM collection_schedules 
                          WHERE team_id = :team_id 
                          AND is_active = 1');
        $this->db->bind(':team_id', $team_id);
        $results = $this->db->resultSet();
        
        $days = [];
        foreach ($results as $result) {
            $days = array_merge($days, explode(',', $result->days_of_week));
        }
        return array_unique($days);
    }

    // public function getVehicleAssignedDays($vehicle_id) {
    //     $this->db->query('SELECT days_of_week 
    //                       FROM collection_schedules 
    //                       WHERE vehicle_id = :vehicle_id 
    //                       AND is_active = 1');
    //     $this->db->bind(':vehicle_id', $vehicle_id);
    //     $results = $this->db->resultSet();
        
    //     $days = [];
    //     foreach ($results as $result) {
    //         $days = array_merge($days, explode(',', $result->days_of_week));
    //     }
    //     return array_unique($days);
    // }

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
        $this->db->query('UPDATE collection_schedules 
                          SET route_id = :route_id,
                              driver_id = :driver_id,
                              -- vehicle_id = :vehicle_id,
                              shift_id = :shift_id,
                              week_number = :week_number
                          WHERE schedule_id = :schedule_id');
    
        // Bind values
        $this->db->bind(':route_id', $data['route_id']);
        $this->db->bind(':driver_id', $data['driver_id']);
        // $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':shift_id', $data['shift_id']);
        $this->db->bind(':week_number', $data['week_number']);
        $this->db->bind(':schedule_id', $data['schedule_id']);
    
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
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

    public function setPartnerReady($scheduleId, $partnerId) {
        // Only set partner_approved, don't start collection yet
        $this->db->query('UPDATE collections 
                          SET partner_approved = 1 
                          WHERE schedule_id = :schedule_id');
        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->execute();
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

    // Add a method to check if collection can start
    public function canStartCollection($collectionId) {
        $this->db->query('SELECT partner_approved, vehicle_manager_approved, initial_weight_bridge, bags 
                          FROM collections 
                          WHERE collection_id = :collection_id');
        $this->db->bind(':collection_id', $collectionId);
        $collection = $this->db->single();
        
        return $collection &&
               $collection->partner_approved == 1 &&
               $collection->vehicle_manager_approved == 1 &&
               $collection->initial_weight_bridge !== null &&
               $collection->bags > 0;
    }

    public function getSupplierCollectionRecord($collectionId, $supplierId) {
        $this->db->query('
                        SELECT csr.*, 
                             u.first_name, 
                             u.last_name,
                             s.contact_number,
                             s.latitude,
                             s.longitude
                      FROM collection_supplier_records csr
                      JOIN suppliers s ON csr.supplier_id = s.supplier_id
                      JOIN users u ON u.user_id = s.user_id
                      WHERE csr.collection_id = :collection_id
                      AND csr.supplier_id = :supplier_id;');

        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);

        return $this->db->single();
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

} 