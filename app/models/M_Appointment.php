<?php

class M_Appointment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    
    // Add function to clean up expired available slots
    private function cleanupExpiredAvailableSlots() {
        // First, get the IDs of slots that will be deleted
        $this->db->query("SELECT slot_id FROM appointment_slots 
                         WHERE status = 'Available' 
                         AND CONCAT(date, ' 00:00:00') <= NOW()");
        $expiredSlots = $this->db->resultSet();
        
        // Delete any pending requests for these slots
        if (!empty($expiredSlots)) {
            foreach ($expiredSlots as $slot) {
                $this->db->query("DELETE FROM appointment_requests 
                                 WHERE slot_id = :slot_id");
                $this->db->bind(':slot_id', $slot->slot_id);
                $this->db->execute();
            }
        }
        
        // Now delete the slots themselves
        $this->db->query("DELETE FROM appointment_slots 
                         WHERE status = 'Available' 
                         AND CONCAT(date, ' 00:00:00') <= NOW()");
        $this->db->execute();
    }
    
    // Add function to clean up expired booked slots
    private function cleanupExpiredBookedSlots() {
        // First, get the IDs of slots that will be deleted
        $this->db->query("SELECT slot_id FROM appointment_slots 
                         WHERE status = 'Booked' 
                         AND CONCAT(date, ' 12:00:00') <= NOW()");
        $expiredSlots = $this->db->resultSet();
        
        // Delete any requests for these slots (though they should be completed already)
        if (!empty($expiredSlots)) {
            foreach ($expiredSlots as $slot) {
                $this->db->query("DELETE FROM appointment_requests 
                                 WHERE slot_id = :slot_id");
                $this->db->bind(':slot_id', $slot->slot_id);
                $this->db->execute();
            }
        }
        
        // Now delete the slots themselves
        $this->db->query("DELETE FROM appointment_slots 
                         WHERE status = 'Booked' 
                         AND CONCAT(date, ' 12:00:00') <= NOW()");
        $this->db->execute();
    }
    
    // Check if a time slot already exists
    public function isSlotDuplicate($data) {
        $this->db->query("SELECT * FROM appointment_slots 
                         WHERE manager_id = :manager_id 
                         AND date = :date 
                         AND start_time = :start_time 
                         AND end_time = :end_time");
        
        $this->db->bind(':manager_id', $data['manager_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        
        $result = $this->db->single();
        return !empty($result);
    }

    public function getManagerTimeSlots($managerId) {
        // Auto-delete expired available slots (at midnight of the slot date)
        $this->cleanupExpiredAvailableSlots();
        
        // Auto-delete expired booked slots (at noon of the slot date)
        $this->cleanupExpiredBookedSlots();
        
        $this->db->query("SELECT * FROM appointment_slots WHERE manager_id = :manager_id AND date >= CURDATE()");
        $this->db->bind(':manager_id', $managerId);
        return $this->db->resultSet();
    }

    public function filteredTimeSlots($managerId, $status = '', $date = '') {
        $sql = "SELECT * FROM appointment_slots WHERE manager_id = :manager_id";
        $params = [':manager_id' => $managerId];
        
        // Add status filter if provided
        if (!empty($status)) {
            $sql .= " AND status = :status";
            $params[':status'] = $status;
        }
        
        // Add date filter if provided
        if (!empty($date)) {
            $sql .= " AND date = :date";
            $params[':date'] = $date;
        }
        
        // Add order by to sort results
        $sql .= " ORDER BY date DESC, start_time ASC";
        
        $this->db->query($sql);
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        return $this->db->resultSet();
    }
    
    public function getIncomingRequests($managerId) {   // tested
        $this->db->query("
            SELECT r.request_id, CONCAT(p.first_name, ' ', p.last_name) AS supplier_name, sl.date, sl.start_time, sl.end_time, r.submitted_at, p.*, s.supplier_id
            FROM appointment_requests r
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            JOIN suppliers s ON r.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id
            WHERE sl.manager_id = :manager_id AND r.status = 'Pending'
        ");
        $this->db->bind(':manager_id', $managerId);
        return $this->db->resultSet();
    }
    
    public function getAcceptedAppointments($managerId) {   // tested
        $this->db->query("
            SELECT a.appointment_id, CONCAT(p.first_name, ' ', p.last_name) AS supplier_name, sl.date, sl.start_time, sl.end_time, p.*, s.supplier_id
            FROM appointments a
            JOIN appointment_requests r ON a.request_id = r.request_id
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            JOIN suppliers s ON r.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id
            WHERE sl.manager_id = :manager_id
            AND sl.date >= CURDATE();
        ");
        $this->db->bind(':manager_id', $managerId);
        return $this->db->resultSet();
    }
    

    public function createSlot($data) {
        // Check if the date is not earlier than today
        if(strtotime($data['date']) < strtotime(date('Y-m-d'))) {
            return false;
        }
        
        // Check if the time slot overlaps with another
        if($this->isSlotOverlapping($data)) {
            return false;
        }
        
        $this->db->query('INSERT INTO appointment_slots 
            (manager_id, date, start_time, end_time, status) 
            VALUES (:manager_id, :date, :start_time, :end_time, :status)');
    
        $this->db->bind(':manager_id', $data['manager_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':status', 'Available');
    
        return $this->db->execute();
    }

    public function acceptRequest($requestId) {
        // First, get the slot_id for the accepted request
        $this->db->query("SELECT slot_id FROM appointment_requests WHERE request_id = :request_id");
        $this->db->bind(':request_id', $requestId);
        $slot = $this->db->single();

        // Insert into appointments table
        $this->db->query("INSERT INTO appointments (request_id, accepted_at) VALUES (:request_id, NOW())");
        $this->db->bind(':request_id', $requestId);
        $this->db->execute();

        // Reject other requests for the same slot
        $this->db->query("UPDATE appointment_requests SET status = 'Rejected' WHERE slot_id = :slot_id AND request_id != :request_id");
        $this->db->bind(':slot_id', $slot->slot_id);
        $this->db->bind(':request_id', $requestId);
        $this->db->execute();

        $this->db->query("UPDATE appointment_requests SET status = 'Accepted' WHERE slot_id = :slot_id AND request_id = :request_id");
        $this->db->bind(':slot_id', $slot->slot_id);
        $this->db->bind(':request_id', $requestId);
        $this->db->execute();

        $this->db->query("UPDATE appointment_slots SET status = 'Booked' WHERE slot_id = :slot_id");
        $this->db->bind(':slot_id', $slot->slot_id);
        return $this->db->execute();
    }

    public function rejectRequest($requestId) {
        $this->db->query("UPDATE appointment_requests SET status = 'Rejected' WHERE request_id = :request_id");
        $this->db->bind(':request_id', $requestId);
        return $this->db->execute();
    }

    public function getAvailableTimeSlots() {
        $this->db->query("SELECT s.*, CONCAT(p.first_name, ' ', p.last_name) as manager_name, p.* 
                          FROM appointment_slots s
                          JOIN managers m ON s.manager_id = m.manager_id
                          JOIN profiles p ON m.profile_id = p.profile_id
                          WHERE s.status = 'Available'"); 
        return $this->db->resultSet();
    }

    
    public function getRequestsBySlotExcept($slotId, $excludeRequestId) {
        $this->db->query("SELECT * FROM appointment_requests 
                          WHERE slot_id = :slot_id AND request_id != :exclude_id AND status = 'Pending'");
        $this->db->bind(':slot_id', $slotId);
        $this->db->bind(':exclude_id', $excludeRequestId);
        return $this->db->resultSet();
    }
    


    public function getMyRequests($supplierId) {
        $this->db->query("
            SELECT r.*, sl.date, sl.start_time, sl.end_time, sl.manager_id, p.*
            FROM appointment_requests r
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            JOIN managers m ON sl.manager_id = m.manager_id
            JOIN profiles p ON m.profile_id = p.profile_id
            WHERE r.supplier_id = :supplier_id
            ORDER BY sl.date, sl.start_time
        ");
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }
    
    public function getConfirmedAppointments($supplierId) {
        $this->db->query("
            SELECT a.*, r.supplier_id, sl.date, sl.start_time, sl.end_time, sl.manager_id, p.*
            FROM appointments a
            JOIN appointment_requests r ON a.request_id = r.request_id
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            JOIN managers m ON sl.manager_id = m.manager_id
            JOIN profiles p ON m.profile_id = p.profile_id
            WHERE r.supplier_id = :supplier_id
            ORDER BY sl.date, sl.start_time
        ");
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }
    
    public function cancelRequest($requestId, $supplierId) {
        $this->db->query("DELETE FROM appointment_requests WHERE request_id = :request_id AND supplier_id = :supplier_id AND status = 'Pending'");
        $this->db->bind(':request_id', $requestId);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->execute();
    }

    public function isSlotOverlapping($data) {
        $this->db->query("SELECT * FROM appointment_slots 
                         WHERE manager_id = :manager_id 
                         AND date = :date 
                         AND (
                             (:start_time BETWEEN start_time AND end_time) OR
                             (:end_time BETWEEN start_time AND end_time) OR
                             (start_time BETWEEN :start_time AND :end_time) OR
                             (end_time BETWEEN :start_time AND :end_time)
                         )");
        
        $this->db->bind(':manager_id', $data['manager_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        
        $result = $this->db->resultSet();
        return !empty($result);
    }

    public function getSlotById($slotId) {
        $this->db->query("SELECT * FROM appointment_slots WHERE slot_id = :slot_id");
        $this->db->bind(':slot_id', $slotId);
        return $this->db->single();
    }
    
    public function getRequestById($requestId) {
        $this->db->query("SELECT * FROM appointment_requests WHERE request_id = :request_id");
        $this->db->bind(':request_id', $requestId);
        return $this->db->single();
    }
    
    public function createRequest($data) {
        $this->db->query("INSERT INTO appointment_requests 
            (supplier_id, slot_id, status, submitted_at) 
            VALUES (:supplier_id, :slot_id, :status, :submitted_at)");
        
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':slot_id', $data['slot_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':submitted_at', $data['submitted_at']);
        
        return $this->db->execute();
    }

    public function hasAlreadyRequested($slotId, $supplierId) {
        $this->db->query("SELECT * FROM appointment_requests WHERE slot_id = :slot_id AND supplier_id = :supplier_id");
        $this->db->bind(':slot_id', $slotId);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single(); 
    }
    
    // Add function to cancel a time slot
    public function cancelSlot($slotId) {
        
        // Then delete the slot itself
        $this->db->query("DELETE FROM appointment_slots 
                         WHERE slot_id = :slot_id 
                         AND status = 'Available'");
        
        $this->db->bind(':slot_id', $slotId);
        
        return $this->db->execute();
    }

    public function getAllAppointments($manager_id) {
        // More comprehensive query to get all relevant information
        $this->db->query("SELECT 
                            a.appointment_id, 
                            ar.supplier_id, 
                            ar.status, 
                            asl.date,
                            asl.start_time,
                            asl.end_time,
                            CONCAT(p.first_name, ' ', p.last_name) AS supplier_name,
                            p.image_path
                          FROM appointments a
                          JOIN appointment_requests ar ON a.request_id = ar.request_id
                          JOIN appointment_slots asl ON ar.slot_id = asl.slot_id
                          LEFT JOIN suppliers s ON ar.supplier_id = s.supplier_id
                          LEFT JOIN profiles p ON s.profile_id = p.profile_id
                          WHERE asl.manager_id = :manager_id
                          ORDER BY asl.date DESC, ar.submitted_at DESC");
        
        $this->db->bind(':manager_id', $manager_id);
        return $this->db->resultSet();
    }


}
?>