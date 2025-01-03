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

    // public function teaorders()
    // {
    //     $data = [];

    //     $this->view('supplier/v_new_order', $data);
    // }

    public function payments()
    {
        $data = [];

        $this->view('shared/supplier/v_view_monthly_statement', $data);
    }

    public function paymentanalysis()
    {
        $data = [];

        $this->view('supplier/v_payment_analysis', $data);
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
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];
    
        // Check if the supplier is logged in
        /*if (!isset($_SESSION['supplier_logged_in']) || !$_SESSION['supplier_logged_in']) {
            echo "Error: You must be logged in to place an order.";
            return;
        }*/

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $required_fields = ['type_id', 'unit', 'total_amount'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            // Get the logged-in supplier's ID
            //$supplier_id = $_SESSION['supplier_id'];

            //TEMP SUPPLIER ID
            //AFTER THE LOGIN IS COMPLETED REMOVE $supplier_id = 1; LINE, UNCOMMENT if (!isset($_SESSION['supplier_logged_in'])... THIS IF, 
            //AND $supplier_id = $_SESSION['supplier_id']; THIS LINE
            $supplier_id = 2;

            // Fetch fertilizer types for dropdown
            $data['fertilizer_types'] = $this->fertilizerOrderModel->getAllFertilizerTypes();

            // Validate and get fertilizer data
            $type_id = trim($_POST['type_id']);
            $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($type_id);

            if (!$fertilizer) {
                throw new Exception('Invalid fertilizer type');
            }

            $unit = $_POST['unit'];
            $total_amount = floatval($_POST['total_amount']);

            // Validate amount
            if ($total_amount <= 0 || $total_amount > 50) {
                throw new Exception('Amount must be between 1 and 50');
            }

            // Calculate prices
            $price_column = 'price_' . $unit;
            if (!isset($fertilizer[$price_column])) {
                throw new Exception('Invalid unit type');
            }

            $price_per_unit = $fertilizer[$price_column];
            $total_price = $total_amount * $price_per_unit;

            // Create order data
            $order_data = [
                'supplier_id' => $supplier_id,
                'type_id' => $fertilizer['type_id'],
                'fertilizer_name' => $fertilizer['name'],
                'total_amount' => $total_amount,
                'unit' => $unit,
                'price_per_unit' => $price_per_unit,
                'total_price' => $total_price
            ];

            // Create the order
            if ($this->fertilizerOrderModel->createOrder($order_data)) {
                $response['success'] = true;
                $response['message'] = 'Order placed successfully!';
            } else {
                throw new Exception($this->fertilizerOrderModel->getError() ?? 'Failed to create order');
            }

        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        header("Refresh:1; url=" . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function viewMonthlyIncome() {
        $data = [
            'title' => 'Schedule Details'
        ];

        $this->view('shared/supplier/v_view_monthly_statement', $data);
    }

    // form validation function 
    private function validateRequest($data) {
        return !empty($data['supplier_id']) && !empty($data['total_amount']) ;
    }


    public function editFertilizerRequest($order_id) {
        // Basic check if order exists and belongs to the current supplier
        $order = $this->fertilizerOrderModel->getOrderById($order_id);
        
        if (!$order) {
            flash('message', 'Order not found', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }
        
        //UNCOMMENT AFTER IMPLEMENTING THE ORDER STATUS AND PAYMENT STATUS
        // Check if order is in an editable state
        /*
        if ($order->status === 'accepted' || $order->status === 'completed' || $order->payment_status === 'paid') {
            flash('message', 'This order cannot be edited as it has already been processed', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }*/
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate inputs
            $type_id = isset($_POST['type_id']) ? trim($_POST['type_id']) : '';
            $unit = isset($_POST['unit']) ? trim($_POST['unit']) : '';
            $total_amount = isset($_POST['total_amount']) ? floatval($_POST['total_amount']) : 0;
    
            // Validation checks
            if (empty($type_id) || empty($unit) || $total_amount <= 0 || $total_amount > 50) {
                flash('message', 'Please check your inputs. Amount should be between 1 and 50.', 'alert alert-danger');
                redirect('supplier/editFertilizerRequest/' . $order_id);
                return;
            }
    
            // Get fertilizer details and calculate prices
            $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($type_id);
            
            // Calculate price based on unit
            $price_column = 'price_' . $unit;
            $price_per_unit = $fertilizer[$price_column];
            $total_price = $total_amount * $price_per_unit;
    
            // Prepare update data
            $updateData = [
                'type_id' => $type_id,
                'fertilizer_name' => $fertilizer['name'],
                'total_amount' => $total_amount,
                'unit' => $unit,
                'price_per_unit' => $price_per_unit,
                'total_price' => $total_price,
                'last_modified' => date('Y-m-d H:i:s')
            ];
    
            // Update the order
            if ($this->fertilizerOrderModel->updateOrder($order_id, $updateData)) {
                flash('message', 'Fertilizer request updated successfully', 'alert alert-success');
                redirect('supplier/requestFertilizer');
            } else {
                flash('message', 'Failed to update request. Please try again.', 'alert alert-danger');
                redirect('supplier/editFertilizerRequest/' . $order_id);
            }
        } else {
            // GET request - show edit form
            $data = [
                'order' => $order,
                'fertilizer_types' => $this->fertilizerOrderModel->getAllFertilizerTypes()
            ];
            $this->view('supplier/v_request_edit', $data);
        }
    }
    
    public function checkFertilizerOrderStatus($orderId) {
        // Verify that the order belongs to the current logged-in supplier
        if (!$this->isSupplierOrder($orderId)) {
            echo json_encode(['canDelete' => false, 'message' => 'Unauthorized access']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }
    
        $order = $this->fertilizerOrderModel->getFertilizerOrderById($orderId);
        
        if (!$order) {
            echo json_encode(['canDelete' => false, 'message' => 'Order not found']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }
    
        // Check if order status allows deletion
        $canDelete = (!isset($order->status) || !in_array($order->status, ['accepted', 'rejected'])) && 
                    (!isset($order->payment_status) || $order->payment_status === 'pending');
    
        echo json_encode([
            'canDelete' => $canDelete,
            'message' => $canDelete ? 'Order can be deleted' : 'Order cannot be deleted'
        ]);
    }

    
    public function deleteFertilizerRequest($orderId) {
        // Set header to return JSON
        header('Content-Type: application/json');
    
        /*if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }
    
        if (!$this->isSupplierOrder($orderId)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }*/
    
        $order = $this->fertilizerOrderModel->getFertilizerOrderById($orderId);
        
        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }
    
        // Check if order can be deleted
        if ($order->status !== 'Pending' || $order->payment_status !== 'Pending') {
            echo json_encode($order->status);
            echo json_encode( $order->payment_status );
            echo json_encode(['success' => false, 'message' => 'This order cannot be deleted']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }

        $res = $this->fertilizerOrderModel->deleteFertilizerOrder($orderId);
    
        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
        }
        header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
    }

    private function isSupplierOrder($orderId) {
        $order = $this->fertilizerOrderModel->getFertilizerOrderById($orderId);
        return $order && $order->supplier_id == $_SESSION['user_id'];
    }
    
    public function scheduleDetails() {
        $data = [
            'title' => 'Schedule Details'
        ];

        $this->view('supplier/v_schedule_details', $data);
    }
}
?>