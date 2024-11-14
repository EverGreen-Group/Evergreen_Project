<?php

class Notification {
    // Function to get all notifications
    public function getAll() {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM notifications");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
