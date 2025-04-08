<?php
class M_Chat {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getActiveSuppliers() {
        try {
            $sql = "SELECT u.user_id, p.first_name, p.last_name
                    FROM users u
                    LEFT JOIN profiles p ON u.user_id = p.user_id
                    WHERE u.role_id = 5";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error fetching active suppliers: " . $e->getMessage());
            return [];
        }
    }

    public function getActiveManagers() {
        try {
            $sql = "SELECT u.user_id, p.first_name, p.last_name
                    FROM users u
                    LEFT JOIN profiles p ON u.user_id = p.user_id
                    WHERE u.role_id = 4";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error fetching active managers: " . $e->getMessage());
            return [];
        }
    }

    public function getUserName($userId) {
        try {
            $sql = "SELECT p.first_name, p.last_name, u.role_id 
                    FROM users u 
                    LEFT JOIN profiles p ON u.user_id = p.user_id 
                    WHERE u.user_id = :user_id 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_OBJ);

            if ($user && $user->first_name && $user->last_name) {
                $rolePrefix = ($user->role_id == 5) ? 'SUP' : 'MGR';
                $userIdPadded = sprintf('%03d', $userId);
                return htmlspecialchars($user->first_name . ' ' . $user->last_name) . " ($rolePrefix$userIdPadded)";
            }
            return "User $userId";
        } catch (PDOException $e) {
            error_log("Error fetching user name for ID $userId: " . $e->getMessage());
            return "User $userId";
        }
    }

    public function saveMessage($senderId, $receiverId, $message, $messageType = 'text') {
        try {
            $sql = "INSERT INTO messages (sender_id, receiver_id, message, message_type, created_at) 
                    VALUES (:sender_id, :receiver_id, :message, :message_type, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindValue(':receiver_id', $receiverId, PDO::PARAM_INT);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            $stmt->bindValue(':message_type', $messageType, PDO::PARAM_STR);
            $stmt->execute();
            
            $messageId = $this->db->lastInsertId();
            $createdAt = $this->db->query("SELECT created_at FROM messages WHERE message_id = $messageId")->fetch(PDO::FETCH_OBJ)->created_at;
            
            return [
                'success' => true,
                'message_id' => $messageId,
                'created_at' => $createdAt
            ];
        } catch (PDOException $e) {
            error_log("Error saving message: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function getMessages($userId, $chatPartnerId) {
        try {
            $sql = "SELECT m.message_id, m.sender_id, m.receiver_id, m.message, m.message_type, 
                           m.created_at, m.read_at, m.edited_at,
                           CONCAT(ps.first_name, ' ', ps.last_name) AS sender_name,
                           CONCAT(pr.first_name, ' ', pr.last_name) AS receiver_name
                    FROM messages m
                    LEFT JOIN profiles ps ON m.sender_id = ps.user_id
                    LEFT JOIN profiles pr ON m.receiver_id = pr.user_id
                    WHERE (m.sender_id = :user_id AND m.receiver_id = :partner_id)
                       OR (m.sender_id = :partner_id AND m.receiver_id = :user_id)
                    ORDER BY m.created_at ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':partner_id', $chatPartnerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error fetching messages: " . $e->getMessage());
            return [];
        }
    }

    public function markMessageAsRead($messageId, $userId) {
        try {
            $sql = "UPDATE messages 
                    SET read_at = NOW() 
                    WHERE message_id = :message_id 
                    AND receiver_id = :user_id 
                    AND read_at IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            error_log("Error marking message as read: " . $e->getMessage());
            return false;
        }
    }

    public function editMessage($messageId, $newMessage, $userId) {
        try {
            $sql = "UPDATE messages 
                    SET message = :new_message, edited_at = NOW() 
                    WHERE message_id = :message_id 
                    AND sender_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':new_message', $newMessage, PDO::PARAM_STR);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error editing message: " . $e->getMessage());
            return false;
        }
    }

    public function deleteMessage($messageId, $userId) {
        try {
            $sql = "DELETE FROM messages 
                    WHERE message_id = :message_id 
                    AND sender_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting message: " . $e->getMessage());
            return false;
        }
    }
}