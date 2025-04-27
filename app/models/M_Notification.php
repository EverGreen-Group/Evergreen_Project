<?php
class M_Notification {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Create a new notification
    public function createNotification($userId, $type, $message, $data = []) {
        $link = isset($data['link']) ? $data['link'] : null;

        $this->db->query("INSERT INTO notifications (user_id, message, link, seen, created_at) 
                          VALUES (:user_id, :message, :link, 0, NOW())");

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':message', $message);
        $this->db->bind(':link', $link);

        return $this->db->execute();
    }

    // Get all notifications for a user
    public function getUserNotifications($userId, $unreadOnly = false) {
        $query = "SELECT * FROM notifications WHERE user_id = :user_id";

        if ($unreadOnly) {
            $query .= " AND seen = 0";
        }

        $query .= " ORDER BY created_at DESC";

        $this->db->query($query);
        $this->db->bind(':user_id', $userId);

        return $this->db->resultSet();
    }

    // Get unread notification count
    public function getUnreadCount($userId) {
        $this->db->query("SELECT COUNT(*) as count FROM notifications 
                          WHERE user_id = :user_id AND seen = 0");

        $this->db->bind(':user_id', $userId);
        $row = $this->db->single();

        return $row->count;
    }

    // Mark a notification as read
    public function markAsRead($id) {
        $this->db->query("UPDATE notifications SET seen = 1 WHERE id = :id");
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    // Mark all notifications as read for a user
    public function markAllAsRead($userId) {
        $this->db->query("UPDATE notifications SET seen = 1 WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    // Delete a notification
    public function deleteNotification($id) {
        $this->db->query("DELETE FROM notifications WHERE id = :id");
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }
}
