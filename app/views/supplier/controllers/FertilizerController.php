<?php

class FertilizerController {
    public function showRequests() {
        $fertilizerModel = new Fertilizer;
        $result = $fertilizerModel->getAllrequests();
        
         // If there's an error, handle it
        if (is_string($result)) {
            echo $result; // Display the error message
            exit;
        }
        
        // Check if $requests is empty before loading the view
        if (empty($result)) {
            echo "Data is unavailable in fertilizercontroller!";
            exit;
        }

        include '../views/pages/FertilizerPage.php';
        
    }
    
}
?>