<?php
class VehicleManager extends controller {
    public function __construct() {
        // Initialization code if needed
    }

    public function index() {
        $data = [];  // Pass any necessary data here
        $this->view('vehicle_manager/v_dashboard', $data);
    }

    public function vehicle() {
        $data = [];
        $this->view('vehicle_manager/v_vehicle', $data);
    }

    public function team() {
        $data = [];
        $this->view('vehicle_manager/v_team', $data);
    }

    public function route() {
        $data = [];
        $this->view('vehicle_manager/v_route', $data);
    }

    public function shift() {
        $data = [];
        $this->view('vehicle_manager/v_shift', $data);
    }

    public function staff() {
        $data = [];
        $this->view('vehicle_manager/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('vehicle_manager/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('vehicle_manager/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }
}

?>
