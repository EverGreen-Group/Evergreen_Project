<?php

class Payment {
    // Function to get all payments
    public function getAll() {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM payments");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
