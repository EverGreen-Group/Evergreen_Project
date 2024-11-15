<?php

    class Fertilizer {

        public function getAllrequests() {
            global $con;
            $query = mysqli_query($con, "SELECT request_id, supplier_id, total_amount, date_and_time FROM fertilizer_requests LIMIT 1");
            
            // Check if query failed
            if (!$query) {
                return "Database query error: " . mysqli_error($con); // Return error message if query fails
            }

            $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
            
            // Debugging step: check if data is returned
            if (empty($result)) {
                return "No data found in fertilizer!";
            }

            return mysqli_fetch_all($query, MYSQLI_ASSOC);
        
    }
}
