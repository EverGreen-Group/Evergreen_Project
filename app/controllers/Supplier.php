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

    public function fertilizerhistory()
    {
        $data = [];

        $this->view('supplier/v_fertilizer_history', $data);
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
            ];
            //load model
            $this->model('M_Fertilizer_Order');
            // Validate form data
            if ($this->validateRequest($data)) {
                // Call model method to insert the data into the database
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
        // Fetch existing orders to display in the form
        $data = ['orders' => $this->fertilizerOrderModel->getAllOrders()];
        $this->view('supplier/v_fertilizer_request', $data);
    }
    

    // form validation function 
    private function validateRequest($data) {
        return !empty($data['supplier_id']) && !empty($data['total_amount']) ;
    }


    public function editFertilizerRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'order_id' => $id,
                'total_amount' => $_POST['total_amount'],
            ];
            if ($this->fertilizerOrderModel->updateOrder($id, $data)) {
                flash('message', 'Order updated successfully!', 'alert alert-success');
                redirect('supplier/requestFertilizer');
            } else {
                flash('message', 'Failed to update order.', 'alert alert-danger');
            }
        } else {
            // Fetch existing order details
            $order = $this->fertilizerOrderModel->getOrderById($id);
            $data = ['order' => $order];
            $this->view('supplier/v_request_edit', $data);
        }
    }

    public function deleteFertilizerRequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Call the model method to delete the order
            if ($this->fertilizerOrderModel->deleteOrder($id)) {
                flash('message', 'Order cancelled successfully!', 'alert alert-success');
            } else {
                flash('message', 'Failed to cancel order. Please try again.', 'alert alert-danger');
            }
            redirect('supplier/requestFertilizer');
        }
    }
    
    
}
?>
