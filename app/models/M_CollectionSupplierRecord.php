<?php

class M_CollectionSupplierRecord {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSupplierRecords($collectionId) {
        $sql = "SELECT 
                    csr.*,
                    s.supplier_id,
                    s.latitude,
                    s.longitude,
                    CONCAT(u.first_name, ' ', u.last_name) as supplier_name
                FROM collection_supplier_records csr
                JOIN suppliers s ON csr.supplier_id = s.supplier_id
                JOIN users u ON s.user_id = u.user_id
                WHERE csr.collection_id = :collection_id
                ORDER BY csr.collection_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet();
    }

    public function updateSupplierRecord($data) {
        $sql = "UPDATE collection_supplier_records 
                SET status = :status,
                    quantity = :quantity,
                    collection_time = :collection_time,
                    notes = :notes
                WHERE record_id = :record_id";
        
        $this->db->query($sql);
        $this->db->bind(':status', $data->status);
        $this->db->bind(':quantity', $data->quantity ?? 0);
        $this->db->bind(':collection_time', $data->collection_time);
        $this->db->bind(':notes', $data->notes ?? null);
        $this->db->bind(':record_id', $data->record_id);
        
        return $this->db->execute();
    }

    public function addSupplierRecord($data) {
        $sql = "INSERT INTO collection_supplier_records 
                (collection_id, supplier_id, status, is_scheduled)
                VALUES (:collection_id, :supplier_id, :status, :is_scheduled)";
        
        $this->db->query($sql);
        $this->db->bind(':collection_id', $data->collection_id);
        $this->db->bind(':supplier_id', $data->supplier_id);
        $this->db->bind(':status', $data->status);
        $this->db->bind(':is_scheduled', $data->is_scheduled);
        
        return $this->db->execute();
    }

    public function updateSupplierStatus($recordId, $status) {
        try {
            // Validate status against enum values
            $validStatuses = ['Collected', 'No Show', 'Added', 'Skipped'];
            if (!in_array($status, $validStatuses)) {
                throw new PDOException("Invalid status value");
            }

            $this->db->query('UPDATE collection_supplier_records 
                             SET status = :status 
                             WHERE record_id = :record_id');
            
            $this->db->bind(':status', $status);
            $this->db->bind(':record_id', $recordId);

            return $this->db->execute();
            
        } catch (PDOException $e) {
            error_log("Error updating supplier status: " . $e->getMessage());
            return false;
        }
    }

    public function removeCollectionSupplier($recordId) {
        $sql = "DELETE FROM collection_supplier_records WHERE record_id = :record_id AND status = 'Added'";
        $this->db->query($sql);
        $this->db->bind(':record_id', $recordId);
        return $this->db->execute();
    }

    public function getMonthlyCollectionData() {
        try {
            $sql = "SELECT 
                        MONTHNAME(csr.collection_time) AS month,
                        ROUND(SUM(csr.quantity), 1) AS quantity
                    FROM collection_supplier_records csr
                    WHERE 
                        csr.supplier_id = :supplier_id
                        AND csr.status = 'Collected' OR csr.status = 'Added'
                        AND csr.collection_time >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                        AND csr.collection_time <= CURDATE()
                    GROUP BY 
                        MONTH(csr.collection_time),
                        MONTHNAME(csr.collection_time)
                    ORDER BY 
                        csr.collection_time DESC
                    LIMIT 6";
            
            $this->db->query($sql);
            $this->db->bind(':supplier_id', 2); // Hardcoded for now, should use session
            
            $result = $this->db->resultSet();
            
            // Create an array of the last 6 months
            $months = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = new DateTime();
                $date->modify("-$i months");
                $months[$date->format('F')] = 0;
            }
            
            // Fill in actual data
            foreach ($result as $row) {
                if (isset($months[$row->month])) {
                    $months[$row->month] = floatval($row->quantity);
                }
            }
            
            // Convert to array of objects for consistency
            $formattedData = [];
            foreach ($months as $month => $quantity) {
                $formattedData[] = [
                    'month' => $month,
                    'quantity' => $quantity
                ];
            }
            
            return array_values($formattedData);
        } catch (Exception $e) {
            error_log("Error fetching collection data: " . $e->getMessage());
            return [];
        }
    }

    // Modified getSupplierSchedule method
    public function getSupplierSchedule($supplier_id) {
        try {
            $sql = "SELECT 
                        cs.schedule_id,
                        cs.shift_id,
                        r.route_id,
                        r.route_name,
                        r.day as route_day,
                        cs.day as schedule_day
                    FROM routes r
                    JOIN route_suppliers rs ON r.route_id = rs.route_id
                    LEFT JOIN collection_schedules cs ON r.route_id = cs.route_id
                    WHERE rs.supplier_id = :supplier_id
                    AND rs.is_active = 1
                    AND rs.is_deleted = 0
                    AND (cs.is_active = 1 OR cs.is_active IS NULL)
                    ORDER BY cs.created_at DESC
                    LIMIT 1";

            $this->db->query($sql);
            $this->db->bind(':supplier_id', $supplier_id);
            $result = $this->db->single();

            if (!$result) {
                return null;
            }

            // Ensure route_day is an integer
            $routeDay = intval($result->route_day);
            
            // Convert numeric day to name and calculate next collection date
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $currentDay = $dayNames[$routeDay % 7];
            
            // Calculate next collection date
            $today = new DateTime();
            $todayDayNum = (int)$today->format('w');
            $scheduleDayNum = $routeDay % 7;
            
            $daysUntilNext = ($scheduleDayNum - $todayDayNum + 7) % 7;
            if ($daysUntilNext === 0 && $today->format('H') >= 17) {
                $daysUntilNext = 7;
            }

            $nextDate = clone $today;
            $nextDate->modify("+{$daysUntilNext} days");

            return [
                'schedule_id' => $result->schedule_id,
                'route_id' => $result->route_id,
                'route_name' => $result->route_name,
                'current_day' => $currentDay,
                'next_collection_date' => $nextDate->format('Y-m-d'),
                'time_slot' => $result->shift_id == 1 ? 'Morning (8:00 AM - 12:00 PM)' : 'Afternoon (1:00 PM - 5:00 PM)',
                'all_collection_days' => $dayNames
            ];
        } catch (Exception $e) {
            error_log("Error getting supplier schedule: " . $e->getMessage());
            return null;
        }
    }

