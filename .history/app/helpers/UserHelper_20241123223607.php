<?php
class UserHelper {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Check if user is admin
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
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

    // Get user role
    public function getUserRole($user_id) {
        $this->db->query('SELECT role FROM users WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        return $result ? $result->role : null;
    }

    // Check if user has specific role
    public function hasRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }

    // Get user details
    public function getUserDetails($user_id) {
        $this->db->query('
            SELECT u.*, e.employee_id, e.first_name, e.last_name, e.email 
            FROM users u 
            LEFT JOIN employees e ON u.user_id = e.user_id 
            WHERE u.user_id = :user_id
        ');
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->single();
    }
}

// Create global helper functions that use the UserHelper class
function isAdmin() {
    $userHelper = new UserHelper();
    return $userHelper->isAdmin();
}

function isLoggedIn() {
    $userHelper = new UserHelper();
    return $userHelper->isLoggedIn();
}

function hasRole($role) {
    $userHelper = new UserHelper();
    return $userHelper->hasRole($role);
} 