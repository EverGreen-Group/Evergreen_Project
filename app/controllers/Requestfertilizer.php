<?php
require_once '../app/models/M_Fertilizer.php';

class Requestfertilizer extends controller {

    private $fertilizerModel;

    public function __construct() {
        // if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::DRIVER])) {
        //     // Redirect unauthorized access
        //     flash('message', 'Unauthorized access', 'alert alert-danger');
        //     redirect('');
        //     exit();
        // }
    }
    public function showRequests() {
        $this->fertilizerModel = new M_Fertilizer;
        $data = $this->fertilizerModel->getAllrequests();
        
         // If there's an error, handle it
        if (is_string($data)) {
            echo $data; // Display the error message
            exit;
        }
        
        // Check if $requests is empty before loading the view
        if (empty($result)) {
            echo "Data is unavailable in fertilizercontroller!";
            exit;
        }

        $this->view('vehicle_driver/v_profile', $data);
        
    }
    
}
?>
