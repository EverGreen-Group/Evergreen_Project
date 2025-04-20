<?php
require_once '../app/models/M_Chat.php';
require_once '../app/libraries/Controller.php';

class C_Chat extends Controller {
    private $chatModel;

    public function __construct() {
        $this->chatModel = new M_Chat();
    }

    public function index() {
        // For testing, let's get messages between two specific users
        // Replace these with actual user IDs from your session/request
        $senderId = $_SESSION['user_id'];
        $receiverId = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;
        
        $data = [
            'title' => 'Chat',
            'messages' => []
        ];
        
        if ($receiverId) {
            $data['messages'] = $this->chatModel->getMessages($senderId, $receiverId);
        }
        
        $this->view('chat/index', $data);
    }
} 