<?php

class M_Notification {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getNotificationsByUserId($userId) {
        $this->db->query("SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function addNotification($data) {
        $this->db->query("INSERT INTO notifications (user_id, type, message, data, status) VALUES (:user_id, :type, :message, :data, :status)");
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':data', $data['data']);
        $this->db->bind(':status', $data['status']);
        return $this->db->execute();
    }

    public function updateNotificationStatus($id, $status) {
        $this->db->query("UPDATE notifications SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getUnreadNotifications($userId) {
        $this->db->query("SELECT * FROM notifications WHERE user_id = :user_id AND status = 'unread' ORDER BY created_at DESC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function createNotification($userId, $type, $message, $data) {
        $this->db->query("INSERT INTO notifications (user_id, type, message, data, status) VALUES (:user_id, :type, :message, :data, 'unread')");
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':type', $type);
        $this->db->bind(':message', $message);
        $this->db->bind(':data', $data);
        return $this->db->execute();
    }
}
