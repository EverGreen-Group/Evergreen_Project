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
    
        $this->view('vehicle_driver/v_dashboard', $data);
    }

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
        $schedule = $this->model('M_CollectionSchedule')->getScheduleById($id);
        if (!$schedule) {
            redirect('vehicledriver/shift');
        }

        $route = $this->model('M_Route')->getRouteById($schedule->route_id);
        $team = $this->model('M_Team')->getTeamById($schedule->team_id);
        $vehicle = $this->model('M_Vehicle')->getVehicleById($schedule->vehicle_id);

        $currentUserId = $_SESSION['user_id'];
        $userRole = RoleHelper::hasRole(RoleHelper::DRIVER) ? 'driver' : 
                   (RoleHelper::hasRole(RoleHelper::DRIVING_PARTNER) ? 'driving_partner' : null);

        $collectionId = $this->collectionModel->getCollectionIdByScheduleId($id);

        $collectionBags = $this->collectionModel->getCollectionBags($collectionId);
        if (!$collectionBags) {
            $collectionBags = [];
        }

        $data = [
            'schedule' => $schedule,
            'route' => $route,
            'team' => $team,
            'vehicle' => $vehicle,
            'userRole' => $userRole,
            'isReady' => $this->model('M_CollectionSchedule')->isUserReady($id, $currentUserId),
            'collectionBags' => $collectionBags
        ];

        // Add this to get route suppliers
        $routeSuppliers = $this->routeModel->getRouteSuppliers($data['route']->route_id);
        $data['routeSuppliers'] = $routeSuppliers;

        $data['collection'] = $this->collectionScheduleModel->getCollectionByScheduleId($id);

        // Check ready status for both team members
        $driverReady = $this->collectionModel->isDriverReady($id);
        $partnerReady = $this->collectionModel->isPartnerReady($id);

        $data['driverReady'] = $driverReady;
        $data['partnerReady'] = $partnerReady;

        $data['viewPath'] = 'shared/collection/schedule_details';
        $this->view($data['viewPath'], $data);
    }

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
        $collection = $this->collectionModel->getCollectionById($collectionId);
        if (!$collection) {
            redirect('vehicledriver/shift');
        }

        // Debug the date
        // var_dump($collection->start_time); // Check what format we're getting

        // Fix the date formatting
        $schedule = $this->collectionScheduleModel->getScheduleById($collection->schedule_id);
        if (!$schedule) {
            redirect('vehicledriver/shift');
        }

        // Use schedule's start time instead
        $shiftDateTime = date('Y-m-d') . ' ' . $schedule->start_time;
        
        if (!$this->checkShiftTime($shiftDateTime)) {
            redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
        }

        // Get schedule, team, and vehicle details
        $schedule = $this->collectionScheduleModel->getScheduleById($collection->schedule_id);
        $team = $this->teamModel->getTeamById($schedule->team_id);
        $vehicle = $this->vehicleModel->getVehicleById($schedule->vehicle_id);

        // Replace hardcoded location with actual driver location
        $driverLocation = $this->getCurrentDriverLocation();

        // Get all suppliers for this collection
        $collectionSuppliers = $this->collectionScheduleModel->getCollectionSupplierRecords($collectionId);

        // Format suppliers for the view
        $formattedSuppliers = array_map(function($supplier) {
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
                'estimatedCollection' => 500,
                'status' => $supplier->status,
                'contact' => $supplier->contact_number,
                'arrival_time' => $supplier->arrival_time,
            ];
        }, $collectionSuppliers);

        $data = [
            'pageTitle' => 'Collection Route',
            'driverName' => $team->driver_name,
            'teamName' => $team->team_name,
            'vehicleInfo' => $vehicle->vehicle_type . ' (' . $vehicle->license_plate . ')',
            'driverLocation' => $driverLocation,
            'collections' => $formattedSuppliers,
            'schedule' => $schedule,
            'collection' => $collection
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

    public function startCollection($collectionId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $collection = $this->collectionModel->getCollectionById($collectionId);
                if (!$collection) {
                    error_log("Collection not found: " . $collectionId);
                    redirect('vehicledriver/a');
                    return;
                }

                // Check if all conditions are met
                if (!$collection->vehicle_manager_approved || 
                    !$collection->partner_approved || 
                    !$collection->initial_weight_bridge || 
                    !$collection->bags) {
                    error_log("Conditions not met for collection: " . $collectionId);
                    redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
                    return;
                }

                // Get route suppliers first
                $schedule = $this->collectionScheduleModel->getScheduleById($collection->schedule_id);
                if (!$schedule) {
                    error_log("Schedule not found for collection: " . $collectionId);
                    redirect('vehicledriver/');
                    return;
                }

                $routeSuppliers = $this->routeModel->getRouteSuppliers($schedule->route_id);
                if (empty($routeSuppliers)) {
                    error_log("No route suppliers found for route: " . $schedule->route_id);
                    redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
                    return;
                }

                // Debug output
                error_log("Starting collection: " . $collectionId);
                error_log("Route suppliers count: " . count($routeSuppliers));

                // Start collection and create supplier records
                if ($this->collectionModel->startCollectionAndCreateRecords($collectionId, $routeSuppliers)) {
                    error_log("Collection started successfully: " . $collectionId);
                    redirect('vehicledriver/collection/' . $collectionId);
                } else {
                    error_log("Failed to start collection: " . $collectionId);
                    redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
                }

            } catch (Exception $e) {
                error_log("Error in startCollection: " . $e->getMessage());
                redirect('vehicledriver/scheduleDetails/' . $collection->schedule_id);
            }
        } else {
            redirect('vehicledriver/shift');
        }
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
            if (!$record->arrival_time) {
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
}

?>
