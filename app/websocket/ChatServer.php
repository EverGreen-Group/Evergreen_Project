<?php
namespace Simaak\EvergreenProject\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $userConnections = [];
    protected $chatModel;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        // Include M_Chat model (manual include since we can't modify composer.json)
        require_once ROOTPATH . '/app/models/M_Chat.php';
        $this->chatModel = new \M_Chat(); // Uses global M_Chat
        
        error_log("Chat server initialized");
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        error_log("New connection! (Resource ID: {$conn->resourceId})");
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        try {
            $data = json_decode($msg, true); // Use true for associative array
            if ($data === null || !isset($data['type'])) {
                error_log("Invalid message format received: " . $msg);
                return;
            }
            
            switch ($data['type']) {
                case 'init':
                    $this->handleInitialization($from, $data);
                    break;
                case 'chat':
                    $this->handleChatMessage($from, $data);
                    break;
                case 'edit':
                    $this->handleEditMessage($from, $data);
                    break;
                case 'delete':
                    $this->handleDeleteMessage($from, $data);
                    break;
                default:
                    error_log("Unknown message type: {$data['type']}");
            }
        } catch (\Exception $e) {
            error_log("Error processing message: " . $e->getMessage());
            $from->send(json_encode([
                'type' => 'error',
                'message' => 'Invalid message format'
            ]));
        }
    }

    //new 
    private function getUserName($userId) {
        try {
            $sql = "SELECT first_name, last_name, role_id FROM users WHERE user_id = :user_id";
            $stmt = $this->chatModel->db->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                $rolePrefix = ($user['role_id'] == 5) ? 'SUP' : 'MGR';
                $userIdPadded = sprintf('%03d', $userId);
                return htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) . " ($rolePrefix$userIdPadded)";
            }
            return "User $userId";
        } catch (PDOException $e) {
            error_log("Error fetching user name for ID $userId: " . $e->getMessage());
            return "User $userId";
        }
    }

    protected function handleInitialization(ConnectionInterface $conn, $data) {
        if (!isset($data['userId']) || !is_numeric($data['userId'])) {
            error_log("Missing or invalid userId in initialization");
            $conn->send(json_encode([
                'type' => 'error',
                'message' => 'Invalid user ID'
            ]));
            return;
        }

        $userId = (int)$data['userId'];
        
        // Remove any existing connection for this user
        if (isset($this->userConnections[$userId])) {
            $this->userConnections[$userId]->close();
            unset($this->userConnections[$userId]);
        }
        
        // Store the connection mapped to the user ID
        $this->userConnections[$userId] = $conn;
        
        // Broadcast user's online status
        $this->broadcastUserStatus($userId, 'online');
        
        error_log("User {$userId} initialized connection (Resource ID: {$conn->resourceId})");
    }

    protected function sendMessageHistory(ConnectionInterface $conn, $userId, $chatPartnerId) {
        try {
            $messages = $this->chatModel->getMessages($userId, $chatPartnerId);
            
            if (!empty($messages)) {
                $formattedMessages = [];
                foreach ($messages as $message) {
                    $senderName = $this->getUserName($message->sender_id);
                    $receiverName = $this->getUserName($message->receiver_id);
                    $isRead = $message->read_at ? 'Read: ' . $message->read_at : 'NULL';
                    $formattedMessages[] = [
                        'message_id' => $message->message_id,
                        'senderId' => $message->sender_id,
                        'receiverId' => $message->receiver_id,
                        'message' => $message->message,
                        'senderName' => $senderName,
                        'receiverName' => $receiverName,
                        'created_at' => $message->created_at,
                        'read_at' => $isRead,
                        'edited_at' => $message->edited_at ? 'Edited: ' . $message->edited_at : 'NULL',
                        'message_type' => $message->message_type ?? 'text'
                    ];
                }
                
                $conn->send(json_encode([
                    'type' => 'history',
                    'messages' => $formattedMessages
                ]));
                
                // Mark received messages as read
                foreach ($messages as $message) {
                    if ($message->receiver_id == $userId && $message->read_at === null) {
                        $this->chatModel->markMessageAsRead($message->message_id, $userId);
                    }
                }
            }
        } catch (\Exception $e) {
            error_log("Error sending message history: " . $e->getMessage());
        }
    }
    
    protected function handleChatMessage(ConnectionInterface $from, $data) {
        if (!isset($data['senderId']) || !isset($data['receiverId']) || !isset($data['message']) || 
            !is_numeric($data['senderId']) || !is_numeric($data['receiverId'])) {
            error_log("Missing or invalid chat message fields: " . json_encode($data));
            $from->send(json_encode(['type' => 'error', 'message' => 'Invalid message data']));
            return;
        }
        
        try {
            $senderId = (int)$data['senderId'];
            $receiverId = (int)$data['receiverId'];
            $message = trim($data['message']);
            
            // Save message to database with default message_type 'text'
            $result = $this->chatModel->saveMessage($senderId, $receiverId, $message, 'text');
            
            if ($result['success']) {
                $messageData = [
                    'type' => 'message',
                    'senderId' => $senderId,
                    'receiverId' => $receiverId,
                    'message' => $message,
                    'created_at' => $result['created_at'],
                    'message_id' => $result['message_id'],
                    'message_type' => 'text'
                ];
    
                // Send to recipient if online
                if (isset($this->userConnections[$receiverId])) {
                    $this->userConnections[$receiverId]->send(json_encode($messageData));
                    $this->sendMessageHistory($this->userConnections[$receiverId], $receiverId, $senderId);
                } else {
                    // Log that the message was saved for an offline recipient
                    error_log("Message saved for offline recipient (Receiver ID: {$receiverId}, Message ID: {$result['message_id']})");
                }
    
                // Send confirmation and history to sender with sender/receiver names and status
                $senderName = $this->getUserName($senderId); // Helper method to get user name
                $receiverName = $this->getUserName($receiverId); // Helper method to get user name
                $isRead = isset($this->userConnections[$receiverId]) ? 'Read: ' . date('Y-m-d H:i:s') : 'NULL';
                $from->send(json_encode([
                    'type' => 'sent',
                    'messageId' => $result['message_id'],
                    'message' => $message,
                    'senderName' => $senderName,
                    'receiverName' => $receiverName,
                    'created_at' => $result['created_at'],
                    'read_at' => $isRead,
                    'message_type' => 'text'
                ]));
                $this->sendMessageHistory($from, $senderId, $receiverId);
            } else {
                $from->send(json_encode(['type' => 'error', 'message' => 'Failed to save message: ' . ($result['error'] ?? 'Unknown error')]));
            }
        } catch (\Exception $e) {
            error_log("Error handling chat message: " . $e->getMessage());
            $from->send(json_encode(['type' => 'error', 'message' => 'Server error while sending message']));
        }
    }

    protected function handleEditMessage(ConnectionInterface $from, $data) {
        if (!isset($data['message_id']) || !isset($data['new_message']) || !isset($data['user_id']) ||
            !is_numeric($data['message_id']) || !is_numeric($data['user_id'])) {
            error_log("Missing or invalid edit message fields: " . json_encode($data));
            $from->send(json_encode(['type' => 'error', 'message' => 'Invalid edit data']));
            return;
        }
    
        try {
            $messageId = (int)$data['message_id'];
            $userId = (int)$data['user_id'];
            $newMessage = trim($data['new_message']);
    
            $result = $this->chatModel->editMessage($messageId, $newMessage, $userId);
    
            if ($result) {
                $senderName = $this->getUserName($userId);
                $from->send(json_encode([
                    'type' => 'edited',
                    'message_id' => $messageId,
                    'message' => $newMessage,
                    'senderName' => $senderName,
                    'edited_at' => date('Y-m-d H:i:s')
                ]));
                // Broadcast update to recipient if online
                if (isset($this->userConnections[$userId])) {
                    $this->userConnections[$userId]->send(json_encode([
                        'type' => 'message_updated',
                        'message_id' => $messageId,
                        'message' => $newMessage,
                        'senderName' => $senderName,
                        'edited_at' => date('Y-m-d H:i:s')
                    ]));
                }
            } else {
                $from->send(json_encode(['type' => 'error', 'message' => 'Failed to edit message']));
            }
        } catch (\Exception $e) {
            error_log("Error editing message: " . $e->getMessage());
            $from->send(json_encode(['type' => 'error', 'message' => 'Server error while editing message']));
        }
    }

    protected function handleDeleteMessage(ConnectionInterface $from, $data) {
        if (!isset($data['message_id']) || !isset($data['user_id']) ||
            !is_numeric($data['message_id']) || !is_numeric($data['user_id'])) {
            error_log("Missing or invalid delete message fields: " . json_encode($data));
            $from->send(json_encode(['type' => 'error', 'message' => 'Invalid delete data']));
            return;
        }
    
        try {
            $messageId = (int)$data['message_id'];
            $userId = (int)$data['user_id'];
    
            $result = $this->chatModel->deleteMessage($messageId, $userId);
    
            if ($result) {
                $senderName = $this->getUserName($userId);
                $from->send(json_encode([
                    'type' => 'deleted',
                    'message_id' => $messageId,
                    'senderName' => $senderName
                ]));
                // Broadcast deletion to recipient if online
                if (isset($this->userConnections[$userId])) {
                    $this->userConnections[$userId]->send(json_encode([
                        'type' => 'message_deleted',
                        'message_id' => $messageId,
                        'senderName' => $senderName
                    ]));
                }
            } else {
                $from->send(json_encode(['type' => 'error', 'message' => 'Failed to delete message']));
            }
        } catch (\Exception $e) {
            error_log("Error deleting message: " . $e->getMessage());
            $from->send(json_encode(['type' => 'error', 'message' => 'Server error while deleting message']));
        }
    }

    protected function broadcastUserStatus($userId, $status) {
        $statusData = [
            'type' => 'status',
            'userId' => $userId,
            'status' => $status,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        foreach ($this->clients as $client) {
            try {
                $client->send(json_encode($statusData));
            } catch (\Exception $e) {
                error_log("Error broadcasting status to client: " . $e->getMessage());
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $userId = array_search($conn, $this->userConnections);
        if ($userId !== false) {
            unset($this->userConnections[$userId]);
            $this->broadcastUserStatus($userId, 'offline');
            error_log("User {$userId} disconnected (Resource ID: {$conn->resourceId})");
        }
        
        $this->clients->detach($conn);
        error_log("Connection {$conn->resourceId} has disconnected");
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        error_log("An error has occurred: {$e->getMessage()} (Resource ID: {$conn->resourceId})");
        $conn->close();
    }
}