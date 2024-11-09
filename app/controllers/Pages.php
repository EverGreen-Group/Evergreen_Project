<?php

require_once APPROOT . '/helpers/auth_middleware.php';
class Pages extends Controller {
    public function __construct() {
        // Any constructor logic if needed
    }

    public function index() {
        $data = [
            'title' => 'Welcome to Evergreen'
        ];
        
        $this->view('pages/landing', $data);
    }

    public function supplier_application_status() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        // Load the supplier application model
        $supplierApplicationModel = $this->model('M_SupplierApplication');
        
        // Get application status for current user
        $application = $supplierApplicationModel->getApplicationByUserId($_SESSION['user_id']);
        
        // Check if we're coming from a form submission
        $justSubmitted = isset($_GET['submitted']) && $_GET['submitted'] === 'true';
        
        $data = [
            'title' => 'Application Status',
            'application' => $application,
            'justSubmitted' => $justSubmitted
        ];

        $this->view('pages/v_supplier_application_status', $data);
    }
}