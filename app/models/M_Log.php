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
    



} 