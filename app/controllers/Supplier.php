<?php
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once APPROOT . '/models/M_Supplier.php';
require_once APPROOT . '/models/M_Route.php';
require_once APPROOT . '/models/M_Collection.php';
require_once APPROOT . '/models/M_CollectionSchedule.php';
require_once APPROOT . '/models/M_Complaint.php';
require_once APPROOT . '/models/M_Bag.php';
require_once APPROOT . '/models/M_Chat.php'; 
require_once '../app/helpers/auth_middleware.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

class Supplier extends Controller {

    private $fertilizerOrderModel;
    private $supplierModel;
    private $routeModel;
    private $collectionModel;
    private $scheduleModel;
    private $complaintModel;
    private $bagModel;
    private $appointmentModel;
    private $chatModel;
    private $userModel;

    public function __construct() {
        requireAuth();

        $this->fertilizerOrderModel = new M_Fertilizer_Order();
        $this->supplierModel = new M_Supplier();
        $this->routeModel = new M_Route();
        $this->collectionModel = new M_Collection();
        $this->scheduleModel = new M_CollectionSchedule();
        $this->complaintModel = new M_Complaint();
        $this->bagModel = new M_Bag();
        $this->appointmentModel = $this->model('M_Appointment');
        $this->chatModel = new M_Chat(); // Initialize the chat model

        $this->bagModel = new M_Bag();
        $this->userModel = $this->model('M_User');
        $supplierDetails = $this->supplierModel->getSupplierDetailsByUserId($_SESSION['user_id']);
        $_SESSION['supplier_id'] = $supplierDetails->supplier_id;
    }

    public function index() {
        $supplierId = $_SESSION['supplier_id'];
    
        // Tea leaves data
        // $teaLeavesKg = $this->supplierModel->getTotalKgThisMonth($supplierId);
        // $teaLeavesKgLastCollection = $this->supplierModel->kgSuppliedLastCollection($supplierId);
        $vehicleModel = $this->model('M_Vehicle');
    
        // Assigned schedule (general one)
        $assignedSchedule = $this->supplierModel->getSupplierSchedule($supplierId);
    
        $supplierStatus = $this->supplierModel->getSupplierStatus($supplierId);
        $data['is_active'] = $supplierStatus;
        // $data['teaLeavesKg'] = $teaLeavesKg;
        // $data['teaLeavesKgLastCollection'] = $teaLeavesKgLastCollection;
    
        // Add general assigned schedule if available
        if ($assignedSchedule) {
            $data['assignedSchedule'] = $assignedSchedule;
        }
    
        $this->view('supplier/v_supply_dashboard', $data);
    }
    

    public function viewAppointments() {
        // Fetch all required data for the view
        $timeSlots = $this->appointmentModel->getAvailableTimeSlots();
        $myRequests = $this->appointmentModel->getMyRequests($_SESSION['supplier_id']);
        $confirmedAppointments = $this->appointmentModel->getConfirmedAppointments($_SESSION['supplier_id']);
        
        // Prepare data to pass to the view
        $data = [
            'time_slots' => $timeSlots,
            'my_requests' => $myRequests,
            'confirmed_appointments' => $confirmedAppointments
        ];
        
        // Load the view with the data
        $this->view('supplier/v_time_slots', $data);
    }
    
    public function requestTimeSlot() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate the slot ID
            if (!isset($_POST['slot_id']) || empty($_POST['slot_id'])) {
                setFlashMessage('Time slot isnt available anymore, please refresh the page!', 'error');
                redirect('Supplier/viewAppointments');
                return;
            }
            
            $slotId = trim($_POST['slot_id']);
            $supplierId = $_SESSION['supplier_id'];
            
            $slot = $this->appointmentModel->getSlotById($_POST['slot_id']);
            if (!$slot || $slot->status !== 'Available') {
                setFlashMessage('Time slot isnt available anymore.', 'error');
                redirect('Supplier/viewAppointments');
                return;
            }

            // Check if this supplier has already requested this slot
            if ($this->appointmentModel->hasAlreadyRequested($slotId, $supplierId)) {
                setFlashMessage('You have already requested this time slot!', 'error');
                redirect('Supplier/viewAppointments');
                return;
            }

