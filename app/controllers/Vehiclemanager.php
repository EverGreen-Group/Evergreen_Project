<?php
require_once '../app/models/M_VehicleManager.php';
require_once '../app/models/M_Route.php';      // Add Route model
require_once '../app/models/M_Team.php';       // Add Team model
require_once '../app/models/M_Vehicle.php';    // Add Vehicle model
require_once '../app/models/M_Shift.php';      // Add Shift model
require_once '../app/models/M_CollectionSchedule.php';  // Add CollectionSchedule model
require_once '../app/models/M_Staff.php';
require_once '../app/models/M_Driver.php';
require_once '../app/models/M_Partner.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/UserHelper.php';
require_once '../app/models/M_Collection.php';    // Add Collection model
require_once '../app/models/M_CollectionSupplierRecord.php';

class VehicleManager extends Controller {
    private $vehicleManagerModel;
    private $routeModel;       // Declare a variable for Route model
    private $teamModel;        // Declare a variable for Team model
    private $vehicleModel;     // Declare a variable for Vehicle model
    private $shiftModel;       // Declare a variable for Shift model
    private $scheduleModel;     // Declare a variable for CollectionSchedule model
    private $driverModel; // Declare a variable for Driver model
    private $partnerModel; // Add this line
    private $staffModel;
    private $userHelper;
    private $collectionModel;
    private $collectionSupplierRecordModel;
    

    public function __construct() {
        // Check if user is logged in
        requireAuth();
        
        // Check if user has Vehicle Manager OR Admin role
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            // Redirect unauthorized access
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }
        

