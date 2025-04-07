<?php

require_once APPROOT . '/helpers/auth_middleware.php';
require_once '../app/models/M_Supplier.php';

class Pages extends Controller {

    private $supplierModel;
    public function __construct() {
        $this->supplierModel = new M_Supplier();
    }

    public function index() {

        if (isset($_SESSION['user_id'])) {
            $hasSubmittedApplication = $this->checkApplicationStatus($_SESSION['user_id']);
        } else {
            $hasSubmittedApplication = false;
        }


        $data = [
            'title' => 'Welcome to Evergreen',
            'hasSubmittedApplication' => $hasSubmittedApplication
        ];
        
        $this->view('pages/landing', $data);
    }

    private function checkApplicationStatus() {

        $hasSubmittedApplication = $this->supplierModel->checkApplicationStatus($_SESSION['user_id']);
    
        return $hasSubmittedApplication;
    }

    public function supplier_application_status() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        $supplierApplicationModel = $this->model('M_SupplierApplication');
        
        $application = $supplierApplicationModel->getApplicationByUserId($_SESSION['user_id']);

        $justSubmitted = isset($_GET['submitted']) && $_GET['submitted'] === 'true';
        
        $data = [
            'title' => 'Application Status',
            'application' => $application,
            'justSubmitted' => $justSubmitted
        ];

        $this->view('pages/v_supplier_application_status', $data);
    }

    public function error404() {
        require_once '../app/views/pages/404.php';
        exit();
    }
}