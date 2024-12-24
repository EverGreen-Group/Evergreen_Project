<?php

class M_Employee {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getLastInsertedId() {
        return $this->db->lastInsertId();
    }

    public function addEmployee($data) {
        $this->db->query("INSERT INTO employees (user_id, hire_date, contact_number, emergency_contact, status, address_line1, address_line2, city) 
                          VALUES (:user_id, :hire_date, :contact_number, :emergency_contact, :status, :address_line1, :address_line2, :city)");
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':hire_date', $data['hire_date']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':emergency_contact', $data['emergency_contact']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':address_line1', $data['address_line1']);
        $this->db->bind(':address_line2', $data['address_line2']);
        $this->db->bind(':city', $data['city']);
        
        return $this->db->execute();
    }

    public function getEmployeeByUserId($user_id) {
        $this->db->query("
            SELECT e.*, u.*
            FROM employees e
            JOIN users u ON e.user_id = u.user_id
            WHERE e.user_id = :user_id
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single(); // This should return a single employee object with user details or null
    }

    public function updateDriverInfo($user_id, $address_line1, $address_line2, $city, $contact_number, $emergency_contact) {
        $sql = "UPDATE employees SET address_line1 = :address_line1, address_line2 = :address_line2, city = :city, contact_number = :contact_number, emergency_contact = :emergency_contact WHERE user_id = :user_id";
    
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':address_line1', $address_line1);
        $stmt->bindValue(':address_line2', $address_line2);
        $stmt->bindValue(':city', $city);
        $stmt->bindValue(':contact_number', $contact_number);
        $stmt->bindValue(':emergency_contact', $emergency_contact);
        $stmt->bindValue(':user_id', $user_id);
    
        return $stmt->execute(); 
    }
}
?>