<?php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// Require all model files
require_once '../app/models/M_VehicleManager.php';
require_once '../app/models/M_Route.php';
require_once '../app/models/M_Team.php';
require_once '../app/models/M_Vehicle.php';
require_once '../app/models/M_Shift.php';
require_once '../app/models/M_CollectionSchedule.php';
require_once '../app/models/M_Staff.php';
require_once '../app/models/M_Driver.php';
require_once '../app/models/M_Partner.php';
require_once '../app/models/M_Collection.php';
require_once '../app/models/M_CollectionSupplierRecord.php';
require_once '../app/models/M_User.php';
require_once '../app/models/M_Employee.php';
require_once '../app/models/M_CollectionBag.php';

// Require helper files
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/UserHelper.php';

class VehicleManager extends Controller
{
    //----------------------------------------
    // PROPERTIES
    //----------------------------------------
    private $vehicleManagerModel;
    private $routeModel;
    private $teamModel;
    private $vehicleModel;
    private $shiftModel;
    private $scheduleModel;
    private $driverModel;
    private $partnerModel;
    private $staffModel;
    private $userHelper;
    private $collectionModel;
    private $collectionSupplierRecordModel;
    private $userModel;
    private $employeeModel;
    private $bagModel;

    //----------------------------------------
    // CONSTRUCTOR
    //----------------------------------------
    public function __construct()
    {
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
        $this->routeModel = new M_Route();
        $this->teamModel = new M_Team();
        $this->vehicleModel = new M_Vehicle();
        $this->shiftModel = new M_Shift();
        $this->scheduleModel = new M_CollectionSchedule();
        $this->driverModel = new M_Driver();
        $this->partnerModel = new M_Partner();
        $this->staffModel = $this->model('M_Staff');
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
        $this->userModel = $this->model('M_User');
        $this->employeeModel = $this->model('M_Employee');
        $this->bagModel = $this->model('M_CollectionBag');
    }

    //----------------------------------------
    // DASHBOARD METHODS
    //----------------------------------------
    public function index()
    {
        // Get dashboard stats from the model
        $stats = $this->vehicleManagerModel->getDashboardStats();

        // Fetch all necessary data for the dropdowns
        $routes = $this->routeModel->getAllRoutes();
        $drivers = $this->driverModel->getUnassignedDrivers();
        $vehicles = $this->vehicleModel->getAllVehicles();
        $shifts = $this->shiftModel->getAllShifts();
        $schedules = $this->scheduleModel->getAllSchedules();
        $collectionSchedules = $this->scheduleModel->getSchedulesForNextWeek(); 
        $ongoingCollections = $this->collectionModel->getOngoingCollections();
        $todayRoutes = $this->routeModel->getTodayAssignedRoutes();

        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_collection', [
            'stats' => $stats,
            'routes' => $routes,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'shifts' => $shifts,
            'schedules' => $schedules,
            'ongoing_collections' => $ongoingCollections,
            'collectionSchedules' => $collectionSchedules,
            'todayRoutes' => $todayRoutes 
        ]);
    }

    public function schedule()
    {
        // Get dashboard stats from the model
        $stats = $this->vehicleManagerModel->getDashboardStats();

        // Fetch all necessary data for the dropdowns
        $routes = $this->routeModel->getAllRoutes();
        $drivers = $this->driverModel->getUnassignedDrivers();
        $vehicles = $this->vehicleModel->getAllVehicles();
        $shifts = $this->shiftModel->getAllShifts();
        $schedules = $this->scheduleModel->getAllSchedules();
        $collectionSchedules = $this->scheduleModel->getSchedulesForNextWeek(); 
        $ongoingCollections = $this->collectionModel->getOngoingCollections();
        $todayRoutes = $this->routeModel->getTodayAssignedRoutes();

        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_collectionschedule', [
            'stats' => $stats,
            'routes' => $routes,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'shifts' => $shifts,
            'schedules' => $schedules,
            'ongoing_collections' => $ongoingCollections,
            'collectionSchedules' => $collectionSchedules,
            'todayRoutes' => $todayRoutes 
        ]);
    }


