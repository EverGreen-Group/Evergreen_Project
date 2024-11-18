<?php

class Profile {

    // Function to get user profile information
    public function getProfileData() {
        global $con;
        $query = mysqli_query($con, "SELECT full_name, address, email, contact, supplier_id, reoute_number, registered_date FROM supplier_profile");
        if (!$query) {
            die("Database query error: " . mysqli_error($con)); // Provides specific query error
        }
        return mysqli_fetch_all($query, MYSQLI_ASSOC);  //fetch data for controller
    }
}