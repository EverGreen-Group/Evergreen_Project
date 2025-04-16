<?php
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once APPROOT . '/models/M_Supplier.php';
require_once APPROOT . '/models/M_Route.php';
require_once APPROOT . '/models/M_Collection.php';
require_once APPROOT . '/models/M_CollectionSchedule.php';
require_once APPROOT . '/models/M_Complaint.php';
require_once APPROOT . '/models/M_Bag.php';
require_once APPROOT . '/models/M_Chat.php';
require_once APPROOT . '/models/M_User.php'; // Add User model
require_once APPROOT . '/models/M_Notification.php'; // Add Notification model
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/utility_helper.php'; // Ensure helper is included

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
    private $notificationModel;

    public function __construct() {
        requireAuth();

        // Initialize models
        $this->fertilizerOrderModel = new M_Fertilizer_Order();
        $this->supplierModel = new M_Supplier();
        $this->routeModel = new M_Route();
        $this->collectionModel = new M_Collection();
        $this->scheduleModel = new M_CollectionSchedule();
        $this->complaintModel = new M_Complaint();
        $this->bagModel = new M_Bag();
        $this->appointmentModel = $this->model('M_Appointment');
        $this->chatModel = new M_Chat();
        $this->userModel = new M_User();
        $this->notificationModel = new M_Notification();

        // Set the supplier ID in the session
        $supplierDetails = $this->supplierModel->getSupplierDetailsByUserId($_SESSION['user_id']);
        if ($supplierDetails) {
            $_SESSION['supplier_id'] = $supplierDetails->supplier_id;
        } else {
            flash('message', 'Unable to load supplier details. Please log in again.', 'alert alert-danger');
            redirect('auth/login');
        }
    }

    public function index() {
        $supplierId = $_SESSION['supplier_id'];
        $collectionId = $this->collectionModel->checkCollectionExistsUsingSupplierId($supplierId);

        try {
            $allSchedules = $this->scheduleModel->getUpcomingSchedulesBySupplierId($supplierId);
            $supplierStatus = $this->supplierModel->getSupplierStatus($supplierId);

            $todaySchedules = [];
            $upcomingSchedules = [];

            foreach ($allSchedules as $schedule) {
                if ($schedule->is_today && $schedule->collection_exists > 0 && 
                    ($this->collectionModel->getCollectionSuppliersStatus($collectionId, $supplierId) == 'Completed')) {
                    continue;
                }

                if ($schedule->is_today) {
                    $todaySchedules[] = $schedule;
                } else {
                    $upcomingSchedules[] = $schedule;
                }
            }

            $data = [
                'todaySchedules' => $todaySchedules,
                'upcomingSchedules' => $upcomingSchedules,
                'currentWeek' => date('W'),
                'currentDay' => date('l'),
                'lastUpdated' => date('Y-m-d H:i:s'),
                'message' => '',
                'error' => '',
                'collectionId' => $collectionId,
                'is_active' => $supplierStatus
            ];

            if (empty($todaySchedules) && empty($upcomingSchedules)) {
                $data['message'] = 'No upcoming schedules found.';
            }
        } catch (Exception $e) {
            error_log($e->getMessage());
            $data = [
                'todaySchedules' => [],
                'upcomingSchedules' => [],
                'message' => '',
                'error' => 'An error occurred while fetching schedules. Please try again later.',
                'collectionId' => $collectionId
            ];
        }

        $this->view('supplier/v_supply_dashboard', $data);
    }

    public function viewAppointments() {
        $timeSlots = $this->appointmentModel->getAvailableTimeSlots();
        $myRequests = $this->appointmentModel->getMyRequests($_SESSION['supplier_id']);
        $confirmedAppointments = $this->appointmentModel->getConfirmedAppointments($_SESSION['supplier_id']);

        $data = [
            'time_slots' => $timeSlots,
            'my_requests' => $myRequests,
            'confirmed_appointments' => $confirmedAppointments
        ];

        $this->view('supplier/v_time_slots', $data);
    }

    public function requestTimeSlot() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('Supplier/viewAppointments');
            return;
        }

        if (!isset($_POST['slot_id']) || empty($_POST['slot_id'])) {
            setFlashMessage('Time slot isn\'t available anymore, please refresh the page!', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }

        $slotId = trim($_POST['slot_id']);
        $supplierId = $_SESSION['supplier_id'];

        $slot = $this->appointmentModel->getSlotById($slotId);
        if (!$slot || $slot->status !== 'Available') {
            setFlashMessage('Time slot isn\'t available anymore.', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }

        if ($this->appointmentModel->hasAlreadyRequested($slotId, $supplierId)) {
            setFlashMessage('You have already requested this time slot!', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }

        $data = [
            'supplier_id' => $supplierId,
            'slot_id' => $slotId,
            'status' => 'Pending',
            'submitted_at' => date('Y-m-d H:i:s')
        ];

        if ($this->appointmentModel->createRequest($data)) {
            setFlashMessage('Request sent successfully!');
        } else {
            setFlashMessage('Request failed, please try again later!', 'error');
        }

        redirect('Supplier/viewAppointments');
    }

    public function cancelRequest($id = null) {
        if (!$id) {
            setFlashMessage('Cancellation failed, please try again later!', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }

        $request = $this->appointmentModel->getRequestById($id);
        if (!$request || $request->supplier_id != $_SESSION['supplier_id'] || $request->status != 'Pending') {
            setFlashMessage('You cannot cancel this request', 'error');
            redirect('Supplier/viewAppointments');
            return;
        }

        if ($this->appointmentModel->cancelRequest($id, $_SESSION['supplier_id'])) {
            setFlashMessage('Appointment request cancelled successfully!');
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

        $month = isset($_GET['month']) ? $_GET['month'] : 'all';
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');

        try {
            $earnings = $this->supplierModel->getSupplierEarnings($supplierId, $month, $year);

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
                'totals' => $totals,
                'error' => ''
            ];
        } catch (Exception $e) {
            error_log($e->getMessage());
            $data = [
                'earnings' => [],
                'totals' => (object)[],
                'error' => 'An error occurred while fetching earnings. Please try again later.'
            ];
        }

        $this->view('supplier/v_supplier_payment', $data);
    }

    public function schedule() {
        $supplierId = $_SESSION['supplier_id'];

        try {
            $subscribedSchedules = $this->scheduleModel->getSubscribedSchedules($supplierId);
            $availableSchedules = $this->scheduleModel->getAvailableSchedules($supplierId);

            $formatSchedule = function($schedule) {
                return [
                    'schedule_id' => $schedule->schedule_id,
                    'route_name' => $schedule->route_name,
                    'day' => $schedule->day,
                    'shift_time' => $schedule->shift_time,
                    'remaining_capacity' => $schedule->remaining_capacity,
                    'vehicle' => $schedule->license_plate,
                    'is_subscribed' => (bool)$schedule->is_subscribed
                ];
            };

            $data = [
                'subscribedSchedules' => array_map($formatSchedule, $subscribedSchedules),
                'availableSchedules' => array_map($formatSchedule, $availableSchedules),
                'error' => ''
            ];
        } catch (Exception $e) {
            error_log($e->getMessage());
            $data = [
                'subscribedSchedules' => [],
                'availableSchedules' => [],
                'error' => 'An error occurred while fetching schedules. Please try again later.'
            ];
        }

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
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $supplierId = $_SESSION['supplier_id']; 
    
            $data = [
                'supplier_id' => $supplierId,
                'complaint_type' => trim($_POST['complaint_type']),
                'subject' => trim($_POST['subject']),
                'description' => trim($_POST['description']),
                'priority' => trim($_POST['priority']),
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

    public function settings() {
        $data = [];
        $this->view('supplier/v_settings', $data);
    }

    // public function fertilizerhistory() {
    //     // Ensure supplier is logged in
    //     if (!isset($_SESSION['supplier_id'])) {

    //         redirect('login');
    //         return;
    //     }
    
    //     // Fetch orders for the current supplier
    //     $orders = $this->fertilizerOrderModel->getOrdersBySupplier($_SESSION['supplier_id']);
    
    //     $data = [
    //         'orders' => $orders,
    //         'fertilizer_types' => $this->fertilizerOrderModel->getAllFertilizerTypes()
    //     ];
    
    //     $this->view('supplier/v_fertilizer_history', $data);
    // }

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

    // public function requestFertilizer() {
    //     $fertilizerModel = new M_Fertilizer_Order();
    //     $data['fertilizer_types'] = $fertilizerModel->getAllFertilizerTypes();
    //     $data['orders'] = $fertilizerModel->getAllOrders(); // Switch to getOrderBySupplier() after logging in
    //     $data['unit'] = flash('used_unit');

    //     $this->view('supplier/v_fertilizer_request', $data);
    // }
   
    public function createFertilizerOrder() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
    
            // Check for required fields
            $required_fields = ['type_id', 'unit', 'total_amount'];
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
    
            // Validate and get fertilizer data
            $type_id = trim($_POST['type_id']);
            $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($type_id);
    
            if (!$fertilizer) {
                throw new Exception('Invalid fertilizer type');
            }
    
            $unit = $_POST['unit'];
            flash('used_unit', $unit);
    
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
                'total_price' => $total_price,
                'status' => 'Pending',
                'payment_status' => 'Pending'
            ];
    
            // Create the order
            /*if ($this->fertilizerOrderModel->createOrder($order_data)) {
                // Set flash message for when redirected
                flash('fertilizer_message', 'Order placed successfully!', 'alert alert-success');
            } else {
                throw new Exception($this->fertilizerOrderModel->getError() ?? 'Failed to create order');
            }*/

            if ($this->fertilizerOrderModel->createOrder($order_data)) {
                $_SESSION['fertilizer_message'] = 'Request submitted!';
                $_SESSION['fertilizer_message_class'] = 'alert-success';
            } else {
                $_SESSION['fertilizer_message'] = 'Request failed!';
                $_SESSION['fertilizer_message_class'] = 'alert-danger';
            }
            
            redirect('Supplier/requestFertilizer');
    
        } catch (Exception $e) {
            // Set flash message for when redirected
            flash('fertilizer_message', $e->getMessage(), 'alert alert-danger');
        }

        // Redirect back to the fertilizer request page
        redirect('Supplier/requestFertilizer');
        exit;
    }

    private function validateRequest($data) {
        $required_fields = ['quantity', 'total_amount'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        if ($data['quantity'] <= 0 || $data['quantity'] > 1000) { // Updated limit to 1000
            return false;
        }

        return true;
    }

    public function viewMonthlyIncome() {
        $data = [
            'title' => 'Schedule Details'
        ];
        $this->view('shared/supplier/v_view_monthly_statement', $data);
    }

    public function scheduleDetails() {
        $data = [
            'title' => 'Schedule Details'
        ];
        $this->view('supplier/v_schedule_details', $data);
    }

    public function collections() {
        $supplier_id = $_SESSION['supplier_id'] ?? null;
        if (!$supplier_id) {
            throw new Exception("Supplier ID not found. Please login again!");
        }

        $collectionDetails = $this->collectionModel->getCollectionDetails($supplier_id);

        $data = [
            'collectionDetails' => $collectionDetails
        ];
        $this->view('supplier/v_collections', $data);
    }

    public function getUnallocatedSuppliersByDay($day) {
        if (!in_array($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid day provided']);
            return;
        }
    
        // Fetch unallocated suppliers for the given day
        $suppliers = $this->routeModel->getUnallocatedSuppliers($day);
    
        // Return the response
        echo json_encode(['success' => true, 'data' => $suppliers]);
    }

    public function getBagDetails($collectionId) {
        try {
            $bagDetails = $this->bagModel->getBagsByCollectionId($collectionId);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $bagDetails]);
        } catch (Exception $e) {
            error_log($e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error fetching bag details']);
        }
        exit();
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
            setFlashMessage('Couldn\'t update your availability! Please contact our managers immediately!', 'error');
        }

        redirect('supplier/');
    }}

    // Chat-related methods

    public function chat() {
        // Ensure the user is logged in and has the Supplier role (role_id = 7)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
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

        // Ensure the receiver is a Vehicle Manager (role_id = 4)
        $receiver = $this->chatModel->getUserName($receiverId);
        if (!$receiver || !str_contains($receiver, 'MGR')) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid receiver']);
            return;
        }

        $result = $this->chatModel->saveMessage($senderId, $receiverId, $message);
        if ($result['success']) {
            echo json_encode(['success' => true, 'message_id' => $result['message_id']]);
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

        $messages = $this->chatModel->getMessages($userId, $receiverId);
        if ($messages === false) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching messages']);
            return;
        }

        echo json_encode([
            'success' => true,
            'messages' => $messages
        ]);
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

        $messageId = (int)$data['message_id'];
        $userId = $_SESSION['user_id'];

        $result = $this->chatModel->deleteMessage($messageId, $userId);
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error deleting message']);
        }
    }

    // Announcements by Theekshana
    public function announcements() {
        // Ensure the user is logged in and has the Supplier role (role_id = 7)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 5) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
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
            error_log($e->getMessage());
            $data = [
                'announcements' => [],
                'error' => 'An error occurred while fetching announcements. Please try again later.'
            ];
        }
    
        $this->view('supplier/v_announcements', $data);
    }



    // public function collections() {
    //     $supplierId = $_SESSION['supplier_id'];

    //     $collections = $this->collectionModel->getSupplierCollections($supplierId);
        
    //     $data = [
    //         'collections' => $collections
    //     ];
        
    //     $this->view('supplier/v_view_collection', $data);
    // }

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


    public function getCartItems($supplier_id) {
        $this->db->query("
            SELECT c.supplier_id, c.fertilizer_id, c.unit_type, c.quantity, c.price, ft.name 
            FROM cart c
            JOIN fertilizer_types ft ON c.fertilizer_id = ft.type_id
            WHERE c.supplier_id = :supplier_id
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        $cart_items = $this->db->resultSet();
        error_log("Cart items for supplier $supplier_id: " . print_r($cart_items, true));
        return $cart_items;
    }


    //cart items

    public function addToCart($fertilizer_id, $unit_type) {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            return;
        }

        $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($fertilizer_id);
        if (!$fertilizer) {
            echo json_encode(['success' => false, 'message' => 'Fertilizer not found']);
            return;
        }

        $price = 0;
        if ($unit_type === 'kg') $price = $fertilizer->unit_price_kg;
        elseif ($unit_type === 'packs') $price = $fertilizer->unit_price_packs;
        elseif ($unit_type === 'box') $price = $fertilizer->unit_price_box;

        if ($this->fertilizerOrderModel->addToCart($_SESSION['user_id'], $fertilizer_id, $unit_type, 1, $price)) {
            echo json_encode(['success' => true, 'message' => 'Item added to cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add item to cart']);
        }
    }

    public function removeFromCart($cart_id) {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            return;
        }

        if ($this->fertilizerOrderModel->removeFromCart($cart_id)) {
            echo json_encode(['success' => true, 'message' => 'Item removed from cart']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to remove item']);
        }
    }

    public function updateCartItem() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            return;
        }

        $cart_id = $_POST['cart_id'];
        $quantity = $_POST['quantity'];

        if ($this->fertilizerOrderModel->updateCartItem($cart_id, $quantity)) {
            echo json_encode(['success' => true, 'message' => 'Cart updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update cart']);
        }
    }

    public function clearCart() {
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Please log in']);
            return;
        }

        if ($this->fertilizerOrderModel->clearCart($_SESSION['user_id'])) {
            echo json_encode(['success' => true, 'message' => 'Cart cleared successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to clear cart']);
        }
    }

    // public function updateCartItem() {
    //     if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    //         die('Method not allowed');
    //     }
    
    //     $supplier_id = $_SESSION['user_id'] ?? null;
    //     if (!$supplier_id) {
    //         echo json_encode(['success' => false, 'message' => 'Please log in to update your cart.']);
    //         return;
    //     }
    
    //     $fertilizer_id = $_POST['fertilizer_id'] ?? null;
    //     $unit_type = $_POST['unit_type'] ?? null;
    //     $quantity = $_POST['quantity'] ?? null;
    
    //     if (!$fertilizer_id || !$unit_type || !$quantity || $quantity < 1) {
    //         echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    //         return;
    //     }
    
    //     $result = $this->fertilizerOrderModel->updateCartItem($supplier_id, $fertilizer_id, $unit_type, $quantity);
    //     if ($result) {
    //         echo json_encode(['success' => true, 'message' => 'Cart item updated successfully!']);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Failed to update cart item.']);
    //     }
    // }

    //newly added function to get cart total

    

    public function requestFertilizer() {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('auth/login');
            return;
        }
    
        // Handle fertilizer request creation
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'create_order') {
            $fertilizer_id = $_POST['fertilizer'];
            $unit_type = $_POST['unit_type'];
            $quantity = $_POST['quantity'];
            $total_amount = $_POST['total_amount'];
    
            $data = [
                'supplier_id' => $supplier_id,
                'type_id' => $fertilizer_id,
                'unit_type' => $unit_type,
                'quantity' => $quantity,
                'total_amount' => $total_amount,
                'status' => 'Pending',
                'payment_status' => 'pending'
            ];
    
            if ($this->fertilizerOrderModel->createOrder($data)) {
                flash('message', 'Fertilizer request submitted successfully!', 'alert alert-success');
            } else {
                flash('message', 'Failed to submit fertilizer request.', 'alert alert-danger');
            }
            redirect('Supplier/requestFertilizer');
            return;
        }
    
        // Handle checkout
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'checkout') {
            $shipping_address = [
                'full_name' => $_POST['full_name'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'city' => $_POST['city'],
                'postal_code' => $_POST['postal_code']
            ];
    
            $data = [
                'supplier_id' => $supplier_id,
                'cart_items' => $this->fertilizerOrderModel->getCartItems($supplier_id),
                'cart_total' => array_sum(array_map(function($item) {
                    return $item->price * $item->quantity;
                }, $this->fertilizerOrderModel->getCartItems($supplier_id))),
                'shipping_fee' => 500.00,
                'tax_amount' => array_sum(array_map(function($item) {
                    return $item->price * $item->quantity;
                }, $this->fertilizerOrderModel->getCartItems($supplier_id))) * 0.10,
                'delivery_date' => $_POST['delivery_date'],
                'shipping_address' => json_encode($shipping_address)
            ];
    
            if ($this->fertilizerOrderModel->checkout($supplier_id)) {
                flash('message', 'Order placed successfully!', 'alert alert-success');
            } else {
                flash('message', 'Failed to place order.', 'alert alert-danger');
            }
            redirect('Supplier/requestFertilizer');
            return;
        }
    
        // Automatically add accepted orders to cart
        $orders = $this->fertilizerOrderModel->getOrdersBySupplier($supplier_id);
        foreach ($orders as $order) {
            if ($order->status === 'Accepted') {
                $this->fertilizerOrderModel->addAcceptedOrderToCart($order->order_id);
            }
        }
    
        // Fetch data for the view
        $data = [
            'fertilizer_types' => $this->fertilizerOrderModel->getFertilizerTypes(), // This line caused the error
            'orders' => $orders,
            'cart_items' => $this->fertilizerOrderModel->getCartItems($supplier_id),
            'cart_total' => array_sum(array_map(function($item) {
                return $item->price * $item->quantity;
            }, $this->fertilizerOrderModel->getCartItems($supplier_id)))
        ];
    
        $this->view('supplier/v_fertilizer_request', $data);
    }

    public function editFertilizerRequest($order_id) {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('auth/login');
            return;
        }
    
        // Fetch the order details
        $order = $this->fertilizerOrderModel->getOrderById($order_id);
        error_log("Order data: " . print_r($order, true)); // Debug log
    
        // Check if the order exists, belongs to the supplier, and is editable
        if (!$order || $order->supplier_id != $supplier_id || $order->status != 'Pending') {
            flash('message', 'You cannot edit this order.', 'alert alert-danger');
            redirect('Supplier/requestFertilizer');
            return;
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'order_id' => $order_id,
                'type_id' => $_POST['type_id'],
                'unit_type' => $_POST['unit'],
                'quantity' => $_POST['quantity'],
                'total_amount' => $_POST['total_amount'],
            ];
    
            // Update the order
            if ($this->fertilizerOrderModel->updateOrder($data)) {
                flash('message', 'Fertilizer request updated successfully!', 'alert alert-success');
                redirect('Supplier/requestFertilizer');
            } else {
                flash('message', 'Failed to update fertilizer request.', 'alert alert-danger');
                $data['order'] = $order;
                $data['fertilizer_types'] = $this->fertilizerOrderModel->getFertilizerTypes();
                $this->view('supplier/v_request_edit', $data);
            }
        } else {
            $data = [
                'order' => $order,
                'fertilizer_types' => $this->fertilizerOrderModel->getFertilizerTypes()
            ];
            $this->view('supplier/v_request_edit', $data);
        }
    }


    public function deleteFertilizerRequest($order_id) {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            echo json_encode(['success' => false, 'message' => 'Please log in to delete an order']);
            return;
        }

        $order = $this->fertilizerOrderModel->getOrderById($order_id);
        if (!$order || $order->supplier_id != $supplier_id) {
            echo json_encode(['success' => false, 'message' => 'Order not found or unauthorized access']);
            return;
        }

        if ($order->status !== 'Pending' || $order->payment_status !== 'pending') {
            echo json_encode(['success' => false, 'message' => 'Order cannot be deleted']);
            return;
        }

        if ($this->fertilizerOrderModel->deleteOrder($order_id)) {
            echo json_encode(['success' => true, 'message' => 'Order deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete order']);
        }
    }

    public function checkFertilizerOrderStatus($orderId) {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id || !$this->isSupplierOrder($orderId)) {
            echo json_encode(['canDelete' => false, 'message' => 'Unauthorized access']);
            return;
        }

        $order = $this->fertilizerOrderModel->getOrderById($orderId);

        if (!$order) {
            echo json_encode(['canDelete' => false, 'message' => 'Order not found']);
            return;
        }

        $canDelete = ($order->status === 'Pending' && $order->payment_status === 'pending');

        echo json_encode([
            'canDelete' => $canDelete,
            'message' => $canDelete ? 'Order can be deleted' : 'Order cannot be deleted'
        ]);
    }

    // public function removeFromCart($fertilizerId) {
    //     $supplier_id = $_SESSION['user_id'] ?? null;
    //     if (!$supplier_id) {
    //         echo json_encode(['success' => false, 'message' => 'Please log in to remove items from cart']);
    //         return;
    //     }

    //     if ($this->fertilizerOrderModel->removeFromCart($supplier_id, $fertilizerId)) {
    //         echo json_encode(['success' => true, 'message' => 'Item removed from cart successfully']);
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Failed to remove item from cart']);
    //     }
    // }

    public function finalizeFertilizerOrder($order_id) {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('auth/login');
            return;
        }

        $order = $this->fertilizerOrderModel->getOrderById($order_id);
        if (!$order || $order->supplier_id != $supplier_id) {
            flash('message', 'Order not found or unauthorized access', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        if ($order->status !== 'Accepted') {
            flash('message', 'Order must be accepted before finalizing', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $delivery_date = $_POST['delivery_date'] ?? '';
            if (empty($delivery_date)) {
                flash('message', 'Please provide a delivery date.', 'alert alert-danger');
                redirect("supplier/finalizeFertilizerOrder/$order_id");
                return;
            }

            if ($this->fertilizerOrderModel->updateOrderWithDeliveryDate($order_id, $delivery_date)) {
                flash('message', 'Order finalized successfully!', 'alert alert-success');
                redirect('supplier/requestFertilizer');
            } else {
                flash('message', 'Failed to finalize order.', 'alert alert-danger');
                redirect("supplier/finalizeFertilizerOrder/$order_id");
            }
        }

        $data['order_id'] = $order_id;
        $this->view('supplier/v_finalize_order', $data);
    }

    public function fertilizerhistory() {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to view your order history', 'alert alert-danger');
            redirect('auth/login');
            return;
        }

        $orders = $this->fertilizerOrderModel->getOrdersBySupplier($supplier_id);

        $data = [
            'orders' => $orders,
            'fertilizer_types' => $this->fertilizerOrderModel->getAvailableFertilizerTypes()
        ];

        $this->view('supplier/v_fertilizer_history', $data);
    }

    private function isSupplierOrder($orderId) {
        $order = $this->fertilizerOrderModel->getOrderById($orderId);
        return $order && $order->supplier_id == ($_SESSION['user_id'] ?? null);
    }


    
}
?>