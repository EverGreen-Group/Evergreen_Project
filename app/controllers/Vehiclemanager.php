<?php
require_once '../app/models/M_VehicleManager.php';
require_once '../app/models/M_Route.php';      // Add Route model
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
    private $vehicleModel;     // Declare a variable for Vehicle model
    private $shiftModel;       // Declare a variable for Shift model
    private $scheduleModel;     // Declare a variable for CollectionSchedule model
    private $driverModel; // Declare a variable for Driver model
    private $partnerModel; // Add this line
    private $staffModel;
    private $userHelper;
    private $collectionModel;
    private $collectionSupplierRecordModel;
    private $supplierModel;
    

    // Role constants
    private const DRIVER_ROLE_ID = 3;
    private const PARTNER_ROLE_ID = 4;
    private const VEHICLE_MANAGER_ROLE_ID = 5;
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
        $this->vehicleModel = new M_Vehicle();    // Instantiate Vehicle model
        $this->shiftModel = new M_Shift();        // Instantiate Shift model
        $this->scheduleModel = new M_CollectionSchedule();  // Instantiate CollectionSchedule model
        $this->driverModel = new M_Driver(); // Instantiate Driver model
        $this->partnerModel = new M_Partner(); // Add this line
        $this->staffModel = $this->model('M_Staff');
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
        $this->supplierModel = $this->model('M_Supplier');
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

    
    

    // Other methods remain unchanged
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
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ]
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

    public function getRouteSuppliers($routeId) {
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
                        'date' => $route->date,
                        'vehicle_id' => $route->vehicle_id,
                        'number_of_suppliers' => $route->number_of_suppliers
                    ],
                    'suppliers' => array_map(function($supplier) {
                        return [
                            'id' => $supplier->supplier_id,
                            'location' => [
                                'lat' => $supplier->latitude,
                                'lng' => $supplier->longitude
                            ],
                            'stop_order' => $supplier->stop_order,
                            'number_of_collections' => $supplier->number_of_collections,
                            'avg_collection' => $supplier->avg_collection
                        ];
                    }, $suppliers)
                ]
            ];

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


    public function createRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $vehicles = $this->vehicleModel->getUnassignedVehicles();
            $suppliers = $this->supplierModel->getAllUnallocatedSuppliers();
            $data = [
                'title' => 'Create Route',
                'vehicles' => $vehicles,
                'suppliers' => $suppliers
            ];
            $this->view('routes/v_create_route', $data);
        } 
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get POST data
            $data = json_decode(file_get_contents('php://input'));
            
            // Validate data
            if (!isset($data->route_name) || !isset($data->vehicle_id) || empty($data->suppliers)) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                return;
            }

            try {
                $routeId = $this->routeModel->createRouteWithSuppliers([
                    'route_name' => $data->route_name,
                    'vehicle_id' => $data->vehicle_id,
                    'status' => $data->status,
                    'suppliers' => $data->suppliers
                ]);

                echo json_encode(['success' => true, 'route_id' => $routeId]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Get all active routes
            $routes = $this->routeModel->getAllRoutes();
            // Get all vehicles
            $vehicles = $this->vehicleModel->getAllVehicles();
            // Get unassigned suppliers
            $unassignedSuppliers = $this->supplierModel->getAllUnallocatedSuppliers();

            $data = [
                'title' => 'Update Route',
                'routes' => $routes,
                'vehicles' => $vehicles,
                'suppliers' => $unassignedSuppliers  // These are the unassigned suppliers
            ];

            $this->view('routes/v_update_route', $data);
        }
    }

    public function createStaff() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'title' => 'Create Staff'
            ];
            $this->view('vehicle_manager/v_create_staff', $data);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Initialize data array with form values
            $data = [
                // Base user data
                'role' => trim($_POST['role']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                
                // Contact information
                'primary_phone' => trim($_POST['primary_phone']),
                'secondary_phone' => !empty($_POST['secondary_phone']) ? trim($_POST['secondary_phone']) : null,
                
                // Address information
                'address_line1' => trim($_POST['address_line1']),
                'address_line2' => !empty($_POST['address_line2']) ? trim($_POST['address_line2']) : null,
                'city' => trim($_POST['city']),
                'postal_code' => trim($_POST['postal_code']),
                'province' => trim($_POST['province']),
                'district' => trim($_POST['district']),
                
                // Employee information
                'nic' => trim($_POST['nic']),
                'gender' => trim($_POST['gender']),
                'hire_date' => trim($_POST['hire_date']),
                
                // Role-specific information
                'license_no' => isset($_POST['license_no']) ? trim($_POST['license_no']) : null,
                'manager_type' => isset($_POST['manager_type']) ? trim($_POST['manager_type']) : null,
                
                'error' => ''
            ];

            // Validate input
            if (!$this->validateStaffData($data)) {
                $this->view('vehicle_manager/v_create_staff', $data);
                return;
            }

            try {
                // Start transaction
                $this->vehicleManagerModel->beginTransaction();

                // 1. Create base user account
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $data['role_id'] = RoleHelper::getRoleByTitle($data['role']); // Using RoleHelper's getRole method
                $data['approval_status'] = 'Approved'; // Auto-approve staff accounts
                $userId = $this->vehicleManagerModel->createUser($data);

                if (!$userId) {
                    throw new Exception("Failed to create user account");
                }

                // 2. Create user address
                if (!$this->vehicleManagerModel->createUserAddress($userId, $data)) {
                    throw new Exception("Failed to create user address");
                }

                // 3. Create user contacts
                if (!$this->vehicleManagerModel->createUserContacts($userId, $data)) {
                    throw new Exception("Failed to create user contacts");
                }

                // 4. Create employee record
                $employeeId = $this->vehicleManagerModel->createEmployee($userId, $data);
                if (!$employeeId) {
                    throw new Exception("Failed to create employee record");
                }

                // 5. Handle profile photo if uploaded
                if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
                    $photoPath = $this->handleFileUpload($_FILES['profile_photo'], 'profile_photos');
                    if (!$this->vehicleManagerModel->updateEmployeePhoto($employeeId, $photoPath)) {
                        throw new Exception("Failed to update profile photo");
                    }
                }

                // 6. Create role-specific records
                if ($data['role'] === 'Driver') {
                    if (!$this->vehicleManagerModel->createDriver($employeeId, $data)) {
                        throw new Exception("Failed to create driver record");
                    }
                } elseif ($data['role'] === 'Driving Partner') {
                    if (!$this->vehicleManagerModel->createPartner($employeeId, $data)) {
                        throw new Exception("Failed to create partner record");
                    }
                } elseif ($data['role'] === 'Vehicle Manager') {
                    if (!$this->vehicleManagerModel->createManager($employeeId, $data)) {
                        throw new Exception("Failed to create manager record");
                    }
                }

                // If everything is successful, commit the transaction
                $this->vehicleManagerModel->commit();
                flash('staff_message', 'Staff member registered successfully');
                redirect('vehiclemanager/staff');

            } catch (Exception $e) {
                // If anything fails, rollback the transaction
                $this->vehicleManagerModel->rollBack();
                $data['error'] = 'Registration failed: ' . $e->getMessage();
                $this->view('vehicle_manager/v_create_staff', $data);
            }
        } else {
            // Initial page load
            $data = [
                'error' => '',
                'role' => '',
                'first_name' => '',
                // ... initialize all other fields
            ];
            $this->view('vehicle_manager/v_create_staff', $data);
        }
    }

    private function validateStaffData($data) {
        // Basic validation rules
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
            return false;
        }

        // Password validation
        if (strlen($data['password']) < 6 || $data['password'] !== $data['confirm_password']) {
            return false;
        }

        // Email validation
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Role-specific validation
        if ($data['role'] === 'driver' && empty($data['license_no'])) {
            return false;
        }

        return true;
    }

}
?>