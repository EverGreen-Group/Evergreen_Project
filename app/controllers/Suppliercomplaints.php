<?php
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once '../app/helpers/auth_middleware.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Suppliercomplaints extends Controller {

    private $complaintModel;

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
        $this->complaintModel = new Complaint();
    }

    
    public function index() {
        $data = [];
        $this->view('supplier/v_complaint', $data);
    }

    public function submit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle file upload
            if (!empty($_FILES['complaint_image']['name'])) {
                $uploadDir = APPROOT . '/public/uploads/complaints/';
                $filename = uniqid() . '_' . basename($_FILES['complaint_image']['name']);
                $uploadPath = $uploadDir . $filename;
                
                // Move uploaded file and validate
                if (move_uploaded_file($_FILES['complaint_image']['tmp_name'], $uploadPath)) {
                    // Save file path to database
                    $imagePath = '/uploads/complaints/' . $filename;
                }
            }
            
            // Process other form data
            // Create complaint model, save to database, etc.
        }
    }
}
?>