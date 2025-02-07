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
        try {
            // Query both tables and combine results using UNION
            $sql = "SELECT 
                        'chat_requests' as source,
                        cr.request_id,
                        u.user_id,
                        u.first_name,
                        u.last_name,
                        cr.status,
                        cr.created_at
                    FROM chat_requests cr
                    JOIN users u ON cr.user_id = u.user_id
                    WHERE cr.status = 'pending'
                    
                    UNION ALL
                    
                    SELECT 
                        'send_chat_requests' as source,
                        scr.request_id,
                        u.user_id,
                        u.first_name,
                        u.last_name,
                        scr.status,
                        scr.created_at
                    FROM send_chat_requests scr
                    JOIN users u ON scr.sender_id = u.user_id
                    WHERE scr.receiver_id = :user_id 
                    AND scr.status = 'pending'
                    AND u.role_id = 5  -- Supplier role
                    
                    ORDER BY created_at DESC";
            
            $this->db->query($sql);
            $this->db->bind(':user_id', $_SESSION['user_id']);
            
            $results = $this->db->resultSet();
            error_log("Chat Requests Query Result: " . print_r($results, true));
            return $results;
        } catch (PDOException $e) {
            error_log("Error getting chat requests: " . $e->getMessage());
            return [];
        }
    }

    // Get messages between two users
    public function getMessages($userId1, $userId2) {
        try {
            $sql = "SELECT * FROM messages 
                    WHERE (sender_id = :user1 AND receiver_id = :user2)
                    OR (sender_id = :user2 AND receiver_id = :user1)
                    ORDER BY created_at ASC";
            
            $this->db->query($sql);
            $this->db->bind(':user1', $userId1);
            $this->db->bind(':user2', $userId2);
            
            return $this->db->resultSet();
            
        } catch (PDOException $e) {
            error_log("Error getting messages: " . $e->getMessage());
            return [];
        }
    }

    // Save new message
    public function saveMessage($outgoingId, $incomingId, $message) {
        $sql = "INSERT INTO messages (outgoing_msg_id, incoming_msg_id, msg, created_at) 
                VALUES (:outgoing_id, :incoming_id, :message, NOW())";
        
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

    // public function sendMessage($senderId, $receiverId, $message) {
    //     try {
    //         $sql = "INSERT INTO messages (sender_id, receiver_id, message, created_at) 
    //                 VALUES (:sender_id, :receiver_id, :message, NOW())";
            
    //         $this->db->query($sql);
    //         $this->db->bind(':sender_id', $senderId);
    //         $this->db->bind(':receiver_id', $receiverId);
    //         $this->db->bind(':message', $message);
            
    //         return $this->db->execute();
    //     } catch (PDOException $e) {
    //         error_log("Error sending message: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // Add this new method newly added by theekshana 20th of Jan
    public function getSupplierManagers() {
        try {
            $sql = "SELECT DISTINCT 
                        u.user_id, 
                        u.first_name, 
                        u.last_name,
                        u.role_id,
                        u.approval_status as status,
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM send_chat_requests scr 
                                WHERE ((scr.sender_id = :current_user AND scr.receiver_id = u.user_id)
                                OR (scr.receiver_id = :current_user AND scr.sender_id = u.user_id))
                                AND scr.status = 'accepted'
                            ) THEN 1 
                            ELSE 0 
                        END as chat_status,
                        CASE 
                            WHEN EXISTS (
                                SELECT 1 FROM send_chat_requests scr 
                                WHERE scr.sender_id = :current_user 
                                AND scr.receiver_id = u.user_id
                                AND scr.status = 'pending'
                            ) THEN 'pending'
                            ELSE 'none'
                        END as request_status
                    FROM users u
                    WHERE u.role_id IN (1, 2)  -- Manager roles
                    AND u.approval_status = 'Approved'
                    ORDER BY u.first_name ASC";
            
            $this->db->query($sql);
            $this->db->bind(':current_user', $_SESSION['user_id']);
            
            $result = $this->db->resultSet();
            error_log("Supplier Managers Query Result: " . print_r($result, true));
            return $result;
        } catch (PDOException $e) {
            error_log("Error getting supplier managers: " . $e->getMessage());
            return [];
        }
    }

    // Add method to create chat request
    public function createChatRequest($userId, $receiverId = null) {
        try {
            if ($receiverId === null) {
                // Old chat_requests table
                $sql = "INSERT INTO chat_requests (user_id, status, created_at, request_type) 
                        VALUES (:user_id, 'pending', NOW(), 'send')";
                
                $this->db->query($sql);
                $this->db->bind(':user_id', $userId);
            } else {
                // New send_chat_requests table
                $sql = "INSERT INTO send_chat_requests (sender_id, receiver_id, status, created_at) 
                        VALUES (:sender_id, :receiver_id, 'pending', NOW())";
                
                $this->db->query($sql);
                $this->db->bind(':sender_id', $userId);
                $this->db->bind(':receiver_id', $receiverId);
            }
            
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error creating chat request: " . $e->getMessage());
            return false;
        }
    }

    // Add method to accept chat request from either table
    public function acceptChatRequest($requestId, $source = 'send_chat_requests') {
        try {
            if ($source === 'chat_requests') {
                $sql = "UPDATE chat_requests 
                        SET status = 'accepted' 
                        WHERE request_id = :request_id";
            } else {
                $sql = "UPDATE send_chat_requests 
                        SET status = 'accepted' 
                        WHERE request_id = :request_id 
                        AND receiver_id = :user_id";
            }
            
            $this->db->query($sql);
            $this->db->bind(':request_id', $requestId);
            if ($source === 'send_chat_requests') {
                $this->db->bind(':user_id', $_SESSION['user_id']);
            }
            
            return $this->db->execute();
        } catch (PDOException $e) {
            error_log("Error accepting chat request: " . $e->getMessage());
            return false;
        }
    }

    // Add method to decline chat request
    public function declineChatRequest($requestId) {
        try {
            $sql = "UPDATE send_chat_requests 
                    SET status = 'declined' 
                    WHERE request_id = :request_id";
            
            $this->db->query($sql);
            $this->db->bind(':request_id', $requestId);
            
            $result = $this->db->execute();
            error_log("Chat request declined - Request ID: $requestId, Result: " . ($result ? "success" : "failed"));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error declining chat request: " . $e->getMessage());
            return false;
        }
    }

    // Add this method to get active chats for a user
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

    // Add this method for debugging
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

    // Add method to reject chat request from either table
    public function rejectChatRequest($requestId, $source) {
        try {
            if ($source === 'chat_requests') {
                $sql = "UPDATE chat_requests 
                        SET status = 'rejected' 
                        WHERE request_id = :request_id";
                
                $this->db->query($sql);
                $this->db->bind(':request_id', $requestId);
            } else {
                $sql = "UPDATE send_chat_requests 
                        SET status = 'rejected' 
                        WHERE request_id = :request_id 
                        AND receiver_id = :user_id";
                
                $this->db->query($sql);
                $this->db->bind(':request_id', $requestId);
                $this->db->bind(':user_id', $_SESSION['user_id']);
            }
            
            if($this->db->execute()) {
                error_log("Successfully rejected request: " . $requestId . " from source: " . $source);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error rejecting chat request: " . $e->getMessage());
            return false;
        }
    }
}