            $data = [
                'supplier_id' => $_SESSION['supplier_id'],
                'slot_id' => trim($_POST['slot_id']),
                'status' => 'Pending',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
            
            // Create the request
            if ($this->appointmentModel->createRequest($data)) {
                setFlashMessage('Request sent sucessfully!');
            } else {
                setFlashMessage('Request failed, please try again later!', 'error');
            }
            
            redirect('Supplier/viewAppointments');
        } else {
            // Redirect if accessed directly without POST
            redirect('Supplier/viewAppointments');
        }
    }
    
    public function cancelRequest($id = null) {
        // Validate the request ID
        if (!$id) {
            setFlashMessage('Cancellation failed, please try again later!', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }
        
        // Check if the request belongs to this supplier and is still pending
        $request = $this->appointmentModel->getRequestById($id);
        if (!$request || $request->supplier_id != $_SESSION['supplier_id'] || $request->status != 'Pending') {
            setFlashMessage('You cannot cancel this request', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }
        
        // Cancel the request
        if ($this->appointmentModel->cancelRequest($id, $_SESSION['supplier_id'])) {
            setFlashMessage('Appointment request cancelled sucessfully!');
        } else {
            setFlashMessage('Failed to cancel the request!', 'error');
        }
        
        redirect('Supplier/viewAppointments');
    }

    public function notifications() {
        $data = [];
        $this->view('supplier/v_all_notifications', $data);
    }
    
    public function changepassword() {
        $data = [];
        $this->view('supplier/v_change_password', $data);
    }

    public function confirmationhistory() {
        $data = [];
        $this->view('supplier/v_confirmation_history', $data);
    }



    public function payments() {
        $supplierId = $_SESSION['supplier_id'];
        
        // Get filter values
        $month = isset($_GET['month']) ? $_GET['month'] : 'all';
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        
        // Get earnings data based on filters
        $earnings = $this->supplierModel->getSupplierEarnings($supplierId, $month, $year);
        
        // Calculate totals
        $totals = (object)[
            'total_normal_kg' => 0,
            'total_super_kg' => 0,
            'total_deduction_kg' => 0,
            'total_kg' => 0,
            'total_base_payment' => 0,
            'total_transport_charge' => 0,
            'total_deduction_amount' => 0,
            'total_payment' => 0
        ];
        
        foreach ($earnings as $earning) {
            $totals->total_normal_kg += $earning->normal_kg;
            $totals->total_super_kg += $earning->super_kg;
            $totals->total_deduction_kg += $earning->total_deduction_kg;
            $totals->total_kg += $earning->total_kg;
            $totals->total_base_payment += $earning->base_payment;
            $totals->total_transport_charge += $earning->transport_charge;
            $totals->total_deduction_amount += $earning->total_deduction_amount;
            $totals->total_payment += $earning->total_payment;
        }
        
        $data = [
            'earnings' => $earnings,
            'totals' => $totals
        ];
        
        $this->view('supplier/v_supplier_payment', $data);
    }

    public function schedule() {
        $supplierId = $_SESSION['supplier_id'];

        $result = $this->supplierModel->getSupplierSchedule($supplierId);
        if(!$result) {
            setFlashMessage('You arent in any schedule! Please submit a complaint requesting a collection day!', 'warning');
            redirect('supplier/');
        } 

        $data = [
            'schedule_id' => $result->schedule_id,
            'route_id' => $result->route_id,
            'driver_id' => $result->driver_id,
            'day' => $result->day,
            'start_time' =>$result->start_time,
            'route_name' => $result->route_name,
            'vehicle_id' => $result->vehicle_id,
            'license_plate' => $result->license_plate,
            'vehicle_type' => $result->vehicle_type,
            'make' => $result->make,
            'model' => $result->model,
            'color' => $result->color,
            'driver_image' => $result->driver_image,
            'vehicle_image' => $result->vehicle_image,
            'driver_name' => $result->driver_name,
            'contact_number' => $result->contact_number

        ];



        $this->view('supplier/v_supplier_schedule', $data);
    }

    public function paymentanalysis() {
        $data = [];
        $this->view('supplier/v_payment_analysis', $data);
    }


    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            
            $profileData = $this->supplierModel->getSupplierProfile($userId);
            
            $data = [
                'supplier_id' => $profileData['supplier']->supplier_id,
                'profile_id' => $profileData['profile']->profile_id,
                'supplier_contact' => trim($_POST['supplier_contact']),
                'image_path' => ''
            ];
            
            if (!empty($_FILES['profile_image']['name'])) {
                $uploadDir = 'uploads/profile_photos/';
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = uniqid() . '_' . $_FILES['profile_image']['name'];
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uploadPath;
                } else {
                    setFlashMessage('Image upload failed!', 'error');
                    redirect('Supplier/profile');
                }
            }
            if ($this->supplierModel->updateSupplierProfile($data)) {
                setFlashMessage('Profile updated sucessfully!');
                redirect('Supplier/profile');
            } else {
                setFlashMessage('Profile update failed!', 'error');
                redirect('Supplier/profile');
            }
        } else {
            redirect('Supplier/profile');
        }
    }

    public function cancelpickup() {
        $data = [];
        $this->view('supplier/v_cancel_pickup', $data);
    }

    // public function requestFertilizer() {
    //     $fertilizerModel = new M_Fertilizer_Order();
    //     $data['fertilizer_types'] = $fertilizerModel->getAllFertilizerTypes();
    //     $data['orders'] = $fertilizerModel->getAllOrders(); 

    //     $this->view('supplier/v_fertilizer_request', $data);
    // }

    public function complaints() {
        $supplier_id = $_SESSION['supplier_id'];
        if (!$supplier_id) {
            throw new Exception("Supplier ID not found. Please login again.");
        }

        $complaints = $this->complaintModel->getComplaints($supplier_id);

        $data = ['complaints' => $complaints];
        $this->view('supplier/v_complaint', $data);
    }

    public function submitComplaint() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $supplierId = trim($_SESSION['supplier_id']); 

            $complaint_type = $_POST['complaint_type'];
            if ($complaint_type == 'Quality Issues' || $complaint_type == 'Payment Issues') {
                $priority = 'High';
            } else if ($complaint_type == 'Delivery Problems' || $complaint_type == 'Customer Service') {
                $priority = 'Medium';
            } else {
                $priority = 'Low';
            }
     
            $data = [
                'supplier_id' => $supplierId,
                'complaint_type' => trim($_POST['complaint_type']),
                'subject' => trim($_POST['subject']),
                'description' => trim($_POST['description']),
                'priority' => $priority,
                'image_path' => null,
            ];
    
            // Image upload handling
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $image = $_FILES['image'];
    
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($image['type'], $allowedTypes)) {
                    $imageExt = pathinfo($image['name'], PATHINFO_EXTENSION);
                    $imageName = 'complaint_' . time() . '.' . $imageExt;
                    $uploadDir = 'uploads/complaints/';
                    $uploadPath = $uploadDir . $imageName;
    
                    // Ensure directory exists
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
    
                    if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                        $data['image_path'] = $uploadPath;
                    }
                }
            }
    
            if ($this->supplierModel->addComplaint($data)) {
                // Notify all managers
                $managers = $this->userModel->getAllManagers();
                $notificationModel = $this->model('M_Notification');
    
                foreach ($managers as $manager) {
                    $notificationModel->createNotification(
                        $manager->user_id,
                        'New Complaint',
                        'A new complaint has been submitted by a supplier!',
                        ['link' => 'manager/viewComplaint/']
                    );
                }
    
                setFlashMessage('Complaint submitted successfully!');
                redirect('supplier/complaints');
            } else {
                setFlashMessage('Complaint didnt get sent! Please try again later', 'error');
                redirect('supplier/complaints');
            }
        } else {
            redirect('supplier/complaints');
        }
    }

    public function resolveComplaint() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {

            $complaintId = $_POST['complaint_id'];
            if($complaintId) {
                $result = $this->complaintModel->markResolved($complaintId);
                if($result) {
                    setFlashMessage('Complaint marked as resolved successfully!');
                    redirect('manager/complaints');
                }

                setFlashMessage('Complaint couldnt be marked as resolved!', 'error');
                redirect('manager/complaints');
                
            }
            setFlashMessage('You havent selected the complaint!', 'warning');
            redirect('manager/complaints');



        }
    }

    public function settings() {
        $data = [];
        $this->view('supplier/v_settings', $data);
    }

    public function fertilizerhistory() {
        // Ensure supplier is logged in
        if (!isset($_SESSION['supplier_id'])) {

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
   
            // Validate form data
            if ($this->validateRequest($data)) {
                // Call model method to insert the data
                if ($this->fertilizerOrderModel->createOrder($data)) {
                    setFlashMessage('Order sucessfully submitted!');
                    redirect('supplier/requestFertilizer');
                } else {
                    setFlashMessage('Fertilizer request failed!', 'error');
                }
            } else {
                setFlashMessage('Please fill all the required fields', 'error');
            }
        }
   
        // Fetch all orders
        $orders = $this->fertilizerOrderModel->getAllOrders();
   
        // Pass data to the view
        $data['orders'] = $orders;
   
        // Load the view and pass the data
        $this->view('supplier/v_fertilizer_request', $data);
    }

    /*private function logUnitUsage($unit){
        error_log ($unit);
    }*/

    public function requestFertilizer() {                       // bug free function

        $fertilizerModel = new M_Fertilizer_Order();
        $supplier_id = $_SESSION['supplier_id'];
        if (!$supplier_id) {
            throw new Exception('Supplier ID not found!');
        };

        $fertilizer_types = $fertilizerModel->getAllFertilizerTypes();
        $orders = $fertilizerModel->getOrdersBySupplier($supplier_id);

        $data = [
            'fertilizer_types' => $fertilizer_types,
            'orders' => $orders
        ];

        if (empty($data['fertilizer_types'])) {
            setFlashMessage('No available fertilizer stocks');
        } else{
            $this->view('supplier/v_fertilizer_request', $data);
        }
        
    }
   
    public function createFertilizerOrder() {                       // bug free function
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
    
            // Check for required fields
            $required_fields = ['fertilizer_id', 'quantity'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }
    
            // Get supplier ID from session
            $supplier_id = $_SESSION['supplier_id'] ?? null;
            if (!$supplier_id) {
                throw new Exception("Supplier ID not found. Please log in again.");
            }
    
            // Validate inputs
            $fertilizer_id = intval($_POST['fertilizer_id']);
            $quantity = floatval($_POST['quantity']);
            $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;
    
            if ($quantity <= 0 || $quantity > 100) {
                throw new Exception('Quantity must be between 1 and 100');
            }
            
            // Create order data
            $order_data = [
                'supplier_id' => $supplier_id,
                'fertilizer_id' => $fertilizer_id,
                'quantity' => $quantity,
                'total_amount' => $total_price,
                'status' => 'Pending',
                'payment_status' => 'Pending'
            ];
    
            // Create the order
            if ($this->fertilizerOrderModel->createOrder($order_data)) {
                setFlashMessage('Order placed successfully!');
            } else {
                throw new Exception($this->fertilizerOrderModel->getError() ?? 'Failed to create order!');
            }
    
        } catch (Exception $e) {
            setFlashMessage($e->getMessage(), 'error');
        }
        
        redirect('/Supplier/requestFertilizer');
        exit;
    }

    public function viewMonthlyIncome() {
        $data = [
            'title' => 'Schedule Details'
        ];
        $this->view('shared/supplier/v_view_monthly_statement', $data);
    }

    // Form validation function 
    private function validateRequest($data) {
        return !empty($data['supplier_id']) && !empty($data['totalamount']);
    }

    public function editFertilizerRequest($order_id) {

        $order = $this->fertilizerOrderModel->getOrderById($order_id);
    
        if (!$order) {
            setFlashMessage('Order not found.', 'error');
            redirect('supplier/requestFertilizer');
            return;
        }
    
        if ($order->status !== 'Pending') {
            setFlashMessage('Only pending orders can be edited!', 'error');
            redirect('supplier/requestFertilizer');
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate inputs
            $fertilizer_id = isset($_POST['fertilizer_id']) ? intval($_POST['fertilizer_id']) : '';
            $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : '';
            $total_price = isset($_POST['total_price']) ? floatval($_POST['total_price']) : 0;

            if ($fertilizer_id !== $order->fertilizer_id) {
                setFlashMessage('Fertilizer type cannot be updated!');
                redirect('supplier/editFertilizerRequest/' . $order_id);
                return;
            }
            
    
            // recalculate total price if invalid
            if ($total_price <= 0) {
                // Calculate price per unit based on original order
                $price_per_unit = $order->total_amount / $order->quantity;
                $total_price = $price_per_unit * $quantity;
                return;
            }
    
            if (empty($fertilizer_id) || $quantity < 1 || $quantity > 100) {
                setFlashMessage('Total amount has to be greater than 1kg and less than 100kg', 'error');
                redirect('supplier/editFertilizerRequest/' . $order_id);
                return;
            }
            
            $updateData = [
                //'fertilizer_id' => $fertilizer_id,
                'quantity' => $quantity,
                'total_amount' => $total_price,
                'last_modified' => date('Y-m-d H:i:s')
            ];
    
            // Update the order
            if ($this->fertilizerOrderModel->updateOrder($order_id, $updateData)) {
                setFlashMessage('Order request updated successfully!');
                redirect('supplier/requestFertilizer');
            } else {
                setFlashMessage('Failed to update the order!', 'error');
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

    public function deleteFertilizerRequest($orderId) {                       // bug free function
        
        $order = $this->fertilizerOrderModel->getOrderById($orderId);
        
        if (!$order) {
            setFlashMessage('Order not found', 'error');
            redirect('supplier/requestFertilizer');
            return;
        }
        
        // Check if order can be deleted
        if (strtolower($order->status) !== 'pending') {
            setFlashMessage('Only pending orders can be deleted!', 'error');
            redirect('supplier/requestFertilizer');
            return;
        }
    
        $res = $this->fertilizerOrderModel->deleteFertilizerOrder($orderId);
    
        if ($res) {
            setFlashMessage('Order deleted successfully');
        } else {
            setFlashMessage('Failed to delete order!', 'error');
        }
        redirect('supplier/requestFertilizer');
    }

    private function isSupplierOrder($orderId) {
        $order = $this->fertilizerOrderModel->getFertilizerOrderById($orderId);
        return $order && $order->supplier_id == $_SESSION['user_id'];
    }

    public function collections() {
        
        $supplierId = $_SESSION['supplier_id'];

        $collections = $this->collectionModel->getSupplierCollections($supplierId);
        
        $data = [
            'collections' => $collections
        ];
        
        $this->view('supplier/v_view_collection', $data);
    }

    public function collectionBags($collection_id) {


        $supplier_id = $_SESSION['supplier_id'];
        
        // Get bags for this collection that belong to this supplier
        $bags = $this->collectionModel->getSupplierBagsForCollection($supplier_id, $collection_id);
        
        $data = [
            'collection_id' => $collection_id,
            'bags' => $bags
        ];
        
        $this->view('supplier/v_collection_bags', $data);
    }

    public function getUnallocatedSuppliersByDay($day) {
        // Ensure the day parameter is valid
        if (!in_array($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid day provided']);
            return;
        }
    
        // Fetch unallocated suppliers for the given day
        $suppliers = $this->routeModel->getUnallocatedSuppliers($day);
    
        // Return the response
        echo json_encode(['success' => true, 'data' => $suppliers]);
    }


    public function bag($bagId) {
        $data = [
            'bagId' => $bagId,
        ];
        $this->view('supplier/v_bag', $data);
    }

    public function fertilizer($fertilizerId) {
        $data = [
            'fertilizerId' => $fertilizerId,
        ];
        $this->view('supplier/v_fertilizer', $data);
    }

    public function toggleAvailability() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the current status from the POST data
            $currentStatus = isset($_POST['current_status']) ? $_POST['current_status'] : '0';
            $supplierId = $_SESSION['supplier_id'];
    
            // Toggle the status
            $newStatus = $currentStatus === '1' ? '0' : '1';
    
            // Update the supplier's availability in the model
            if ($this->supplierModel->updateSupplierStatus($supplierId, $newStatus)) {
                if($newStatus){
                setFlashMessage('You will be included in the next schedule!');
                } else {
                    setFlashMessage('You will not be included in the next schedule!', 'error');
                }
            } else {
                setFlashMessage('Couldnt update you availability! Please contact our managers immediately!');
            }
    
            redirect('supplier/');
        } else {
            redirect('supplier/');
        }
    }

    // Chat-related methods

    public function chat() {
        // Ensure the user is logged in and has the Supplier role (role_id = 7)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
            redirect('auth/login');
            return;
        }

        // Fetch the list of active Vehicle Managers (role_id = 4)
        $activeManagers = $this->chatModel->getActiveManagers();
        error_log("Managers in Supplier chat(): " . print_r($activeManagers, true));

        $data = [
            'active_managers' => $activeManagers,
            'page_title' => 'Chat with Managers',
            'user_id' => $_SESSION['user_id']
        ];
        
        $this->view('supplier/v_chat', $data);
    }


    public function sendMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }
    
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['receiver_id']) || !is_numeric($data['receiver_id']) || !isset($data['message'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }
    
        $senderId = $_SESSION['user_id'];
        $receiverId = (int)$data['receiver_id'];
        $message = trim($data['message']);
    
        // Ensure the receiver is a Vehicle Manager (role_id = 4 or 12)
        $receiver = $this->chatModel->getUserName($receiverId);
        if (!$receiver || !str_contains($receiver, 'MGR')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid receiver']);
            return;
        }
    
        $result = $this->chatModel->saveMessage($senderId, $receiverId, $message);
        if ($result['success']) {
            echo json_encode([
                'success' => true,
                'data' => [  // Wrap message_id and created_at in a "data" object
                    'message_id' => $result['message_id'],
                    'created_at' => $result['created_at']
                ]
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error saving message']);
        }
    }

    public function getMessages() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }
    
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['receiver_id']) || !is_numeric($data['receiver_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid receiver ID']);
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $receiverId = (int)$data['receiver_id'];
        $lastMessageId = isset($data['last_message_id']) && is_numeric($data['last_message_id']) ? (int)$data['last_message_id'] : 0;
    
        try {
            $messages = $this->chatModel->getMessages($userId, $receiverId, $lastMessageId);
            echo json_encode([
                'success' => true,
                'data' => [
                    'messages' => $messages
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error fetching messages: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching messages']);
        }
    }

    public function editMessage() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['message_id']) || !is_numeric($data['message_id']) || !isset($data['new_message'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            return;
        }

        $messageId = (int)$data['message_id'];
        $newMessage = trim($data['new_message']);
        $userId = $_SESSION['user_id'];

        $result = $this->chatModel->editMessage($messageId, $newMessage, $userId);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error editing message']);
        }
    }

    public function deleteMessage() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }
    
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['message_id']) || !is_numeric($data['message_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid message ID']);
            return;
        }
    
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
            return;
        }
    
        $messageId = (int)$data['message_id'];
        $userId = $_SESSION['user_id'];
    
        try {
            $result = $this->chatModel->deleteMessage($messageId, $userId);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'message' => 'Error deleting message']);
            }
        } catch (Exception $e) {
            error_log("Error deleting message: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error deleting message']);
        }
    }

    public function announcements() {
        // Ensure the user is logged in and has the Supplier role (role_id = 5)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
            // Use session directly for error message
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['flash']['message'] = [
                'message' => 'Unauthorized access',
                'class' => 'alert alert-danger'
            ];
            redirect('auth/login');
            return;
        }
    
        try {
            // Fetch announcements created by Vehicle Managers (role_id = 4)
            $announcements = $this->chatModel->getAnnouncementsForSupplier($_SESSION['supplier_id']);
            
            $data = [
                'announcements' => $announcements,
                'error' => ''
            ];
        } catch (Exception $e) {
            error_log("Error fetching announcements for supplier ID {$_SESSION['supplier_id']}: " . $e->getMessage());
            $data = [
                'announcements' => [],
                'error' => 'An error occurred while fetching announcements. Please try again later.'
            ];
        }
    
        $this->view('supplier/v_announcements', $data);
    }
}