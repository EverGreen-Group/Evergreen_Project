<?php

class Complaint {
    // Function to create a new complaint
    public function create($type, $description, $email, $phone) {
        // Assume you have a database connection set up
        $db = Database::connect();
        $query = $db->prepare("INSERT INTO complaints (type, description, email, phone) VALUES (?, ?, ?, ?)");
        $query->execute([$type, $description, $email, $phone]);
    }
}
