<?php

class M_Log {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($userId, $email, $ipAddress, $message, $url = null, $statusCode = 200)
    {
        $sql = "INSERT INTO user_logs (user_id, email, ip_address, message, url, status_code, timestamp) 
                VALUES (:user_id, :email, :ip_address, :message, :url, :status_code, NOW())";
        
        $this->db->query($sql);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':email', $email);
        $this->db->bind(':ip_address', $ipAddress);
        $this->db->bind(':message', $message);
        $this->db->bind(':url', $url);
        $this->db->bind(':status_code', $statusCode);
    
        return $this->db->execute();
    }

    public function getAllUserLogs() {
        $sql = "SELECT * FROM user_logs ORDER BY timestamp DESC"; 
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getFilteredUserLogs($userId = null, $email = null) {
        $sql = "SELECT * FROM user_logs WHERE 1=1"; // Start with a base query
    
        // Add conditions based on provided filters
        if ($userId) {
            $sql .= " AND user_id = :user_id";
        }
        if ($email) {
            $sql .= " AND email LIKE :email";
        }
    
        $sql .= " ORDER BY timestamp DESC";
    
        $this->db->query($sql);
    
        // Bind parameters if they exist
        if ($userId) {
            $this->db->bind(':user_id', $userId);
        }
        if ($email) {
            $this->db->bind(':email', "%$email%");
        }
    
        return $this->db->resultSet();
    }
    



} 