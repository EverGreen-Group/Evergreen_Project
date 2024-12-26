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
                    JOIN suppliers s ON csr.supplier_id = s.supplier_id
                    JOIN users u ON s.user_id = u.user_id
                    WHERE 
                        YEAR(csr.collection_time) = YEAR(CURDATE())
                        AND csr.status = 'Collected'
                        AND s.supplier_id = :supplier_id
                    GROUP BY 
                        MONTH(csr.collection_time),
                        MONTHNAME(csr.collection_time)
                    ORDER BY 
                        MONTH(csr.collection_time)";
            
            $this->db->query($sql);
            
            // Get supplier_id from session or use default for testing
            $supplier_id = isset($_SESSION['supplier_id']) ? $_SESSION['supplier_id'] : 2;
            $this->db->bind(':supplier_id', $supplier_id);
            
            $result = $this->db->resultSet();
            
            // Log the query result for debugging
            error_log("Collection data query result: " . print_r($result, true));
            
            return $result;
        } catch (Exception $e) {
            error_log("Error fetching collection data: " . $e->getMessage());
            return [];
        }
    }

    public function getSupplierSchedule($supplier_id) {
        try {
            // First, check if the supplier exists in route_suppliers
            $this->db->query("
                SELECT 
                    rs.route_id,
                    rs.supplier_id,
                    r.route_name,
                    CAST(r.day AS UNSIGNED) as route_day,  -- Explicitly cast to integer in the query
                    cs.shift_id,
                    cs.schedule_id
                FROM route_suppliers rs
                JOIN routes r ON rs.route_id = r.route_id
                LEFT JOIN collection_schedules cs ON cs.route_id = r.route_id
                WHERE rs.supplier_id = :supplier_id 
                AND rs.is_active = 1 
                AND rs.is_deleted = 0
                AND r.is_deleted = 0
                AND (cs.is_active = 1 OR cs.is_active IS NULL)
                AND (cs.is_deleted = 0 OR cs.is_deleted IS NULL)
                LIMIT 1
            ");
            
            $this->db->bind(':supplier_id', $supplier_id);
            $result = $this->db->single();
    
            if (!$result) {
                error_log("No route found for supplier_id: " . $supplier_id);
                return null;
            }
    
            // Convert day number to name and calculate next collection date
            $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $routeDay = intval($result->route_day); // Ensure integer type
            $currentDay = $dayNames[$routeDay % 7];
            
            // Calculate next collection date
            $today = new DateTime();
            $todayDayNum = (int)$today->format('w'); // 0 (Sunday) to 6 (Saturday)
            $scheduleDayNum = $routeDay % 7;
            
            $daysUntilNext = ($scheduleDayNum - $todayDayNum + 7) % 7;
            if ($daysUntilNext === 0 && $today->format('H') >= 17) { // After 5 PM
                $daysUntilNext = 7;
            }
    
            $nextDate = clone $today;
            $nextDate->modify("+{$daysUntilNext} days");
    
            // Determine time slot based on shift_id
            $timeSlot = $result->shift_id == 1 ? 
                'Morning (8:00 AM - 12:00 PM)' : 
                'Afternoon (1:00 PM - 5:00 PM)';
    
            return [
                'schedule_id' => $result->schedule_id,
                'next_collection_day' => $currentDay,
                'next_collection_date' => $nextDate->format('Y-m-d'),
                'route_name' => $result->route_name,
                'time_slot' => $timeSlot,
                'route_id' => $result->route_id,
                'all_collection_days' => [$currentDay]
            ];
    
        } catch (Exception $e) {
            error_log("Error fetching supplier schedule: " . $e->getMessage());
            return null;
        }
    }
    
    
} 
