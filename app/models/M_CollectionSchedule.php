<?php

class M_CollectionSchedule {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllSchedules() {
        $sql = "SELECT 
                    cs.*,
                    r.route_name,
                    t.team_name,
                    v.license_plate,
                    s.shift_name
                FROM collection_schedules cs
                LEFT JOIN routes r ON cs.route_id = r.route_id
                LEFT JOIN teams t ON cs.team_id = t.team_id
                LEFT JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
                LEFT JOIN shifts s ON cs.shift_id = s.shift_id
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

    public function getUpcomingSchedules($teamId) {
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.week_number,
                cs.days_of_week,
                r.route_name,
                t.team_name,
                v.vehicle_type,
                v.license_plate,
                cs_shift.shift_name,
                cs_shift.start_time,
                cs_shift.end_time
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN teams t ON cs.team_id = t.team_id
            JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
            JOIN collection_shifts cs_shift ON cs.shift_id = cs_shift.shift_id
            WHERE cs.team_id = :team_id
            AND cs.is_active = 1
            AND cs.is_deleted = 0
            ORDER BY 
                cs.week_number,
                FIELD(SUBSTRING_INDEX(cs.days_of_week, ',', 1), 
                    'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun')
        ");

        $this->db->bind(':team_id', $teamId);
        return $this->db->resultSet();
    }

    public function getScheduleDetails($scheduleId) {
        $this->db->query("
            SELECT 
                cs.schedule_id,
                cs.week_number,
                cs.days_of_week,
                r.route_name,
                r.start_location_lat,
                r.start_location_long,
                r.end_location_lat,
                r.end_location_long,
                t.team_name,
                v.vehicle_type,
                v.license_plate as vehicle_number,
                cs_shift.shift_name,
                cs_shift.start_time,
                cs_shift.end_time,
                GROUP_CONCAT(DISTINCT csr.status) as collection_statuses
            FROM collection_schedules cs
            JOIN routes r ON cs.route_id = r.route_id
            JOIN teams t ON cs.team_id = t.team_id
            JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
            JOIN collection_shifts cs_shift ON cs.shift_id = cs_shift.shift_id
            LEFT JOIN collection_supplier_records csr ON cs.schedule_id = csr.collection_id
            WHERE cs.schedule_id = :schedule_id
            AND cs.is_active = 1
            AND cs.is_deleted = 0
            GROUP BY cs.schedule_id
        ");

        $this->db->bind(':schedule_id', $scheduleId);
        return $this->db->single();
    }

    public function getScheduleById($scheduleId) {
        $this->db->query("
            SELECT 
                cs.*,
                cs_shift.shift_name,
                cs_shift.start_time,
                cs_shift.end_time,
                t.team_name,
                v.vehicle_type,
                v.license_plate as vehicle_number,
                r.route_name
            FROM collection_schedules cs
            JOIN collection_shifts cs_shift ON cs.shift_id = cs_shift.shift_id
            JOIN teams t ON cs.team_id = t.team_id
            JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
            JOIN routes r ON cs.route_id = r.route_id
            WHERE cs.schedule_id = :schedule_id
            AND cs.is_active = 1
            AND cs.is_deleted = 0
            LIMIT 1
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
        $this->db->beginTransaction();
        try {
            // Determine if user is driver or partner
            $this->db->query("
                SELECT 
                    CASE 
                        WHEN :user_id IN (
                            SELECT u.user_id 
                            FROM drivers d 
                            JOIN employees e ON d.employee_id = e.employee_id 
                            JOIN users u ON e.user_id = u.user_id
                            JOIN teams t ON d.driver_id = t.driver_id
                            JOIN collection_schedules cs ON t.team_id = cs.team_id
                            WHERE cs.schedule_id = :schedule_id
                        ) THEN 'driver'
                        ELSE 'partner'
                    END as role
                FROM collection_schedules cs
                WHERE cs.schedule_id = :schedule_id
            ");
            
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->bind(':user_id', $userId);
            $role = $this->db->single()->role;

            // Update the appropriate approval field
            $this->db->query("
                UPDATE collections 
                SET " . ($role === 'driver' ? 'driver_approved' : 'partner_approved') . " = 1
                WHERE schedule_id = :schedule_id
            ");
            
            $this->db->bind(':schedule_id', $scheduleId);
            $this->db->execute();

            // Check if both are ready
            $this->db->query("
                SELECT c.*, cs.route_id 
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                WHERE c.schedule_id = :schedule_id
            ");
            $this->db->bind(':schedule_id', $scheduleId);
            $collection = $this->db->single();

            // If both are ready and start_time is not set
            if ($collection->driver_approved && $collection->partner_approved && !$collection->start_time) {
                // Set collection start time
                $this->db->query("
                    UPDATE collections 
                    SET start_time = CURRENT_TIMESTAMP,
                        status = 'In Progress'
                    WHERE collection_id = :collection_id
                ");
                $this->db->bind(':collection_id', $collection->collection_id);
                $this->db->execute();

                // Create supplier records for all suppliers in the route
                $this->db->query("
                    INSERT INTO collection_supplier_records 
                    (collection_id, supplier_id, status, is_scheduled)
                    SELECT 
                        :collection_id,
                        rs.supplier_id,
                        'Added',
                        1
                    FROM route_suppliers rs
                    WHERE rs.route_id = :route_id
                    AND rs.is_active = 1
                    AND rs.is_deleted = 0
                ");
                $this->db->bind(':collection_id', $collection->collection_id);
                $this->db->bind(':route_id', $collection->route_id);
                $this->db->execute();

                // Optimize route based on driver's current location
                $this->optimizeRoute($collection->collection_id, 6.223440958667509, 80.2850332126462);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
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

    private function startCollection($collectionId) {
        $this->db->beginTransaction();
        try {
            // Set collection start time
            $this->db->query("
                UPDATE collections 
                SET start_time = CURRENT_TIMESTAMP,
                    status = 'In Progress'
                WHERE collection_id = :collection_id
            ");
            $this->db->bind(':collection_id', $collectionId);
            $this->db->execute();

            // Create supplier records
            $this->db->query("
                INSERT INTO collection_supplier_records (
                    collection_id,
                    supplier_id,
                    status,
                    is_scheduled
                )
                SELECT 
                    :collection_id,
                    rs.supplier_id,
                    'Added',
                    1
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                JOIN route_suppliers rs ON cs.route_id = rs.route_id
                WHERE c.collection_id = :collection_id
                AND rs.is_active = 1
                AND rs.is_deleted = 0
            ");
            $this->db->bind(':collection_id', $collectionId);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
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
                s.latitude,
                s.longitude,
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
} 