    // Add new method to get current month's collection count
    public function getCurrentMonthCollectionCount($supplier_id) {
        $sql = "SELECT COUNT(*) as count 
                FROM collection_supplier_records 
                WHERE supplier_id = :supplier_id 
                AND MONTH(collection_time) = MONTH(CURRENT_DATE())
                AND YEAR(collection_time) = YEAR(CURRENT_DATE())
                AND (status = 'Collected' OR status = 'Added')";
        
        $this->db->query($sql);
        $this->db->bind(':supplier_id', $supplier_id);
        $result = $this->db->single();
        return $result->count;
    }


    // Add method to update supplier schedule
    public function updateSupplierSchedule($supplier_id, $new_day) {
        try {
            $this->db->beginTransaction();

            // First, update the route day
            $sql = "UPDATE routes r
                    JOIN route_suppliers rs ON r.route_id = rs.route_id
                    SET r.day = :new_day
                    WHERE rs.supplier_id = :supplier_id
                    AND rs.is_active = 1
                    AND rs.is_deleted = 0";
            
            $this->db->query($sql);
            $this->db->bind(':new_day', $new_day);
            $this->db->bind(':supplier_id', $supplier_id);
            $this->db->execute();

            // Then, update collection schedules
            $sql = "UPDATE collection_schedules cs
                    JOIN routes r ON cs.route_id = r.route_id
                    JOIN route_suppliers rs ON r.route_id = rs.route_id
                    SET cs.day = :new_day
                    WHERE rs.supplier_id = :supplier_id
                    AND rs.is_active = 1
                    AND rs.is_deleted = 0
                    AND cs.is_active = 1";
            
            $this->db->query($sql);
            $this->db->bind(':new_day', $new_day);
            $this->db->bind(':supplier_id', $supplier_id);
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Error updating supplier schedule: " . $e->getMessage());
            return false;
        }
    }

    public function getMissedCollections() {
        try {
            $sql = "SELECT 
                        csr.record_id,
                        csr.supplier_id,
                        CONCAT(u.first_name, ' ', u.last_name) as supplier_name,
                        csr.collection_time,
                        csr.status,
                        csr.notes
                    FROM collection_supplier_records csr
                    JOIN suppliers s ON csr.supplier_id = s.supplier_id
                    JOIN users u ON s.user_id = u.user_id
                    WHERE csr.status IN ('No Show', 'Skipped')
                    ORDER BY csr.collection_time DESC
                    LIMIT 5";  // Limiting to 5 most recent missed collections
                    
            $this->db->query($sql);
            return $this->db->resultSet();
        } catch (Exception $e) {
            error_log("Error fetching missed collections: " . $e->getMessage());
            return [];
        }
    }
    

    public function getMonthlyCollectionsByPeriod($supplier_id, $period) {
        try {
            $sql = "SELECT 
                        csr.collection_time as date,
                        c.leaf_type,
                        c.leaf_age,
                        csr.quantity,
                        c.moisture,
                        c.deductions,
                        (csr.quantity - c.deductions) as true_weight,
                        c.rate_per_kg as rate,
                        ((csr.quantity - c.deductions) * c.rate_per_kg) as amount
                    FROM collection_supplier_records csr
                    JOIN collections c ON csr.collection_id = c.collection_id
                    WHERE csr.supplier_id = :supplier_id 
                    AND DATE_FORMAT(csr.collection_time, '%Y-%m') = :period
                    AND (csr.status = 'Collected' OR csr.status = 'Added')
                    ORDER BY csr.collection_time ASC";
            
            $this->db->query($sql);
            $this->db->bind(':supplier_id', $supplier_id);
            $this->db->bind(':period', $period);
            
            $results = $this->db->resultSet();
            
            // Format the results
            $formatted_results = [];
            foreach ($results as $row) {
                $formatted_results[] = [
                    'date' => date('Y-m-d', strtotime($row->date)),
                    'leaf_type' => $row->leaf_type,
                    'leaf_age' => $row->leaf_age,
                    'quantity' => floatval($row->quantity),
                    'moisture' => $row->moisture,
                    'deductions' => floatval($row->deductions),
                    'true_weight' => floatval($row->true_weight),
                    'rate' => floatval($row->rate),
                    'amount' => floatval($row->amount)
                ];
            }
            
            return $formatted_results;
        } catch (Exception $e) {
            error_log("Error fetching monthly collections: " . $e->getMessage());
            return [];
        }
    }
    
} 