        // Initialize models
        $this->vehicleManagerModel = new M_VehicleManager();
        $this->routeModel = new M_Route();        // Instantiate Route model
        $this->teamModel = new M_Team();          // Instantiate Team model
        $this->vehicleModel = new M_Vehicle();    // Instantiate Vehicle model
        $this->shiftModel = new M_Shift();        // Instantiate Shift model
        $this->scheduleModel = new M_CollectionSchedule();  // Instantiate CollectionSchedule model
        $this->driverModel = new M_Driver(); // Instantiate Driver model
        $this->partnerModel = new M_Partner(); // Add this line
        $this->staffModel = $this->model('M_Staff');
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
    }

    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    public function index() {
        // Get dashboard stats from the model
        $stats = $this->vehicleManagerModel->getDashboardStats();

        // Fetch all necessary data for the dropdowns
        $routes = $this->routeModel->getAllRoutes();
        $teams = $this->teamModel->getAllTeams();
        $vehicles = $this->vehicleModel->getAllVehicles();
        $shifts = $this->shiftModel->getAllShifts();
        $schedules = $this->scheduleModel->getAllSchedules();
        $ongoingCollections = $this->collectionModel->getOngoingCollections();

        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_collection', [
            'stats' => $stats,
            'routes' => $routes,
            'teams' => $teams,
            'vehicles' => $vehicles,
            'shifts' => $shifts,
            'schedules' => $schedules,
            'ongoing_collections' => $ongoingCollections
        ]);
    }

    public function getSupplierRecords($collectionId) {
        $records = $this->collectionSupplierRecordModel->getSupplierRecords($collectionId);
        echo json_encode($records);
    }
    
    public function updateSupplierRecord() {
        $data = json_decode(file_get_contents('php://input'));
        $success = $this->collectionSupplierRecordModel->updateSupplierRecord($data);
        echo json_encode(['success' => $success]);
    }
    
    public function addSupplierRecord() {
        $data = json_decode(file_get_contents('php://input'));
        $success = $this->collectionSupplierRecordModel->addSupplierRecord($data);
        echo json_encode(['success' => $success]);
    }

    // Add method to handle the assignment of collections
    public function createSchedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'route_id' => $_POST['route_id'],
                'team_id' => $_POST['team_id'],
                'vehicle_id' => $_POST['vehicle_id'],
                'shift_id' => $_POST['shift_id'],
                'week_number' => $_POST['week_number'],
                'days_of_week' => isset($_POST['days_of_week']) ? implode(',', $_POST['days_of_week']) : ''
            ];

            // Call the model to create a new schedule
            $result = $this->scheduleModel->create($data);

            if ($result) {
                flash('schedule_success', 'Schedule created successfully');
                redirect('vehiclemanager/index');
            } else {
                flash('schedule_error', 'Error creating schedule');
                redirect('vehiclemanager/index');
            }
        }
    }
    
    

    // Other methods remain unchanged
    public function vehicle() {
        $data = [
            'totalVehicles' => $this->vehicleModel->getTotalVehicles(),
            'availableVehicles' => $this->vehicleModel->getAvailableVehicles(),
            'vehicles' => $this->vehicleModel->getVehicleDetails(),
            'vehicleTypeStats' => $this->vehicleModel->getVehicleTypeStats()
        ];

        $this->view('vehicle_manager/v_vehicle', $data);
    }

    public function team() {
        $teamStats = $this->teamModel->getTeamStatistics();
        $teams = $this->teamModel->getTeamsWithMembers();
        $unassignedDrivers = $this->teamModel->getUnassignedDrivers(); // Fetch unassigned drivers
        $unassignedPartners = $this->teamModel->getUnassignedPartner(); // Fetch unassigned partners

        $data = [
            'teamStats' => $teamStats,
            'teams' => $teams,
            'unassigned_drivers' => $unassignedDrivers, // Add unassigned drivers to the data array
            'unassigned_partners' => $unassignedPartners // Add unassigned partners to the data array
        ];
        
        $this->view('vehicle_manager/v_team', $data);
    }

    public function updateTeam() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'team_id' => trim($_POST['team_id']),
                'team_name' => trim($_POST['team_name']),
                'driver_id' => trim($_POST['driver_id']),
                'partner_id' => trim($_POST['partner_id']),
                'status' => trim($_POST['status'])
            ];

            if (empty($data['team_name'])) {
                $_SESSION['flash_messages']['team_message'] = [
                    'message' => 'Please enter team name',
                    'class' => 'alert alert-danger'
                ];
                redirect('vehiclemanager/team');
                return;
            }

            if ($this->teamModel->updateTeam($data)) {
                $_SESSION['flash_messages']['team_message'] = [
                    'message' => 'Team updated successfully',
                    'class' => 'alert alert-success'
                ];
            } else {
                $_SESSION['flash_messages']['team_message'] = [
                    'message' => 'Failed to update team',
                    'class' => 'alert alert-danger'
                ];
            }
            redirect('vehiclemanager/team');
        }
    }

    public function createTeam() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get manager_id using helper
            $manager_id = $this->userHelper->getManagerId($_SESSION['user_id']);
            if (!$manager_id) {
                die('Invalid manager access');
            }

            $data = [
                'team_name' => trim($_POST['team_name']),
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'partner_id' => !empty($_POST['partner_id']) ? trim($_POST['partner_id']) : null,
                'status' => trim($_POST['status']),
                'manager_id' => $this->userHelper->getManagerId($_SESSION['user_id'])
            ];

            // Validate team name
            if (empty($data['team_name'])) {
                die('Please enter team name');
            }

            // Debug line - remove in production
            error_log('Creating team with data: ' . print_r($data, true));

            if ($this->teamModel->createTeam($data)) {
                $_SESSION['flash_messages'] = [
                    'team_message' => [
                        'message' => 'Team created successfully',
                        'class' => 'alert alert-success'
                    ]
                ];
            } else {
                $_SESSION['flash_messages'] = [
                    'team_message' => [
                        'message' => 'Failed to create team',
                        'class' => 'alert alert-danger'
                    ]
                ];
            }
            redirect('vehiclemanager/team');
        }
    }

    public function route() {
        $allRoutes = $this->routeModel->getAllRoutes();
        $totalRoutes = $this->routeModel->getTotalRoutes();
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();
        $unallocatedSuppliers = $this->routeModel->getUnallocatedSuppliers();
        
        // Format suppliers for the map/dropdown
        $suppliersForMap = array_map(function($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'name' => $supplier->full_name, // Changed from supplier_name to full_name
                'preferred_day' => $supplier->preferred_day, // Include preferred_day
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ],
                'average_collection' => $supplier->average_collection,
                'number_of_collections' => $supplier->number_of_collections

            ];
        }, $unallocatedSuppliers);

        $data = [
            'allRoutes' => $allRoutes,
            'totalRoutes' => $totalRoutes,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'unallocatedSuppliers' => $suppliersForMap,
            'unassignedSuppliersList' => $unallocatedSuppliers
        ];

        $this->view('vehicle_manager/v_route', $data);
    }

    public function shift() {
        // Handle POST request for creating new shift
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Validate and sanitize input
                $data = [
                    'shift_name' => trim($_POST['shift_name']),
                    'start_time' => trim($_POST['start_time']),
                    'end_time' => trim($_POST['end_time'])
                ];

                // Check for duplicate shift name
                if ($this->shiftModel->isShiftNameDuplicate($data['shift_name'])) {
                    flash('shift_error', 'Shift name already exists', 'alert alert-danger');
                    redirect('vehiclemanager/shift');
                    return;
                }

                // Use addShift instead of createShift
                if ($this->shiftModel->addShift($data)) {
                    flash('shift_success', 'New shift created successfully');
                } else {
                    // Get specific error message from model
                    flash('shift_error', $this->shiftModel->getError() ?? 'Failed to create shift');
                }
                redirect('vehiclemanager/shift');
                return;
            } catch (Exception $e) {
                flash('shift_error', 'Error: ' . $e->getMessage());
                redirect('vehiclemanager/shift');
                return;
            }
        }

        // GET request - display shifts page
        $shifts = $this->shiftModel->getAllShifts();
        $totalShifts = $this->shiftModel->getTotalShifts();
        $totalTeamsInCollection = $this->teamModel->getTotalTeamsInCollection();
        
        // Initialize the schedules array
        $schedules = [];

        // Define the date range for the next 7 days
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+6 days'));

        // Fetch schedules for each shift within the date range
        foreach ($shifts as $shift) {
            // Fetch schedules for the specific shift
            $shiftSchedules = $this->scheduleModel->getSchedulesByShiftIdAndDate($shift->shift_id, $startDate, $endDate);
            
            // Organize schedules by date
            foreach ($shiftSchedules as $schedule) {
                $date = date('Y-m-d', strtotime($schedule->created_at)); // Assuming schedule has a created_at field
                if (!isset($schedules[$shift->shift_id][$date])) {
                    $schedules[$shift->shift_id][$date] = [];
                }
                $schedules[$shift->shift_id][$date][] = $schedule; // Add the schedule to the appropriate date
            }
        }

        // Prepare data to pass to the view
        $data = [
            'shifts' => $shifts,
            'totalShifts' => $totalShifts,
            'totalTeamsInCollection' => $totalTeamsInCollection,
            'schedules' => $schedules // Pass the organized schedules to the view
        ];
        
        // Load the view with the data
        $this->view('vehicle_manager/v_shift', $data);
    }

    // Add method for handling shift deletion
    public function deleteShift($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->shiftModel->deleteShift($id)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'error' => 'Failed to delete shift']);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
            exit;
        }
    }

    public function getShift($id) {
        header('Content-Type: application/json'); // Set the content type to JSON
        try {
            $shift = $this->shiftModel->getShiftById($id);
            if ($shift) {
                echo json_encode($shift);
            } else {
                echo json_encode(['error' => 'Shift not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit; // Ensure no additional output is sent
    }

    public function updateShift($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'shift_id' => $id,
                'shift_name' => trim($_POST['shift_name']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time'])
            ];

            try {
                if ($this->shiftModel->updateShift($data)) {
                    flash('shift_success', 'Shift updated successfully');
                } else {
                    flash('shift_error', $this->shiftModel->getError(), 'alert alert-danger');
                }
            } catch (Exception $e) {
                flash('shift_error', $e->getMessage(), 'alert alert-danger');
            }
            redirect('vehiclemanager/shift');
        }
    }

    public function staff() {
        // Get manager_id and add debug logging
        $manager_id = $this->staffModel->getManagerIdByUserId($_SESSION['user_id']);
        error_log("Controller got manager_id: " . $manager_id);
        
        $data = [
            'drivers' => $this->staffModel->getAllDrivers(),
            'partners' => $this->staffModel->getAllPartners(),
            'managers' => $this->staffModel->getAllManagers(),
            'totalDrivers' => $this->staffModel->getTotalDrivers(),
            'totalPartners' => $this->staffModel->getTotalPartners(),
            'totalUnavailableDriver' => $this->staffModel->getTotalUnavailableDriver(),
            'totalUnavailablePartner' => $this->staffModel->getTotalUnavailablePartner(),
            'currentLeaves' => $this->staffModel->getUpcomingLeaves(),
            'pendingLeaves' => $this->staffModel->getPendingLeaves(),
            'manager_id' => $manager_id,
            'leaveTypeStats' => $this->staffModel->getLeaveTypeDistribution(),
            'monthlyLeaveStats' => $this->staffModel->getMonthlyLeaveDistribution()
        ];
        
        // Add debug logging
        error_log("Data being sent to view: " . print_r($data, true));
        
        $this->view('vehicle_manager/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('vehicle_manager/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('vehicle_manager/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }

    public function uploadVehicleImage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['vehicle_image'])) {
            $vehicle_id = $_POST['vehicle_id'];
            $file = $_FILES['vehicle_image'];
            
            // Configure upload settings
            $upload_dir = 'uploads/vehicles/';
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            // Check file type
            $allowed_types = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_extension, $allowed_types)) {
                echo json_encode(['error' => 'Invalid file type']);
                return;
            }
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                // Save to database
                $result = $this->vehicleModel->saveVehicleDocument(
                    $vehicle_id,
                    'Image',
                    $file_path
                );
                
                echo json_encode(['success' => true, 'file_path' => $file_path]);
            } else {
                echo json_encode(['error' => 'Failed to upload file']);
            }
        }
    }

    public function createVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Create vehicle
                $result = $this->vehicleModel->createVehicle($_POST);
                
                if ($result === true) {
                    flash('vehicle_message', 'Vehicle Added Successfully', 'alert alert-success');
                    redirect('vehiclemanager/index');
                } else {
                    flash('vehicle_message', $result, 'alert alert-danger');
                    redirect('vehiclemanager/index');
                }
            } catch (Exception $e) {
                flash('vehicle_message', 'Error creating vehicle: ' . $e->getMessage(), 'alert alert-danger');
                redirect('vehiclemanager/index');
            }
        } else {
            redirect('vehiclemanager/index');
        }
    }

    public function updateVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Render the update vehicle view (if applicable)
            $data = [
                'title' => 'Update Vehicle'
                // You can add more data here if needed
            ];
            $this->view('vehicle_manager/v_update_vehicle', $data); // Assuming you have a view for updating vehicles
        } else {
            // Handle POST request
            $this->handleVehicleUpdateSubmission();
        }
    }

    private function handleVehicleUpdateSubmission() {
        // Prevent PHP errors from being output
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        
        // Set JSON header
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            // Get JSON data from request body
            $json = file_get_contents('php://input');
            if (!$json) {
                throw new Exception('No data received');
            }

            $data = json_decode($json);
            if (!$data || !isset($data->vehicle_id)) {
                throw new Exception('Invalid data format');
            }

            // Log the received data for debugging
            error_log('Received vehicle update data: ' . print_r($data, true));

            $result = $this->vehicleModel->updateVehicle($data);
            
            if ($result === true) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Vehicle updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => is_string($result) ? $result : 'Failed to update vehicle'
                ]);
            }

        } catch (Exception $e) {
            error_log('Vehicle Update Error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit; // Ensure no additional output
    }

    public function getVehicleById($id) {
        $vehicle = $this->vehicleModel->getVehicleById($id);
        if ($vehicle) {
            echo json_encode(['success' => true, 'vehicle' => $vehicle]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function deleteVehicle($id) {
        header('Content-Type: application/json');
        
        try {
            // Log the request method and ID
            error_log("Delete request received for vehicle ID: " . $id);
            error_log("Request method: " . $_SERVER['REQUEST_METHOD']);

            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }

            // Try to delete the vehicle
            if ($this->vehicleModel->deleteVehicle($id)) {
                error_log("Vehicle " . $id . " deleted successfully");
                echo json_encode([
                    'success' => true,
                    'message' => 'Vehicle deleted successfully'
                ]);
            } else {
                throw new Exception('Failed to delete vehicle from database');
            }

        } catch (Exception $e) {
            error_log("Error in deleteVehicle: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function createRoute() {
        // Clear any previous output
        ob_clean();
        
        // Set JSON headers
        header('Content-Type: application/json');
        
        try {
            // Get and validate JSON input
            $json = file_get_contents('php://input');
            error_log("Received data: " . $json); // Debug log
            
            $data = json_decode($json);
            
            if (!$data) {
                throw new Exception('Invalid JSON data received');
            }

            // Create the route
            $result = $this->routeModel->createRoute($data);
            
            $response = [
                'success' => true,
                'message' => 'Route created successfully',
                'routeId' => $result // Assuming createRoute returns the new route ID
            ];
            
            error_log("Sending response: " . json_encode($response)); // Debug log
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log("Error in createRoute: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function getRouteSuppliers($routeId) {
        // Clear any previous output and set JSON header
        ob_clean();
        header('Content-Type: application/json');
        
        if (!$routeId) {
            echo json_encode(['error' => 'Route ID is required']);
            return;
        }

        try {
            // Get route details
            $route = $this->routeModel->getRouteById($routeId);
            if (!$route) {
                throw new Exception('Route not found');
            }

            // Get suppliers for this route
            $suppliers = $this->routeModel->getRouteSuppliers($routeId);
            
            // Combine the data
            $response = [
                'success' => true,
                'data' => [
                    'route' => [
                        'id' => $route->route_id,
                        'name' => $route->route_name,
                        'status' => $route->status,
                        'start_location' => [
                            'lat' => $route->start_location_lat,
                            'lng' => $route->start_location_long
                        ],
                        'end_location' => [
                            'lat' => $route->end_location_lat,
                            'lng' => $route->end_location_long
                        ],
                        'date' => $route->date,
                        'number_of_suppliers' => $route->number_of_suppliers
                    ],
                    'suppliers' => array_map(function($supplier) {
                        return [
                            'id' => $supplier->supplier_id,
                            'name' => $supplier->full_name,
                            'location' => [
                                'lat' => $supplier->latitude,
                                'lng' => $supplier->longitude
                            ],
                            'stop_order' => $supplier->stop_order,
                            'supplier_order' => $supplier->supplier_order
                        ];
                    }, $suppliers)
                ]
            ];

            error_log('Sending response: ' . json_encode($response));
            echo json_encode($response);
            
        } catch (Exception $e) {
            error_log('Error in getRouteSuppliers: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }


    public function remove_staff() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Set JSON header
            header('Content-Type: application/json');

            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['staffId']) && isset($data['role'])) {
                $staffId = $data['staffId'];
                $role = $data['role'];

                $success = false;
                if ($role === 'driver') {
                    $success = $this->driverModel->softDeleteDriver($staffId);
                } elseif ($role === 'partner') {
                    $success = $this->partnerModel->softDeletePartner($staffId);
                }

                echo json_encode(['success' => $success]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Invalid input']);
            }
        } else {
            // If the request method is not POST, return an error
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
        }
    }

    public function update_leave_status() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        try {
            $rawInput = file_get_contents("php://input");
            error_log("Received raw input: " . $rawInput);
            
            $data = json_decode($rawInput);

            if (!$data || !isset($data->requestId) || !isset($data->status) || !isset($data->vehicle_manager_id)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Missing required data',
                    'received' => $data
                ]);
                return;
            }

            // Validate status value
            if (!in_array($data->status, ['approved', 'rejected'])) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Invalid status value'
                ]);
                return;
            }

            $result = $this->staffModel->updateLeaveStatus(
                (int)$data->requestId,
                $data->status,
                (int)$data->vehicle_manager_id
            );

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Leave status updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to update leave status'
                ]);
            }

        } catch (Exception $e) {
            error_log("Exception in update_leave_status: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteTeam($teamId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request method');
        }

        if ($this->teamModel->setTeamVisibility($teamId, 0)) {
            $_SESSION['flash_messages']['team_message'] = [
                'message' => 'Team deleted successfully',
                'class' => 'alert alert-success'
            ];
        } else {
            $_SESSION['flash_messages']['team_message'] = [
                'message' => 'Failed to delete team',
                'class' => 'alert alert-danger'
            ];
        }
        redirect('vehiclemanager/team');
    }

    public function getCollectionRoute($collectionId) {
        // Get collection details
        $collection = $this->collectionModel->getCollectionById($collectionId);
        
        // Get route and supplier details
        $routeData = $this->routeModel->getRouteWithSuppliers($collection->route_id);
        
        // Get current progress from supplier records
        $supplierRecords = $this->collectionSupplierRecordModel->getSupplierRecords($collectionId);
        
        $data = [
            'team_name' => $collection->team_name,
            'route_name' => $collection->route_name,
            'start_location' => [
                'latitude' => $routeData->start_location_lat,
                'longitude' => $routeData->start_location_long
            ],
            'end_location' => [
                'latitude' => $routeData->end_location_lat,
                'longitude' => $routeData->end_location_long
            ],
            'suppliers' => $routeData->suppliers,
            'current_stop' => $this->getCurrentStop($supplierRecords)
        ];

        echo json_encode($data);
    }

    private function getCurrentStop($supplierRecords) {
        // Find the last collected supplier
        foreach ($supplierRecords as $index => $record) {
            if ($record->status === 'Collected') {
                return $index;
            }
        }
        return 0; // Return 0 if no collections yet
    }

    public function getCollectionDetails($collectionId) {
        // Get collection basic info
        $collection = $this->collectionModel->getCollectionById($collectionId);
        
        // Get supplier records for this collection
        $suppliers = $this->collectionSupplierRecordModel->getSupplierRecords($collectionId);
        
        $data = [
            'team_name' => $collection->team_name,
            'route_name' => $collection->route_name,
            'suppliers' => $suppliers
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function updateSupplierStatus($recordId) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            
            if ($this->collectionSupplierRecordModel->updateSupplierStatus($recordId, $data->status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function removeCollectionSupplier($recordId) {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $data = json_decode(file_get_contents('php://input'));
            if($this->collectionSupplierRecordModel->removeCollectionSupplier($recordId)){
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function addVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'title' => 'Add New Vehicle'
            ];
            $this->view('vehicle_manager/v_add_vehicle', $data);
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // send thru postt
            $this->handleVehicleSubmission();
        }
    }

    private function handleVehicleSubmission() {
        try {
            // Basic vehicle data
            $vehicleData = [
                'license_plate' => $_POST['license_plate'],
                'vehicle_type' => $_POST['vehicle_type'],
                'status' => $_POST['status'],
                'make' => $_POST['make'],
                'model' => $_POST['model'],
                'manufacturing_year' => $_POST['manufacturing_year'],
                'color' => $_POST['color'],
                'capacity' => $_POST['capacity']
            ];

            // Handle vehicle image
            if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] === UPLOAD_ERR_OK) {
                $this->handleVehicleImage($_FILES['vehicle_image'], $_POST['license_plate']);
            }

            // Create vehicle (temporarily without documents)
            $result = $this->vehicleModel->createVehicle($vehicleData);

            if ($result) {
                flash('vehicle_message', 'Vehicle added successfully', 'alert-success');
                redirect('vehiclemanager/vehicle'); // Changed from vehiclemanager to vehiclemanager/vehicles
            } else {
                flash('vehicle_message', 'Failed to add vehicle', 'alert-danger');
                redirect('vehiclemanager/addVehicle');
            }

        } catch (Exception $e) {
            flash('vehicle_message', 'Error: ' . $e->getMessage(), 'alert-danger');
            redirect('vehiclemanager/addVehicle');
        }
    }

    private function handleFileUpload($file, $uploadDir) {
        $uploadDir = '../public/uploads/' . $uploadDir . '/';
        $fileName = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }
        return false;
    }

    private function handleVehicleImage($file, $licensePlate) {
        try {
            if ($file['error'] === UPLOAD_ERR_OK) {
                // Define the upload directory path
                $uploadDir = UPLOADROOT . '/vehicle_photos/';
                
                // Log the upload directory path
                error_log("Upload directory: " . $uploadDir);
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    if (!mkdir($uploadDir, 0777, true)) {
                        error_log("Failed to create directory: " . $uploadDir);
                        return false;
                    }
                }

                $fileName = $licensePlate . '.jpg';
                $targetPath = $uploadDir . $fileName;
                
                // Log the target path
                error_log("Target path: " . $targetPath);

                // Check if file is actually uploaded
                if (is_uploaded_file($file['tmp_name'])) {
                    // Move the uploaded file
                    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                        error_log("File successfully uploaded to: " . $targetPath);
                        return true;
                    } else {
                        error_log("Failed to move uploaded file. Error: " . error_get_last()['message']);
                        return false;
                    }
                } else {
                    error_log("File was not uploaded via HTTP POST");
                    return false;
                }
            } else {
                error_log("File upload error code: " . $file['error']);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error uploading vehicle image: " . $e->getMessage());
            return false;
        }
    }

    public function checkVehicleUsage($id) {
        $schedules = $this->scheduleModel->getSchedulesByVehicleId($id);
        $collections = $this->collectionModel->getCollectionsByVehicleId($id);

        echo json_encode([
            'inUse' => !empty($schedules) || !empty($collections),
            'schedules' => !empty($schedules),
            'collections' => !empty($collections)
        ]);
    }

    public function getRouteDetails($routeId) {
        // Clear any previous output
        ob_clean();
        
        // Set JSON headers
        header('Content-Type: application/json');
        
        try {
            if (!$routeId) {
                throw new Exception('Route ID is required');
            }

            // Get route details from model
            $routeDetails = $this->routeModel->getRouteById($routeId);
            
            if (!$routeDetails) {
                throw new Exception('Route not found');
            }

            // Get route suppliers
            $routeSuppliers = $this->routeModel->getRouteSuppliers($routeId);

            // Combine route details with suppliers
            $response = [
                'success' => true,
                'route' => [
                    'id' => $routeId,
                    'name' => $routeDetails->route_name,
                    'status' => $routeDetails->status,
                    'suppliers' => array_map(function($supplier) {
                        return [
                            'id' => $supplier->supplier_id,
                            'name' => $supplier->full_name,
                            'coordinates' => [
                                'lat' => (float)$supplier->latitude,
                                'lng' => (float)$supplier->longitude
                            ]
                        ];
                    }, $routeSuppliers)
                ]
            ];

            error_log("Sending route details: " . json_encode($response)); // Debug log
            echo json_encode($response);

        } catch (Exception $e) {
            error_log("Error in getRouteDetails: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }


    public function deleteRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $route_id = $_POST['route_id'];
            
            // Call model method to delete route
            if ($this->routeModel->deleteRoute($route_id)) {
                // Redirect with success message
                flash('route_message', 'Route deleted successfully');
                redirect('vehiclemanager/route');
            } else {
                // Redirect with error message
                flash('route_message', 'Failed to delete route', 'error');
                redirect('vehiclemanager/route');
            }
        }
    }

    public function getAvailableVehicles($day) {
        // Make sure nothing is output before this
        ob_clean(); // Clear any previous output
        
        try {
            $vehicles = $this->vehicleModel->getAvailableVehiclesByDay($day);
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $vehicles,
                'message' => 'Vehicles retrieved successfully'
            ]);
            exit; // End the script after sending JSON
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function getVehicleDetails($id) {
        ob_clean();
        
        try {
            $vehicle = $this->vehicleModel->getVehicleById($id);
        
            
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $vehicle,
                'message' => 'Vehicle details retrieved successfully'
            ]);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function getRoutesByDay($day) {
        $routes = $this->routeModel->getRoutesByDay($day);
        echo json_encode(['routes' => $routes]);
    }



}
?>