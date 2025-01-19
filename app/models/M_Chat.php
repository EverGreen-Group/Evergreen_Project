<?php
class M_Chat {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Add this method to get active suppliers
    public function getActiveSuppliers() {
        $sql = "SELECT u.user_id, u.first_name, u.last_name, 
                       CASE WHEN cr.status = 'accepted' THEN 1 ELSE 0 END as online_status
                FROM users u
                INNER JOIN chat_requests cr ON u.user_id = cr.user_id
                WHERE u.role_id = 5 
                AND cr.status = 'accepted'
                ORDER BY cr.created_at DESC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    // Get chat requests from suppliers
    public function getChatRequests() {
        $sql = "SELECT cr.request_id, u.user_id, u.first_name, u.last_name, 
                       u.role_id, cr.status, cr.created_at 
                FROM chat_requests cr 
                JOIN users u ON cr.user_id = u.user_id 
                WHERE u.role_id = 5 AND cr.status = 'pending'";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    // Accept chat request
    public function acceptChatRequest($requestId) {
        $this->db->query("UPDATE chat_requests SET status = 'accepted' WHERE request_id = :request_id");
        $this->db->bind(':request_id', $requestId);
        return $this->db->execute();
    }

    // Get messages between two users
    public function getMessages($userId1, $userId2) {
        $sql = "SELECT m.*, u.first_name, u.last_name 
                FROM messages m 
                JOIN users u ON m.outgoing_msg_id = u.user_id 
                WHERE (outgoing_msg_id = :user1 AND incoming_msg_id = :user2) 
                OR (outgoing_msg_id = :user2 AND incoming_msg_id = :user1) 
                ORDER BY msg_id";
        
        $this->db->query($sql);
        $this->db->bind(':user1', $userId1);
        $this->db->bind(':user2', $userId2);
        return $this->db->resultSet();
    }

    // Save new message
    public function saveMessage($outgoingId, $incomingId, $message) {
        $sql = "INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg) 
                VALUES (:outgoing_id, :incoming_id, :message)";
        
        $this->db->query($sql);
        $this->db->bind(':outgoing_id', $outgoingId);
        $this->db->bind(':incoming_id', $incomingId);
        $this->db->bind(':message', $message);
        return $this->db->execute();
    }

    // Edit message
    public function editMessage($msgId, $newMessage, $userId) {
        // Only allow editing if user owns the message
        $sql = "UPDATE messages 
                SET msg = :message, edited_at = NOW() 
                WHERE msg_id = :msg_id 
                AND outgoing_msg_id = :user_id";
        
        $this->db->query($sql);
        $this->db->bind(':message', $newMessage);
        $this->db->bind(':msg_id', $msgId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    // Delete message
    public function deleteMessage($msgId, $userId) {
        // Only allow deletion if user owns the message
        $sql = "DELETE FROM messages 
                WHERE msg_id = :msg_id 
                AND outgoing_msg_id = :user_id";
        
        $this->db->query($sql);
        $this->db->bind(':msg_id', $msgId);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function sendMessage($outgoingId, $incomingId, $message) {
        try {
            // Log the attempt
            error_log("Attempting to send message - From: $outgoingId, To: $incomingId");
            
            $sql = "INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg) 
                    VALUES (:outgoing_id, :incoming_id, :message)";
            
            $this->db->query($sql);
            $this->db->bind(':outgoing_id', $outgoingId);
            $this->db->bind(':incoming_id', $incomingId);
            $this->db->bind(':message', $message);
            
            $result = $this->db->execute();
            error_log("Message send result: " . ($result ? "success" : "failed"));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }
}