<?php

class M_Complaint {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Function to create a new complaint
    public function create($type, $description, $email, $phone) {
        $query = $this->db->query("INSERT INTO complaints (type, description, email, phone) VALUES (?, ?, ?, ?)");
        $query->execute([$type, $description, $email, $phone]);
    }

    public function getComplaints($supplier_id) {
        $this->db->query("SELECT * FROM complaints WHERE supplier_id = :supplier_id ORDER BY updated_at DESC");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultSet();
    }

    public function markResolved($complaintId) {
        $sql = "UPDATE complaints SET status = 'Resolved' WHERE complaint_id = :complaint_id";
        $this->db->query($sql);
        $this->db->bind(':complaint_id', $complaintId);
        return $this->db->execute();
        
    }
}
