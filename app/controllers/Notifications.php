<?php

require_once APPROOT . '/helpers/auth_middleware.php';

class Notifications extends Controller
{
    private $userModel;
    private $notificationModel;

    public function __construct() {
        $this->userModel = $this->model('M_User');
        $this->notificationModel = $this->model('M_Notification');
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        $notifications = $this->notificationModel->getNotificationsByUserId($userId);
        $data = [
            'notifications' => $notifications
        ];
        $this->view('notifications/index', $data);
    }

    public function add() {
        $data = [
            'user_id' => $_SESSION['user_id'],
            'type' => 'info',
            'message' => 'This is a test notification.',
            'data' => json_encode(['key' => 'value']),
            'status' => 'unread'
        ];
        $this->notificationModel->addNotification($data);
    }

    public function markAsRead($id) {
        $this->notificationModel->updateNotificationStatus($id, 'read');
    }

    public function getUnreadNotifications() {
        $userId = $_SESSION['user_id'];
        $unreadNotifications = $this->notificationModel->getUnreadNotifications($userId);
        $data = [
            'notifications' => $unreadNotifications
        ];
        $this->view('notifications/unread', $data);
    }

    public function getUserNotifications($userId) {
        $notifications = $this->notificationModel->getNotificationsByUserId($userId);
        
        header('Content-Type: application/json');
        echo json_encode($notifications);
    }

    public function getUnreadUserNotifications($userId) {
        $notifications = $this->notificationModel->getUnreadNotifications($userId);
        
        header('Content-Type: application/json');
        echo json_encode($notifications);
    }

    public function getUnreadNotificationCount() {
        if (!isset($_SESSION['user_id'])) {
            header('Content-Type: application/json');
            echo json_encode(['count' => 0]);
            return;
        }

        $userId = $_SESSION['user_id'];
        $unreadNotifications = $this->notificationModel->getUnreadNotifications($userId); 
        $count = count($unreadNotifications);

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }
}