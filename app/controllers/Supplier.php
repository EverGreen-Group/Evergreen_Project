<?php
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once APPROOT . '/models/M_Supplier.php';
require_once APPROOT . '/models/M_Route.php';
require_once APPROOT . '/models/M_Collection.php';
require_once APPROOT . '/models/M_CollectionSchedule.php';
require_once APPROOT . '/models/M_Bag.php';
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
    private $bagModel;
    private $appointmentModel;

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
        $this->supplierModel = new M_Supplier();
        $this->routeModel = new M_Route();
        $this->collectionModel= new M_Collection();
        $this->scheduleModel= new M_CollectionSchedule();
        $this->bagModel = new M_Bag();
        $this->appointmentModel = $this->model('M_Appointment');
        $supplierDetails = $this->supplierModel->getSupplierDetailsByUserId($_SESSION['user_id']);
        $_SESSION['supplier_id'] = $supplierDetails->supplier_id;
    }

    
    public function index() {
        $supplierId = $_SESSION['supplier_id'];
        $collectionId = $this->collectionModel->checkCollectionExistsUsingSupplierId($supplierId);
    
        try {
            // Get all schedules
            $allSchedules = $this->scheduleModel->getUpcomingSchedulesBySupplierId($supplierId);
            $supplierStatus = $this->supplierModel->getSupplierStatus($supplierId);
            
            // Organize schedules by day, filtering out already collected schedules for today
            $todaySchedules = [];
            $upcomingSchedules = [];

            /*
            WE CAN SIMPLY OMIT THE SCHEDULES IF THERE EXISTS A COLLECTION FOR IT...
            */
            
            foreach ($allSchedules as $schedule) {
                // Skip schedules that already have collections for today
                if ($schedule->is_today && $schedule->collection_exists > 0) {
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
            // Log the error (assuming you have a logging system)
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
    
            if (!isset($_POST['slot_id']) || empty($_POST['slot_id'])) {
                flash('request_message', 'Invalid time slot.', 'alert alert-error');
                redirect('Supplier/viewAppointments');
                return;
            }
    
            $slotId = trim($_POST['slot_id']);
            $supplierId = $_SESSION['supplier_id'];
    
            // Check if the slot is available
            $slot = $this->appointmentModel->getSlotById($slotId);
            if (!$slot || $slot->status !== 'Available') {
                flash('request_message', 'This time slot is no longer available.', 'alert alert-error');
                redirect('Supplier/viewAppointments');
                return;
            }
    
            // Check if this supplier has already requested this slot
            if ($this->appointmentModel->hasAlreadyRequested($slotId, $supplierId)) {
                flash('request_message', 'You have already requested this time slot.', 'alert alert-warning');
                redirect('Supplier/viewAppointments');
                return;
            }
    
            // Prepare request data
            $data = [
                'supplier_id' => $supplierId,
                'slot_id' => $slotId,
                'status' => 'Pending',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
    
            // Insert the request
            if ($this->appointmentModel->createRequest($data)) {
                flash('request_message', 'Time slot requested successfully.');
            } else {
                flash('request_message', 'Failed to request time slot.', 'alert alert-error');
            }
    
            redirect('Supplier/viewAppointments');
        } else {
            redirect('Supplier/viewAppointments');
        }
    }
    
    
    public function cancelRequest($id = null) {
        // Validate the request ID
        if (!$id) {
            flash('request_message', 'Invalid request.', 'alert alert-error');
            redirect('Supplier/viewAppointments');
            return;
        }
        
        // Check if the request belongs to this supplier and is still pending
        $request = $this->appointmentModel->getRequestById($id);
        if (!$request || $request->supplier_id != $_SESSION['supplier_id'] || $request->status != 'Pending') {
            flash('request_message', 'You cannot cancel this request.', 'alert alert-error');
            redirect('Supplier/viewAppointments');
            return;
        }
        
        // Cancel the request
        if ($this->appointmentModel->cancelRequest($id, $_SESSION['supplier_id'])) {
            flash('request_message', 'Appointment request cancelled successfully.');
        } else {
            flash('request_message', 'Failed to cancel request.', 'alert alert-error');
        }
        
        redirect('Supplier/viewAppointments');
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


        $this->view('supplier/v_supplier_payment', []);
    }

    public function schedule()
    {
        $supplierId = $_SESSION['supplier_id'];

        try {
            // Get subscribed and available schedules separately
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

    public function paymentanalysis()
    {
        $data = [];

        $this->view('supplier/v_payment_analysis', $data);
    }


    public function profile()
    {
        $userId = $_SESSION['user_id'];
        
        $profileData = $this->supplierModel->getSupplierProfile($userId);
        
        if (!$profileData) {
            flash('profile_message', 'Unable to load profile information', 'alert alert-error');
            redirect('Supplier/dashboard');
        }
        
        $data = $profileData;
        
        $this->view('supplier/v_profile', $data);
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
                'account_holder_name' => trim($_POST['account_holder_name']),
                'bank_name' => trim($_POST['bank_name']),
                'branch_name' => trim($_POST['branch_name']),
                'account_type' => $_POST['account_type'],
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
                    flash('profile_message', 'Error uploading image', 'alert alert-error');
                    redirect('Supplier/profile');
                }
            }
            if ($this->supplierModel->updateSupplierProfile($data)) {
                flash('profile_message', 'Profile updated successfully');
                redirect('Supplier/profile');
            } else {
                flash('profile_message', 'Error updating profile', 'alert alert-error');
                redirect('Supplier/profile');
            }
        } else {
            redirect('Supplier/profile');
        }
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

    public function submitComplaint()
    {
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
                flash('complaint_success', 'Complaint submitted successfully');
                redirect('supplier/complaints');
            } else {
                redirect('supplier/complaints');
            }
        } else {
            redirect('supplier/complaints');
        }
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

    public function collection($collectionId) {

        $collectionDetails = $this->collectionModel->getCollectionDetails($collectionId);

        $data = [
            'collectionId' => $collectionId,
            'collectionDetails' => $collectionDetails
        ];

        $this->view('supplier/v_collection', $data);
    }

    public function getUnallocatedSuppliersByDay($day) {
        // Ensure the day parameter is valid
        if (!in_array($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid day provided']);
            return;
        }
    
        // Fetch unallocated suppliers for the given day
        $suppliers = $this->routeModel->getUnallocatedSuppliersByDay($day);
    
        // Return the response
        echo json_encode(['success' => true, 'data' => $suppliers]);
    }

    public function getBagDetails($collectionId) {
        // Fetch bag details from the model using the collection ID
        $bagDetails = $this->bagModel->getBagsByCollectionId($collectionId);

        // Return the bag details as JSON
        header('Content-Type: application/json');
        echo json_encode($bagDetails);
        exit();
    }

    public function bag($bagId) {
        // $collectionDetails = $this->collectionModel->getCollectionDetails($collectionId);

        $data = [
            'bagId' => $bagId,
            // 'bagDetails' => $collectionDetails
        ];

        $this->view('supplier/v_bag', $data);
    }

    public function fertilizer($fertilizerId) {
        // $collectionDetails = $this->collectionModel->getCollectionDetails($collectionId);

        $data = [
            'fertilizerId' => $fertilizerId,
            // 'bagDetails' => $collectionDetails
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
            
            // If supplier is becoming inactive, unsubscribe from all routes
            if ($newStatus === '0') {
                $subscribedSchedules = $this->scheduleModel->getSubscribedSchedules($supplierId);
                
                // Process each subscription
                $unsubscribeResults = [];
                foreach ($subscribedSchedules as $schedule) {
                    $routeId = $schedule->route_id;
                    
                    try {
                        // Remove supplier from route
                        if ($this->routeModel->removeSupplierFromRoute($routeId, $supplierId)) {
                            $this->routeModel->updateRemainingCapacity($routeId, 'remove');
                            $unsubscribeResults[] = true;
                        } else {
                            $unsubscribeResults[] = false;
                        }
                    } catch (Exception $e) {
                        $unsubscribeResults[] = false;
                    }
                }
                
                // If any unsubscriptions failed, you may want to handle that
                $allUnsubscribesSuccessful = !in_array(false, $unsubscribeResults);
                // You could decide whether to proceed based on this result
            }
    
            // Update the supplier's availability in the model
            if ($this->supplierModel->updateSupplierStatus($supplierId, $newStatus)) {
                flash('message', 'Supplier availability updated successfully.', 'alert alert-success');
            } else {
                flash('message', 'Failed to update availability. Please try again.', 'alert alert-danger');
            }
    
            // Redirect back to the dashboard
            redirect('supplier/');
        } else {
            redirect('supplier/'); // Redirect if not a POST request
        }
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
}
?>