// In your VehicleManager controller
public function updateSchedule($scheduleId)
{
    // Fetch the specific schedule details
    $schedule = $this->scheduleModel->getScheduleById($scheduleId);
    
    if (!$schedule) {
        // Handle case where schedule doesn't exist
        flash('schedule_message', 'Schedule not found', 'alert alert-danger');
        redirect('vehiclemanager/schedule'); // Or wherever your schedules list is
        return;
    }
    
    $shifts = $this->shiftModel->getAllShifts();
    
    // Pass data to the view - notice we've removed routes
    $this->view('vehicle_manager/v_schedule_update', [
        'schedule' => $schedule,
        'shifts' => $shifts
    ]);
}

    //----------------------------------------
    // SUPPLIER RECORD METHODS
    //----------------------------------------
    public function getSupplierRecords($collectionId)
    {
        $records = $this->collectionSupplierRecordModel->getSupplierRecords($collectionId);
        echo json_encode($records);
    }

    public function updateSupplierRecord()
    {
        $data = json_decode(file_get_contents('php://input'));
        $success = $this->collectionSupplierRecordModel->updateSupplierRecord($data);
        echo json_encode(['success' => $success]);
    }

    public function addSupplierRecord()
    {
        $data = json_decode(file_get_contents('php://input'));
        $success = $this->collectionSupplierRecordModel->addSupplierRecord($data);
        echo json_encode(['success' => $success]);
    }

    //----------------------------------------
    // SCHEDULE METHODS
    //----------------------------------------
    public function createSchedule()
    {
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

    //----------------------------------------
    // VEHICLE METHODS
    //----------------------------------------
    public function vehicle() {
        // Retrieve filter parameters from the GET request
        $license_plate = isset($_GET['license_plate']) ? $_GET['license_plate'] : null;
        $vehicle_type = isset($_GET['vehicle_type']) ? $_GET['vehicle_type'] : null;
        $capacity = isset($_GET['capacity']) ? $_GET['capacity'] : null;
        $make = isset($_GET['make']) ? $_GET['make'] : null;
        $model = isset($_GET['model']) ? $_GET['model'] : null;
        $manufacturing_year = isset($_GET['manufacturing_year']) ? $_GET['manufacturing_year'] : null;
    
        // Fetch vehicles based on filters
        if ($license_plate || $vehicle_type || $capacity || $make || $model || $manufacturing_year) {
            $data['vehicles'] = $this->vehicleModel->getFilteredVehicles($license_plate, $vehicle_type, $capacity, $make, $model, $manufacturing_year);
        } else {
            // Otherwise, fetch all vehicles
            $data['vehicles'] = $this->vehicleModel->getVehicleDetails();
        }
    
        // Additional data for the view
        $data['totalVehicles'] = $this->vehicleModel->getTotalVehicles();
        $data['availableVehicles'] = $this->vehicleModel->getAvailableVehicles();
        $data['vehicleTypeStats'] = $this->vehicleModel->getVehicleTypeStats();
    
        // Load the view and pass the data
        $this->view('vehicle_manager/v_new_vehicle', $data);
    }

    public function vehiclez() {
        $data = [
            'totalVehicles' => $this->vehicleModel->getTotalVehicles(),
            'availableVehicles' => $this->vehicleModel->getAvailableVehicles(),
            'vehicles' => $this->vehicleModel->getVehicleDetails(),
            'vehicleTypeStats' => $this->vehicleModel->getVehicleTypeStats()
        ];

        $this->view('vehicle_manager/v_vehicle', $data);
    }

    public function updateVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate and sanitize input
            $license_plate = htmlspecialchars(trim($_POST['license_plate'])); // Keep the license plate as is
            $vehicle_type = htmlspecialchars(trim($_POST['vehicle_type']));
            $make = htmlspecialchars(trim($_POST['make']));
            $model = htmlspecialchars(trim($_POST['model']));
            $manufacturing_year = htmlspecialchars(trim($_POST['manufacturing_year']));
            $color = htmlspecialchars(trim($_POST['color']));
            $capacity = htmlspecialchars(trim($_POST['capacity']));

            // Check the current status of the vehicle
            $currentVehicle = $this->vehicleModel->getVehicleByLicensePlate($license_plate);
            if ($currentVehicle && $currentVehicle->status === 'In Use') {
                // Handle the case where the vehicle is in use
                echo "Cannot update vehicle. The vehicle is currently in use.";
                return; // Exit the function if the vehicle is in use
            }

            // Initialize the data array for updating
            $data = [
                'license_plate' => $license_plate, // Keep the existing license plate
                'vehicle_type' => $vehicle_type,
                'make' => $make,
                'model' => $model,
                'manufacturing_year' => $manufacturing_year,
                'color' => $color,
                'capacity' => $capacity,
            ];

            // Handle file upload if a new image is provided
            if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] == 0) {
                $image = $_FILES['vehicle_image'];
                $target_dir = "/opt/lampp/htdocs/Evergreen_Project/public/uploads/vehicle_photos/";
                $target_file = $target_dir . $license_plate . ".jpg"; // Save as {license_plate}.jpg

                // Move the uploaded file to the target directory
                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    // Update the image path in the data array
                    $data['image_path'] = $target_file; // Optional: store the new image path in the database
                } else {
                    // Handle file upload error
                    echo "Error uploading file.";
                    return; // Exit the function if the upload fails
                }
            }

            // Update vehicle details in the database
            $this->vehicleModel->updateVehicle($data);

            // Redirect or show success message
            header('Location: ' . URLROOT . '/vehiclemanager/vehicle');
            exit();
        }
    }

    public function getVehicleById($id)
    {
        $vehicle = $this->vehicleModel->getVehicleById($id);
        if ($vehicle) {
            echo json_encode(['success' => true, 'vehicle' => $vehicle]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function deleteVehicle($id)
    {
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

    //----------------------------------------
    // DRIVER METHODS
    //----------------------------------------
    public function driver() {
        // Initialize $data array first
        $data = [];

        // Get filter parameters
        $driver_id = isset($_GET['driver_id']) ? $_GET['driver_id'] : null;
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $nic = isset($_GET['nic']) ? $_GET['nic'] : null;
        $contact_number = isset($_GET['contact_number']) ? $_GET['contact_number'] : null;
        $driver_status = isset($_GET['driver_status']) ? $_GET['driver_status'] : null;
        $employee_status = isset($_GET['employee_status']) ? $_GET['employee_status'] : null;

        // Get filtered or all drivers
        if ($driver_id || $name || $nic || $contact_number || $driver_status || $employee_status) {
            $data['drivers'] = $this->driverModel->getFilteredDrivers($driver_id, $name, $nic, $contact_number, $driver_status, $employee_status);
        } else {
            $data['drivers'] = $this->driverModel->getAllDrivers();
        }

        // Add other data to the array
        $data['unassigned_drivers'] = $this->driverModel->getUnassignedDriversList();
        $data['total_drivers'] = $this->driverModel->getTotalDrivers();
        $data['on_duty_drivers'] = $this->driverModel->getDriversOnDuty();
        $data['unassigned_drivers_count'] = $this->driverModel->getUnassignedDriversCount();
        $data['users'] = $this->userModel->getAllUnassignedUsers();
        $data['update_users'] = $this->userModel->getAllUserDrivers();
        
        $this->view('vehicle_manager/v_driver_2', $data);
    }

    public function getDriverDetails($driverId) {
        // Set header to return JSON
        header('Content-Type: application/json');
    
        try {
            $driver = $this->driverModel->getDriverDetails($driverId);
            
            if ($driver) {
                echo json_encode($driver);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Driver not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
    }

    public function addDriver() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get the user_id from the form submission
            $user_id = trim($_POST['user_id']); // Get the user_id from the dropdown

            // Validate that a user has been selected
            if (empty($user_id)) {
                die('Please select a user.');
            }

            // Update the role_id to 6 for the selected user
            if ($this->userModel->updateUserRole($user_id, 6)) {
                $_SESSION['flash_messages'] = [
                    'driver_message' => [
                        'message' => 'User role updated to 6 successfully.',
                        'class' => 'alert alert-success'
                    ]
                ];

                // Prepare data for the employees table
                $employeeData = [
                    'user_id' => $user_id,
                    'hire_date' => date('Y-m-d'), // Set the hire date to today
                    'contact_number' => trim($_POST['contact_number']),
                    'emergency_contact' => trim($_POST['emergency_contact']),
                    'status' => !empty($_POST['status']) ? trim($_POST['status']) : 'Active', // Default to 'Active' if not set
                    'address_line1' => trim($_POST['address_line1']),
                    'address_line2' => trim($_POST['address_line2']),
                    'city' => trim($_POST['city'])
                ];

                // Insert the employee data into the employees table
                if ($this->employeeModel->addEmployee($employeeData)) {
                    $_SESSION['flash_messages']['driver_message']['message'] .= ' Employee added successfully.';

                    // Get the last inserted employee ID
                    $employee_id = $this->employeeModel->getLastInsertedId(); // Assuming you have this method

                    // Prepare data for the drivers table
                    $driverData = [
                        'employee_id' => $employee_id,
                        'user_id' => $user_id,
                        'status' => 'Available', // Default status
                        'is_deleted' => 0 // Default to not deleted
                    ];

                    // Insert the driver data into the drivers table
                    if ($this->driverModel->addDriver($driverData)) {
                        $_SESSION['flash_messages']['driver_message']['message'] .= ' Driver added successfully.';
                    } else {
                        $_SESSION['flash_messages']['driver_message']['message'] .= ' Failed to add driver.';
                        $_SESSION['flash_messages']['driver_message']['class'] = 'alert alert-danger';
                    }
                } else {
                    $_SESSION['flash_messages']['driver_message']['message'] .= ' Failed to add employee.';
                    $_SESSION['flash_messages']['driver_message']['class'] = 'alert alert-danger';
                }
            } else {
                $_SESSION['flash_messages'] = [
                    'driver_message' => [
                        'message' => 'Failed to update user role.',
                        'class' => 'alert alert-danger'
                    ]
                ];
            }

            redirect('vehiclemanager/driver'); // Redirect to the drivers page
        } else {
            $data = [
                'first_name' => '',
                'last_name' => '',
                'license_no' => '',
                'experience_years' => '',
                'contact_number' => '',
                'status' => '',
                'users' => $this->userModel->getAllUnassignedUsers()
            ];

            // Load the view for adding a driver
            $this->view('vehicle_manager/v_add_driver', $data);
        }
    }

    public function updateDriver()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and retrieve the input data
            $user_id = trim($_POST['user_id']);
            $address_line1 = trim($_POST['address_line1']);
            $address_line2 = trim($_POST['address_line2']);
            $city = trim($_POST['city']);
            $contact_number = trim($_POST['contact_number']);
            $emergency_contact = trim($_POST['emergency_contact']);

            // Validate the input data as needed

            // Update the driver information in the database
            $result = $this->employeeModel->updateDriverInfo($user_id, $address_line1, $address_line2, $city, $contact_number, $emergency_contact);

            // Check if the update was successful
            if ($result) {
                // Redirect or provide feedback
                flash('driver_update_success', 'Driver information updated successfully.');
                header('Location: ' . URLROOT . '/vehiclemanager/driver'); // Redirect to a relevant page
                exit;
            } else {
                // Handle the error
                flash('driver_update_error', 'Failed to update driver information.');
            }
        } else {

            // Prepare data to pass to the view
            $data = [
                'users' => $this->userModel->getAllUserDrivers() // Ensure you are passing the users as well
            ];

            // Load the view for updating a driver
            $this->view('vehicle_manager/v_update_driver', $data);
        }
    }

    //----------------------------------------
    // ROUTE METHODS
    //----------------------------------------
    public function route() {
        $allRoutes = $this->routeModel->getAllUndeletedRoutes();
        $totalRoutes = $this->routeModel->getTotalRoutes();
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();
        $unallocatedSuppliers = $this->routeModel->getUnallocatedSuppliers();

        // Format suppliers for the map/dropdown
        $suppliersForMap = array_map(function ($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'name' => $supplier->full_name, // Changed from supplier_name to full_name
                'preferred_day' => $supplier->preferred_day, // Include preferred_day
                'location' => [
                    'lat' => (float) $supplier->latitude,
                    'lng' => (float) $supplier->longitude
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

    public function createRoute()
    {
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

    public function getRouteSuppliers($routeId)
    {
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
                    'suppliers' => array_map(function ($supplier) {
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

    //----------------------------------------
    // SHIFT METHODS
    //----------------------------------------
    public function shift()
    {
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

    public function deleteShift($id)
    {
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

    public function getShift($id)
    {
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

    public function updateShift($id)
    {
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

    //----------------------------------------
    // PROFILE & SETTINGS METHODS
    //----------------------------------------
    public function settings()
    {
        $data = [];
        $this->view('vehicle_manager/v_settings', $data);
    }

    public function personal_details()
    {
        $data = [];
        $this->view('vehicle_manager/v_personal_details', $data);
    }

    public function logout()
    {
        // Handle logout functionality
    }


    public function update_leave_status()
    {
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
                (int) $data->requestId,
                $data->status,
                (int) $data->vehicle_manager_id
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

    //==============================================================================
    // ROUTE MANAGEMENT
    //==============================================================================
    
    /**
     * Gets collection route details including suppliers
     * @param int $collectionId The ID of the collection
     */
    public function getCollectionRoute($collectionId)
    {
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

    /**
     * Determines the current stop in the collection route
     * @param array $supplierRecords The supplier collection records
     * @return int The index of the current stop
     */
    private function getCurrentStop($supplierRecords)
    {
        // Find the last collected supplier
        foreach ($supplierRecords as $index => $record) {
            if ($record->status === 'Collected') {
                return $index;
            }
        }
        return 0; // Return 0 if no collections yet
    }

    /**
     * Updates the status of a supplier in a collection
     * @param int $recordId The ID of the supplier record
     */
    public function updateSupplierStatus($recordId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'));

            if ($this->collectionSupplierRecordModel->updateSupplierStatus($recordId, $data->status)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    /**
     * Removes a supplier from a collection
     * @param int $recordId The ID of the supplier record
     */
    public function removeCollectionSupplier($recordId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            if ($this->collectionSupplierRecordModel->removeCollectionSupplier($recordId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    /**
     * Gets details for a specific route
     * @param int $routeId The ID of the route
     */
    public function getRouteDetails($routeId)
    {
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
                    'suppliers' => array_map(function ($supplier) {
                        return [
                            'id' => $supplier->supplier_id,
                            'name' => $supplier->full_name,
                            'coordinates' => [
                                'lat' => (float) $supplier->latitude,
                                'lng' => (float) $supplier->longitude
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

    /**
     * Gets routes scheduled for a specific day
     * @param string $day The day to get routes for
     */
    public function getRoutesByDay($day)
    {
        $routes = $this->routeModel->getRoutesByDay($day);
        echo json_encode(['routes' => $routes]);
    }

    //==============================================================================
    // VEHICLE MANAGEMENT
    //==============================================================================
    
    /**
     * Adds a new vehicle to the system
     */
    public function addVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate and sanitize input
            $license_plate = htmlspecialchars(trim($_POST['license_plate']));
            $vehicle_type = htmlspecialchars(trim($_POST['vehicle_type']));
            $make = htmlspecialchars(trim($_POST['make']));
            $model = htmlspecialchars(trim($_POST['model']));
            $manufacturing_year = htmlspecialchars(trim($_POST['manufacturing_year']));
            $color = htmlspecialchars(trim($_POST['color']));
            $capacity = htmlspecialchars(trim($_POST['capacity']));

            // Handle file upload
            if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] == 0) {
                $image = $_FILES['vehicle_image'];
                $target_dir = "/opt/lampp/htdocs/Evergreen_Project/public/uploads/vehicle_photos/";
                $target_file = $target_dir . $license_plate . ".jpg"; // Save as {license_plate}.jpg

                // Move the uploaded file to the target directory
                if (move_uploaded_file($image['tmp_name'], $target_file)) {
                    // File upload successful, now save vehicle details to the database
                    $this->vehicleModel->addVehicle([
                        'license_plate' => $license_plate,
                        'vehicle_type' => $vehicle_type,
                        'make' => $make,
                        'model' => $model,
                        'manufacturing_year' => $manufacturing_year,
                        'color' => $color,
                        'capacity' => $capacity,
                        'image_path' => $target_file // Optional: store the image path in the database
                    ]);

                    // Redirect or show success message
                    header('Location: ' . URLROOT . '/vehiclemanager/vehicle');
                    exit();
                } else {
                    // Handle file upload error
                    echo "Error uploading file.";
                }
            } else {
                // Handle no file uploaded or other errors
                echo "No file uploaded or there was an error.";
            }
        }
    }

    /**
     * Removes a vehicle from the system
     */
    public function removeVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate and sanitize input
            $license_plate = htmlspecialchars(trim($_POST['license_plate']));

            // Check if the vehicle exists
            $vehicle = $this->vehicleModel->getVehicleByLicensePlate($license_plate);
            if ($vehicle) {
                // Remove the vehicle from the database
                if ($this->vehicleModel->deleteVehicle($license_plate)) {
                    // Optionally, remove the vehicle image file
                    $imagePath = "/opt/lampp/htdocs/Evergreen_Project/public/uploads/vehicle_photos/" . $license_plate . ".jpg";
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image file
                    }

                    // Redirect or show success message
                    header('Location: ' . URLROOT . '/vehiclemanager/vehicle');
                    exit();
                } else {
                    echo "Error removing vehicle.";
                }
            } else {
                echo "Vehicle not found.";
            }
        }
    }

    /**
     * Checks if a vehicle is being used in schedules or collections
     * @param int $id The vehicle ID
     */
    public function checkVehicleUsage($id)
    {
        $schedules = $this->scheduleModel->getSchedulesByVehicleId($id);
        $collections = $this->collectionModel->getCollectionsByVehicleId($id);

        echo json_encode([
            'inUse' => !empty($schedules) || !empty($collections),
            'schedules' => !empty($schedules),
            'collections' => !empty($collections)
        ]);
    }

    /**
     * Gets details for a specific vehicle
     * @param int $id The vehicle ID
     */
    public function getVehicleDetails($id)
    {
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

    //==============================================================================
    // EMPLOYEE/DRIVER MANAGEMENT
    //==============================================================================
    
    /**
     * Gets employee details by user ID
     * @param int $user_id The user ID
     */
    public function getEmployeeByUserId($user_id)
    {
        // Fetch employee data
        $employeeData = $this->employeeModel->getEmployeeByUserId($user_id);

        // Ensure all expected keys exist
        $response = [
            'employee_id' => $employeeData->employee_id ?? null,
            'user_id' => $employeeData->user_id ?? null,
            'hire_date' => $employeeData->hire_date ?? null,
            'contact_number' => $employeeData->contact_number ?? '',
            'emergency_contact' => $employeeData->emergency_contact ?? '',
            'status' => $employeeData->status ?? 'Active',
            'address_line1' => $employeeData->address_line1 ?? '',
            'address_line2' => $employeeData->address_line2 ?? '',
            'city' => $employeeData->city ?? ''
        ];

        echo json_encode($response);
        exit;
    }

    /**
     * Removes a driver from the system
     * @param int $user_id The user ID of the driver
     */
    public function removeDriver($user_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Call the model method to remove the driver
                if ($this->driverModel->removeDriver($user_id)) {
                    flash('driver_message', 'Driver removed successfully', 'alert alert-success');
                } else {
                    flash('driver_message', 'Failed to remove driver', 'alert alert-danger');
                }
            } catch (Exception $e) {
                flash('driver_message', 'Error: ' . $e->getMessage(), 'alert alert-danger');
            }

            redirect('vehiclemanager/driver'); // Redirect to the driver management page
        } else {
            // If not a POST request, redirect to the driver management page
            redirect('vehiclemanager/driver');
        }
    }

    //==============================================================================
    // COLLECTION BAG MANAGEMENT
    //==============================================================================
    
    /**
     * Loads the collection bag management view
     */
    public function bag()
    {
        $data = [
            // 'totalVehicles' => $this->vehicleModel->getTotalVehicles(),
            // 'availableVehicles' => $this->vehicleModel->getAvailableVehicles(),
            // 'vehicles' => $this->vehicleModel->getVehicleDetails(),
            // 'vehicleTypeStats' => $this->vehicleModel->getVehicleTypeStats()
        ];

        $this->view('vehicle_manager/collection_bags/index', $data);
    }

    /**
     * Creates a new collection bag
     */
    public function createBag()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the raw POST data
            $input = file_get_contents("php://input");
            $data = json_decode($input, true); // Decode the JSON payload

            // Log the received data
            error_log("Received data: " . print_r($data, true));

            // Convert to appropriate types
            $data['capacity_kg'] = (float) ($data['capacity_kg'] ?? 50.00); // Default value
            $data['bag_weight_kg'] = isset($data['bag_weight_kg']) ? (float) $data['bag_weight_kg'] : null; // Convert to float or null

            // Call the model method to create the collection bag
            $bagId = $this->bagModel->createCollectionBag($data);

            if ($bagId) {
                // Generate QR Code
                $this->generateQRCode($bagId);

                // Return success response
                echo json_encode(['success' => true, 'lastInsertedId' => $bagId]);
            } else {
                // Handle error
                echo json_encode(['success' => false, 'message' => 'Failed to create collection bag.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    /**
     * Generates a QR code for a collection bag
     * @param int $bagId The bag ID
     */
    private function generateQRCode($bagId)
    {
        try {
            $qrCode = new QrCode($bagId);
            $qrCode->setSize(300); // Set the size
            $qrCode->setMargin(10); // Set the margin

            $writer = new PngWriter();

            // Define the path to save the QR code image
            $filePath = UPLOADROOT . '/qr_codes/' . $bagId . '.png';

            // Save the generated QR code to a file
            $writer->writeFile($qrCode, $filePath); // Directly write to file

        } catch (\Exception $e) {
            error_log('QR Code generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Gets all collection bags
     */
    public function getBags()
    {
        // Fetch bags from the model
        $bags = $this->bagModel->getAllBags(); // Assuming this method exists in your model

        // Return the bags as a JSON response
        echo json_encode(['success' => true, 'bags' => $bags]);
    }

    /**
     * Gets details for a specific collection bag
     * @param int $bagId The bag ID
     */
    public function getBagDetails($bagId)
    {
        // Fetch bag details from the model
        $bag = $this->bagModel->getBagDetails($bagId); // Assuming this method exists in your model

        // Check if bag exists and return as JSON response
        if ($bag) {
            echo json_encode(['success' => true, 'bag' => $bag]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Bag not found.']);
        }
    }

    /**
     * Updates an existing collection bag
     */
    public function updateBag()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the raw POST data
            $input = file_get_contents("php://input");
            $data = json_decode($input, true); // Decode the JSON payload

            // Validate and sanitize input
            $bagId = $data['bag_id'] ?? null;
            $capacityKg = (float) ($data['capacity_kg'] ?? 0);
            $bagWeightKg = (float) ($data['bag_weight_kg'] ?? 0);
            $status = $data['status'] ?? 'inactive'; // Default to inactive if not provided

            // Call the model method to update the collection bag
            $result = $this->bagModel->updateCollectionBag($bagId, $capacityKg, $bagWeightKg, $status);

            if ($result) {
                // Return success response
                echo json_encode(['success' => true]);
            } else {
                // Handle error
                echo json_encode(['success' => false, 'message' => 'Failed to update collection bag.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    /**
     * Removes a collection bag
     */
    public function removeBag()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            // Get the raw POST data
            $input = file_get_contents("php://input");
            $data = json_decode($input, true); // Decode the JSON payload

            // Validate and sanitize input
            $bagId = $data['bag_id'] ?? null;

            // Check if the bag is in use
            if ($this->bagModel->isBagInUse($bagId)) {
                echo json_encode(['success' => false, 'message' => 'Cannot remove bag. It is currently in use.']);
                return;
            }

            // Call the model method to remove the collection bag
            $result = $this->bagModel->removeCollectionBag($bagId);

            if ($result) {
                // Return success response
                echo json_encode(['success' => true]);
            } else {
                // Handle error
                echo json_encode(['success' => false, 'message' => 'Failed to remove collection bag.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    /**
     * Deletes a bag's QR code image
     */
    public function deleteBagQR()
    {
        // Get the JSON input
        $input = json_decode(file_get_contents('php://input'), true);

        // Check if the image path is provided
        if (isset($input['image_path'])) {
            $imagePath = $input['image_path'];

            // Debugging: Log the full path
            error_log("Full path to image: " . $imagePath);

            // Check if the file exists
            if (file_exists($imagePath)) {
                // Attempt to delete the file
                if (unlink($imagePath)) {
                    // File deleted successfully
                    echo json_encode(['success' => true, 'message' => 'Image deleted successfully.']);
                } else {
                    // Failed to delete the file
                    echo json_encode(['success' => false, 'message' => 'Failed to delete the image.']);
                }
            } else {
                // File does not exist
                echo json_encode(['success' => false, 'message' => 'Image file not found.']);
            }
        } else {
            // No image path provided
            echo json_encode(['success' => false, 'message' => 'No image path provided.']);
        }
    }

    //==============================================================================
    // COLLECTION MANAGEMENT
    //==============================================================================
    
    /**
     * Gets pending collection requests
     */
    public function getCollectionRequests()
    {
        $collections = $this->collectionModel->getPendingCollections();
        header('Content-Type: application/json');
        echo json_encode($collections);
    }

    /**
     * Gets details for a specific collection
     * @param int $id The collection ID
     */
    public function getCollectionDetails($id)
    {
        // Validate ID
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid collection ID']);
            return;
        }

        // Get collection details
        $collection = $this->collectionModel->getCollectionDetails($id);

        if (!$collection) {
            http_response_code(404);
            echo json_encode(['error' => 'Collection not found']);
            return;
        }

        // Get bags associated with the collection
        $bags = $this->collectionModel->getBagsByCollectionId($id);

        // Format the response
        $response = [
            'collection_id' => $collection->collection_id,
            'collection_status' => $collection->collection_status,
            'created_at' => $collection->created_at,
            'start_time' => $collection->start_time,
            'end_time' => $collection->end_time,
            'total_quantity' => $collection->total_quantity,
            'fertilizer_distributed' => $collection->fertilizer_distributed,
            'schedule_id' => $collection->schedule_id,
            'day' => $collection->day,
            'route_id' => $collection->route_id,
            'route_name' => $collection->route_name,
            'number_of_suppliers' => $collection->number_of_suppliers,
            'driver_id' => $collection->driver_id,
            'driver_status' => $collection->driver_status,
            'first_name' => $collection->first_name,
            'last_name' => $collection->last_name,
            'shift_id' => $collection->shift_id,
            'shift_start' => $collection->shift_start,
            'shift_end' => $collection->shift_end,
            'shift_name' => $collection->shift_name,
            'bags' => $bags  // Include the bags in the response
        ];

        header('Content-Type: application/json');
        echo json_encode($response);
    }

    /**
     * Approves a collection request
     */
    public function approveCollection()
    {
        // Check if it's an AJAX request
        if (!$this->isAjaxRequest()) {
            redirect('pages/error');
            return;
        }

        // Get the input data
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate input
        if (!isset($data['collection_id']) || !is_numeric($data['collection_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid collection ID']);
            return;
        }

        // Prepare the data for updating
        $collectionId = $data['collection_id'];
        // $startTime = $data['start_time'];
        // $vehicleManagerId = $_SESSION['user_id'];
        // $vehicleManagerApprovedAt = $data['vehicle_manager_approved_at'];
        // $bags = $data['bags']; 
        // $bagsAdded = $data['bags_added'];

        // Update the collection in the model
        $result = $this->collectionModel->approveCollection($collectionId);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to approve collection']);
        }
    }

    /**
     * Gets collections for a specific date
     */
    public function getCollectionsByDate() {
        // Get the date from the request
        $date = $_GET['date'] ?? null;
    
        if ($date) {
            // Fetch collections for the specified date
            $collections = $this->collectionModel->getCollectionsByDate($date);
            
            // Return the collections as JSON
            header('Content-Type: application/json');
            echo json_encode($collections);
        } else {
            // Handle the case where no date is provided
            header('Content-Type: application/json');
            echo json_encode([]);
        }
    }

}
?>
