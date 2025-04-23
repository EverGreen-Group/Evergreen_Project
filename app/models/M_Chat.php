<?php
class M_Chat {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getActiveSuppliers() {
        try {
            $userId = $_SESSION['user_id']; 
    
            // Fixed SQL to properly count unread messages only
            $sql = "SELECT u.user_id, p.first_name, p.last_name, 
                           COUNT(CASE WHEN m.read_at IS NULL AND m.sender_id = u.user_id AND m.receiver_id = :user_id THEN 1 ELSE NULL END) AS unread_count
                    FROM users u
                    JOIN profiles p ON u.user_id = p.user_id
                    JOIN suppliers s ON s.profile_id = p.profile_id
                    LEFT JOIN messages m ON m.sender_id = u.user_id AND m.receiver_id = :user_id
                    WHERE u.role_id = 5 
                    GROUP BY u.user_id
                    ORDER BY unread_count DESC, p.last_name ASC;
                    "; 
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error fetching active suppliers: " . $e->getMessage());
            return [];
        }
    }

    public function getActiveManagers() {
        try {
            $userId = $_SESSION['user_id']; 

            $sql = "SELECT u.user_id, p.first_name, p.last_name, 
                           COUNT(CASE WHEN m.read_at IS NULL AND m.sender_id = u.user_id AND m.receiver_id = :user_id THEN 1 ELSE NULL END) AS unread_count
                    FROM users u
                    JOIN profiles p ON u.user_id = p.user_id
                    LEFT JOIN messages m ON m.sender_id = u.user_id AND m.receiver_id = :user_id
                    WHERE u.role_id = 12
                    GROUP BY u.user_id
                    ORDER BY unread_count DESC, p.last_name ASC;";

            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Error fetching active managers: " . $e->getMessage());
            return [];
        }
    }

    public function getUserById($userId) {
        try {
            $sql = "SELECT u.user_id, u.role_id, p.first_name, p.last_name
                    FROM users u
                    LEFT JOIN profiles p ON u.user_id = p.user_id
                    WHERE u.user_id = :user_id
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
        } catch (PDOException $e) {
            error_log("Error fetching user by ID $userId: " . $e->getMessage());
            return null;
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
                $rolePrefix = ($user->role_id == 7) ? 'SUP' : 'MGR'; // Updated role_id for Supplier
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
            // Sanitize message
            $sanitizedMessage = htmlspecialchars($message);
    
            // Step 1: Check for duplicate
            $checkSql = "SELECT COUNT(*) FROM messages 
                         WHERE sender_id = :sender_id 
                         AND receiver_id = :receiver_id 
                         AND message = :message 
                         AND message_type = :message_type 
                         AND created_at > NOW() - INTERVAL 5 SECOND";
    
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([
                ':sender_id' => $senderId,
                ':receiver_id' => $receiverId,
                ':message' => $sanitizedMessage,
                ':message_type' => $messageType
            ]);
    
            if ($checkStmt->fetchColumn() > 0) {
                return [
                    'success' => false,
                    'error' => 'Duplicate message blocked (sent too quickly)'
                ];
            }
    
            // Step 2: Insert if no duplicate
            $sql = "INSERT INTO messages (sender_id, receiver_id, message, message_type, created_at) 
                    VALUES (:sender_id, :receiver_id, :message, :message_type, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':sender_id', $senderId, PDO::PARAM_INT);
            $stmt->bindValue(':receiver_id', $receiverId, PDO::PARAM_INT);
            $stmt->bindValue(':message', $sanitizedMessage, PDO::PARAM_STR);
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
    

    public function getMessages($userId, $chatPartnerId, $lastMessageId = 0) {
        try {
            $sql = "SELECT m.message_id, m.sender_id, m.receiver_id, m.message, m.message_type, 
                           m.created_at, m.read_at, m.edited_at,
                           CONCAT(ps.first_name, ' ', ps.last_name) AS sender_name,
                           CONCAT(pr.first_name, ' ', pr.last_name) AS receiver_name
                    FROM messages m
                    LEFT JOIN profiles ps ON m.sender_id = ps.user_id
                    LEFT JOIN profiles pr ON m.receiver_id = pr.user_id
                    WHERE ((m.sender_id = :user_id AND m.receiver_id = :partner_id)
                       OR (m.sender_id = :partner_id AND m.receiver_id = :user_id))
                       AND m.message_id > :last_message_id
                    ORDER BY m.created_at ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':partner_id', $chatPartnerId, PDO::PARAM_INT);
            $stmt->bindValue(':last_message_id', $lastMessageId, PDO::PARAM_INT);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_OBJ);
    
            error_log("getMessages: Fetched " . count($messages) . " messages for user $userId and partner $chatPartnerId with last_message_id $lastMessageId");
    
            // Mark messages as read if the receiver is the current user
            foreach ($messages as $message) {
                if ($message->receiver_id == $userId && $message->read_at === null) {
                    $this->markMessageAsRead($message->message_id, $userId);
                    $message->read_at = date('Y-m-d H:i:s');
                }
            }
    
            return $messages;
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
            $stmt->bindValue(':new_message', htmlspecialchars($newMessage), PDO::PARAM_STR);
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
            // Verify the message exists and belongs to the user
            $sql = "SELECT sender_id FROM messages WHERE message_id = :message_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->execute();
            $message = $stmt->fetch(PDO::FETCH_OBJ);
    
            if (!$message || $message->sender_id != $userId) {
                return false; // Message not found or user is not the sender
            }
    
            // Delete the message
            $sql = "DELETE FROM messages WHERE message_id = :message_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':message_id', $messageId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Error deleting message: " . $e->getMessage());
            return false;
        }
    }
    

    // public function getAnnouncementsForSupplier($supplierId) {
    //     $this->db->query("
    //         SELECT a.announcement_id, a.title, a.content, a.created_at, a.updated_at, 
    //                CONCAT(p.first_name, ' ', p.last_name) AS sender_name,
    //                u.user_id
    //         FROM announcements a
    //         JOIN users u ON a.created_by = u.user_id
    //         JOIN profiles p ON u.user_id = p.user_id
    //     ");

    //     $announcements = $this->db->resultSet();

    //     // Ensure the data matches the expected format for the view
    //     foreach ($announcements as $announcement) {
    //         // Truncate content for display if needed (to match the table display)
    //         if (strlen($announcement->content) > 50) {
    //             $announcement->content = substr($announcement->content, 0, 47) . '...';
    //         }
    //         // Fallback for sender_name if first_name and last_name are missing
    //         if (empty($announcement->sender_name) || $announcement->sender_name === ' ') {
    //             $announcement->sender_name = 'MGR' . sprintf('%03d', $announcement->user_id);
    //         }
    //     }

    //     return $announcements;
    // }

    public function getAnnouncementsForSupplier($supplierId) {
        $this->db->query("
            SELECT a.announcement_id, a.title, a.content, a.banner, a.created_at, a.updated_at, 
                   CONCAT(p.first_name, ' ', p.last_name) AS sender_name,
                   u.user_id
            FROM announcements a
            JOIN users u ON a.created_by = u.user_id
            JOIN profiles p ON u.user_id = p.user_id
        ");

        $announcements = $this->db->resultSet();

        // Ensure the data matches the expected format for the view
        foreach ($announcements as $announcement) {
            // Fallback for sender_name if first_name and last_name are missing
            if (empty($announcement->sender_name) || $announcement->sender_name === ' ') {
                $announcement->sender_name = 'MGR' . sprintf('%03d', $announcement->user_id);
            }
        }

        return $announcements;
    }
}