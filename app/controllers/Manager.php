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
require_once '../app/helpers/image_helper.php';

class Manager extends Controller
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
    private $supplierModel;
    private $appointmentModel;

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
        $this->supplierApplicationModel = $this->model('M_SupplierApplication');
        $this->supplierModel = $this->model('M_Supplier');
        $this->appointmentModel = $this->model('M_Appointment');
    }



     public function index() {
        // Get all applications
        $applications = $this->model('M_SupplierApplication')->getAllApplications();
        
        // Get approved applications pending role assignment
        $approvedPendingRole = $this->model('M_SupplierApplication')->getApprovedPendingRoleApplications();

        $data = [
            'applications' => $applications,
            'approved_pending_role' => $approvedPendingRole
        ];

        // Load view
        $this->view('supplier_manager/v_applications', $data);
    }

    /** 
     * Application Management
     * ------------------------------------------------------------
     */

     public function viewApplication($applicationId) {
        // Load the model
        $supplierApplicationModel = $this->model('M_SupplierApplication');
        
        // Get application details using the model
        $application = $supplierApplicationModel->getApplicationById($applicationId);
    
        // If application not found, redirect with error
        if (!$application) {
            redirect('manager/applications');
        }
    
        $profile = $supplierApplicationModel->getProfileInfo($application->user_id);
        $bankInfo = $supplierApplicationModel->getBankInfo($applicationId);
        $documents = $supplierApplicationModel->getApplicationDocuments($applicationId);
        
        // Get cultivation data (appears to be part of the application in your view)
        $cultivation = (object)[
            'tea_cultivation_area' => $application->tea_cultivation_area,
            'plant_age' => $application->plant_age,
            'monthly_production' => $application->monthly_production
        ];
        
        // Get location data
        $location = (object)[
            'latitude' => $application->latitude,
            'longitude' => $application->longitude
        ];
        
        // Get reviewer information if application is assigned
        $reviewer = null;
        if (!empty($application->reviewed_by)) {
            $reviewer = $this->model('M_User')->getUserById($application->reviewed_by);
        }
    
        // Prepare data array matching the view's expected structure
        $data = [
            'application' => (array)$application,
            'profile' => $profile,
            'bank_info' => $bankInfo,
            'documents' => $documents,
            'cultivation' => $cultivation,
            'location' => $location,
            'reviewer' => $reviewer
        ];
    
        // Load the view
        $this->view('supplier_manager/v_view_application', $data);
    }

    public function assignApplication($applicationId) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    

        $supplierApplicationModel = $this->model('M_SupplierApplication');
    
        $supplierApplicationModel->updateApplicationStatus($applicationId, $_SESSION['manager_id'], 'under_review');

    
        redirect('manager/');
    }

    public function approveApplication($applicationId) {
        /* 
        WE NEED TO FOLLOW THESE STEPS
        1. we need to update the application status (that is already done)
        2. we need to create a supplier account for this user
        3. we need to copy the details from the application to suppliers entry
        4. we need to change the users role to 5 for supplier accees
        -simaak
        */

        $supplierApplicationModel = $this->model('M_SupplierApplication');
        $userModel = $this->model('M_User');
        $application = $supplierApplicationModel->getApplicationById($applicationId);

        if (!$application) {
            // small validation, but if we are here then ofc the application exists
            redirect('manager/');
        }

        $userUpdated = $userModel->updateRole($application->user_id, 5);

        if (!$userUpdated) {
            redirect('manager/');
        }

        $profile = $userModel->getProfileByUserId($application->user_id);
        $supplierExpectedAmount  = ($application->monthly_production)/4.0;


        // Create supplier data array
        $supplierData = [
            'profile_id' => $profile->profile_id,
            'contact_number' => $profile->contact_number, 
            'application_id' => $applicationId,
            'latitude' => $application->latitude,
            'longitude' => $application->longitude,
            'is_active' => 1,
            'is_deleted' => 0,
            'number_of_collections' => 0,
            'average_collection' => $supplierExpectedAmount
        ];

        $supplierCreated = $this->supplierModel->createSupplier($supplierData);

        if (!$supplierCreated) {
            redirect('manager/');
        }

        $supplierApplicationModel->updateApplicationStatus($applicationId, $_SESSION['manager_id'], 'approved');

        redirect('manager/');
    }

    public function rejectApplication($applicationId) {
        $supplierApplicationModel = $this->model('M_SupplierApplication');
        $supplierApplicationModel->updateApplicationStatus($applicationId, $_SESSION['manager_id'], 'rejected');
        redirect('manager/');
    }

    public function confirmSupplierRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $applicationId = $data['application_id'];

            $result = $this->supplierModel->confirmSupplierRole($applicationId);
            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to confirm role. Check application ID and user data.']);
            }
        } else {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid request.']);
        }
    }


    /** 
     * Supplier Management
     * ------------------------------------------------------------
     */

     public function suppliers() {
        // Get all suppliers from the database
        $suppliers = $this->supplierModel->getAllSuppliers();

        $data = [
            'suppliers' => $suppliers
        ];

        $this->view('supplier_manager/v_suppliers', $data);
    }


    public function getSupplierRecords($collectionId){
        $records = $this->collectionSupplierRecordModel->getSupplierRecords($collectionId);
        echo json_encode($records);
    }


    public function updateSupplierRecord(){
        $data = json_decode(file_get_contents('php://input'));
        $success = $this->collectionSupplierRecordModel->updateSupplierRecord($data);
        echo json_encode(['success' => $success]);
    }


    public function addSupplierRecord(){
        $data = json_decode(file_get_contents('php://input'));
        $success = $this->collectionSupplierRecordModel->addSupplierRecord($data);
        echo json_encode(['success' => $success]);
    }

    public function updateSupplierStatus($recordId){
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
     * Vehicle Management
     * ------------------------------------------------------------
     */

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
            $allVehicles = $this->vehicleModel->getFilteredVehicles($license_plate, $vehicle_type, $capacity, $make, $model, $manufacturing_year);
        } else {
            // Otherwise, fetch all vehicles
            $allVehicles = $this->vehicleModel->getAllAvailableVehicles();
        }
    
        // Get total vehicles and available vehicles for display
        $totalVehicles = count($allVehicles);
        $availableVehicles = count(array_filter($allVehicles, function($vehicle) {
            return $vehicle->status === 'Available';
        }));
    
        // Prepare data to pass to the view
        $data = [
            'allVehicles' => $allVehicles,
            'totalVehicles' => $totalVehicles,
            'availableVehicles' => $availableVehicles
        ];
    
        $this->view('vehicle_manager/v_vehicle', $data);
    }   
    
    public function viewVehicle($vehicle_id) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Fetch vehicle details from the model
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);

        // Check if vehicle exists
        if (!$vehicle) {
            flash('vehicle_not_found', 'Vehicle not found.');
            redirect('manager/vehicle'); // Redirect to the vehicle list or another page
        }

        // Fetch collection history
        $collectionHistory = $this->vehicleModel->getVehicleCollectionHistory($vehicle_id);
        
        // Fetch upcoming schedules
        $upcomingSchedules = $this->vehicleModel->getUpcomingSchedulesForVehicle($vehicle_id);

        $this->view('vehicle_manager/v_vehicle_profile', [
            'vehicle' => $vehicle,
            'collectionHistory' => $collectionHistory,
            'upcomingSchedules' => $upcomingSchedules
        ]);
    }

    public function createVehicle() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Get data for the form
        $data = [
            'license_plate' => '',
            'status' => 'Available', // Default status
            'capacity' => '',
            'vehicle_type' => '',
            'make' => '',
            'model' => '',
            'manufacturing_year' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and get POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'license_plate' => trim($_POST['license_plate']),
                'status' => trim($_POST['status']),
                'capacity' => trim($_POST['capacity']),
                'vehicle_type' => trim($_POST['vehicle_type']),
                'make' => trim($_POST['make']),
                'model' => trim($_POST['model']),
                'manufacturing_year' => trim($_POST['manufacturing_year']),
                'error' => ''
            ];

            // Validate data
            if (empty($data['license_plate']) || 
                empty($data['capacity']) || 
                empty($data['vehicle_type']) || 
                empty($data['make']) || 
                empty($data['model']) || 
                empty($data['manufacturing_year'])) {
                $data['error'] = 'Please fill in all fields';
            } else {
                // Check if the license plate is unique
                if ($this->vehicleModel->isLicensePlateTaken($data['license_plate'])) {
                    $data['error'] = 'This license plate is already taken.';
                } else {
                    // Handle image upload
                    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $uploadResult = uploadVehicleImage($_FILES['image'], $data['license_plate']);
                        if ($uploadResult['success']) {
                            $data['image_path'] = $uploadResult['path']; // Store the file path
                        } else {
                            $data['error'] = $uploadResult['message'];
                        }
                    } else {
                        $data['error'] = 'Image file is required.';
                    }

                    // If no errors, create vehicle
                    if (empty($data['error'])) {
                        if ($this->vehicleModel->createVehicle($data)) {
                            flash('vehicle_success', 'Vehicle created successfully');
                            redirect('manager/vehicle'); // Redirect to the vehicle list or another page
                        } else {
                            $data['error'] = 'Something went wrong. Please try again.';
                        }
                    }
                }
            }
        }

        // Load the view for creating a vehicle
        $this->view('vehicle_manager/v_create_vehicle', $data);
    }

    public function updateVehicle($vehicle_id) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Fetch the current vehicle details
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);
        if (!$vehicle) {
            flash('vehicle_not_found', 'Vehicle not found.');
            redirect('manager/vehicle');
        }

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
            if ($vehicle->status === 'In Use') {
                // Handle the case where the vehicle is in use
                flash('update_error', 'Cannot update vehicle. The vehicle is currently in use.');
                redirect('manager/vehicle');
            }

            // Initialize the data array for updating
            $data = [
                'vehicle_id' => $vehicle_id,
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
                $uploadResult = uploadVehicleImage($_FILES['vehicle_image'], $license_plate);
                if ($uploadResult['success']) {
                    // Update the image path in the data array
                    $data['image_path'] = $uploadResult['path']; // Store relative path
                } else {
                    // Handle file upload error
                    flash('upload_error', $uploadResult['message']);
                    redirect('manager/vehicle');
                }
            }

            // Update vehicle details in the database
            if ($this->vehicleModel->updateVehicle($data)) {
                flash('update_success', 'Vehicle updated successfully.');
                redirect('manager/vehicle');
            } else {
                flash('update_error', 'Failed to update vehicle. Please try again.');
                redirect('manager/vehicle');
            }
        }

        // Load the view for updating the vehicle
        $this->view('vehicle_manager/v_update_vehicle', ['vehicle' => $vehicle]);
    }

    public function getVehicleById($id){
        $vehicle = $this->vehicleModel->getVehicleById($id);
        if ($vehicle) {
            echo json_encode(['success' => true, 'vehicle' => $vehicle]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function deleteVehicle($id){

        // NEED TO DOUBLE CHECK THIS!!! A SIMPLE INSTRUCTION BUT ITS NOT DELETING. IDK ...

        // if ($this->vehicleModel->isVehicleInSchedule($id)) {
        //     if ($this->vehicleModel->markAsDeleted($id)) {
        //         error_log("Vehicle " . $id . " marked as deleted");
        //         flash('delete_success', 'Vehicle marked as deleted');
        //     } else {
        //         flash('delete_error', 'Failed to mark vehicle as deleted');
        //     }
        // } else {
        //     if ($this->vehicleModel->deleteVehicle($id)) {
        //         error_log("Vehicle " . $id . " deleted successfully");
        //         flash('delete_success', 'Vehicle deleted successfully');
        //     } else {
        //         flash('delete_error', 'Failed to delete vehicle from database');
        //     }
        // }

        if ($this->vehicleModel->markAsDeleted($id)) {
            error_log("Vehicle " . $id . " marked as deleted");
            flash('delete_success', 'Vehicle marked as deleted');
        } else {
            flash('delete_error', 'Failed to mark vehicle as deleted');
        }

        redirect('manager/vehicle');
    }

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
                    header('Location: ' . URLROOT . '/manager/vehicle');
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
                    header('Location: ' . URLROOT . '/manager/vehicle');
                    exit();
                } else {
                    echo "Error removing vehicle.";
                }
            } else {
                echo "Vehicle not found.";
            }
        }
    }

    public function checkVehicleUsage($id){
        $schedules = $this->scheduleModel->getSchedulesByVehicleId($id);
        $collections = $this->collectionModel->getCollectionsByVehicleId($id);

        echo json_encode([
            'inUse' => !empty($schedules) || !empty($collections),
            'schedules' => !empty($schedules),
            'collections' => !empty($collections)
        ]);
    }

    public function getVehicleDetails($id){
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


    /** 
     * Driver Management
     * ------------------------------------------------------------
     */

    public function driver() {
        // Initialize $data array first
        $data = [];

        // Get filter parameters
        $driver_id = isset($_GET['driver_id']) ? $_GET['driver_id'] : null;
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $nic = isset($_GET['nic']) ? $_GET['nic'] : null;
        $contact_number = isset($_GET['contact_number']) ? $_GET['contact_number'] : null;
        $license_number = isset($_GET['license_number']) ? $_GET['license_number'] : null;
        $driver_status = isset($_GET['driver_status']) ? $_GET['driver_status'] : null;

        // Get filtered or all drivers
        if ($driver_id || $name || $nic || $contact_number || $license_number || $driver_status) {
            $data['drivers'] = $this->driverModel->getFilteredDrivers($driver_id, $name, $nic, $contact_number, $license_number, $driver_status);
        } else {
            $data['drivers'] = $this->driverModel->getAllDrivers();
        }

        // Add other data to the array
        $data['total_drivers'] = $this->driverModel->getTotalDrivers();
        $data['on_duty_drivers'] = $this->driverModel->getDriversOnDuty();
        
        $this->view('vehicle_manager/v_driver', $data);
    }

    public function viewDriver($driver_id) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $driver = $this->driverModel->getDriverById($driver_id);
        if (!$driver) {
            flash('driver_not_found', 'Driver not found.');
            redirect('manager/driver'); 
        }

        $collectionHistory = $this->driverModel->getDriverCollectionHistory($driver_id);
        
        $upcomingSchedules = $this->driverModel->getUpcomingSchedulesForDriver($driver_id);

        $this->view('vehicle_manager/v_driver_profile', [
            'driver' => $driver,
            'collectionHistory' => $collectionHistory,
            'upcomingSchedules' => $upcomingSchedules
        ]);
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

            redirect('manager/driver'); // Redirect to the drivers page
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

    public function updateDriver($id) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get the driver data
        $driver = $this->driverModel->getDriverById($id);
        if (!$driver) {
            flash('driver_error', 'Driver not found', 'alert alert-danger');
            redirect('manager/driver');
        }
        
        // Get the profile data
        $profile = $this->userModel->getProfileById($driver->profile_id);
        if (!$profile) {
            flash('driver_error', 'Profile not found', 'alert alert-danger');
            redirect('manager/driver');
        }
        
        // Initialize data array
        $data = [
            'driver' => $driver,
            'profile' => $profile,
            'error' => ''
        ];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get submitted data
            $data = [
                'driver' => $driver,
                'profile' => $profile,
                'driver_id' => $id,
                'profile_id' => $driver->profile_id,
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'contact_number' => trim($_POST['contact_number']),
                'emergency_contact' => trim($_POST['emergency_contact']),
                'address_line1' => trim($_POST['address_line1']),
                'address_line2' => isset($_POST['address_line2']) ? trim($_POST['address_line2']) : '',
                'city' => trim($_POST['city']),
                'license_expiry_date' => trim($_POST['license_expiry_date']),
                'status' => trim($_POST['status']),
                'error' => ''
            ];
            
            // Validate inputs
            if (empty($data['first_name'])) {
                $data['error'] = 'Please enter first name';
            } elseif (empty($data['last_name'])) {
                $data['error'] = 'Please enter last name';
            } elseif (empty($data['date_of_birth'])) {
                $data['error'] = 'Please enter date of birth';
            } elseif (empty($data['contact_number'])) {
                $data['error'] = 'Please enter contact number';
            } elseif (empty($data['emergency_contact'])) {
                $data['error'] = 'Please enter emergency contact';
            } elseif (empty($data['address_line1'])) {
                $data['error'] = 'Please enter address line 1';
            } elseif (empty($data['city'])) {
                $data['error'] = 'Please enter city';
            } elseif (empty($data['license_expiry_date'])) {
                $data['error'] = 'Please enter license expiry date';
            } elseif (strtotime($data['license_expiry_date']) <= time()) {
                $data['error'] = 'License expiry date must be in the future';
            }
            
            // Handle image upload if a new image was provided
            if (isset($_FILES['driver_image']) && $_FILES['driver_image']['error'] === 0) {
                $uniqueId = $profile->nic . '_' . time();
                $uploadResult = uploadDriverImage($_FILES['driver_image'], $uniqueId);
                
                if ($uploadResult['success']) {
                    $data['image_path'] = $uploadResult['path'];
                } else {
                    $data['error'] = $uploadResult['message'];
                }
            }
            
            // Update records if no errors
            if (empty($data['error'])) {
                try {
                    
                    // Update profile information
                    $profileData = [
                        'profile_id' => $data['profile_id'],
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'date_of_birth' => $data['date_of_birth'],
                        'contact_number' => $data['contact_number'],
                        'emergency_contact' => $data['emergency_contact'],
                        'address_line1' => $data['address_line1'],
                        'address_line2' => $data['address_line2'],
                        'city' => $data['city']
                    ];
                    
                    if (!$this->userModel->updateProfile($profileData)) {
                        throw new Exception('Failed to update profile');
                    }
                    
                    // Update driver information
                    $driverData = [
                        'driver_id' => $data['driver_id'],
                        'license_expiry_date' => $data['license_expiry_date'],
                        'status' => $data['status']
                    ];
                    
                    // Add image path if a new image was uploaded
                    if (isset($data['image_path'])) {
                        $driverData['image_path'] = $data['image_path'];
                    }
                    
                    if (!$this->driverModel->updateDriver($driverData)) {
                        throw new Exception('Failed to update driver');
                    }
                    
                    
                    flash('driver_success', 'Driver updated successfully');
                    redirect('manager/driver');
                    
                } catch (Exception $e) {
                    $data['error'] = 'Error updating driver: ' . $e->getMessage();
                }
            }
        }
        
        $this->view('vehicle_manager/v_update_driver', $data);
    }

    public function createDriver() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Initialize data array for the view
        $data = [
            'email' => '',
            'password' => '',
            'confirm_password' => '',
            'first_name' => '',
            'last_name' => '',
            'nic' => '',
            'date_of_birth' => '',
            'contact_number' => '',
            'emergency_contact' => '',
            'address_line1' => '',
            'address_line2' => '',
            'city' => '',
            'license_number' => '',
            'license_expiry_date' => '',
            'hire_date' => date('Y-m-d'), // Default to today
            'status' => 'Active',
            'error' => ''
        ];
        
        // Process form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get submitted data
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'nic' => trim($_POST['nic']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'contact_number' => trim($_POST['contact_number']),
                'emergency_contact' => trim($_POST['emergency_contact']),
                'address_line1' => trim($_POST['address_line1']),
                'address_line2' => isset($_POST['address_line2']) ? trim($_POST['address_line2']) : '',
                'city' => trim($_POST['city']),
                'license_expiry_date' => trim($_POST['license_expiry_date']),
                'hire_date' => trim($_POST['hire_date']),
                'status' => trim($_POST['status']),
                'error' => ''
            ];
            
            // Validate email
            if (empty($data['email'])) {
                $data['error'] = 'Please enter an email address';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email address';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['error'] = 'Email is already taken';
            }
            
            // Validate password
            if (empty($data['password'])) {
                $data['error'] = 'Please enter a password';
            } elseif (strlen($data['password']) < 6) {
                $data['error'] = 'Password must be at least 6 characters';
            } elseif ($data['password'] !== $data['confirm_password']) {
                $data['error'] = 'Passwords do not match';
            }
            
            // Validate personal information
            if (empty($data['first_name'])) {
                $data['error'] = 'Please enter a first name';
            }
            
            if (empty($data['last_name'])) {
                $data['error'] = 'Please enter a last name';
            }
            
            if (empty($data['nic'])) {
                $data['error'] = 'Please enter a NIC number';
            } elseif ($this->userModel->findProfileByNIC($data['nic'])) {
                $data['error'] = 'This NIC is already registered';
            }
            
            if (empty($data['date_of_birth'])) {
                $data['error'] = 'Please enter date of birth';
            }
            
            if (empty($data['contact_number'])) {
                $data['error'] = 'Please enter a contact number';
            }
            
            if (empty($data['address_line1'])) {
                $data['error'] = 'Please enter address line 1';
            }
            
            if (empty($data['city'])) {
                $data['error'] = 'Please enter city';
            }
            
            // Validate driver information
            if (empty($data['license_number'])) {
                $data['error'] = 'Please enter a license number';
            } elseif ($this->driverModel->findDriverByLicenseNumber($data['license_number'])) {
                $data['error'] = 'This license number is already registered';
            }
            
            if (empty($data['license_expiry_date'])) {
                $data['error'] = 'Please enter license expiry date';
            } elseif (strtotime($data['license_expiry_date']) <= time()) {
                $data['error'] = 'License expiry date must be in the future';
            }
            
            if (empty($data['hire_date'])) {
                $data['error'] = 'Please enter hire date';
            }
            
            // Handle image upload
            if (isset($_FILES['driver_image']) && $_FILES['driver_image']['error'] == 0) {
                $uniqueId = $data['nic'] . '_' . time(); // Create a unique identifier
                $uploadResult = uploadDriverImage($_FILES['driver_image'], $uniqueId);
                if ($uploadResult['success']) {
                    $data['image_path'] = $uploadResult['path']; // Store the file path
                } else {
                    $data['error'] = $uploadResult['message'];
                }
            } else {
                $data['error'] = 'Driver photo is required.';
            }
            
            // If no errors, proceed with creating the driver
            if (empty($data['error'])) {
                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                
                try {

                    
                    // 1. Create user account
                    $userData = [
                        'email' => $data['email'],
                        'password' => $data['password'],
                        'role_id' => 2, // Assuming role_id 2 is for drivers
                        'account_status' => 'Active'
                    ];
                    
                    $user_id = $this->userModel->registerUser($userData);
                    
                    if (!$user_id) {
                        throw new Exception('Failed to create user account');
                    }
                    
                    // 2. Create profile
                    $profileData = [
                        'user_id' => $user_id,
                        'first_name' => $data['first_name'],
                        'last_name' => $data['last_name'],
                        'nic' => $data['nic'],
                        'date_of_birth' => $data['date_of_birth'],
                        'contact_number' => $data['contact_number'],
                        'emergency_contact' => $data['emergency_contact'],
                        'address_line1' => $data['address_line1'],
                        'address_line2' => $data['address_line2'],
                        'city' => $data['city']
                    ];
                    
                    $profile_id = $this->userModel->createProfile($profileData);
                    
                    if (!$profile_id) {
                        throw new Exception('Failed to create profile');
                    }
                    
                    // 3. Create driver record
                    $driverData = [
                        'profile_id' => $profile_id,
                        'license_number' => $data['license_number'],
                        'license_expiry_date' => $data['license_expiry_date'],
                        'hire_date' => $data['hire_date'],
                        'status' => $data['status'],
                        'image_path' => $data['image_path']
                    ];
                    
                    $driver_id = $this->driverModel->createDriver($driverData);
                    
                    if (!$driver_id) {
                        throw new Exception('Failed to create driver record');
                    }
                    

                    
                    // Success - redirect with success message
                    flash('driver_success', 'Driver created successfully');
                    redirect('manager/driver');
                    
                } catch (Exception $e) {
                    // Rollback transaction on error
                    $this->db->rollBack();
                    $data['error'] = 'Error creating driver: ' . $e->getMessage();
                }
            }
        }
        
        // Load view with data
        $this->view('vehicle_manager/v_create_driver', $data);
    }

    public function deleteDriver($id) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get the driver data
        $driver = $this->driverModel->getDriverById($id);
        if (!$driver) {
            flash('driver_error', 'Driver not found', 'alert alert-danger');
            redirect('manager/driver');
        }
        
        // Check if the driver is assigned to any active schedules
        $schedulesWithDriver = $this->scheduleModel->getSchedulesByDriverId($id);
        
        if ($schedulesWithDriver) {
            // Driver is in one or more schedules, show error
            $scheduleNames = [];
            foreach ($schedulesWithDriver as $schedule) {
                $scheduleNames[] = "Schedule ID: {$schedule->schedule_id} ({$schedule->day}, {$schedule->start_time} - {$schedule->end_time})";
            }
            
            $schedulesStr = implode("<br>", $scheduleNames);
            flash('driver_error', "Cannot delete this driver as they are currently assigned to the following schedules:<br>{$schedulesStr}<br>Please reassign these schedules before deleting the driver.", 'alert alert-danger');
            redirect('manager/driver');
        }
        
        // Not in any schedules, so we can mark as deleted
        if ($this->driverModel->markDriverAsDeleted($id)) {
            flash('driver_success', 'Driver has been successfully marked as deleted');
            redirect('manager/driver');
        } else {
            flash('driver_error', 'Something went wrong while trying to delete the driver', 'alert alert-danger');
            redirect('manager/driver');
        }
    }


    /** 
     * Route Management
     * ------------------------------------------------------------
     */

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

    public function createRoute(){
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

    public function getRouteSuppliers($routeId){
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

    public function getRouteDetails($routeId){
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

    public function getRoutesByDay($day){
        $routes = $this->routeModel->getRoutesByDay($day);
        echo json_encode(['routes' => $routes]);
    }

    public function removeCollectionSupplier($recordId){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            if ($this->collectionSupplierRecordModel->removeCollectionSupplier($recordId)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    private function getCurrentStop($supplierRecords){
        // Find the last collected supplier
        foreach ($supplierRecords as $index => $record) {
            if ($record->status === 'Collected') {
                return $index;
            }
        }
        return 0; // Return 0 if no collections yet
    }


    /** 
     * Collection Management
     * ------------------------------------------------------------
     */

    public function collection(){
        // Get dashboard stats from the model
        $stats = $this->vehicleManagerModel->getDashboardStats();
        $stats['collections'] = (array)$stats['collections'];

        // Retrieve filter parameters from the GET request
        $collection_id = isset($_GET['collection_id']) ? $_GET['collection_id'] : null;
        $schedule_id = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;
        $min_quantity = isset($_GET['min_quantity']) ? $_GET['min_quantity'] : null;
        $max_quantity = isset($_GET['max_quantity']) ? $_GET['max_quantity'] : null;
        $bags_added = isset($_GET['bags_added']) ? $_GET['bags_added'] : null;

        // Fetch collections based on filters
        if ($collection_id || $schedule_id || $status || $start_date || $end_date || $min_quantity || $max_quantity || $bags_added !== null) {
            $allCollections = $this->collectionModel->getFilteredCollections(
                $collection_id, 
                $schedule_id, 
                $status, 
                $start_date, 
                $end_date, 
                $min_quantity, 
                $max_quantity, 
                $bags_added
            );
        } else {
            // Otherwise, fetch all collections
            $allCollections = $this->collectionModel->getAllCollections();
        }

        // Fetch all necessary data for the dropdowns
        $schedules = $this->scheduleModel->getAllSchedules();
        $collectionSchedules = $this->scheduleModel->getSchedulesForNextWeek(); 
        $todayRoutes = $this->routeModel->getTodayAssignedRoutes();

        $data = [
            'stats' => $stats,
            'schedules' => $schedules,
            'all_collections' => $allCollections,
            'collectionSchedules' => $collectionSchedules,
            'todayRoutes' => $todayRoutes,
            // Pass the filter values back to the view to maintain state
            'filters' => [
                'collection_id' => $collection_id,
                'schedule_id' => $schedule_id,
                'status' => $status,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'min_quantity' => $min_quantity,
                'max_quantity' => $max_quantity,
                'bags_added' => $bags_added
            ]
        ];

        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_collection_0', $data);
    }


    /** 
     * Schedule Management
     * ------------------------------------------------------------
     */

    public function schedule()
    {
        // Get dashboard stats from the model
        $totalSchedules = $this->scheduleModel->getTotalSchedules();
        $availableSchedules = $this->scheduleModel->getActiveSchedulesCount();

        // Fetch all necessary data for the dropdowns
        $routes = $this->routeModel->getAllRoutes();
        $drivers = $this->driverModel->getUnassignedDrivers();
        $vehicles = $this->vehicleModel->getAllAvailableVehicles();
        $shifts = $this->shiftModel->getAllShifts();
        $schedules = $this->scheduleModel->getAllSchedules();

        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_collectionschedule', [
            'totalSchedules' => $totalSchedules, // Total schedules
            'availableSchedules' => $availableSchedules, // Currently ongoing schedules
            'routes' => $routes,
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'shifts' => $shifts,
            'schedules' => $schedules
        ]);
    }

    public function createSchedule() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $drivers = $this->driverModel->getAllDrivers();
        $routes = $this->routeModel->getAllUnAssignedRoutes();

        $data = [
            'drivers' => $drivers,
            'routes' => $routes,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and get POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'day' => trim($_POST['day']),
                'driver_id' => trim($_POST['driver_id']),
                'route_id' => trim($_POST['route_id']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'drivers' => $drivers,
                'routes' => $routes,
                'error' => ''
            ];

            // Validate data
            if (empty($data['day']) || 
                empty($data['driver_id']) || empty($data['route_id']) || 
                empty($data['start_time']) || empty($data['end_time'])) {
                $data['error'] = 'Please fill in all fields';
            } else {
                // Validate time duration
                $startTime = strtotime("2000-01-01 " . $data['start_time']);
                $endTime = strtotime("2000-01-01 " . $data['end_time']);
                
                // If end time is earlier than start time, assume it's the next day
                if ($endTime < $startTime) {
                    $endTime = strtotime("2000-01-02 " . $data['end_time']);
                }
                
                // Check if end time is before 10 PM
                $tenPM = strtotime("2000-01-01 22:00:00");
                if ($endTime > $tenPM) {
                    $data['error'] = 'Shift end time cannot be after 10 PM.';
                } else {
                    // Check for a minimum gap of 2 hours between shifts
                    $minGap = 2 * 60 * 60; // 2 hours in seconds
                    if (($endTime - $startTime) < $minGap) {
                        $data['error'] = 'There must be at least a 2-hour gap between shifts.';
                    } else {
                        // Check if the driver is already scheduled for this day and time
                        $driverScheduleConflict = $this->scheduleModel->checkDriverScheduleConflict(
                            $data['driver_id'],
                            $data['day'],
                            $data['start_time'],
                            $data['end_time']
                        );

                        // Check if the route is already scheduled for this day and time
                        $routeScheduleConflict = $this->scheduleModel->checkRouteScheduleConflict(
                            $data['route_id'],
                            $data['day'],
                            $data['start_time'],
                            $data['end_time']
                        );

                        if ($driverScheduleConflict) {
                            $data['error'] = 'This driver is already scheduled during this time period.';
                        } elseif ($routeScheduleConflict) {
                            $data['error'] = 'This route is already scheduled during this time period.';
                        } else {
                            // Create schedule
                            if ($this->scheduleModel->create($data)) {
                                flash('schedule_success', 'Schedule created successfully');
                                redirect('manager/schedule');
                            } else {
                                $data['error'] = 'Something went wrong. Please try again. Debug info: ' . 
                                'day: ' . $data['day'] . ', ' .
                                'driver_id: ' . $data['driver_id'] . ', ' .
                                'route_id: ' . $data['route_id'] . ', ' .
                                'start_time: ' . $data['start_time'] . ', ' .
                                'end_time: ' . $data['end_time'];
                            }
                        }
                    }
                }
            }
        }

        // Load the view for creating a schedule
        $this->view('vehicle_manager/v_create_schedule', $data);
    }

    public function updateSchedule($scheduleId = null) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Check if schedule ID is provided
        if (!$scheduleId) {
            flash('schedule_error', 'Invalid schedule ID');
            redirect('manager/collectionschedule');
        }

        // Get the existing schedule
        $schedule = $this->scheduleModel->getScheduleById($scheduleId);
        
        // Check if schedule exists
        if (!$schedule) {
            flash('schedule_error', 'Schedule not found');
            redirect('manager/collectionschedule');
        }

        // Get data for the form
        $drivers = $this->driverModel->getAllDrivers();
        $routes = $this->routeModel->getAllUndeletedRoutes();

        $data = [
            'schedule' => $schedule,
            'drivers' => $drivers,
            'routes' => $routes,
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize and get POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'schedule_id' => $scheduleId,
                'day' => trim($_POST['day']),
                'driver_id' => trim($_POST['driver_id']),
                'route_id' => trim($_POST['route_id']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'schedule' => $schedule,
                'drivers' => $drivers,
                'routes' => $routes,
                'error' => ''
            ];

            // Validate data
            if (empty($data['day']) || 
                empty($data['driver_id']) || empty($data['route_id']) || 
                empty($data['start_time']) || empty($data['end_time'])) {
                $data['error'] = 'Please fill in all fields';
            } else {
                // Validate time duration
                $startTime = strtotime("2000-01-01 " . $data['start_time']);
                $endTime = strtotime("2000-01-01 " . $data['end_time']);
                
                // If end time is earlier than start time, assume it's the next day
                if ($endTime < $startTime) {
                    $endTime = strtotime("2000-01-02 " . $data['end_time']);
                }
                
                $duration = $endTime - $startTime;
                $maxDuration = 24 * 60 * 60; // 24 hours in seconds
                
                // Check if end time is before 10 PM
                $tenPM = strtotime("2000-01-01 22:00:00");
                if ($endTime > $tenPM) {
                    $data['error'] = 'Shift end time cannot be after 10 PM.';
                } elseif ($duration > $maxDuration) {
                    $data['error'] = 'Schedule duration cannot exceed 24 hours.';
                } elseif (($endTime - $startTime) < (2 * 60 * 60)) { // 2 hours in seconds
                    $data['error'] = 'There must be at least a 2-hour gap between shifts.';
                } else {
                    // Check for conflicts only if the driver or day or time has changed
                    $driverConflict = false;
                    $routeConflict = false;
                    
                    if ($data['driver_id'] != $schedule->driver_id || 
                        $data['day'] != $schedule->day || 
                        $data['start_time'] != $schedule->start_time || 
                        $data['end_time'] != $schedule->end_time) {
                        
                        // Check if the driver is already scheduled for this day and time
                        $driverConflict = $this->scheduleModel->checkDriverScheduleConflictExcludingCurrent(
                            $data['driver_id'],
                            $data['day'],
                            $data['start_time'],
                            $data['end_time'],
                            $scheduleId
                        );
                    }
                    
                    if ($data['route_id'] != $schedule->route_id || 
                        $data['day'] != $schedule->day || 
                        $data['start_time'] != $schedule->start_time || 
                        $data['end_time'] != $schedule->end_time) {
                        
                        // Check if the route is already scheduled for this day and time
                        $routeConflict = $this->scheduleModel->checkRouteScheduleConflictExcludingCurrent(
                            $data['route_id'],
                            $data['day'],
                            $data['start_time'],
                            $data['end_time'],
                            $scheduleId
                        );
                    }

                    if ($driverConflict) {
                        $data['error'] = 'This driver is already scheduled during this time period.';
                    } elseif ($routeConflict) {
                        $data['error'] = 'This route is already scheduled during this time period.';
                    } else {
                        // Update schedule
                        if ($this->scheduleModel->updateSchedule($data)) {
                            flash('schedule_success', 'Schedule updated successfully');
                            redirect('manager/schedule');
                        } else {
                            $data['error'] = 'Something went wrong. Please try again.';
                        }
                    }
                }
            }
        }

        // Load the view for updating a schedule
        $this->view('vehicle_manager/v_update_schedule', $data);
    }

    /** 
     * Appointment Management
     * ------------------------------------------------------------
     */

    public function appointments() {
        $this->requireLogin(); // Assuming session check
        
        $managerId = $_SESSION['manager_id'];
    
        $timeSlots = $this->appointmentModel->getManagerTimeSlots($managerId);
        $incomingRequests = $this->appointmentModel->getIncomingRequests($managerId);
        $acceptedAppointments = $this->appointmentModel->getAcceptedAppointments($managerId);
    
        $this->view('supplier_manager/v_appointments', [
            'timeSlots' => $timeSlots,
            'incomingRequests' => $incomingRequests,
            'acceptedAppointments' => $acceptedAppointments
        ]);
    }

    public function createSlot() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'manager_id' => $_SESSION['manager_id'],
                'date' => trim($_POST['date']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time'])
            ];
    
            if ($this->appointmentModel->createSlot($data)) {
                flash('slot_message', 'Time slot created successfully.');
                redirect('manager/appointments');
            } else {
                redirect('manager/createSlot');
            }
        } else {
            // Load the form view for creating a slot
            $data = [
                'date' => '',
                'start_time' => '',
                'end_time' => ''
            ];
    
            $this->view('supplier_manager/v_create_slot', $data);
        }
    }


    /** 
     * Bag Management
     * ------------------------------------------------------------
     */

    public function createBag(){
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

    private function generateQRCode($bagId){
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
    
    public function getBags()
    {
        // Fetch bags from the model
        $bags = $this->bagModel->getAllBags(); // Assuming this method exists in your model

        // Return the bags as a JSON response
        echo json_encode(['success' => true, 'bags' => $bags]);
    }

    public function getBagDetails($bagId){
        // Fetch bag details from the model
        $bag = $this->bagModel->getBagDetails($bagId); // Assuming this method exists in your model

        // Check if bag exists and return as JSON response
        if ($bag) {
            echo json_encode(['success' => true, 'bag' => $bag]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Bag not found.']);
        }
    }

    public function updateBag(){
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

    public function removeBag(){
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

    public function deleteBagQR(){
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

    /** 
     * Complaint Management
     * ------------------------------------------------------------
     */

    public function complaints()
     {
         $data = [];
 
         $this->view('supplier_manager/v_complaints', $data);
    }

    public function respondRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $requestId = trim($_POST['request_id']);
            $action = trim($_POST['action']);

            if ($action === 'accept') {
                // Accept the request and log it
                if ($this->appointmentModel->acceptRequest($requestId)) {
                    flash('request_message', 'Request accepted successfully.');
                } else {
                    flash('request_message', 'Failed to accept the request.');
                }
            } elseif ($action === 'reject') {
                // Reject the request
                $this->appointmentModel->rejectRequest($requestId);
                flash('request_message', 'Request rejected successfully.');
            }

            redirect('manager/appointments'); // Redirect back to appointments
        }
    }


    /** 
     * User Settings
     **/
    public function settings(){
        $data = [];
        $this->view('vehicle_manager/v_settings', $data);
    }

    public function personal_details(){
        $data = [];
        $this->view('vehicle_manager/v_personal_details', $data);
    }


}
?>
