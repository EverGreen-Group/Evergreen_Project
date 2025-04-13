<?php
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once APPROOT . '/models/M_Supplier.php';
require_once APPROOT . '/models/M_Route.php';
require_once APPROOT . '/models/M_Collection.php';
require_once APPROOT . '/models/M_CollectionSchedule.php';
require_once APPROOT . '/models/M_Bag.php';
require_once APPROOT . '/models/M_Chat.php'; // Add the chat model
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
    private $chatModel; // Add the chat model property

    public function __construct() {
        // Check if the user is logged in
        requireAuth();

        // Initialize the models
        //$this->fertilizerOrderModel = new M_Fertilizer_Order();
        
        $this->supplierModel = new M_Supplier();
        $this->routeModel = new M_Route();
        $this->collectionModel = new M_Collection();
        $this->scheduleModel = new M_CollectionSchedule();
        $this->bagModel = new M_Bag();
        $this->appointmentModel = $this->model('M_Appointment');
        $this->chatModel = new M_Chat(); // Initialize the chat model
        $this->fertilizerOrderModel = $this->model('M_FertilizerOrder');

        // Set the supplier ID in the session
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
            // Log the error
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
            // Validate the slot ID
            if (!isset($_POST['slot_id']) || empty($_POST['slot_id'])) {
                flash('request_message', 'Invalid time slot.', 'alert alert-error');
                redirect('Supplier/viewAppointments');
                return;
            }
            
            // Check if the slot is still available
            $slot = $this->appointmentModel->getSlotById($_POST['slot_id']);
            if (!$slot || $slot->status !== 'Available') {
                flash('request_message', 'This time slot is no longer available.', 'alert alert-error');
                redirect('Supplier/viewAppointments');
                return;
            }
            
            // Prepare data for the request
            $data = [
                'supplier_id' => $_SESSION['supplier_id'],
                'slot_id' => trim($_POST['slot_id']),
                'status' => 'Pending',
                'submitted_at' => date('Y-m-d H:i:s')
            ];
            
            // Create the request
            if ($this->appointmentModel->createRequest($data)) {
                flash('request_message', 'Time slot requested successfully.');
            } else {
                flash('request_message', 'Failed to request time slot.', 'alert alert-error');
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
        $this->view('supplier/v_supplier_payment', []);
    }

    public function schedule() {
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

    public function paymentanalysis() {
        $data = [];
        $this->view('supplier/v_payment_analysis', $data);
    }

    public function profile() {
        $data = [];
        $this->view('supplier/v_profile', $data);
    }

    public function cancelpickup() {
        $data = [];
        $this->view('supplier/v_cancel_pickup', $data);
    }

    public function requestFertilizer() {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('login');
            return;
        }

        $data = [
            'fertilizer_types' => $this->fertilizerOrderModel->getAvailableFertilizerTypes(),
            'orders' => $this->fertilizerOrderModel->getOrdersBySupplier($supplier_id)
        ];

        $this->view('supplier/v_fertilizer_request', $data);
    }

    public function fertilizerOrders() {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'fertilizer_order_id' => $_POST['order_id'] ?? null,
                'supplier_id' => $supplier_id,
                'fertilizer_name' => $_POST['fertilizer_name'] ?? '',
                'quantity' => $_POST['quantity'] ?? 0,
                'total_amount' => $_POST['total_amount'] ?? 0,
                'order_date' => date('Y-m-d'),
                'order_time' => date('H:i:s'),
            ];

            if ($this->validateRequest($data)) {
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

        $data = [
            'fertilizer_types' => $this->fertilizerOrderModel->getAvailableFertilizerTypes(),
            'orders' => $this->fertilizerOrderModel->getOrdersBySupplier($supplier_id)
        ];

        $this->view('supplier/v_fertilizer_request', $data);
    }

    public function createFertilizerOrder() {
        header('Content-Type: application/json');
        $response = ['success' => false, 'message' => ''];

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            $required_fields = ['fertilizer', 'quantity'];
            foreach ($required_fields as $field) {
                if (!isset($_POST[$field]) || empty($_POST[$field])) {
                    throw new Exception("Missing required field: $field");
                }
            }

            $supplier_id = $_SESSION['user_id'] ?? null;
            if (!$supplier_id) {
                throw new Exception('Please log in to place an order');
            }

            $type_id = trim($_POST['fertilizer']);
            $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($type_id);

            if (!$fertilizer) {
                throw new Exception('Invalid fertilizer type');
            }

            $quantity = floatval($_POST['quantity']);

            if ($quantity <= 0 || $quantity > 50) {
                throw new Exception('Quantity must be between 1 and 50');
            }
            if ($quantity > $fertilizer->quantity) {
                throw new Exception("Requested quantity exceeds available stock. Only {$fertilizer->quantity} {$fertilizer->unit} available.");
            }

            $total_amount = $quantity * $fertilizer->price;

            $order_data = [
                'supplier_id' => $supplier_id,
                'type_id' => $type_id,
                'quantity' => $quantity,
                'total_amount' => $total_amount
            ];

            if ($this->fertilizerOrderModel->createOrder($order_data)) {
                $response['success'] = true;
                $response['message'] = 'Order placed successfully! Awaiting approval.';
            } else {
                throw new Exception('Failed to create order');
            }

        } catch (Exception $e) {
            $response['message'] = $e->getMessage();
        }

        echo json_encode($response);
        header("Refresh:1; url=" . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function editFertilizerRequest($order_id) {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('login');
            return;
        }

        $order = $this->fertilizerOrderModel->getOrderById($order_id);

        if (!$order || $order->supplier_id != $supplier_id) {
            flash('message', 'Order not found or unauthorized access', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        if ($order->status !== 'Pending') {
            flash('message', 'This order cannot be edited as it is not in Pending status', 'alert alert-danger');
            redirect('supplier/requestFertilizer');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $type_id = isset($_POST['type_id']) ? trim($_POST['type_id']) : '';
            $quantity = isset($_POST['quantity']) ? floatval($_POST['quantity']) : 0;

            if (empty($type_id) || $quantity <= 0 || $quantity > 50) {
                flash('message', 'Please check your inputs. Quantity should be between 1 and 50.', 'alert alert-danger');
                redirect('supplier/editFertilizerRequest/' . $order_id);
                return;
            }

            $fertilizer = $this->fertilizerOrderModel->getFertilizerByTypeId($type_id);

            if (!$fertilizer) {
                flash('message', 'Invalid fertilizer type selected.', 'alert alert-danger');
                redirect('supplier/editFertilizerRequest/' . $order_id);
                return;
            }

            if ($quantity > $fertilizer->quantity) {
                flash('message', "Requested quantity exceeds available stock. Only {$fertilizer->quantity} {$fertilizer->unit} available.", 'alert alert-danger');
                redirect('supplier/editFertilizerRequest/' . $order_id);
                return;
            }

            $total_amount = $quantity * $fertilizer->price;

            $updateData = [
                'type_id' => $type_id,
                'quantity' => $quantity,
                'total_amount' => $total_amount
            ];

            if ($this->fertilizerOrderModel->updateOrder($order_id, $updateData)) {
                flash('message', 'Fertilizer request updated successfully', 'alert alert-success');
                redirect('supplier/requestFertilizer');
            } else {
                flash('message', 'Failed to update request. Please try again.', 'alert alert-danger');
                redirect('supplier/editFertilizerRequest/' . $order_id);
            }
        } else {
            $data = [
                'order' => $order,
                'fertilizer_types' => $this->fertilizerOrderModel->getAvailableFertilizerTypes()
            ];
            $this->view('supplier/v_request_edit', $data);
        }
    }

    public function checkFertilizerOrderStatus($orderId) {
        if (!$this->isSupplierOrder($orderId)) {
            echo json_encode(['canDelete' => false, 'message' => 'Unauthorized access']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }

        $order = $this->fertilizerOrderModel->getOrderById($orderId);

        if (!$order) {
            echo json_encode(['canDelete' => false, 'message' => 'Order not found']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }

        $canDelete = ($order->status === 'Pending' && $order->payment_status === 'pending');

        echo json_encode([
            'canDelete' => $canDelete,
            'message' => $canDelete ? 'Order can be deleted' : 'Order cannot be deleted'
        ]);
    }

    public function deleteFertilizerRequest($orderId) {
        header('Content-Type: application/json');

        if (!$this->isSupplierOrder($orderId)) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }

        $order = $this->fertilizerOrderModel->getOrderById($orderId);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
            header("Refresh:2; url=" . $_SERVER['HTTP_REFERER']);
            return;
        }

        if ($order->status !== 'Pending' || $order->payment_status !== 'pending') {
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

    public function finalizeFertilizerOrder($order_id) {
        $supplier_id = $_SESSION['user_id'] ?? null;
        if (!$supplier_id) {
            flash('message', 'Please log in to access this page', 'alert alert-danger');
            redirect('login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $delivery_date = $_POST['delivery_date'] ?? '';
            if (empty($delivery_date)) {
                flash('message', 'Please provide a delivery date.', 'alert alert-danger');
                redirect("supplier/finalizeFertilizerOrder/$order_id");
            }

            $order = $this->fertilizerOrderModel->getOrderById($order_id);
            if ($order && $order->supplier_id == $supplier_id && $order->status == 'Accepted') {
                if ($this->fertilizerOrderModel->updateOrderWithDeliveryDate($order_id, $delivery_date)) {
                    flash('message', 'Order finalized successfully!', 'alert alert-success');
                    redirect('supplier/requestFertilizer');
                } else {
                    flash('message', 'Failed to finalize order.', 'alert alert-danger');
                }
            } else {
                flash('message', 'Order not found or not yet accepted.', 'alert alert-danger');
            }
        }

        $data['order_id'] = $order_id;
        $this->view('supplier/v_finalize_order', $data);
    }

    public function fertilizerhistory() {
        // Ensure supplier is logged in
        if (!isset($_SESSION['user_id'])) {
            flash('message', 'Please log in to view your order history', 'alert alert-danger');
            redirect('login');
            return;
        }

        // Fetch orders for the current supplier
        $orders = $this->fertilizerOrderModel->getOrdersBySupplier($_SESSION['user_id']);

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

    private function validateRequest($data) {
        $required_fields = ['quantity', 'total_amount'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                return false;
            }
        }

        if ($data['quantity'] <= 0 || $data['quantity'] > 50) {
            return false;
        }

        return true;
    }

    

    public function complaints() {
        $data = [];
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
                flash('complaint_success', 'Complaint submitted successfully');
                redirect('supplier/complaints');
            } else {
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


    

    public function viewMonthlyIncome() {
        $data = [
            'title' => 'Schedule Details'
        ];
        $this->view('shared/supplier/v_view_monthly_statement', $data);
    }

    // Form validation function 
    // private function validateRequest($data) {
    //     return !empty($data['supplier_id']) && !empty($data['totalamount']);
    // }

    
    
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

    // Chat-related methods

    public function chat() {
        // Ensure the user is logged in and has the Supplier role (role_id = 5)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 7) {
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

    //announcements by Theekshana
    public function announcements() {
        // Ensure the user is logged in and has the Supplier role (role_id = 5)
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 7) {
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

    public function getAnnouncementsForSupplier($supplierId) {
        // Fetch announcements created by Vehicle Managers (role_id = 4)
        $this->db->query("
            SELECT a.announcement_id, a.title, a.content, a.created_at, a.updated_at, 
                   CONCAT(u.first_name, ' ', u.last_name) AS sender_name
            FROM announcements a
            JOIN users u ON a.created_by = u.user_id
            WHERE u.role_id = 4
            ORDER BY a.created_at DESC
        ");
        return $this->db->resultSet();
    }
}