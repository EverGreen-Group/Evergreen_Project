<?php
require_once APPROOT . '/models/M_FertilizerOrder.php';
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once '../app/helpers/auth_middleware.php';

class Supplier extends Controller {
    private $fertilizerOrderModel;

    public function __construct() {
        // Check if the user is logged in
        requireAuth();

        // You may want to check if the user has the right role (uncomment if needed)
        // if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::SUPPLIER])) {
        //     flash('message', 'Unauthorized access', 'alert alert-danger');
        //     redirect('');
        //     exit();
        // }

        // Initialize the model
        $this->fertilizerOrderModel = new M_Fertilizer_Order();
    }

    
    public function index() {
        $data = [ /* Add any dashboard data here if needed*/ ];
        $this->view('supplier/v_supply_dashboard', $data);
    }

    
    public function requestFertilizer() {
        // Fetch fertilizer orders and pass data to the view
        $data = [ 'orders' => $this->fertilizerOrderModel->getOrders() ];

        $this->view('supplier/v_fertilizer_request', $data);
    }

}
?>
