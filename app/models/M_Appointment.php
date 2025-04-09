<?php

class M_Appointment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getManagerTimeSlots($managerId) {
        $this->db->query("SELECT * FROM appointment_slots WHERE manager_id = :manager_id");
        $this->db->bind(':manager_id', $managerId);
        return $this->db->resultSet();
    }
    
    public function getIncomingRequests($managerId) {
        $this->db->query("
            SELECT r.request_id, CONCAT(p.first_name, ' ', p.last_name) AS supplier_name, sl.date, sl.start_time, sl.end_time, r.submitted_at, p.*
            FROM appointment_requests r
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            JOIN suppliers s ON r.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id
            WHERE sl.manager_id = :manager_id AND r.status = 'Pending'
        ");
        $this->db->bind(':manager_id', $managerId);
        return $this->db->resultSet();
    }
    
    public function getAcceptedAppointments($managerId) {
        $this->db->query("
            SELECT a.appointment_id, CONCAT(p.first_name, ' ', p.last_name) AS supplier_name, sl.date, sl.start_time, sl.end_time, p.*
            FROM appointments a
            JOIN appointment_requests r ON a.request_id = r.request_id
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            JOIN suppliers s ON r.supplier_id = s.supplier_id
            JOIN profiles p ON s.profile_id = p.profile_id
            WHERE sl.manager_id = :manager_id
        ");
        $this->db->bind(':manager_id', $managerId);
        return $this->db->resultSet();
    }
    

        public function createSlot($data) {
        $this->db->query('INSERT INTO appointment_slots 
            (manager_id, date, start_time, end_time) 
            VALUES (:manager_id, :date, :start_time, :end_time)');

        $this->db->bind(':manager_id', $data['manager_id']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);

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
        $this->db->query("SELECT * FROM appointment_slots WHERE status = 'Available'"); // Adjust the query as needed
        return $this->db->resultSet();
    }


    public function getMyRequests($supplierId) {
        $this->db->query("
            SELECT r.*, sl.date, sl.start_time, sl.end_time, sl.manager_id
            FROM appointment_requests r
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
            WHERE r.supplier_id = :supplier_id
            ORDER BY sl.date, sl.start_time
        ");
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }
    
    public function getConfirmedAppointments($supplierId) {
        $this->db->query("
            SELECT a.*, r.supplier_id, sl.date, sl.start_time, sl.end_time, sl.manager_id
            FROM appointments a
            JOIN appointment_requests r ON a.request_id = r.request_id
            JOIN appointment_slots sl ON r.slot_id = sl.slot_id
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
    

}
?>