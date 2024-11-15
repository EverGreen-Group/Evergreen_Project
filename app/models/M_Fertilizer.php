<?php

    class M_Fertilizer {

        public function getAllrequests() {
            global $con;
            $query = mysqli_query($con, "SELECT request_id, supplier_id, total_amount, date_and_time FROM fertilizer_requests LIMIT 1");
            
            // Check if query failed
            if (!$query) {
                return "Database query error: " . mysqli_error($con); 
            }

            $result = mysqli_fetch_all($query, MYSQLI_ASSOC);
            
            // Debugging 
            if (empty($result)) {
                return "No data found in fertilizer!";
            }

            return mysqli_fetch_all($query, MYSQLI_ASSOC);
        
    }
}
