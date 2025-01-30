<?php
class M_Supplier {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllSuppliers() {
        $this->db->query('
        SELECT 
            s.supplier_id,
            s.is_active,
            u.first_name,
            u.last_name,
            sp.profile_image
        FROM suppliers s 
        JOIN users u ON s.user_id = u.user_id 
        LEFT JOIN supplier_photos sp ON s.supplier_id = sp.supplier_id
        WHERE s.is_active = 1
        ');
        
        return $this->db->resultSet();
    }


    public function confirmSupplierRole($applicationId) {
        // Step 1: Get the user_id from the supplier_applications table
        $sql1 = "SELECT user_id FROM supplier_applications WHERE application_id = :application_id";
        $this->db->query($sql1);
        $this->db->bind(':application_id', $applicationId);
        $userIdResult = $this->db->single(); // Assuming single() returns an object

        // Check if userId was found
        if (!$userIdResult) {
            error_log("No user found for application ID: " . $applicationId); // Log the error
            return false; // No user found for the given application ID
        }
        $userId = $userIdResult->user_id;

        // Step 2: Get latitude and longitude from application_addresses
        $sql2 = "SELECT latitude, longitude FROM application_addresses WHERE application_id = :application_id";
        $this->db->query($sql2);
        $this->db->bind(':application_id', $applicationId);
        $addressData = $this->db->single(); // Assuming single() returns an object

        // Check if address data was found
        if (!$addressData) {
            error_log("No address found for application ID: " . $applicationId); // Log the error
            return false; // No address found for the given application ID
        }

        // Step 3: Insert the new supplier record into the suppliers table
        $sql3 = "INSERT INTO suppliers (user_id, application_id, latitude, longitude, preferred_day, is_active, is_deleted) 
                  VALUES (:user_id, :application_id, :latitude, :longitude, 'Monday', 1, 0)";
        $this->db->query($sql3);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':application_id', $applicationId);
        $this->db->bind(':latitude', $addressData->latitude);
        $this->db->bind(':longitude', $addressData->longitude);

        // Step 4: Execute the query and check if it was successful
        if ($this->db->execute()) {
            // Step 5: Update the user's role_id to 5
            $sql4 = "UPDATE users SET role_id = 5 WHERE user_id = :user_id";
            $this->db->query($sql4);
            $this->db->bind(':user_id', $userId);
            return $this->db->execute(); // Returns true on success
        }

        return false; // Return false if the insertion failed
    }
} 