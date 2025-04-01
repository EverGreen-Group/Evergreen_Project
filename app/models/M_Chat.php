<?php

class M_Chat {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function markMessageAsRead($messageId, $userId) {
        try {
            $sql = "UPDATE messages SET read_at = NOW() WHERE message_id = :message_id AND receiver_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error marking message as read: " . $e->getMessage());
            return false;
        }
    }

    // Get active suppliers for supplier managers
    public function getActiveSuppliers() {
        $sql = "SELECT u.user_id, u.first_name, u.last_name 
                FROM users u
                WHERE u.role_id = 5 
                AND u.approval_status = 'Approved'
                ORDER BY u.first_name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    // Get supplier managers for suppliers
    public function getSupplierManagers() {
        $sql = "SELECT u.user_id, u.first_name, u.last_name
                FROM users u
                WHERE u.role_id = 1
                AND u.approval_status = 'Approved'
                ORDER BY u.first_name ASC";
        
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSupplierDetailsByUserId($user_id) {
        $this->db->query("
            SELECT * FROM users 
            WHERE user_id = :user_id 
            AND role_id = 5
            LIMIT 1
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->single(); // Fetch one row as an object
    }

    // Get messages between two users
    public function getMessages($senderId, $receiverId) {
        try {
            $sql = "SELECT m.*, u_sender.first_name AS sender_first_name, u_sender.last_name AS sender_last_name, 
                           u_receiver.first_name AS receiver_first_name, u_receiver.last_name AS receiver_last_name
                    FROM messages m
                    LEFT JOIN users u_sender ON m.sender_id = u_sender.user_id
                    LEFT JOIN users u_receiver ON m.receiver_id = u_receiver.user_id
                    WHERE (m.sender_id = :sender_id AND m.receiver_id = :receiver_id)
                    OR (m.sender_id = :receiver_id AND m.receiver_id = :sender_id)
                    ORDER BY m.created_at ASC
                    LIMIT 0, 25";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindValue(':receiver_id', $receiverId, PDO::PARAM_INT);
            $stmt->execute();
            
            $messages = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            foreach ($messages as &$message) {
                $message->sender_name = htmlspecialchars($message->sender_first_name . ' ' . $message->sender_last_name);
                $message->receiver_name = htmlspecialchars($message->receiver_first_name . ' ' . $message->receiver_last_name);
                // Ensure all fields are present, even if NULL
                $message->read_at = $message->read_at ?? null;
                $message->edited_at = $message->edited_at ?? null;
                $message->message_type = $message->message_type ?? 'text';
            }
            
            error_log("Fetched messages for sender $senderId and receiver $receiverId: " . print_r($messages, true));
            return $messages;
        } catch (PDOException $e) {
            error_log("Error getting messages: " . $e->getMessage());
            return [];
        }
    }
    
    // Save message to database
    public function saveMessage($senderId, $receiverId, $message, $messageType = 'text') {
        try {
            $sql = "INSERT INTO messages 
                    (sender_id, receiver_id, message, created_at, message_type) 
                    VALUES 
                    (:sender_id, :receiver_id, :message, NOW(), :message_type)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindValue(':receiver_id', $receiverId, PDO::PARAM_INT);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            $stmt->bindValue(':message_type', $messageType, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message_id' => $this->db->lastInsertId(),
                    'sender_id' => $senderId,
                    'receiver_id' => $receiverId,
                    'message' => $message,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
            return ['success' => false, 'error' => 'Failed to save message'];
        } catch (PDOException $e) {
            error_log("Error saving message: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    // Edit message (update message and edited_at)
    public function editMessage($messageId, $newMessage, $userId) {
        try {
            $sql = "UPDATE messages 
                    SET message = :message, edited_at = NOW()
                    WHERE message_id = :message_id AND sender_id = :user_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message', $newMessage, PDO::PARAM_STR);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            error_log("Edit message result for message_id $messageId by user $userId: " . ($result ? 'Success' : 'Failed'));
            return $result;
        } catch (PDOException $e) {
            error_log("Error editing message: " . $e->getMessage());
            return false;
        }
    }

    // Delete message
    public function deleteMessage($messageId, $userId) {
        try {
            $sql = "DELETE FROM messages 
                    WHERE message_id = :message_id AND sender_id = :user_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            
            $result = $stmt->execute();
            error_log("Delete message result for message_id $messageId by user $userId: " . ($result ? 'Success' : 'Failed'));
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting message: " . $e->getMessage());
            return false;
        }
    }

    // Get active chats for a user (optional, for chat list)
    public function getActiveChats($userId) {
        try {
            $sql = "SELECT DISTINCT 
                        u.user_id,
                        u.first_name,
                        u.last_name,
                        scr.status,
                        scr.created_at
                    FROM send_chat_requests scr
                    JOIN users u ON (
                        CASE 
                            WHEN scr.sender_id = :user_id THEN scr.receiver_id = u.user_id
                            WHEN scr.receiver_id = :user_id THEN scr.sender_id = u.user_id
                        END
                    )
                    WHERE (scr.sender_id = :user_id OR scr.receiver_id = :user_id)
                    AND scr.status = 'accepted'
                    ORDER BY scr.created_at DESC";
            
            $this->db->query($sql);
            $this->db->bind(':user_id', $userId);
            
            return $this->db->resultSet();
        } catch (PDOException $e) {
            error_log("Error getting active chats: " . $e->getMessage());
            return [];
        }
    }

    // Debugging method
    public function debugSupplierManagers() {
        try {
            $sql = "SELECT 
                        user_id,
                        first_name,
                        last_name,
                        role_id,
                        approval_status
                    FROM users 
                    WHERE role_id IN (1, 2)";
            
            $this->db->query($sql);
            $result = $this->db->resultSet();
            error_log("Debug supplier managers: " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Debug Error: " . $e->getMessage());
            return [];
        }
    }
}