<?php

require_once APPROOT . '/helpers/auth_middleware.php';
require_once '../app/models/M_Supplier.php';
require_once '../app/models/M_Products.php';
require_once '../app/models/M_Fertilizer_Order.php'; //tempo

class Pages extends Controller {

    private $supplierModel;
    private $productModel; // Declare productModel property
    private $fertilizerOrderModel;//tempo
    public function __construct() {
        $this->supplierModel = new M_Supplier();
        $this->productModel = new M_Products();
        $this->fertilizerOrderModel = new M_Fertilizer_Order();
    }

    public function index() {

        if (isset($_SESSION['user_id'])) {
            $hasSubmittedApplication = $this->checkApplicationStatus($_SESSION['user_id']);
        } else {
            $hasSubmittedApplication = false;
        }
        $products = $this->productModel->getAllProducts(); // Fetch products


        $data = [
            'title' => 'Welcome to Evergreen',
            'hasSubmittedApplication' => $hasSubmittedApplication,
            'products' => $products
        ];
        
        $this->view('pages/landing', $data);
    }
    //newwly added by theekshana
    public function store() {
        $products = $this->productModel->getAllProducts();

        $data = [
            'title' => 'Our Store',
            'products' => $products
        ];

        $this->view('pages/store', $data);
    }

    //temporary function to test fertilizer order
    public function fertilizerTypes() {
        $fertilizers = $this->fertilizerOrderModel->getAllFertilizerTypes();

        $data = [
            'title' => 'Fertilizer Types',
            'fertilizers' => $fertilizers
        ];

        $this->view('pages/fertilizer_types', $data);
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