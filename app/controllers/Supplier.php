<?php
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once '../app/helpers/auth_middleware.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

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
        $data = [];
        $this->view('supplier/v_supply_dashboard', $data);
    }

    public function notifications()
    {
        $data = [];

        $this->view('supplier/v_all_notifications', $data);
    }
    
    public function changepassword()
    {
        $data = [];

        $this->view('supplier/v_change_password', $data);
    }

    public function confirmationhistory()
    {
        $data = [];

        $this->view('supplier/v_confirmation_history', $data);
    }

    public function teaorders()
    {
        $data = [];

        $this->view('supplier/v_new_order', $data);
    }

    public function payments()
    {
        $data = [];

        $this->view('supplier/v_payments', $data);
    }

    public function profile()
    {
        $data = [];

        $this->view('supplier/v_profile', $data);
    }

    public function cancelpickup()
    {
        $data = [];

        $this->view('supplier/v_cancel_pickup', $data);
    }

    public function requestFertilizer()
    {
        $fertilizerModel = new M_Fertilizer_Order();
        $data['fertilizer_types'] = $fertilizerModel->getAllFertilizerTypes();
        $data['orders'] = $fertilizerModel->getAllOrders();     //switch to getOrderBySupplier() after logging in

        $this->view('supplier/v_fertilizer_request', $data);
    }

    public function complaints()
    {
        $data = [];

        $this->view('supplier/v_complaint', $data);
    }

    public function settings()
    {
        $data = [];

        $this->view('supplier/v_settings', $data);
    }

    public function fertilizerhistory() {
        // Ensure supplier is logged in
        if (!isset($_SESSION['supplier_id'])) {
            flash('message', 'Please log in to view your order history', 'alert alert-danger');
            redirect('login');
            return;
        }
    
        // Fetch orders for the current supplier
        $orders = $this->fertilizerOrderModel->getOrdersBySupplier($_SESSION['supplier_id']);
    
        $data = [
            'orders' => $orders,
            'fertilizer_types' => $this->fertilizerOrderModel->getAllFertilizerTypes()
        ];
    
        $this->view('supplier/v_fertilizer_history', $data);
    }

    public function fertilizerOrders() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect form data
            $data = [
                'fertilizer_order_id' => $_POST['order_id'],
                'supplier_id' => $_POST['supplier_id'],
                'fertilizer_name' => $_POST['fertilizer_name'],
                'totalamount' => $_POST['total_amount'],
                'unit' => $_POST['unit'],
                'price_per_unit' => $_POST['price_per_unit'],
                'total_price' => $_POST['total_price'],
                'order_date' => $_POST['order_date'],
                'order_time' => $_POST['order_time'],
            ];
   
            // Load model
            $this->model('M_Fertilizer_Order');
   
            // Validate form data
            if ($this->validateRequest($data)) {
                // Call model method to insert the data
                if ($this->fertilizerOrderModel->createOrder($data)) {
                    flash('message', 'Order successfully submitted!', 'alert alert-success');
                    redirect('supplier/requestFertilizer');
                } else {
                    flash('message', 'Something went wrong. Please try again.', 'alert alert-danger');
                }
            } else {
                flash('message', 'Please fill in all required fields.', 'alert alert-danger');
            }
        }
   
        // Fetch all orders
        $orders = $this->fertilizerOrderModel->getAllOrders();
   
        // Pass data to the view
        $data['orders'] = $orders;
   
        // Load the view and pass the data
        $this->view('supplier/v_fertilizer_request', $data);
    }
   
    public function createFertilizerOrder() {
        // Check if the supplier is logged in
        /*if (!isset($_SESSION['supplier_logged_in']) || !$_SESSION['supplier_logged_in']) {
            echo "Error: You must be logged in to place an order.";
            return;
        }*/

        if (!isset($_POST['type_id']) || !isset($_POST['unit']) || !isset($_POST['total_amount'])) {
            flash('message', 'Please fill all required fields', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        // Get the logged-in supplier's ID
        //$supplier_id = $_SESSION['supplier_id'];

        //TEMP SUPPLIER ID
        //AFTER THE LOGIN IS COMPLETED REMOVE $supplier_id = 1; LINE, UNCOMMENT if (!isset($_SESSION['supplier_logged_in'])... THIS IF, AND $supplier_id = $_SESSION['supplier_id']; THIS LINE
        $supplier_id = 1;

        // Fetch fertilizer types for dropdown
        $data['fertilizer_types'] = $this->fertilizerOrderModel->getAllFertilizerTypes();


        // Get input data
        $type_id = trim($_POST['type_id']);
        $unit = $_POST['unit'];
        $total_amount = $_POST['total_amount'];

        // Validate fertilizer type
        $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($type_id);
        if (!$fertilizer) {
            flash('message', 'Invalid fertilizer type selected', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        // Automatically fill price per unit based on unit type
        $price_per_unit = $fertilizer['price_' . $unit];
        if (!$price_per_unit) {
            flash('message', 'Invalid unit type selected', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        // Dynamically get the price based on the selected unit
        $price_column = 'price_' . $unit;
        $price_per_unit = $fertilizer[$price_column];
        
        if (!$price_per_unit) {
            flash('message', 'Invalid unit type selected', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        // Calculate total price
        $total_price = $total_amount * $price_per_unit;

        // Insert the order
        $isInserted = $this->fertilizerOrderModel->createOrder([
            'supplier_id' => $supplier_id,
            'type_id' => $fertilizer['type_id'],
            'fertilizer_name' => $fertilizer['name'],
            'total_amount' => $total_amount,
            'unit' => $unit,
            'price_per_unit' => $price_per_unit,
            'total_price' => $total_price,
        ]);

        if ($isInserted) {
            flash('message', 'Order placed successfully!', 'alert alert-success');
        } else {
            flash('message', 'Failed to place the order.', 'alert alert-danger');
        }
        redirect('supplier/requestFertilizer');
    }
    

    // form validation function 
    private function validateRequest($data) {
        return !empty($data['supplier_id']) && !empty($data['total_amount']) ;
    }


    public function editFertilizerRequest($order_id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $total_amount = $_POST['total_amount'];
    
            $data = ['total_amount' => $total_amount];
            if ($this->fertilizerOrderModel->updateOrder($order_id, $data)) {
                flash('message', 'Fertilizer request updated successfully', 'alert alert-success');
                redirect('Supplier/fertilizerRequestHistory');
            } else {
                flash('message', 'Something went wrong. Please try again.', 'alert alert-danger');
                redirect('Supplier/fertilizerRequestHistory');
            }
        }
    
        $order = $this->fertilizerOrderModel->getOrderById($order_id);
        $data = ['order' => $order];
    
        $this->view('supplier/v_request_edit', $data);
    }
    

    public function deleteFertilizerRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Call the model method to delete the order
            if ($this->fertilizerOrderModel->deleteOrder($id)) {
                flash('message', 'Order cancelled successfully!', 'alert alert-success');
            } else {
                flash('message', 'Failed to cancel order. Please try again.', 'alert alert-danger');
            }
            header('Location: ' . URLROOT . '/Supplier/requestfertilizer');
            exit();
        }
    }
    
    
}
?>
