<?php

class Notifications extends Controller {
    private $notificationModel;

    public function __construct() {
        $this->notificationModel = $this->model('M_Notification');
    }

    // Get unread notification count for the current user
    public function getUnreadNotificationCount() {
        $userId = $_SESSION['user_id'];
        $count = $this->notificationModel->getUnreadCount($userId);

        header('Content-Type: application/json');
        echo json_encode(['count' => $count]);
    }

    // Get all unread notifications for a user
    public function getUnreadUserNotifications($userId = null) {
        if (!$userId) {
            $userId = $_SESSION['user_id'];
        }

        $notifications = $this->notificationModel->getUserNotifications($userId, true);

        header('Content-Type: application/json');
        echo json_encode($notifications);
    }

    // Mark a notification as read
    public function markAsRead($id = null) {
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'No notification ID provided']);
            return;
        }

        $success = $this->notificationModel->markAsRead($id);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }

    // Create a new notification
    public function create($userId, $message, $link) {
        return $this->notificationModel->createNotification(
            $userId,
            'General',
            $message,
            ['link' => $link]
        );
    }
}
