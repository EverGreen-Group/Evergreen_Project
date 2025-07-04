<?php

require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/UserHelper.php';
require_once '../app/helpers/image_helper.php';

class Manager extends Controller
{
    //----------------------------------------
    // PROPERTIES
    //----------------------------------------
    private $routeModel;
    private $vehicleModel;
    private $scheduleModel;
    private $driverModel;
    private $userHelper;
    private $collectionModel;
    private $collectionSupplierRecordModel;
    private $userModel;
    private $bagModel;
    private $supplierModel;
    private $chatModel; 
    private $appointmentModel;
    private $notificationModel;
    private $logModel;

    //----------------------------------------
    // CONSTRUCTOR
    //----------------------------------------
    public function __construct()
    {
        requireAuth();

        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::MANAGER])) {

            redirect('');
            exit();
        }

        $this->routeModel = $this->model('M_Route');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->scheduleModel = $this->model('M_CollectionSchedule');
        $this->driverModel = $this->model('M_Driver');
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
        $this->userModel = $this->model('M_User');
        $this->bagModel = $this->model('M_CollectionBag');
        $this->supplierModel = $this->model('M_Supplier');
        $this->notificationModel = $this->model('M_Notification');
        $this->chatModel = $this->model('M_Chat');
        $this->appointmentModel = $this->model('M_Appointment');
        $this->logModel = $this->model('M_Log');

    }



    public function index() {

        $approvedPendingRole = $this->model('M_SupplierApplication')->getApprovedPendingRoleApplications();

        $filters = [
            'application_id' => isset($_GET['application_id']) ? $_GET['application_id'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'date-from' => isset($_GET['date-from']) ? $_GET['date-from'] : '',
            'date-to' => isset($_GET['date-to']) ? $_GET['date-to'] : ''
        ];
        
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; 
        $offset = ($page - 1) * $limit;

        $applications = $this->model('M_SupplierApplication')->getAllApplications($filters, $limit, $offset);
        
        $totalApplications = $this->model('M_SupplierApplication')->getTotalApplications($filters);
        $totalPages = ceil($totalApplications / $limit);

        $data = [
            'applications' => $applications,
            'approved_pending_role' => $approvedPendingRole,
            'totalApplications' => $totalApplications,            
            'pendingApplications' => $this->model('M_SupplierApplication')->countByStatus('Pending'),
            'approvedApplications' => $this->model('M_SupplierApplication')->countByStatus('Approved'),
            'rejectedApplications' => $this->model('M_SupplierApplication')->countByStatus('Rejected'),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters
        ];

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

        $this->logModel->create(
            $_SESSION['user_id'],
            $_SESSION['email'],
            $_SERVER['REMOTE_ADDR'],
            "Application with ID {$applicationId} assigned to under review.",
            $_SERVER['REQUEST_URI'],     
            http_response_code()     
        );

        redirect('manager/');
    }

    public function approveApplication($applicationId) {
        /* 
        WE NEED TO FOLLOW THESE STEPS
        1. we need to update the application status (that is already done)
        2. we need to create a supplier account for this user
        3. we need to copy the details from the application to suppliers entry
        4. we need to change the users role to 5 for supplier access
        -simaak
        */

        $supplierApplicationModel = $this->model('M_SupplierApplication');
        $userModel = $this->model('M_User');
        $application = $supplierApplicationModel->getApplicationById($applicationId);

        if (!$application) {
            // small validation, but if we are here then of course the application exists
            redirect('manager/');
        }

        $userUpdated = $userModel->updateRole($application->user_id, 5);

        if (!$userUpdated) {
            redirect('manager/');
        }

        $profile = $userModel->getProfileByUserId($application->user_id);
        $supplierExpectedAmount  = ($application->monthly_production) / 4.0;

        $supplierData = [
            'profile_id' => $profile->profile_id,
            'contact_number' => $profile->contact_number, 
            'application_id' => $applicationId,
            'latitude' => $application->latitude,
            'longitude' => $application->longitude,
            'address' => $application->address,
            'is_active' => 1,
            'is_deleted' => 0,
            'number_of_collections' => 0,
            'average_collection' => $supplierExpectedAmount,
            'user_id' => $application->user_id
        ];

        $supplierCreated = $this->supplierModel->createSupplier($supplierData);

        if (!$supplierCreated) {
            redirect('manager/');
        }

        $supplierApplicationModel->updateApplicationStatus($applicationId, $application->user_id, 'approved');

        $this->logModel->create(
            $_SESSION['user_id'],
            $_SESSION['email'],
            $_SERVER['REMOTE_ADDR'],
            "Application with ID {$applicationId} approved and supplier account created.",
            $_SERVER['REQUEST_URI'],     
            http_response_code()     
        );

        redirect('manager/');
    }

    public function rejectApplication($applicationId) {
        $supplierApplicationModel = $this->model('M_SupplierApplication');
        $supplierApplicationModel->updateApplicationStatus($applicationId, $_SESSION['manager_id'], 'rejected');

        $this->logModel->create(
            $_SESSION['user_id'],
            $_SESSION['email'],
            $_SERVER['REMOTE_ADDR'],
            "Application with ID {$applicationId} rejected.",
            $_SERVER['REQUEST_URI'],     
            http_response_code()     
        );

        redirect('manager/');
    }

    public function confirmSupplierRole() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $applicationId = $data['application_id'];

            $result = $this->supplierModel->confirmSupplierRole($applicationId);
            if ($result) {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Supplier role confirmed for application ID {$applicationId}.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                echo json_encode(['success' => true]);
            } else {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Failed to confirm supplier role for application ID {$applicationId}.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
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

    public function supplier() {
        $data = [];
        
        // Get filter parameters (optional)
        $supplier_id = isset($_GET['supplier_id']) ? $_GET['supplier_id'] : null;
        $name = isset($_GET['name']) ? $_GET['name'] : null;
        $nic = isset($_GET['nic']) ? $_GET['nic'] : null;
        $contact_number = isset($_GET['contact_number']) ? $_GET['contact_number'] : null;
        $application_id = isset($_GET['application_id']) ? $_GET['application_id'] : null;
        $supplier_status = isset($_GET['supplier_status']) ? $_GET['supplier_status'] : null;

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; 
        $offset = ($page - 1) * $limit;

        // Apply filters to get suppliers with pagination
        if ($supplier_id || $name || $nic || $contact_number || $application_id || $supplier_status) {
            $data['suppliers'] = $this->supplierModel->getFilteredSuppliers($supplier_id, $name, $nic, $contact_number, $application_id, $supplier_status, $limit, $offset);
        } else {
            $data['suppliers'] = $this->supplierModel->getFilteredSuppliers(null, null, null, null, null, null, $limit, $offset);
        }

        $data['total_suppliers'] = $this->supplierModel->getTotalSuppliers();
        $data['active_suppliers'] = $this->supplierModel->getActiveSuppliers();
        $data['currentPage'] = $page;
        $data['totalPages'] = ceil($data['total_suppliers'] / $limit);

        $this->view('supplier_manager/v_supplier', $data);
    }


    public function manageSupplier($id = null) {
        if (!$id) {
            setFlashMessage('Supplier ID is required!', 'error');
            redirect('manager/supplier');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'supplier_id' => $id,
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'contact_number' => trim($_POST['contact_number']),
                'address_line1' => trim($_POST['address_line1']),
                'address_line2' => trim($_POST['address_line2']),
                'city' => trim($_POST['city']),
                'nic' => trim($_POST['nic']),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ];
    
            $errors = [];
            
            if (empty($data['first_name'])) {
                $errors['first_name'] = 'First name is required';
            }
            
            if (empty($data['last_name'])) {
                $errors['last_name'] = 'Last name is required';
            }
            
            if (empty($data['email'])) {
                $errors['email'] = 'Email is required';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Please enter a valid email';
            }
            
            if (empty($errors)) {
                if (!empty($_FILES['supplier_image']['name'])) {
                    $file = $_FILES['supplier_image'];
                    $upload_dir = 'uploads/suppliers/';
                    
                    if (!file_exists($upload_dir)) {
                        mkdir($upload_dir, 0777, true);
                    }
                    
                    $file_name = uniqid() . '_' . $file['name'];
                    $destination = $upload_dir . $file_name;
                    
                    if (move_uploaded_file($file['tmp_name'], $destination)) {
                        $data['image_path'] = $destination;
                    } else {
                        $errors['supplier_image'] = 'Failed to upload image';
                    }
                }
                
                if ($this->supplierModel->updateSupplier($data)) {
                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Supplier with ID {$id} updated successfully.",
                        $_SERVER['REQUEST_URI'],     
                        http_response_code()     
                    );
                    setFlashMessage('Supplier updated successfully!');
                    redirect('manager/manageSupplier/' . $id);
                } else {
                    setFlashMessage('Failed to update supplier', 'error');
                }
            } else {
                $data['errors'] = $errors;
            }
        }

        $supplier = $this->supplierModel->getSupplierById($id);
        
        if (!$supplier) {
            setFlashMessage('Supplier not found, please try again later!', 'error');
            redirect('manager/supplier');
        }
        
        $upcomingSchedules = $this->scheduleModel->getUpcomingSchedulesBySupplierId($id);
        $collectionHistory = $this->collectionModel->getSupplierCollections($id);
        
        $viewData = [
            'supplier' => $supplier,
            'upcomingSchedules' => $upcomingSchedules,
            'collectionHistory' => $collectionHistory,
            'errors' => $errors ?? []
        ];
        
        if (isset($data) && isset($data['errors'])) {
            $viewData = array_merge($viewData, $data);
        }
        
        $this->view('supplier_manager/v_supplier_profile', $viewData);
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

    public function viewRemovedSuppliers() {
        $removedSuppliers = $this->supplierModel->getRemovedSuppliers();
        $data = [
            'removed_suppliers' => $removedSuppliers
        ];

        $this->view('supplier_manager/v_removed_suppliers', $data);
    }

    public function removeSupplier($supplierId) {
        $result = $this->supplierModel->removeSupplier($supplierId);
        if($result == 1) {
            setFlashMessage('Removed supplier successfully!');
            redirect('manager/supplier');

        } elseif ($result == 0) {
            redirect('manager/supplier');
        } else {
            setFlashMessage("Couldnt delete the supplier, please try again later", 'error');
            redirect('manager/supplier');
        }

    }

    public function restoreSupplier($supplierId) {
        $result = $this->supplierModel->restoreSupplier($supplierId);
        
        if ($result == 1) {
            setFlashMessage('Restored supplier successfully!');
            redirect('manager/supplier');
        } elseif ($result == 0) {
            setFlashMessage('Supplier not found or is not deleted.', 'error');
            redirect('manager/supplier');
        } else {
            setFlashMessage("Could not restore the supplier, please try again later", 'error');
            redirect('manager/supplier');
        }
    }


    /** 
     * Vehicle Management
     * ------------------------------------------------------------
     */

    public function vehicle() { // tested
        $license_plate = isset($_GET['license_plate']) ? $_GET['license_plate'] : null;
        $vehicle_type = isset($_GET['vehicle_type']) ? $_GET['vehicle_type'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;

        $allVehicles = $this->vehicleModel->getFilteredVehicles($license_plate, $vehicle_type, $status);
        $totalMaintainance = $this->vehicleModel->inMaintainanceCount();
        $totalVehicles = count($allVehicles);
        $data = [
            'allVehicles' => $allVehicles,
            'totalVehicles' => $totalVehicles,
            'totalMaintainance' => $totalMaintainance
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
            setFlashMessage('Vehicle not found, please try again later', 'error');
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

    public function createVehicle() {   // tested fully
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $data = [
            'license_plate' => '',
            'capacity' => '',
            'vehicle_type' => '',
            'make' => '',
            'model' => '',
            'color' => '',
            'manufacturing_year' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'license_plate' => trim($_POST['license_plate']),
                'capacity' => trim($_POST['capacity']),
                'vehicle_type' => trim($_POST['vehicle_type']),
                'make' => trim($_POST['make']),
                'model' => trim($_POST['model']),
                'color' => trim($_POST['color']),
                'manufacturing_year' => trim($_POST['manufacturing_year'])
            ];

            if (empty($data['license_plate']) || 
                empty($data['capacity']) || 
                empty($data['vehicle_type']) || 
                empty($data['make']) || 
                empty($data['model']) || 
                empty($data['color']) || 
                empty($data['manufacturing_year'])) {
                setFlashMessage("Please Enter All the Fields", 'error');
            } else {
                if ($this->vehicleModel->isLicensePlateTaken($data['license_plate'])) { // tested
                    setFlashMessage("License Plate is already used", 'error');
                    redirect('manager/createVehicle');
                } else {
                    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                        $uploadResult = uploadVehicleImage($_FILES['image'], $data['license_plate']);   // tested
                        if ($uploadResult['success']) {
                            $data['image_path'] = $uploadResult['path'];
                        } else {
                            setFlashMessage("Error when uploading: {$uploadResult['message']}", 'error');
                            redirect('manager/createVehicle');
                        }
                    } else {
                        setFlashMessage("Please upload an image", 'error');
                        redirect('manager/createVehicle');
                    }


                        if ($this->vehicleModel->createVehicle($data)) {
                            $this->logModel->create(
                                $_SESSION['user_id'],
                                $_SESSION['email'],
                                $_SERVER['REMOTE_ADDR'],
                                "Vehicle with license plate {$data['license_plate']} created successfully.",
                                $_SERVER['REQUEST_URI'],     
                                http_response_code()     
                            );
                            setFlashMessage('Vehicle created successfully!');
                            redirect('manager/vehicle');
                        
                        }
                }
            }
        }

        $this->view('vehicle_manager/v_create_vehicle', $data);
    }

    public function updateVehicle($vehicle_id) {    // tested
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    
        $vehicle = $this->vehicleModel->getVehicleById($vehicle_id);    // tested
        if (!$vehicle) {
            setFlashMessage('Vehicle not found', 'error');
            redirect('manager/vehicle');
        }
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $license_plate = htmlspecialchars(trim($_POST['license_plate']));
            $vehicle_type = htmlspecialchars(trim($_POST['vehicle_type']));
            $make = htmlspecialchars(trim($_POST['make']));
            $model = htmlspecialchars(trim($_POST['model']));
            $color = htmlspecialchars(trim($_POST['color']));
            $manufacturing_year = htmlspecialchars(trim($_POST['manufacturing_year']));
            $capacity = htmlspecialchars(trim($_POST['capacity']));
    
    
            $data = [
                'vehicle_id' => $vehicle_id,
                'license_plate' => $license_plate, 
                'vehicle_type' => $vehicle_type,
                'make' => $make,
                'model' => $model,
                'color' => $color,
                'manufacturing_year' => $manufacturing_year,
                'capacity' => $capacity,
                'current_image_path' => $vehicle->image_path
            ];
    
            if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] == 0) {
                $uploadResult = uploadVehicleImage($_FILES['vehicle_image'], $license_plate);   // tested this custom function
                if ($uploadResult['success']) {
                    $data['image_path'] = $uploadResult['path']; 
                } else {
                    setFlashMessage('There is an issue when updating, Error: ' . $uploadResult['message']);
                    redirect('manager/vehicle');
                }
            }
    
            if ($this->vehicleModel->updateVehicle($data)) {    // working , tested
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Vehicle with ID {$vehicle_id} updated successfully.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage('Vehicle updated successfully!');
                redirect('manager/vehicle');
            } else {
                setFlashMessage('Vehicle update failed!', 'error');
                redirect('manager/vehicle');
            }
        }
    
        $this->view('vehicle_manager/v_update_vehicle', ['vehicle' => $vehicle]);
    }

    public function getVehicleById($id){    // tested
        $vehicle = $this->vehicleModel->getVehicleById($id);
        if ($vehicle) {
            echo json_encode(['success' => true, 'vehicle' => $vehicle]);
        } else {
            echo json_encode(['success' => false]);
        }
    }

    public function deleteVehicle($id){ // tested

        if ($this->vehicleModel->isVehicleAssignedToRoute($id)) {
            setFlashMessage('Cannot delete vehicle. It is currently assigned to a route', 'error');
            redirect('manager/vehicle');
        }
        if ($this->vehicleModel->markAsDeleted($id)) {  // tested
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Vehicle with ID {$id} marked as deleted.",
                $_SERVER['REQUEST_URI'],     
                http_response_code()     
            );
            setFlashMessage('Vehicle deleted successfully!');
        } else {
            setFlashMessage('Vehicle deletion failed!', 'error');
        }

        redirect('manager/vehicle');
    }


    public function addMaintenance($vehicleId) {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }
    
        $vehicle = $this->vehicleModel->getVehicleById($vehicleId);
        if (!$vehicle) {
            setFlashMessage('Vehicle not found', 'error');
            redirect('manager/vehicle');
        }
    
        if ($vehicle->status == 'Maintenance') {
            setFlashMessage('Vehicle is already under maintenance', 'error');
            redirect('manager/vehicle/' . $vehicleId);
        }
    
        if ($routeName = $this->vehicleModel->isVehicleInRoute($vehicleId)) {
            setFlashMessage('Cannot add maintenance for vehicle currently assigned to the route: ' . $routeName, 'error');
            redirect('manager/viewMaintenance/' . $vehicleId);
        }
    
        $data = [
            'vehicle_id' => $vehicleId,
            'vehicle_info' => $vehicle,
            'maintenance_type' => 'Repair',
            'description' => '',
            'cost' => ''
        ];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $data = [
                'vehicle_id' => $vehicleId,
                'vehicle_info' => $vehicle,
                'maintenance_type' => trim($_POST['maintenance_type']),
                'description' => trim($_POST['description']),
                'cost' => trim($_POST['cost'])
            ];
    
            if (empty($data['description']) || empty($data['cost'])) {
                setFlashMessage('Please fill in all required fields', 'error');
                redirect('manager/addMaintenance/' . $vehicleId);
            }
    
            if (!is_numeric($data['cost']) || $data['cost'] < 0) {
                setFlashMessage('Cost must be a positive value', 'error');
                redirect('manager/addMaintenance/' . $vehicleId);
            }
    
            if ($this->vehicleModel->addMaintenanceLog($data) && 
                $this->vehicleModel->updateVehicleStatus($vehicleId, 'Maintenance')) {
                
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Maintenance added for vehicle ID: {$vehicleId}",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
                
                setFlashMessage('Maintenance record added successfully!');
                redirect('manager/viewMaintenance/' . $vehicleId);
            } else {
                setFlashMessage('Failed to add maintenance record', 'error');
            }
        }
    
        $this->view('vehicle_manager/v_add_maintenance', $data);
    }

    public function updateMaintenance($maintenanceId)
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        $maintenance = $this->vehicleModel->getMaintenanceById($maintenanceId);

        if (!$maintenance) {
            setFlashMessage('Maintenance record not found', 'error');
            redirect('manager/vehicle');
        }

        $vehicle = $this->vehicleModel->getVehicleById($maintenance->vehicle_id);
        if (!$vehicle) {
            setFlashMessage('Vehicle not found', 'error');
            redirect('manager/vehicle');
        }

        $data = [
            'maintenance_id' => $maintenanceId,
            'vehicle_id' => $maintenance->vehicle_id,
            'vehicle_info' => $vehicle,
            'maintenance_type' => $maintenance->maintenance_type,
            'description' => $maintenance->description,
            'cost' => $maintenance->cost
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data['maintenance_type'] = trim($_POST['maintenance_type']);
            $data['description'] = trim($_POST['description']);
            $data['cost'] = trim($_POST['cost']);
            $data['end_date'] = date('Y-m-d');
            $data['status'] = 'Completed'; 
            $data['vehicle_id'] = $maintenance->vehicle_id; 


            if (empty($data['description']) || empty($data['cost'])) {
                setFlashMessage('Please fill in all required fields', 'error');
                redirect('manager/updateMaintenance/' . $maintenanceId);
            }

            if (!is_numeric($data['cost']) || $data['cost'] < 0) {
                setFlashMessage('Cost must be a positive value', 'error');
                redirect('manager/updateMaintenance/' . $maintenanceId);
            }

            if ($this->vehicleModel->updateMaintenanceLog($maintenanceId, $data)) {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Updated maintenance record ID: {$maintenanceId}",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );

                setFlashMessage('Maintenance record updated successfully!');
                redirect('manager/updateMaintenance/' . $maintenance->vehicle_id);
            } else {
                setFlashMessage('Failed to update maintenance record', 'error');
            }
        }

        $this->view('vehicle_manager/v_update_maintenance', $data);
    }



    public function viewMaintenance() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        $ongoingMaintenance = $this->vehicleModel->getMaintenanceLogs('Ongoing');
        $completedMaintenance = $this->vehicleModel->getMaintenanceLogs('Completed');
        
        $data = [
            'ongoingMaintenance' => $ongoingMaintenance,
            'completedMaintenance' => $completedMaintenance
        ];
        
        $this->view('vehicle_manager/v_view_maintenance', $data);
    }
    
    public function completeMaintenance($logId) {   // tested
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $maintenanceLog = $this->vehicleModel->getMaintenanceLogById($logId);   // tested
        
        if (!$maintenanceLog) {
            setFlashMessage('Maintenance log not found', 'error');
            redirect('manager/viewMaintenance');
        }
        
        if ($this->vehicleModel->updateMaintenanceStatus($logId, 'Completed')) {
            if ($this->vehicleModel->updateVehicleStatus($maintenanceLog->vehicle_id, 'Active')) {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Maintenance completed for vehicle ID: {$maintenanceLog->vehicle_id}",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
                
                setFlashMessage('Maintenance marked as completed successfully!');
            } else {
                setFlashMessage('Failed to update vehicle status', 'error');
            }
        } else {
            setFlashMessage('Failed to complete maintenance', 'error');
        }
        
        redirect('manager/viewMaintenance');
    }

    // public function addVehicle() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $license_plate = htmlspecialchars(trim($_POST['license_plate']));
    //         $vehicle_type = htmlspecialchars(trim($_POST['vehicle_type']));
    //         $make = htmlspecialchars(trim($_POST['make']));
    //         $model = htmlspecialchars(trim($_POST['model']));
    //         $manufacturing_year = htmlspecialchars(trim($_POST['manufacturing_year']));
    //         $color = htmlspecialchars(trim($_POST['color']));
    //         $capacity = htmlspecialchars(trim($_POST['capacity']));

    //         if (isset($_FILES['vehicle_image']) && $_FILES['vehicle_image']['error'] == 0) {
    //             $image = $_FILES['vehicle_image'];
    //             $target_dir = "/opt/lampp/htdocs/Evergreen_Project/public/uploads/vehicle_photos/";
    //             $target_file = $target_dir . $license_plate . ".jpg";

    //             if (move_uploaded_file($image['tmp_name'], $target_file)) {
    //                 $this->vehicleModel->addVehicle([
    //                     'license_plate' => $license_plate,
    //                     'vehicle_type' => $vehicle_type,
    //                     'make' => $make,
    //                     'model' => $model,
    //                     'manufacturing_year' => $manufacturing_year,
    //                     'color' => $color,
    //                     'capacity' => $capacity,
    //                     'image_path' => $target_file
    //                 ]);

    //                 $this->logModel->create(
    //                     $_SESSION['user_id'],
    //                     $_SESSION['email'],
    //                     $_SERVER['REMOTE_ADDR'],
    //                     "Vehicle with license plate {$license_plate} added successfully.",
    //                     $_SERVER['REQUEST_URI'],     
    //                     http_response_code()     
    //                 );

    //                 header('Location: ' . URLROOT . '/manager/vehicle');
    //                 exit();
    //             } else {
    //                 setFlashMessage('Error uploading file.', 'error');
    //             }
    //         } else {
    //             setFlashMessage('No file uploaded or there was an error.', 'error');
    //         }
    //     }
    // }




    // public function removeVehicle() {
    //     if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //         $license_plate = htmlspecialchars(trim($_POST['license_plate']));

    //         $vehicle = $this->vehicleModel->getVehicleByLicensePlate($license_plate);
    //         if ($vehicle) {
    //             if ($this->vehicleModel->deleteVehicle($license_plate)) {
    //                 $imagePath = "/opt/lampp/htdocs/Evergreen_Project/public/uploads/vehicle_photos/" . $license_plate . ".jpg";
    //                 if (file_exists($imagePath)) {
    //                     unlink($imagePath);
    //                 }

    //                 $this->logModel->create(
    //                     $_SESSION['user_id'],
    //                     $_SESSION['email'],
    //                     $_SERVER['REMOTE_ADDR'],
    //                     "Vehicle with license plate {$license_plate} removed successfully.",
    //                     $_SERVER['REQUEST_URI'],     
    //                     http_response_code()     
    //                 );

    //                 setFlashMessage('Vehicle removed successfully!');
    //                 header('Location: ' . URLROOT . '/manager/vehicle');
    //                 exit();
    //             } else {
    //                 setFlashMessage('Error removing vehicle.', 'error');
    //             }
    //         } else {
    //             setFlashMessage('Vehicle not found.', 'error');
    //         }
    //     }
    // }

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
            setFlashMessage('Driver could not be found!');
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


    public function createDriver() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $data = [
            'email' => '',
            'license_number' => '',
            'hire_date' => date('Y-m-d'), 
            'status' => 'Active'
        ];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'email' => trim($_POST['email']),
                'license_number' => trim($_POST['license_number']),
                'hire_date' => trim($_POST['hire_date'])
            ];
            
            // Find existing user by email
            $existingUser = $this->userModel->findUserByEmail($data['email']);
            
            if (!$existingUser) {
                setFlashMessage('Email not found in the system. Please use an existing email.', 'error');
                redirect('manager/createDriver');
            } 
            
            if ($existingUser->role_id !== 7) {
                setFlashMessage('The user must be a normal web user to be assigned as a driver.', 'error');
                redirect('manager/createDriver');
            } 
            
            if (empty($data['license_number'])) {
                setFlashMessage('Please enter a license number', 'error');
                redirect('manager/createDriver');
            } elseif ($this->driverModel->findDriverByLicenseNumber($data['license_number'])) {
                setFlashMessage('This license number is already in the system', 'error');
                redirect('manager/createDriver');
            }
            
            
            if (empty($data['hire_date'])) {
                setFlashMessage('Please enter hire date', 'error');
                redirect('manager/createDriver');
            }
            
            // If all validations pass, proceed to create the driver
            try {
                $this->userModel->updateRole($existingUser->user_id, 6);
                
                // Get the profile using the model method
                $profileData = $this->userModel->getProfile($existingUser->user_id);
                if (!$profileData['profile']) {
                    throw new Exception('User does not have a profile');
                }
                
                $driverData = [
                    'profile_id' => $profileData['profile']->profile_id,
                    'license_number' => $data['license_number'],
                    'hire_date' => $data['hire_date']
                ];
                
                $driver_id = $this->driverModel->createDriver($driverData);
                
                if (!$driver_id) {
                    throw new Exception('Failed to create driver record');
                }
                
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Assigned user as driver: " . $data['email'],
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                
                setFlashMessage('User successfully assigned as driver!');
                redirect('manager/driver');
                
            } catch (Exception $e) {
                setFlashMessage('Error when creating the driver. Error: ' . $e->getMessage(), 'error');
                redirect('manager/createDriver');
            }
        }
        
        $this->view('vehicle_manager/v_create_driver', $data);
    }

    public function updateDriver($driverId) {
        if (!$driverId) {
            setFlashMessage('You haven\'t selected a driver to edit!', 'error');
            redirect('manager/driver');
        }
    
        // Load existing driver details
        $driverData = $this->driverModel->getDriverDetails($driverId);
        
        // Check if driver data was found
        if (!$driverData) {
            setFlashMessage('Driver not found.', 'error');
            redirect('manager/driver');
        }

        $data = [
            'driver_id' => $driverId,
            'image_path' => $driverData->image_path ?? '', 
            'license_number' => trim($driverData->license_number), 
            'hire_date' => trim($driverData->hire_date),
            'status' => trim($driverData->status)
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newData = [
                'license_number' => trim($_POST['license_number']), 
                'hire_date' => trim($_POST['hire_date']), 
                'status' => trim($_POST['status']),
                'image_path' => $data['image_path'] 
            ];

            // Check for duplicate license number
            if ($newData['license_number'] !== $data['license_number'] && 
                $this->driverModel->findDriverByLicenseNumber($newData['license_number'])) {
                setFlashMessage('The license number already exists for another driver.', 'error');
                redirect('manager/driver');
            }

            // Validate hire date
            if (empty($newData['hire_date']) || strtotime($newData['hire_date']) >= time()) {
                setFlashMessage("Hire date must be a valid date in the past", 'error');
                redirect('manager/driver');
            }

            if ($data['status'] !== $newData['status']) {
                $activeSchedules = $this->scheduleModel->getSchedulesByDriverId($driverId);
                
                if (!empty($activeSchedules)) {
                    setFlashMessage("This driver is currently assigned to a schedule.", "error");
                    redirect("manager/driver");
                }
            }
            


            // Handle image upload if a new image was provided
            if (!empty($_FILES['image_path']['name'])) {
                $uploadDir = 'uploads/profile_photos/'; 
                $fileName = time() . '_' . $_FILES['image_path']['name'];
                $uploadPath = $uploadDir . $fileName;
                
                // Make sure directory exists
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                // Move uploaded file
                if (move_uploaded_file($_FILES['image_path']['tmp_name'], $uploadPath)) {
                    $newData['image_path'] = $uploadPath;
                } else {
                    setFlashMessage('Error uploading image.', 'error');
                }
            }

            try {
                $this->userModel->updateProfilePhoto([
                    'image_path' => $newData['image_path'],
                    'profile_id' => $driverData->profile_id 
                ]);
                
                
                // Update the driver's information in the drivers table
                $this->driverModel->updateDriverInfo($driverId, [
                    'license_number' => $newData['license_number'],
                    'hire_date' => $newData['hire_date'],
                    'status' => $newData['status']
                ]);

                setFlashMessage('Driver profile and information updated successfully!');
                redirect('manager/driver');
            } catch (Exception $e) {
                setFlashMessage('Error updating driver profile: ' . $e->getMessage(), 'error');
            }
        }

        $this->view('vehicle_manager/v_update_driver', $data);
    }



    public function deleteDriver($id) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        // Get the driver data
        $driver = $this->driverModel->getDriverById($id);
        if (!$driver) {
            setFlashMessage('Driver not found!, please refresh the page!', 'error');
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
            setFlashMessage("Cannot delete this driver becasue they are currently assigned to the schedule {$schedulesStr}", 'error');
            redirect('manager/driver');
        }
        
        // Not in any schedules, so we can mark as deleted
        if ($this->driverModel->markDriverAsDeleted($id)) {

            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Driver " . $id . " marked as deleted",
                $_SERVER['REQUEST_URI'],     
                http_response_code()     
            );
            setFlashMessage('Driver successfully marked as deleted!');
            redirect('manager/driver');
        } else {
            setFlashMessage('Driver deletion failed!', 'error');
            redirect('manager/driver');
        }
    }


    /** 
     * Route Management
     * ------------------------------------------------------------
     */

    // public function route() {
    //     $allRoutes = $this->routeModel->getAllUndeletedRoutes();
    //     $totalRoutes = $this->routeModel->getTotalRoutes();
    //     $totalActive = $this->routeModel->getTotalActiveRoutes();
    //     $totalInactive = $this->routeModel->getTotalInactiveRoutes();
    //     $unallocatedSuppliers = $this->routeModel->getUnallocatedSuppliers();

    //     // Format suppliers for the map/dropdown
    //     $suppliersForMap = array_map(function ($supplier) {
    //         return [
    //             'id' => $supplier->supplier_id,
    //             'name' => $supplier->full_name, // Changed from supplier_name to full_name
    //             'preferred_day' => $supplier->preferred_day, // Include preferred_day
    //             'location' => [
    //                 'lat' => (float) $supplier->latitude,
    //                 'lng' => (float) $supplier->longitude
    //             ],
    //             'average_collection' => $supplier->average_collection,
    //             'number_of_collections' => $supplier->number_of_collections

    //         ];
    //     }, $unallocatedSuppliers);

    //     $data = [
    //         'allRoutes' => $allRoutes,
    //         'totalRoutes' => $totalRoutes,
    //         'totalActive' => $totalActive,
    //         'totalInactive' => $totalInactive,
    //         'unallocatedSuppliers' => $suppliersForMap,
    //         'unassignedSuppliersList' => $unallocatedSuppliers
    //     ];

    //     //$this->view('vehicle_manager/v_route', $data);
    // }

    // public function createRoute(){
    //     // Clear any previous output
    //     ob_clean();

    //     // Set JSON headers
    //     header('Content-Type: application/json');

    //     try {
    //         // Get and validate JSON input
    //         $json = file_get_contents('php://input');
    //         error_log("Received data: " . $json); // Debug log

    //         $data = json_decode($json);

    //         if (!$data) {
    //             throw new Exception('Invalid JSON data received');
    //         }

    //         // Create the route
    //         $result = $this->routeModel->createRoute($data);

    //         $response = [
    //             'success' => true,
    //             'message' => 'Route created successfully',
    //             'routeId' => $result 
    //         ];

    //         $this->logModel->create(
    //             $_SESSION['user_id'],
    //             $_SESSION['email'],
    //             $_SERVER['REMOTE_ADDR'],
    //             "Created a new route",
    //             $_SERVER['REQUEST_URI'],     
    //             http_response_code()     
    //         );

    //         echo json_encode($response);

    //     } catch (Exception $e) {
    //         error_log("Error in createRoute: " . $e->getMessage());
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    //     exit;
    // }

    // public function getRouteSuppliers($routeId){
    //     // Clear any previous output and set JSON header
    //     ob_clean();
    //     header('Content-Type: application/json');

    //     if (!$routeId) {
    //         echo json_encode(['error' => 'Route ID is required']);
    //         return;
    //     }

    //     try {
    //         // Get route details
    //         $route = $this->routeModel->getRouteById($routeId);
    //         if (!$route) {
    //             throw new Exception('Route not found');
    //         }

    //         // Get suppliers for this route
    //         $suppliers = $this->routeModel->getRouteSuppliers($routeId);

    //         // Combine the data
    //         $response = [
    //             'success' => true,
    //             'data' => [
    //                 'route' => [
    //                     'id' => $route->route_id,
    //                     'name' => $route->route_name,
    //                     'status' => $route->status,
    //                     'start_location' => [
    //                         'lat' => $route->start_location_lat,
    //                         'lng' => $route->start_location_long
    //                     ],
    //                     'end_location' => [
    //                         'lat' => $route->end_location_lat,
    //                         'lng' => $route->end_location_long
    //                     ],
    //                     'date' => $route->date,
    //                     'number_of_suppliers' => $route->number_of_suppliers
    //                 ],
    //                 'suppliers' => array_map(function ($supplier) {
    //                     return [
    //                         'id' => $supplier->supplier_id,
    //                         'name' => $supplier->full_name,
    //                         'location' => [
    //                             'lat' => $supplier->latitude,
    //                             'lng' => $supplier->longitude
    //                         ],
    //                         'stop_order' => $supplier->stop_order,
    //                         'supplier_order' => $supplier->supplier_order
    //                     ];
    //                 }, $suppliers)
    //             ]
    //         ];

    //         error_log('Sending response: ' . json_encode($response));
    //         echo json_encode($response);

    //     } catch (Exception $e) {
    //         error_log('Error in getRouteSuppliers: ' . $e->getMessage());
    //         echo json_encode([
    //             'success' => false,
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    //     exit;
    // }

    // public function getRouteDetails($routeId){
    //     // Clear any previous output
    //     ob_clean();

    //     // Set JSON headers
    //     header('Content-Type: application/json');

    //     try {
    //         if (!$routeId) {
    //             throw new Exception('Route ID is required');
    //         }

    //         // Get route details from model
    //         $routeDetails = $this->routeModel->getRouteById($routeId);

    //         if (!$routeDetails) {
    //             throw new Exception('Route not found');
    //         }

    //         // Get route suppliers
    //         $routeSuppliers = $this->routeModel->getRouteSuppliers($routeId);

    //         // Combine route details with suppliers
    //         $response = [
    //             'success' => true,
    //             'route' => [
    //                 'id' => $routeId,
    //                 'name' => $routeDetails->route_name,
    //                 'status' => $routeDetails->status,
    //                 'suppliers' => array_map(function ($supplier) {
    //                     return [
    //                         'id' => $supplier->supplier_id,
    //                         'name' => $supplier->full_name,
    //                         'coordinates' => [
    //                             'lat' => (float) $supplier->latitude,
    //                             'lng' => (float) $supplier->longitude
    //                         ]
    //                     ];
    //                 }, $routeSuppliers)
    //             ]
    //         ];

    //         error_log("Sending route details: " . json_encode($response)); // Debug log
    //         echo json_encode($response);

    //     } catch (Exception $e) {
    //         error_log("Error in getRouteDetails: " . $e->getMessage());
    //         echo json_encode([
    //             'success' => false,
    //             'message' => $e->getMessage()
    //         ]);
    //     }
    //     exit;
    // }

    // public function getRoutesByDay($day){
    //     $routes = $this->routeModel->getRoutesByDay($day);
    //     echo json_encode(['routes' => $routes]);
    // }

    // public function removeCollectionSupplier($recordId){
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $data = json_decode(file_get_contents('php://input'));
    //         if ($this->collectionSupplierRecordModel->removeCollectionSupplier($recordId)) {
    //             echo json_encode(['success' => true]);
    //         } else {
    //             echo json_encode(['success' => false]);
    //         }
    //     }
    // }

    // private function getCurrentStop($supplierRecords){
    //     // Find the last collected supplier
    //     foreach ($supplierRecords as $index => $record) {
    //         if ($record->status === 'Collected') {
    //             return $index;
    //         }
    //     }
    //     return 0; // Return 0 if no collections yet
    // }


    /** 
     * Collection Management
     * ------------------------------------------------------------
     */

    public function collection() {
        $stats = $this->userModel->getDashboardStats();
        $stats['collections'] = (array)$stats['collections'];

        $collection_id = isset($_GET['collection_id']) ? $_GET['collection_id'] : null;
        $schedule_id = isset($_GET['schedule_id']) ? $_GET['schedule_id'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

        if ($collection_id || $schedule_id || $status || $start_date || $end_date) {
            $allCollections = $this->collectionModel->getFilteredCollections(
                $collection_id, 
                $schedule_id, 
                $status, 
                $start_date, 
                $end_date
            );
        } else {
            $allCollections = $this->collectionModel->getFilteredCollections(
                null, 
                null, 
                "Completed", 
                null, 
                null
            );
        }

        $data = [
            'stats' => $stats,
            'all_collections' => $allCollections,
            'currentPage' => $page,
            'totalCollections' => $this->collectionModel->getTotalCollections(),
            'filters' => [
                'collection_id' => $collection_id,
                'schedule_id' => $schedule_id,
                'status' => $status,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]
        ];

        $this->view('vehicle_manager/v_collection_0', $data);
    }


    /** 
     * Schedule Management
     * ------------------------------------------------------------
     */

     public function schedule() // TESTED
     {
         // Stats
         $totalSchedules = $this->scheduleModel->getTotalSchedules();   // TESTED
         $availableSchedules = $this->scheduleModel->getActiveSchedulesCount(); // tested
     
         // for the drop down
         $routes = $this->routeModel->getAllUndeletedRoutes();  // tested
         $drivers = $this->driverModel->getAllDrivers();       // tested
         $vehicles = $this->vehicleModel->getAllAvailableVehicles();    // tested
     
         // Filters from GET
         $route_id = $_GET['route_id'] ?? null;
         $vehicle_id = $_GET['vehicle_id'] ?? null;
         $driver_id = $_GET['driver_id'] ?? null;
         $day = $_GET['day'] ?? null;
     
         if ($route_id || $vehicle_id || $driver_id || $day) {
             $schedules = $this->scheduleModel->getFilteredSchedules($route_id, $vehicle_id, $driver_id, $day);
         } else {
             $schedules = $this->scheduleModel->getFilteredSchedules(); // tested
         }
     
         $filters = compact('route_id', 'vehicle_id', 'driver_id', 'day');
     
         $this->view('vehicle_manager/v_collectionschedule', [
             'totalSchedules' => $totalSchedules,
             'availableSchedules' => $availableSchedules,
             'routes' => $routes,
             'drivers' => $drivers,
             'vehicles' => $vehicles,
             'schedules' => $schedules,
             'filters' => $filters
         ]);
     }
     

    public function createSchedule() {  // tested
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    
        $drivers = $this->driverModel->getAllDrivers(); // tested
        $routes = $this->routeModel->getAllUnAssignedRoutes();  // tested
    
        $data = [
            'drivers' => $drivers,
            'routes' => $routes
        ];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'day' => htmlspecialchars(trim($_POST['day'])),
                'driver_id' => htmlspecialchars(trim($_POST['driver_id'])),
                'route_id' => htmlspecialchars(trim($_POST['route_id'])),
                'start_time' => htmlspecialchars(trim($_POST['start_time'])),
                'end_time' => htmlspecialchars(trim($_POST['end_time'])),
                'drivers' => $drivers,
                'routes' => $routes
            ];
    
            // base case noh
            if (empty($data['day']) || 
                empty($data['driver_id']) || empty($data['route_id']) || 
                empty($data['start_time']) || empty($data['end_time'])) {
                setFlashMessage('Please enter all the fields!', 'error');
            } else {
                // we use the timestamp in the database therefore we need to convert to unix timestamp
                // thats why we are using this dummy date, because only the time matters for this part
                $startTime = strtotime("2000-01-01 " . $data['start_time']);
                $endTime = strtotime("2000-01-01 " . $data['end_time']);

                if($endTime < $startTime) {
                    setFlashMessage('Start time cannot be after the end time!', 'error');
                    redirect('manager/createSchedule');

                }
                
                // Check if end time is before midnight
                $midnight = strtotime("2000-01-01 23:59:59");
                if ($endTime > $midnight) {
                    setFlashMessage('Shift end time cannot be after 11.59 PM.', 'error');
                } else {
                    // Check for a minimum gap of 2 hours between shifts
                    $minGap = 2 * 60 * 60; // 2 hours in seconds
                    if (($endTime - $startTime) < $minGap) {
                        setFlashMessage('There must be at least a 2-hour gap between shifts.', 'error');
                    } else {
                        // Check if the driver is already scheduled for this day and time
                        $driverScheduleConflict = $this->scheduleModel->checkDriverScheduleConflict(    // tested
                            $data['driver_id'],
                            $data['day'],
                            $data['start_time'],
                            $data['end_time']
                        );
    
                        // just like for driver we have to check for the route
                        $routeScheduleConflict = $this->scheduleModel->checkRouteScheduleConflict(  //tesed
                            $data['route_id'],
                            $data['day']
                        );
    
                        if ($driverScheduleConflict) {
                            setFlashMessage('This driver is already scheduled during this time period.', 'error');
                        } elseif ($routeScheduleConflict) {
                            setFlashMessage('This route is already scheduled for this day.', 'error');
                        } else {
                            // nw can create a schedule
                            if ($this->scheduleModel->create($data)) {
                                $driverUserId = $this->userModel->getUserIdByDriverId($data['driver_id']);  // tested
                                if ($driverUserId) {
                                    $this->notificationModel->createNotification(   // tested
                                        $driverUserId,
                                        'New Schedule Assigned',
                                        'You have been assigned a new collection schedule.',
                                        ['link' => 'vehicledriver/']
                                    );

                                    $this->notificationModel->createNotification(   // tested
                                        $_SESSION['user_id'],
                                        'You assigned a new schedule Assigned',
                                        'You have created a new collection schedule.',
                                        ['link' => 'manager/schedule']
                                    );
                                }
    
                                $suppliers = $this->userModel->getSuppliersByRouteId($data['route_id']);    // tested
                                foreach ($suppliers as $supplierId) {
                                    $supplierUserId = $this->userModel->getUserIdBySupplierId($supplierId); // tested
                                    if ($supplierUserId) {
                                        $this->notificationModel->createNotification(
                                            $supplierUserId,
                                            'New Schedule Created',
                                            'A new collection schedule has been created for your route.',
                                            ['link' => 'supplier/schedule']
                                        );
                                    } else {
                                        // just a small test for my notification controller!!! error_log("Notification failed: No user ID for supplier ID: $supplierId");
                                    }
                                }

                                $this->logModel->create(    // adding to the admins log, tested
                                    $_SESSION['user_id'],
                                    $_SESSION['email'],
                                    $_SERVER['REMOTE_ADDR'],
                                    "Created a new schedule",
                                    $_SERVER['REQUEST_URI'],     
                                    http_response_code()     
                                );
    
                                setFlashMessage('Schedule created successfully!');
                                redirect('manager/schedule');
                            } else {
                                setFlashMessage('Could not create the schedule, please try again later', 'error');
                            }
                        }
                    }
                }
            }
        }
    
        $this->view('vehicle_manager/v_create_schedule', $data);
    }
    

    public function updateSchedule($scheduleId = null) {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
    
        if (!$scheduleId) {
            setFlashMessage('Invalid schedule!', 'error');
            redirect('manager/schedule');
        }
    
        $schedule = $this->scheduleModel->getScheduleById($scheduleId);
        if (!$schedule) {
            setFlashMessage('Schedule doesnt exist, please try again later!', 'error');
            redirect('manager/schedule');
        }
    
        $drivers = $this->driverModel->getAllDrivers();
        $routes = $this->routeModel->getAllUndeletedRoutes();
    
        $data = [
            'schedule' => $schedule,
            'drivers' => $drivers,
            'routes' => $routes
        ];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
                'status' => $_POST['status']
            ];
    
            if (empty($data['day'])) {
                setFlashMessage('Please enter the day!', 'error');
            } 
            if (empty($data['driver_id'])) {
                setFlashMessage('Please select a driver!', 'error');
            } 
            if (empty($data['route_id'])) {
                setFlashMessage('Please select a route!', 'error');
            } 
            if (empty($data['start_time'])) {
                setFlashMessage('Please enter the start time!', 'error');
            } 
            if (empty($data['end_time'])) {
                setFlashMessage('Please enter the end time!', 'error');
            }
            else {
                $startTime = strtotime("2000-01-01 " . $data['start_time']);
                $endTime = strtotime("2000-01-01 " . $data['end_time']);

                if($endTime < $startTime) {
                    setFlashMessage('Start time cannot be after the end time!', 'error');
                    redirect('manager/updateSchedule/' . $scheduleId);

                }
                
                $midnight = strtotime("2000-01-01 23:59:59");
                if ($endTime > $midnight) {
                    setFlashMessage('Shift end time cannot be after 11.59 PM.', 'error');
                } elseif (($endTime - $startTime) < (2 * 60 * 60)) {
                    setFlashMessage('There must be at least a 2-hour gap between shifts.', 'error');
                } else {
                    $driverConflict = false;
                    $routeConflict = false;
                    
                    if ($data['driver_id'] != $schedule->driver_id || 
                        $data['day'] != $schedule->day || 
                        $data['start_time'] != $schedule->start_time || 
                        $data['end_time'] != $schedule->end_time) {
                        
                        $driverConflict = $this->scheduleModel->checkDriverScheduleConflictExcludingCurrent(    //tested
                            $data['driver_id'],
                            $data['day'],
                            $data['start_time'],
                            $data['end_time'],
                            $scheduleId
                        );
                    }
                    
                    if ($data['route_id'] != $schedule->route_id || 
                        $data['day'] != $schedule->day) {
                        
                        $routeConflict = $this->scheduleModel->checkRouteScheduleConflictExcludingCurrent(  //tested
                            $data['route_id'],
                            $scheduleId
                        );
                    }

                    if ($driverConflict) {
                        setFlashMessage('This driver is already scheduled during this time period.', 'error');
                    } elseif ($routeConflict) {
                        setFlashMessage('This route is already scheduled', 'error');
                    } else {
                        if ($this->scheduleModel->updateSchedule($data)) {  //tested
                            $this->logModel->create(
                                $_SESSION['user_id'],
                                $_SESSION['email'],
                                $_SERVER['REMOTE_ADDR'],
                                "Updated the schedule " . $scheduleId,
                                $_SERVER['REQUEST_URI'],     
                                http_response_code()     
                            );

                            $driverUserId = $this->userModel->getUserIdByDriverId($data['driver_id']);  //tested
                            $this->notificationModel->createNotification(
                                $driverUserId,
                                'Schedule Updated',
                                'Your schedule has been updated.',
                                ['link' => 'vehicledriver/']
                            );

                            $suppliers = $this->userModel->getSuppliersByRouteId($data['route_id']);    //tested
                            foreach ($suppliers as $supplierId) {
                                $supplierUserId = $this->userModel->getUserIdBySupplierId($supplierId); //tested
                                if ($supplierUserId) {
                                    $this->notificationModel->createNotification(
                                        $supplierUserId,
                                        'Schedule Updated',
                                        'The schedule for your route has been updated.',
                                        ['link' => 'supplier/schedule/']
                                    );
                                }
                            }

                            setFlashMessage('Schedule updated successfully!');
                            redirect('manager/schedule');
                        } else {
                            setFlashMessage('Something went wrong. Please try again.', 'error');
                        }
                    }
                }
            }
        }
    
        $this->view('vehicle_manager/v_update_schedule', $data);
    }

    public function deleteSchedule() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schedule_id = $_POST['schedule_id'];
    
            $schedule = $this->scheduleModel->getScheduleById($schedule_id);
            if ($schedule) {
                $driverUserId = $this->userModel->getUserIdByDriverId($schedule->driver_id);
                if ($driverUserId) {
                    $this->notificationModel->createNotification(
                        $driverUserId,
                        'Schedule Deleted',
                        'Your assigned schedule has been deleted by the manager.',
                        ['link' => 'vehicledriver/']
                    );
                }
    
                // Notify the suppliers 
                $suppliers = $this->userModel->getSuppliersByRouteId($schedule->route_id);
                foreach ($suppliers as $supplierId) {
                    $supplierUserId = $this->userModel->getUserIdBySupplierId($supplierId);
                    if ($supplierUserId) {
                        $this->notificationModel->createNotification(
                            $supplierUserId,
                            'Schedule Deleted',
                            'A schedule related to your route has been deleted.',
                            ['link' => 'supplier/viewSchedule']
                        );
                        
                    } else {
                        // not sure to put flash or nt
                    }
                }

                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Deleted the schedule " . $schedule_id,
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage("Deleted the schedule sucessfully!");
            }
    
            $this->scheduleModel->delete($schedule_id);
    
            redirect('manager/');
        }
    }
    

    /** 
     * Appointment Management
     * ------------------------------------------------------------
     */

    public function appointments() {
        $this->requireLogin(); 
    
        if(!isset($_SESSION['manager_id'])) {
            redirect('auth/login');
        }
        
        $managerId = $_SESSION['manager_id'];
        
        // Check if filters are applied
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $date = isset($_GET['date']) ? trim($_GET['date']) : '';
        
        // Get filtered time slots (or all if no filters applied)
        if (!empty($status) || !empty($date)) {
            $timeSlots = $this->appointmentModel->filteredTimeSlots($managerId, $status, $date);
        } else {
            $timeSlots = $this->appointmentModel->getManagerTimeSlots($managerId);
        }
        
        $incomingRequests = $this->appointmentModel->getIncomingRequests($managerId);
        $acceptedAppointments = $this->appointmentModel->getAcceptedAppointments($managerId);
    
        $data = [
            'timeSlots' => $timeSlots,
            'incomingRequests' => $incomingRequests,
            'acceptedAppointments' => $acceptedAppointments
        ];
    
        $this->view('supplier_manager/v_appointments', $data);
    }

    public function allAppointments() {
        // Ensure user is logged in
        $this->requireLogin();
        
        // if(!isset($_SESSION['manager_id']) || empty($_SESSION['manager_id'])) {
        //     redirect('users/login');
        //     return;
        // }
        
        $manager_id = $_SESSION['manager_id'];
        
        try {
            $appointments = $this->model('M_Appointment')->getAllAppointments($manager_id);
            
            $data = [
                'appointments' => $appointments,
                'title' => 'All Appointments'
            ];
        
            $this->view('supplier_manager/v_all_appointments', $data);
        } catch (Exception $e) {
            redirect('manager/');
        }
    }

    public function createSlot() {
        $this->requireLogin();
        
        // If form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            // Process form
            $data = [
                'manager_id' => $_SESSION['manager_id'],
                'date' => trim($_POST['date']),
                'start_time' => trim($_POST['start_time']),
                'end_time' => trim($_POST['end_time']),
                'date_err' => '',
                'time_err' => ''
            ];
            
            if (strtotime($data['date']) < strtotime(date('Y-m-d'))) {
                setFlashMessage('Time slots must be scheduled for future dates','error');
                redirect('manager/appointments');
                return;
            }
            
            $start_timestamp = strtotime($data['start_time']);
            $end_timestamp = strtotime($data['end_time']);

            if ($start_timestamp >= $end_timestamp) {
                setFlashMessage('End time must be after start time','error');
                redirect('manager/appointments');
                return;
            } else {

                $duration_minutes = ($end_timestamp - $start_timestamp) / 60;

                if ($duration_minutes < 30) {
                    setFlashMessage('Time slots must be at least 30 minutes long','error');
                    redirect('manager/appointments');
                    return;
                } else if ($duration_minutes > 120) {
                    setFlashMessage('Time slots cannot exceed 2 hours','error') ;
                    redirect('manager/appointments');
                    return;
                }
            }

            if ($start_timestamp < strtotime('08:00') || $end_timestamp > strtotime('18:00')) {
                setFlashMessage('Time slots must be created between 8:00AM and 06:00PM','error');
                redirect('manager/appointments');
                return;
            }
            
            // Check if slot already exists or overlaps with another slot
            $overlap = $this->appointmentModel->isSlotOverlapping($data);
            if ($overlap) {
                setFlashMessage('This time slot overlaps with an existing slot','error');
                redirect('manager/appointments');
                return;
            }
            
            // Make sure no errors
            if (empty($data['date_err']) && empty($data['time_err'])) {
                // Create slot
                if ($this->appointmentModel->createSlot($data)) {
                    redirect('manager/appointments');
                } else {
                }
            }
            
            $this->view('supplier_manager/v_create_slot', $data);
        } else {
            $data = [
                'date' => '',
                'start_time' => '',
                'end_time' => '',
                'date_err' => '',
                'time_err' => ''
            ];
            
            // Load view
            $this->view('supplier_manager/v_create_slot', $data);
        }
    }

    public function cancelSlot($slotId) {
        $this->requireLogin();
        
        $managerId = $_SESSION['manager_id'];
        
        if ($slotId) {
            $incomingSlot = $this->appointmentModel->getIncomingRequests($managerId);
            
            // Check if there's a pending request for this slot
            $isPendingRequest = false;
            foreach ($incomingSlot as $slot) {
                if ($slot->slot_id == $slotId) {
                    $isPendingRequest = true;
                    break;
                }
            }
    
            // If pending request exists, set message and exit early
            if ($isPendingRequest) {
                setFlashMessage('Cannot cancel time slot. Pending request for this time slot is already present.', 'alert alert-danger');
                redirect('manager/appointments');
                return; // This is crucial - exit the function here
            }
            
            // Only reach this code if no pending request exists
            if ($this->appointmentModel->cancelSlot($slotId, $managerId)) {
                setFlashMessage('Time slot canceled successfully');
            } else {
                setFlashMessage('Unable to cancel time slot. It may already be booked or requested.', 'alert alert-danger');
            }
            redirect('manager/appointments');
        } else {
            setFlashMessage('Invalid request', 'alert alert-danger');
            redirect('manager/appointments');
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
        $totalComplaints = $this->supplierModel->getTotalComplaints();
        $resolvedComplaints = $this->supplierModel->getComplaintsByStatus('Resolved');
        $pendingComplaints = $this->supplierModel->getComplaintsByStatus('Pending');
        
        $complaint_id = isset($_GET['complaint_id']) ? $_GET['complaint_id'] : null;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $date_from = isset($_GET['date_from']) ? $_GET['date_from'] : null;
        $date_to = isset($_GET['date_to']) ? $_GET['date_to'] : null;


        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; 
        $offset = ($page - 1) * $limit;

        $complaints = $this->supplierModel->getFilteredComplaints(
            $complaint_id, 
            $status, 
            $date_from, 
            $date_to,
            $limit,
            $offset  
        );

        $totalComplaints = $this->supplierModel->getTotalComplaints($complaint_id, $status, $date_from, $date_to);
        $totalPages = ceil($totalComplaints / $limit);

        $data = [
            'complaints' => $complaints,
            'totalComplaints' => $totalComplaints,
            'resolvedComplaints' => count($resolvedComplaints),
            'pendingComplaints' => count($pendingComplaints),
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ];
        
        $this->view('supplier_manager/v_complaints', $data);
    }


    public function viewComplaint($id = null)
    {
        if ($id === null) {
            redirect('manager/complaints');
        }
    
        $complaint = $this->supplierModel->getComplaintById($id);
    
        if (!$complaint) {
            setFlashMessage('Complaint not found!', 'error');
            redirect('manager/complaints');
        }
    
        $data = [
            'complaint' => $complaint
        ];
    
        $this->view('supplier_manager/v_view_complaint', $data);
    }

    public function resolveComplaint()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Make sure to sanitize input
            $complaintId = trim($_POST['complaint_id']);
            
            $data = [
                'complaint_id' => $complaintId,
                'status' => 'Resolved'
            ];

            if ($this->supplierModel->updateStatus($data)) {
                setFlashMessage('Complaint marked as resolved successfully!', 'success');
            } else {
                setFlashMessage('Failed to resolve complaint!', 'error');
            }
            
            // Redirect back to the complaint view page
            redirect('manager/viewComplaint/' . $complaintId);
        } else {
            redirect('manager/complaints');
        }
    }

    public function applications() {

        $approvedPendingRole = $this->model('M_SupplierApplication')->getApprovedPendingRoleApplications();

        $filters = [
            'application_id' => isset($_GET['application_id']) ? $_GET['application_id'] : '',
            'status' => isset($_GET['status']) ? $_GET['status'] : '',
            'date-from' => isset($_GET['date-from']) ? $_GET['date-from'] : '',
            'date-to' => isset($_GET['date-to']) ? $_GET['date-to'] : ''
        ];
        
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 10; 
        $offset = ($page - 1) * $limit;

        $applications = $this->model('M_SupplierApplication')->getAllApplications($filters, $limit, $offset);
        
        $totalApplications = $this->model('M_SupplierApplication')->getTotalApplications($filters);
        $totalPages = ceil($totalApplications / $limit);

        $data = [
            'applications' => $applications,
            'approved_pending_role' => $approvedPendingRole,
            'totalApplications' => $totalApplications,            
            'pendingApplications' => $this->model('M_SupplierApplication')->countByStatus('Pending'),
            'approvedApplications' => $this->model('M_SupplierApplication')->countByStatus('Approved'),
            'rejectedApplications' => $this->model('M_SupplierApplication')->countByStatus('Rejected'),
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'filters' => $filters
        ];

        $this->view('supplier_manager/v_applications', $data);
    }

    public function supplierStatement() {
        $data = [];
        $this->view('shared/supplier/v_view_monthly_statement', $data);
    }

    public function allcomplaints()
    {
        $data = [];

        $this->view('supplier_manager/v_all_complaints', $data);
    }

    public function payments()
    {
        $data = [];

        $this->view('supplier_manager/v_payments', $data);
    }

    public function profile()
    {
        $data = [];

        $this->view('supplier_manager/v_profile', $data);
    }

    //Theekshana Chat part

    public function chat() {
        $activeSuppliers = $this->chatModel->getActiveSuppliers();
        error_log("Suppliers in Manager chat(): " . print_r($activeSuppliers, true));

        $data = [
            'active_suppliers' => $activeSuppliers,
            'page_title' => 'Chat with Suppliers',
            'user_id' => $_SESSION['user_id']
        ];
        
        $this->view('vehicle_manager/v_chat', $data);
    }

    public function sendMessage() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['receiver_id']) || empty($data['message'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit();
            }
            
            if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit();
            }
            
            $result = $this->chatModel->saveMessage(
                $_SESSION['user_id'],
                $data['receiver_id'],
                $data['message'],
                'text'
            );
            
            if ($result['success']) {
                echo json_encode([
                    'success' => true,
                    'data' => [  // Wrap message_id and created_at in a "data" object
                        'message_id' => $result['message_id'],
                        'created_at' => $result['created_at']
                    ]
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => $result['error'] ?? 'Failed to send message'
                ]);
            }
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }


    public function getMessages() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            error_log("getMessages: Method Not Allowed");
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }
    
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['receiver_id']) || !is_numeric($data['receiver_id'])) {
            error_log("getMessages: Invalid receiver ID");
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid receiver ID']);
            return;
        }
    
        $userId = $_SESSION['user_id'];
        $receiverId = (int)$data['receiver_id'];
        $lastMessageId = isset($data['last_message_id']) && is_numeric($data['last_message_id']) ? (int)$data['last_message_id'] : 0;
        error_log("getMessages: Fetching messages for user $userId and receiver $receiverId with last_message_id $lastMessageId");
    
        try {
            $messages = $this->chatModel->getMessages($userId, $receiverId, $lastMessageId);
            echo json_encode([
                'success' => true,
                'data' => [
                    'messages' => $messages
                ]
            ]);
        } catch (Exception $e) {
            error_log("getMessages: Error fetching messages for user $userId and receiver $receiverId: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error fetching messages']);
        }
    }
    
    public function editMessage() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['message_id']) || empty($data['new_message'])) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit();
            }
            
            if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit();
            }
            
            $result = $this->chatModel->editMessage(
                $data['message_id'],
                $data['new_message'],
                $_SESSION['user_id']
            );
            
            echo json_encode(['success' => $result]);
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
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

    //announcements
    // Set a flash message in the session
    protected function flash($name, $message, $class = 'alert alert-success') {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['flash'][$name] = [
            'message' => $message,
            'class' => $class
        ];
    }

    public function announcements() {
        $db = new Database();
        
        // Fetch all announcements
        $db->query("SELECT * FROM announcements ORDER BY created_at DESC");
        $announcements = $db->resultSet();

        $data = [
            'announcements' => $announcements,
            'page_title' => 'Announcements'
        ];

        $this->view('vehicle_manager/v_announcements', $data);
    }

    public function createAnnouncementPage() {
        $data = [
            'announcement' => (object) [
                'announcement_id' => '',
                'title' => '',
                'content' => '',
                'banner' => ''
            ],
            'error' => ''
        ];
        $this->view('vehicle_manager/v_edit_announcement', $data);
    }

    public function createAnnouncement() {
        error_log("----------- CREATE ANNOUNCEMENT START -----------");
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            error_log("POST data: " . print_r($_POST, true));
            error_log("FILES data: " . print_r($_FILES, true));
    
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
    
            if (empty($title) || empty($content)) {
                setFlashMessage("Title and content are required.", "error");
                redirect('manager/createAnnouncement');
                return;
            }
    
            $banner = null;
    
            if (isset($_FILES['banner']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['banner'];
    
                // Validations
                if ($file['size'] > 2 * 1024 * 1024) {
                    setFlashMessage("File size exceeds 2MB limit", "error");
                    redirect('manager/createAnnouncement');
                    return;
                }
    
                $allowedTypes = ['image/jpeg', 'image/png'];
                if (!in_array($file['type'], $allowedTypes)) {
                    setFlashMessage("Invalid file type. Only JPEG and PNG allowed.", "error");
                    redirect('manager/createAnnouncement');
                    return;
                }
    
                $uploadDir = dirname(APPROOT) . '/public/uploads/announcements/';

                error_log("The upload dir is " . $uploadDir);
                if (!is_dir($uploadDir)) {
                    error_log("Upload dir not found, trying relative path.");
                    $uploadDir = 'public/uploads/announcements/';
                }
    
                if (!is_dir($uploadDir)) {
                    error_log("Creating upload directory: " . $uploadDir);
                    if (!mkdir($uploadDir, 0777, true)) {
                        $err = error_get_last();
                        error_log("mkdir failed: " . ($err ? $err['message'] : 'Unknown error'));
                        setFlashMessage("Server error: Cannot create upload directory.", "error");
                        redirect('manager/createAnnouncement');
                        return;
                    }
                }
    
                chmod($uploadDir, 0777); // Just in case
    
                $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $banner = uniqid() . '.' . $extension;
                $uploadPath = $uploadDir . $banner;
    
                // Check tmp_name exists
                if (!file_exists($file['tmp_name'])) {
                    error_log("Temp file does not exist: " . $file['tmp_name']);
                    setFlashMessage("Temporary upload file missing. Try again.", "error");
                    redirect('manager/createAnnouncement');
                    return;
                }
    
                // Check if upload dir is writable
                if (!is_writable($uploadDir)) {
                    error_log("Upload directory not writable: " . $uploadDir);
                    setFlashMessage("Server error: Upload folder not writable.", "error");
                    redirect('manager/createAnnouncement');
                    return;
                }
    
                // Attempt move
                if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
                    $err = error_get_last();
                    error_log("move_uploaded_file failed: " . ($err ? $err['message'] : 'Unknown error'));
    
                    // Fallback copy
                    if (!copy($file['tmp_name'], $uploadPath)) {
                        $err = error_get_last();
                        error_log("copy fallback also failed: " . ($err ? $err['message'] : 'Unknown error'));
                        setFlashMessage("Failed to upload image. Server error.", "error");
                        redirect('manager/createAnnouncement');
                        return;
                    } else {
                        error_log("File copied successfully to: " . $uploadPath);
                    }
                } else {
                    error_log("File uploaded via move_uploaded_file to: " . $uploadPath);
                }
    
                chmod($uploadPath, 0644);
            } elseif (isset($_FILES['banner']) && $_FILES['banner']['error'] != UPLOAD_ERR_NO_FILE) {
                $error_codes = [
                    UPLOAD_ERR_INI_SIZE => 'File size exceeds server limit',
                    UPLOAD_ERR_FORM_SIZE => 'File size exceeds form limit',
                    UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
                ];
                $error_msg = $error_codes[$_FILES['banner']['error']] ?? 'Unknown upload error';
                error_log("Upload error code: {$_FILES['banner']['error']} - {$error_msg}");
                setFlashMessage($error_msg, "error");
                redirect('manager/createAnnouncement');
                return;
            }
    
            try {
                $db = new Database();
                $query = "INSERT INTO announcements (title, content, banner, created_by, created_at) 
                          VALUES (:title, :content, :banner, :created_by, NOW())";
                $db->query($query);
                $db->bind(':title', $title);
                $db->bind(':content', $content);
                $db->bind(':banner', $banner);
                $db->bind(':created_by', $_SESSION['user_id']);
    
                if ($db->execute()) {
                    setFlashMessage('Announcement created successfully!');
                    redirect('manager/announcements');
                } else {
                    if ($banner && file_exists($uploadPath)) unlink($uploadPath);
                    setFlashMessage('Database error. Please try again.', 'alert alert-danger');
                    redirect('manager/createAnnouncement');
                }
            } catch (Exception $e) {
                error_log("DB Exception: " . $e->getMessage());
                if ($banner && file_exists($uploadPath)) unlink($uploadPath);
                setFlashMessage('System error occurred. Please try again.', 'alert alert-danger');
                redirect('manager/createAnnouncement');
            }
        } else {
            redirect('manager/createAnnouncementPage');
        }
    
        error_log("----------- CREATE ANNOUNCEMENT END -----------");
    }
    
    

    public function getAnnouncement($id) {
        header('Content-Type: application/json');
        
        $db = new Database();
        $query = "SELECT * FROM announcements WHERE announcement_id = :id";
        $db->query($query);
        $db->bind(':id', $id);
        
        $announcement = $db->single();
        
        if ($announcement) {
            echo json_encode(['success' => true, 'announcement' => $announcement]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Announcement not found']);
        }
        exit();
    }

    public function updateAnnouncement() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $announcement_id = $_POST['announcement_id'] ?? '';
            $title = $_POST['title'] ?? '';
            $content = $_POST['content'] ?? '';
            $remove_banner = isset($_POST['remove_banner']) && $_POST['remove_banner'] == '1';
            
            if (empty($announcement_id) || empty($title) || empty($content)) {
                setFlashMessage("This field is required.", "error");
                redirect('manager/editAnnouncement/' . $announcement_id);
                return;
            }
    
            $db = new Database();
            $banner = null;
    
            // Fetch current announcement to get existing banner
            $db->query("SELECT banner FROM announcements WHERE announcement_id = :id");
            $db->bind(':id', $announcement_id);
            $current = $db->single();
            $current_banner = $current ? $current->banner : null;
    
            // Handle banner upload or removal
            if ($remove_banner && $current_banner) {
                $banner_path = dirname(APPROOT) . '/public/uploads/announcements/' . $current_banner;
                if (file_exists($banner_path)) {
                    unlink($banner_path);
                    error_log("Removed banner: {$banner_path}");
                }
                $banner = null;
            } elseif (isset($_FILES['banner']) && $_FILES['banner']['error'] == UPLOAD_ERR_OK) {
                $file = $_FILES['banner'];
                // Log file details for debugging
                error_log("Banner upload attempt (update): Name={$file['name']}, Size={$file['size']}, Type={$file['type']}, Error={$file['error']}");
    
                // Validate file size (max 2MB)
                if ($file['size'] > 2 * 1024 * 1024) {
                    setFlashMessage("File size exceeds 2MB limit", "error");
                    redirect('manager/editAnnouncement/' . $announcement_id);
                    return;
                }
                if ($file['type'] == 'image/jpeg') {
                    $banner = uniqid() . '.jpg';
                    $uploadPath = dirname(APPROOT) . '/public/uploads/announcements/' . $banner;
                    if (!is_dir(dirname(APPROOT) . '/public/uploads/announcements/')) {
                        if (!mkdir(dirname(APPROOT) . '/public/uploads/announcements/', 0755, true)) {
                            error_log("Failed to create directory: public/uploads/announcements/");
                            setFlashMessage("Failed to create directory for uploads", "error");
                            redirect('manager/editAnnouncement/' . $announcement_id);
                            return;
                        }
                    }
                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        // Remove old banner if exists
                        if ($current_banner) {
                            $old_banner_path = dirname(APPROOT) . '/public/uploads/announcements/' . $current_banner;
                            if (file_exists($old_banner_path)) {
                                unlink($old_banner_path);
                                error_log("Removed old banner: {$old_banner_path}");
                            }
                        }
                        error_log("Banner uploaded successfully: {$banner}");
                    } else {
                        error_log("Failed to upload banner: Name={$file['name']}, Size={$file['size']}, Error={$file['error']}, Path={$uploadPath}");
                        setFlashMessage('Failed to upload banner. Please try again.', 'alert alert-danger');
                        redirect('manager/editAnnouncement/' . $announcement_id);
                        return;
                    }
                } else {
                    setFlashMessage('Only JPEG files are allowed', 'alert alert-danger');
                    redirect('manager/editAnnouncement/' . $announcement_id);
                    return;
                }
            } else {
                $banner = $current_banner; // Keep existing banner
            }
    
            $query = "UPDATE announcements 
                     SET title = :title, content = :content, banner = :banner, updated_at = NOW()
                     WHERE announcement_id = :id AND created_by = :user_id";
            
            $db->query($query);
            $db->bind(':title', $title);
            $db->bind(':content', $content);
            $db->bind(':banner', $banner);
            $db->bind(':id', $announcement_id);
            $db->bind(':user_id', $_SESSION['user_id']);
            
            if ($db->execute()) {
                // Log banner filename saved to DB
                error_log("Announcement updated with banner: " . ($banner ?? 'NULL'));
                setFlashMessage('Announcement updated successfully');
                redirect('manager/announcements');
            } else {
                // Delete uploaded banner if DB update fails
                if ($banner && $banner != $current_banner && file_exists(dirname(APPROOT) . '/public/uploads/announcements/' . $banner)) {
                    unlink(dirname(APPROOT) . '/public/uploads/announcements/' . $banner);
                }
                setFlashMessage('Failed to update announcement', 'alert alert-danger');
                redirect('manager/editAnnouncement/' . $announcement_id);
            }
            return;
        }
        
        setFlashMessage('Invalid request method', 'alert alert-danger');
        redirect('manager/announcements');
    }
    

    public function deleteAnnouncement() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['announcement_id'])) {
                echo json_encode(['success' => false, 'message' => 'Announcement ID is required']);
                exit();
            }

            $db = new Database();
            // Fetch banner to delete file
            $db->query("SELECT banner FROM announcements WHERE announcement_id = :id");
            $db->bind(':id', $data['announcement_id']);
            $announcement = $db->single();
            
            $query = "DELETE FROM announcements 
                     WHERE announcement_id = :id AND created_by = :user_id";
            
            $db->query($query);
            $db->bind(':id', $data['announcement_id']);
            $db->bind(':user_id', $_SESSION['user_id']);
            
            if ($db->execute()) {
                // Delete banner file if exists
                if ($announcement->banner) {
                    $banner_path = 'public/uploads/announcements/' . $announcement->banner;
                    if (file_exists($banner_path)) {
                        unlink($banner_path);
                        error_log("Deleted banner: {$banner_path}");
                    }
                }
                setFlashMessage('Announcement deleted successfully!');
                echo json_encode(['success' => true, 'message' => 'Announcement deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete announcement']);
                setFlashMessage('Failed to delete announcement', 'alert alert-danger');
            }
            exit();
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }

    public function editAnnouncement($id) {
        try {
            $db = new Database();
            $query = "SELECT * FROM announcements WHERE announcement_id = :id AND created_by = :user_id";
            $db->query($query);
            $db->bind(':id', $id);
            $db->bind(':user_id', $_SESSION['user_id']);
            $announcement = $db->single();
            
            if (!$announcement) {
                setFlashMessage('Announcement not found or you do not have permission to edit it', 'alert alert-danger');
                redirect('manager/announcements');
                return;
            }

            $data = [
                'announcement' => $announcement,
                'error' => ''
            ];
            $this->view('vehicle_manager/v_edit_announcement', $data);
        } catch (Exception $e) {
            error_log("Error fetching announcement ID {$id}: " . $e->getMessage());
            setFlashMessage('Failed to fetch announcement', 'alert alert-danger');
            redirect('manager/announcements');
        }
    }
    
    
    public function deleteComplaint($id)
    {
        if ($this->supplierModel->deleteComplaint($id)) {

            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Deleted the complaint: ".$id,
                $_SERVER['REQUEST_URI'],     
                http_response_code()     
            );
            setFlashMessage('Complaint deleted sucessfully!');
            redirect('manager/complaints');
        } else {
            setFlashMessage('Couldnt delete the complaint please try again later!', 'error');
            redirect('manager/viewComplaint/' . $id);
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }

    public function respondRequest() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $requestId = trim($_POST['request_id']);
            $action = trim($_POST['action']);
    
            $notificationModel = $this->model('M_Notification');
    

            $request = $this->appointmentModel->getRequestById($requestId);
            $slotId = $request->slot_id;
            $supplierId = $request->supplier_id;
    
            if ($action === 'accept') {
                if ($this->appointmentModel->acceptRequest($requestId)) {

                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Accepted the request for the timeslot",
                        $_SERVER['REQUEST_URI'],     
                        http_response_code()     
                    );
                    setFlashMessage('Request accepted successful for request ID: ' . $requestId );
    
                    $notificationModel->createNotification(
                        $this->userModel->getUserIdBySupplierId($supplierId),
                        'Appointment Accepted',
                        'Your appointment request has been accepted.',
                        ['link' => 'supplier/viewAppointment/']
                    );
    
                    $otherRequests = $this->appointmentModel->getRequestsBySlotExcept($slotId, $requestId);
                    foreach ($otherRequests as $req) {

                        $this->appointmentModel->rejectRequest($req->request_id);
    
                        $notificationModel->createNotification(
                            $this->userModel->getUserIdBySupplierId($req->supplier_id),
                            'Appointment Rejected',
                            'Your request for the same time slot was rejected.',
                            ['link' => 'supplier/viewAppointment/']
                        );
                    }
    
                } else {
                    setFlashMessage('Failed to accept the request. Please refresh the page', 'error');
                }
    
            } elseif ($action === 'reject') {
                $this->appointmentModel->rejectRequest($requestId);

                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Rejected the request for the time slot",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage('Request rejected successfuly!');
    
                $notificationModel->createNotification(
                    $supplierId,
                    'Appointment Rejected',
                    'Your appointment request has been rejected.',
                    ['link' => 'supplier/viewAppointment/' . $requestId]
                );
            }
    
            redirect('manager/appointments');
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
