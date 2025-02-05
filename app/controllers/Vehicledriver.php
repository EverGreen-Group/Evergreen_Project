<?php
// Update the include to use APPROOT instead of URLROOT
include_once APPROOT . '/services/GoogleMapsService.php';

class VehicleDriver extends controller {
    private $collectionScheduleModel;
    private $driverModel;
    private $teamModel;
    private $vehicleModel;
    private $routeModel;
    private $collectionModel;
    private $googleMapsService;
    private $scheduleModel;

    public function __construct() {
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::DRIVER])) {
            // Redirect unauthorized access
            redirect('');
            exit();
        }

        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
        $this->collectionModel = $this->model('M_Collection');
        $this->driverModel = $this->model('M_Driver');
        $this->teamModel = $this->model('M_Team');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->routeModel = $this->model('M_Route');
        $this->googleMapsService = new GoogleMapsService();
        $this->scheduleModel = $this->model('M_CollectionSchedule');
    }

    public function index() {
        if (!isset($_SESSION['driver_id'])) {
            redirect('login'); // Assuming you have a redirect helper
            return;
        }
    
        $driverId = $_SESSION['driver_id'];
        
        try {
            // Get all schedules
            $allSchedules = $this->scheduleModel->getUpcomingSchedules($driverId);
            
            // Organize schedules by day
            $todaySchedules = [];
            $upcomingSchedules = [];
            
            foreach ($allSchedules as $schedule) {
                if ($schedule->is_today) {
                    $todaySchedules[] = $schedule;
                } else {
                    $upcomingSchedules[] = $schedule;
                }
            }
            
            // Get driver details (assuming you have a driver model)
            // $driverDetails = $this->driverModel->getDriverById($driverId);
            
            $data = [
                'todaySchedules' => $todaySchedules,
                'upcomingSchedules' => $upcomingSchedules,
                // 'driverDetails' => $driverDetails,
                'currentWeek' => date('W'),
                'currentDay' => date('l'),
                'lastUpdated' => date('Y-m-d H:i:s'),
                'message' => '',
                'error' => ''
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
                'error' => 'An error occurred while fetching schedules. Please try again later.'
            ];
        }
    
        // Add refresh interval for automatic updates (e.g., every 5 minutes)
        $data['refreshInterval'] = 300; // 5 minutes in seconds
        
        $this->view('vehicle_driver/v_dashboard', $data);
    }

    // protected function isAjaxRequest() {
    //     return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    // }

    public function profile() {
        $data = [];
        $this->view('pages/profile', $data);
    }

    public function team() {
        $data = [];
        $this->view('vehicle_driver/v_team', $data);
    }

    public function route() {
        $data = [];
        $this->view('vehicle_driver/v_route', $data);
    }

    public function shift() {
        $driverModel = $this->model('M_Driver');
        $scheduleModel = $this->model('M_CollectionSchedule');
        
        // Get driver's team ID
        $driverDetails = $driverModel->getDriverDetails($_SESSION['user_id']);
        $teamId = $driverDetails->team_id ?? null;
    
        if (!$teamId) {
            $data = [
                'upcomingShifts' => [],
                'message' => 'No team assigned'
            ];
        } else {
            // Get upcoming schedules for the team
            $upcomingShifts = $scheduleModel->getUpcomingSchedules($teamId);
            $data = [
                'upcomingShifts' => $upcomingShifts,
                'currentTeam' => $driverDetails->current_team
            ];
        }
    
        $this->view('shared/management/shift', $data);
    }
    
    public function scheduleDetails($id) {
        // Get schedule details by ID
        $schedule = $this->model('M_CollectionSchedule')->getScheduleById($id);
        if (!$schedule) {
            redirect('vehicledriver/shift');
        }
    
        // Get related route and vehicle information
        $route = $this->model('M_Route')->getRouteById($schedule->route_id);
        $vehicle = $this->model('M_Vehicle')->getVehicleByRouteId($schedule->vehicle_id);
    
        // Get collection details for the schedule
        $collectionId = $this->collectionModel->getUpcomingCollectionIdByScheduleId($id);
        $collections = $collectionId ? $this->collectionModel->getUpcomingCollectionDetailsByScheduleId($id) : [];
        // $collection = isset($collections[0]) ? $collections[0] : null;
    
        // Get route suppliers for the route
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($route->route_id);
    
        // Prepare default values for collection-related data
        $collectionBags = $collections ? $this->collectionModel->getCollectionBagsByCollectionId($collectionId) : [];
        $bagsAdded = $collection->bags_added ?? 0;
        $fertilizerDistributed = $collection->fertilizer_distributed ?? 0;
        $collectionCompleted = (is_object($collections) && isset($collection->end_time) && $collections->end_time !== null) ? true : false;

    
        // Prepare data to pass to the view
        $data = [
            'schedule' => $schedule,
            'route' => $route,
            'vehicle' => $vehicle,
            'collectionBags' => $collectionBags,
            'collection' => (object) $collections,
            'routeSuppliers' => $routeSuppliers,
            'bagsAdded' => $bagsAdded,
            'fertilizerDistributed' => $fertilizerDistributed,
            'collectionCompleted' => $collectionCompleted
        ];
    
        // Render the view with the data
        $this->view('vehicle_driver/v_schedule_details', $data);
    }


    // public function scheduleDetails($id) {
    //     // Get schedule details by ID
    //     $schedule = $this->model('M_CollectionSchedule')->getScheduleById($id);
    //     if (!$schedule) {
    //         redirect('vehicledriver/shift');
    //     }
    
    //     // Get related route and vehicle information
    //     $route = $this->model('M_Route')->getRouteById($schedule->route_id);
    //     $vehicle = $this->model('M_Vehicle')->getVehicleByRouteId($schedule->vehicle_id);
    
    //     // Get collection details for the schedule
    //     $collection = $this->collectionModel->getUpcomingCollectionDetailsByScheduleId($id);
        
    //     // Get route suppliers for the route
    //     $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($route->route_id);
    
    //     // Prepare default values for collection-related data
    //     $bagsAdded = $collection->bags_added ?? 0;
    //     $fertilizerDistributed = $collection->fertilizer_distributed ?? 0;
    //     $collectionCompleted = $collection && $collection->end_time !== null;
    
    //     // Prepare data to pass to the view
    //     $data = [
    //         'schedule' => $schedule,
    //         'route' => $route,
    //         'vehicle' => $vehicle,
    //         'collection' => $collection,
    //         'routeSuppliers' => $routeSuppliers,
    //         'bagsAdded' => $bagsAdded,
    //         'fertilizerDistributed' => $fertilizerDistributed,
    //         'collectionCompleted' => $collectionCompleted
    //     ];
    
    //     // Render the view with the data
    //     $this->view('vehicle_driver/v_schedule_details', $data);
    // }
    
    

    private function checkShiftTime($scheduleTime, $windowMinutes = 10) {
        try {
            $scheduleDateTime = new DateTime($scheduleTime);
            $now = new DateTime();
            $diff = ($scheduleDateTime->getTimestamp() - $now->getTimestamp()) / 60;
            return $diff <= $windowMinutes && $diff >= -360;
        } catch (Exception $e) {
            // Log the error
            error_log("Date parsing error: " . $e->getMessage());
            return false;
        }
    }

    public function v_collection_route() {
        $data = [];
        $this->view('vehicle_driver/v_collection_route', $data);
    }

    public function setReady($scheduleId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('vehicledriver/a');
        }

        $schedule = $this->scheduleModel->getScheduleById($scheduleId);
        if (!$schedule) {
            redirect('vehicledriver/b');
        }

        // Check if within time window
        $shiftDateTime = date('Y-m-d ') . $schedule->start_time;
        if (!$this->checkShiftTime($shiftDateTime)) {
            redirect('vehicledriver/scheduleDetails/' . $scheduleId);
        }

        $currentUserId = $_SESSION['user_id'];
        
        // First check if collection exists, if not create it
        $collection = $this->collectionScheduleModel->getCollectionByScheduleId($scheduleId);
        if (!$collection) {
            $this->collectionScheduleModel->createInitialCollection($scheduleId);
        }
        
        // Then set the user as ready
        $this->collectionScheduleModel->setUserReady($scheduleId, $currentUserId);
        redirect('vehicledriver/scheduleDetails/' . $scheduleId);
    }

    public function staff() {
        $data = [];
        $this->view('vehicle_driver/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('vehicle_driver/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('vehicle_driver/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }

    public function collection($collectionId) {
        $collection = $this->collectionModel->getCollectionDetails($collectionId);
        // if (!$collection) {
        //     redirect('vehicledriver/shift');
        // }

        // Replace hardcoded location with actual driver location
        $driverLocation = $this->getCurrentDriverLocation();
        $vehicleLocation = $this->vehicleModel->getVehicleLocation($collection->vehicle_id);

        // Get all suppliers for this collection
        $collectionSuppliers = $this->collectionScheduleModel->getCollectionSupplierRecords($collectionId);

        $leafTypesResult = $this->collectionModel->getCollectionTeaLeafTypes();
        $leafTypes = $leafTypesResult['success'] ? $leafTypesResult['leafTypes'] : [];

        $currentSupplier = null;
        foreach ($collectionSuppliers as $record) {
            if (!$record->arrival_time && $record->status != 'Collected') {
                // Convert the object to an associative array
                $currentSupplier = [
                    'id' => $record->supplier_id,
                    'supplierName' => $record->supplier_name,
                    'remarks' => 'Call upon arrival',
                    'location' => [
                        'lat' => (float)$record->latitude,
                        'lng' => (float)$record->longitude
                    ],
                    'address' => $record->address ?? 'No address provided',
                    'image' => $record->profile_image ? 
                        URLROOT . '/public/uploads/supplier_photos/' . $record->profile_image : 
                        URLROOT . '/public/img/default-user.png',
                    'estimatedCollection' => $record->average_collection,
                    'status' => $record->status,
                    'contact' => $record->contact_number,
                    'arrival_time' => $record->arrival_time,
                ];
                break;
            }
        }

        // ADDED ARRAY_VALLUES AND IT WORKEEEEEEEEEED!!!!

            $formattedSuppliers = array_values(array_filter(array_map(function($supplier) {
                return [
                    'id' => $supplier->supplier_id,
                    'supplierName' => $supplier->supplier_name,
                    'remarks' => 'Call upon arrival',
                    'location' => [
                        'lat' => (float)$supplier->latitude,
                        'lng' => (float)$supplier->longitude
                    ],
                    'address' => $supplier->address ?? 'No address provided',
                    'image' => $supplier->profile_image ? 
                        URLROOT . '/public/uploads/supplier_photos/' . $supplier->profile_image : 
                        URLROOT . '/public/img/default-user.png',
                    'estimatedCollection' => $supplier->average_collection,
                    'status' => $supplier->status,
                    'contact' => $supplier->contact_number,
                    'arrival_time' => $supplier->arrival_time,
                    'stop_order' => $supplier->stop_order
                ];
            }, $collectionSuppliers), function($supplier) {
                return $supplier['status'] !== 'Collected';
            }));

        $data = [
            'pageTitle' => 'Collection Route',
            'driverName' => $collection->first_name,
            'vehicleInfo' => 'TEST VEHICLE',
            'driverLocation' => $driverLocation,
            'collections' => $formattedSuppliers,
            'collection' => $collection,
            'vehicleLocation' => $vehicleLocation,
            'currentSupplier' => $currentSupplier,
            'leafTypes' => $leafTypes
        ];

        $this->view('vehicle_driver/v_collection_route', $data);
    }

    public function markArrival() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get JSON data from request body
            $jsonData = json_decode(file_get_contents('php://input'), true);
            
            $collection_id = $jsonData['collection_id'];
            $supplier_id = $jsonData['supplier_id'];
            
            $data = [
                'collection_id' => $collection_id,
                'supplier_id' => $supplier_id,
                'arrival_time' => date('Y-m-d H:i:s')
            ];
            
            if ($this->collectionModel->updateArrivalTime($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update arrival time']);
            }
        }
    }


    // Add this new method to get current driver location
    private function getCurrentDriverLocation() {
        // If you have a stored location in session or database, retrieve it
        // Otherwise, get it from browser geolocation
        return [
            'lat' => $_SESSION['driver_lat'] ?? null,
            'lng' => $_SESSION['driver_lng'] ?? null
        ];
    }

    public function markSupplierArrival($collectionId, $supplierId, $arrivalTime, $latitude = null, $longitude = null) {
        $sql = "UPDATE collection_supplier_records 
                SET arrival_time = :arrival_time,
                    latitude = :latitude,
                    longitude = :longitude
                WHERE collection_id = :collection_id 
                AND supplier_id = :supplier_id";
                
        $params = [
            ':arrival_time' => $arrivalTime,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':collection_id' => $collectionId,
            ':supplier_id' => $supplierId
        ];
        
        return $this->db->query($sql, $params);
    }

    public function leave() {
        $userId = $_SESSION['user_id'];
        $leaveModel = $this->model('M_Leave');
        
        // Get leave balances first
        $leaveBalances = $leaveModel->getLeaveBalance($userId);
        
        $data = [
            'title' => 'Leave Management',
            'leaveBalance' => (object)[
                'annual' => $this->findLeaveBalanceByName($leaveBalances, 'Annual Leave'),
                'sick' => $this->findLeaveBalanceByName($leaveBalances, 'Sick Leave')
            ],
            'pendingLeaveCount' => $leaveModel->getPendingLeaveCount($userId),
            'leaveHistory' => $leaveModel->getLeaveHistory($userId),
            'leaveTypes' => $leaveModel->getLeaveTypes(),
            'availableSwapUsers' => $leaveModel->getAvailableSwapUsers($userId, $_SESSION['role_id']),
            'swapRequests' => $leaveModel->getSwapRequests($userId)
        ];
        
        $this->view('vehicle_driver/v_leave', $data);
    }

    public function handle_swap_request() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('vehicledriver/leave');
        }

        $jsonData = json_decode(file_get_contents('php://input'), true);
        $leaveModel = $this->model('M_Leave');
        
        $success = $leaveModel->updateSwapRequest(
            $jsonData['requestId'], 
            $jsonData['action'], 
            $_SESSION['user_id']
        );

        echo json_encode(['success' => $success]);
    }

    // Add this helper method
    private function findLeaveBalanceByName($balances, $leaveName) {
        if (!is_array($balances) && !is_object($balances)) {
            return 0;
        }
        foreach ($balances as $balance) {
            if ($balance->name === $leaveName) {
                return $balance->remaining_days;
            }
        }
        return 0;
    }


    public function collectionRoute($collectionId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
                if (!$collection) {
                    redirect('vehicledriver/');
                }

                // Verify conditions before starting
                if (!$collection->vehicle_manager_approved || 
                    !$collection->partner_approved || 
                    !$collection->initial_weight_bridge || 
                    !$collection->bags) {
                    redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
                }

                // Start the collection
                if ($this->collectionScheduleModel->startCollection($collectionId)) {
                    $routeSuppliers = $this->routeModel->getRouteSuppliers($collection->schedule_id);
                    $collectionSupplierRecords = $this->collectionScheduleModel->getCollectionSuppliers($collectionId);
                    $data = [
                        'collection' => $collection,
                        'routeSuppliers' => $routeSuppliers,
                        'collectionSupplierRecords' => $collectionSupplierRecords
                    ];
                    $this->view('vehicle_driver/v_collection_route', $data);
                } else {
                    redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
                }
            } catch (Exception $e) {
                redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
            }
        } else {
            // Handle GET request
            $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
            if ($collection && $collection->start_time) {
                $routeSuppliers = $this->routeModel->getRouteSuppliers($collection->schedule_id);
                $collectionSupplierRecords = $this->collectionScheduleModel->getCollectionSuppliers($collectionId);
                $data = [
                    'collection' => $collection,
                    'routeSuppliers' => $routeSuppliers,
                    'collectionSupplierRecords' => $collectionSupplierRecords
                ];
                $this->view('vehicle_driver/v_collection_route', $data);
            } else {
                redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
            }
        }
    }

    public function setDriverReady($collectionId, $scheduleId) {
        $this->collectionModel->setDriverReady($collectionId);
        redirect('vehicledriver/scheduleDetails/' . $scheduleId);

    }

    public function collectionStatus($collectionId) {
        $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
        if (!$collection) {
            redirect('vehicledriver/shift');
        }

        // Get all supplier records for this collection
        $collectionSupplierRecords = $this->collectionScheduleModel->getCollectionSuppliers($collectionId);
        
        // Find the current supplier (first one without arrival_time)
        $currentSupplier = null;
        $currentStopNumber = 0;
        foreach ($collectionSupplierRecords as $index => $record) {
            if (!$record->arrival_time and $record->status != 'Collected') {
                $currentSupplier = $record;
                $currentStopNumber = $index + 1;
                break;
            }
        }

        $data = [
            'collection' => $collection,
            'currentSupplier' => $currentSupplier,
            'currentStopNumber' => $currentStopNumber,
            'collectionSupplierRecords' => $collectionSupplierRecords
        ];

        $this->view('vehicle_driver/v_collection_status', $data);
    }


    public function setReadyTest() {
        $data = [];
        $this->view('v_schedule_details.php', $data);
    }


    public function approveTemp($collectionId) {
        // Load the collection model
        $this->collectionModel = $this->model('M_Collection');

        // Prepare the data to update
        $data = [
            'collection_id' => $collectionId,
            // 'status' => 'In Progress',
            'status' => 'Pending',
            'vehicle_manager_approved' => 1,
            'initial_weight_bridge' => 13000,
            'vehicle_manager_id' => 5, // Temporary vehicle manager ID
            'vehicle_manager_approved_at' => date('Y-m-d H:i:s') // Current datetime
        ];

        // Update the collection in the database
        if ($this->collectionModel->updateCollection($data)) {
            // Redirect or set a success message
            redirect('vehicledriver/scheduleDetails/' . $collectionId);
        } else {
            // Handle the error (e.g., set an error message)
            redirect('vehicledriver/shift'); // Redirect to a suitable page on failure
        }
    }

    public function assignBags() {
        error_log('assignBags method called');
        error_log('Request Method: ' . $_SERVER['REQUEST_METHOD']);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get schedule_id and bags from POST data
            $scheduleId = $_POST['schedule_id'] ?? null;
            $bags = $_POST['bags'] ?? [];
            
            // Debug: Log the incoming data
            error_log(print_r($_POST, true));
            
            if (!$scheduleId || empty($bags)) {
                flash('schedule_message', 'Missing schedule ID or no bags were selected', 'alert alert-danger');
                redirect('vehicledriver/scheduleDetails/' . $scheduleId);
                return;
            }

            // Attempt to create collection with bags
            if ($this->collectionModel->createCollectionWithBags($scheduleId, $bags)) {
                flash('schedule_message', 'Bags assigned successfully', 'alert alert-success');
            } else {
                flash('schedule_message', 'Failed to assign bags', 'alert alert-danger');
            }
            
            redirect('vehicledriver/scheduleDetails/' . $scheduleId);
        } else {
            redirect('vehicledriver');
        }
    }

    public function checkBag($bagId) {
        $result = $this->collectionModel->checkBag($bagId);
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function updateVehicleLocation() {
        if (!$this->isAjaxRequest()) {
            redirect('pages/error');
            return;
        }

        $data = json_decode(file_get_contents('php://input'));
        
        if (!$data || !isset($data->collection_id, $data->latitude, $data->longitude)) {
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            return;
        }

        try {
            // Get vehicle ID from collection -> schedule -> route -> vehicle chain
            $vehicleId = $this->collectionModel->getVehicleIdFromCollection($data->collection_id);
            
            if (!$vehicleId) {
                throw new Exception('Vehicle not found');
            }

            // Update vehicle location
            $result = $this->vehicleModel->updateLocation(
                $vehicleId, 
                $data->latitude, 
                $data->longitude
            );

            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Location updated' : 'Failed to update location'
            ]);

        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function submitCollection() {
        if (!$this->isAjaxRequest()) {
            redirect('pages/error');
            return;
        }

        $data = json_decode(file_get_contents('php://input'));
        
        $result = $this->collectionModel->addCollection($data);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function addBagToCollection() {
        $data = json_decode(file_get_contents("php://input"));

        // Validate input data
        // ...

        // Call the model to add bag usage history
        $result = $this->collectionModel->addBagUsageHistory($data);

        echo json_encode($result);
    }

    public function finalizeCollection() {
        // if (!$this->isAjaxRequest()) {
        //     redirect('pages/error');
        //     return;
        // }

        $data = json_decode(file_get_contents('php://input'));
        
        // Update collection_supplier_records
        $result = $this->collectionModel->finalizeSupplierCollection($data);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function getAssignedBags($supplierId) {
        // if (!$this->isAjaxRequest()) {
        //     redirect('pages/error');
        //     return;
        // }

        $result = $this->collectionModel->getAssignedBags($supplierId);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }

    public function createCollection($scheduleId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Logic to create a collection
            $collectionId = $this->collectionModel->createCollection($scheduleId);

            if ($collectionId) {
                flash('schedule_message', 'Collection created successfully. Awaiting vehicle manager approval.', 'alert alert-success');
                redirect('vehicledriver/scheduleDetails/' . $scheduleId);
            } else {
                flash('schedule_message', 'Failed to create collection', 'alert alert-danger');
                redirect('vehicledriver/scheduleDetails/' . $scheduleId);
            }
        } else {
            // If not a POST request, show the form or redirect
            redirect('vehicledriver/scheduleDetails/' . $scheduleId);
        }
    }

    public function endCollection() {
        // Get the JSON input
        $data = json_decode(file_get_contents("php://input"));
    
        if (isset($data->collection_id)) {
            // Call the model method to finalize the collection
            $result = $this->collectionModel->finalizeCollection($data->collection_id);
    
            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => 'Collection ended successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to end collection.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid collection ID.']);
        }
    }
}

?>