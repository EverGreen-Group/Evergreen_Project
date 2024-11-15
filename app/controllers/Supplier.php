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

    public function v_notifications()
    {
        $data = [];

        $this->view('supplier/v_notifications', $data);
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

    public function v_tea_orders()
    {
        $data = [];

        $this->view('supplier/v_new_order', $data);
    }

    public function v_payments()
    {
        $data = [];

        $this->view('supplier/v_payments', $data);
    }

    public function v_profile()
    {
        $data = [];

        $this->view('supplier/v_profile', $data);
    }

    public function v_complaints()
    {
        $data = [];

        $this->view('supplier/v_complaint', $data);
    }

    public function v_settings()
    {
        $data = [];

        $this->view('supplier/v_settings', $data);
    }


    
    public function requestFertilizer() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Collect form data
            $data = [
                'supplier_id' => $_POST['supplier_id'],
                'total_amount' => $_POST['total_amount'],
                'address' => $_POST['address'],
                'email' => $_POST['email'],
                'phone_number' => $_POST['phone_number'],
            ];
    
            // Validate form data (optional but recommended)
            if ($this->validateRequest($data)) {
                // Call model method to insert the data into the database
                if ($this->fertilizerOrderModel->createOrder($data)) {
                    // Flash success message
                    flash('message', 'Order successfully submitted!', 'alert alert-success');
                    // Redirect to the dashboard or another page
                    redirect('supplier/requestFertilizer');
                } else {
                    // Flash error message
                    flash('message', 'Something went wrong. Please try again.', 'alert alert-danger');
                }
            } else {
                // Flash validation error message
                flash('message', 'Please fill in all required fields.', 'alert alert-danger');
            }
        }
    
        // Fetch existing orders to display in the form (if needed)
        $data = ['orders' => $this->fertilizerOrderModel->getAllOrders()];
    
        // Load the view
        $this->view('supplier/v_fertilizer_request', $data);
    }
    
    // Simple form validation function (you can extend it as needed)
    private function validateRequest($data) {
        return !empty($data['supplier_id']) && !empty($data['total_amount']) && !empty($data['address']) && !empty($data['email']);
    }
}
?>
