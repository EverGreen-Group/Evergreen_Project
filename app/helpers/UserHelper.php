<?php
class UserHelper {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get manager_id from user_id
    public function getManagerId($user_id) {
        $this->db->query('
            SELECT m.manager_id 
            FROM managers m 
            JOIN employees e ON m.employee_id = e.employee_id 
            JOIN users u ON e.user_id = u.user_id 
            WHERE u.user_id = :user_id
        ');
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        return $result ? $result->manager_id : null;
    }

    // Get supplier_id from user_id
    public function getSupplierId($user_id) {
        $this->db->query('
            SELECT s.supplier_id 
            FROM suppliers s 
            JOIN employees e ON s.employee_id = e.employee_id 
            JOIN users u ON e.user_id = u.user_id 
            WHERE u.user_id = :user_id
        ');
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        return $result ? $result->supplier_id : null;
    }

    // Add more helper methods as needed...
} 