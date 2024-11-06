<?php
class VehicleDriver extends controller {
    public function __construct() {
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::DRIVER])) {
            // Redirect unauthorized access
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }
    }

    public function index() {
        $data = [];  // Pass any necessary data here
        $this->view('vehicle_driver/v_dashboard', $data);
    }

    public function profile() {
        $data = [];
        $this->view('vehicle_driver/v_profile', $data);
    }

    public function team() {
        $data = [];
        $this->view('vehicle_driver/v_team', $data);
    }

    public function route() {
        $data = [];
        $this->view('vehicle_driver/v_route', $data);
    }

    public function shift() {
        $data = [];
        $this->view('vehicle_driver/v_shift', $data);
    }

    public function staff() {
        $data = [];
        $this->view('vehicle_driver/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('vehicle_driver/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('vehicle_driver/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }
}

?>
