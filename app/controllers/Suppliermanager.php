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
require_once '../app/models/M_SupplierApplication.php';
require_once '../app/models/M_Supplier.php';
require_once '../app/models/M_Chat.php';


class SupplierManager extends Controller {
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
    private $supplierApplicationModel;
    private $supplierModel;
    private $chatModel; // Add this line
    

    public function __construct() {
        if(!isLoggedIn()) {
            redirect('users/login');
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
        $this->supplierApplicationModel = $this->model('M_SupplierApplication');
        $this->supplierModel = new M_Supplier();
        $this->chatModel = $this->model('M_Chat');
    }

    public function index() {
        // Get dashboard stats from the model
        // $stats = $this->vehicleManagerModel->getDashboardStats();

        // // Fetch all necessary data for the dropdowns
        // $routes = $this->routeModel->getAllRoutes();
        // $teams = $this->teamModel->getAllTeams();
        // $vehicles = $this->vehicleModel->getAllVehicles();
        // $shifts = $this->shiftModel->getAllShifts();
        // $schedules = $this->scheduleModel->getAllSchedules();
        // $ongoingCollections = $this->collectionModel->getOngoingCollections();

        // // Pass the stats and data for the dropdowns to the view
        // $this->view('vehicle_manager/v_collection', [
        //     'stats' => $stats,
        //     'routes' => $routes,
        //     'teams' => $teams,
        //     'vehicles' => $vehicles,
        //     'shifts' => $shifts,
        //     'schedules' => $schedules,
        //     'ongoing_collections' => $ongoingCollections
        // ]);
        redirect('suppliermanager/applications/');
    }

    public function applications() {
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

    public function viewApplication($applicationId) {
        // Load the model
        $supplierApplicationModel = $this->model('M_SupplierApplication');
        
        // Get application details using the model
        $application = $supplierApplicationModel->getApplicationById($applicationId);

        // If application not found, redirect with error
        if (!$application) {
            flash('application_error', 'Application not found');
            redirect('supplier_manager/applications');
        }

        // Get all related data
        $bankInfo = $supplierApplicationModel->getBankInfo($applicationId);
        $waterSources = $supplierApplicationModel->getWaterSources($applicationId);
        $teaVarieties = $supplierApplicationModel->getTeaVarieties($applicationId);
        $teaDetails = $supplierApplicationModel->getTeaDetails($applicationId);
        $documents = $supplierApplicationModel->getApplicationDocuments($applicationId);
        $infrastructure = $supplierApplicationModel->getInfrastructure($applicationId);
        $structures = $supplierApplicationModel->getStructures($applicationId);
        $propertyDetails = $supplierApplicationModel->getPropertyDetails($applicationId);
        $address = $supplierApplicationModel->getAddress($applicationId);

        // Prepare data array
        $data = [
            'application' => [
                'application_id' => $application->application_id,
                'user_id' => $application->user_id,
                'status' => $application->status,
                'primary_phone' => $application->primary_phone,
                'secondary_phone' => $application->secondary_phone,
                'whatsapp_number' => $application->whatsapp_number,
                'created_at' => $application->created_at,
                'updated_at' => $application->updated_at
            ],
            'bank_info' => $bankInfo,
            'water_sources' => $waterSources,
            'tea_varieties' => $teaVarieties,
            'tea_details' => $teaDetails,
            'documents' => $documents,
            'infrastructure' => $infrastructure,
            'structures' => $structures,
            'property' => $propertyDetails,
            'address' => $address
        ];

        // Load the view
        $this->view('suppliermanager/v_view_application', $data);
    }

    public function approveApplication($applicationId) {
        // Update application status to 'approved'
        if ($this->supplierApplicationModel->updateApplicationStatus($applicationId, 'approved')) {
            // flash('application_message', 'Application has been approved successfully');
        } else {
            // flash('application_message', 'Something went wrong while approving the application', 'alert alert-danger');
        }
        redirect('suppliermanager/applications');
    }
    
    public function rejectApplication($applicationId) {
        // Update application status to 'rejected'
        if ($this->supplierApplicationModel->updateApplicationStatus($applicationId, 'rejected')) {
            // flash('application_message', 'Application has been rejected successfully');
        } else {
            // flash('application_message', 'Something went wrong while rejecting the application', 'alert alert-danger');
        }
        redirect('suppliermanager/applications');
    }

    public function suppliers() {
        // Get all suppliers from the database
        $suppliers = $this->supplierModel->getAllSuppliers();

        $data = [
            'suppliers' => $suppliers
        ];

        $this->view('supplier_manager/v_suppliers', $data);
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

    public function complaints()
    {
        $data = [];

        $this->view('supplier_manager/v_complaints', $data);
    }

    public function requests()
    {
        $data = [];

        $this->view('supplier_manager/v_supplier_requests', $data);
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
//Added by theekshana

    public function chat() {
        $data = [
            'active_suppliers' => $this->chatModel->getActiveSuppliers(),
            'page_title' => 'Chat with Suppliers',
            'user_id' => $_SESSION['user_id'] // For WebSocket initialization
        ];
        
        $this->view('supplier_manager/v_chat', $data);
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
                    'message' => 'Message sent successfully',
                    'data' => $result
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
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['receiver_id'])) {
                echo json_encode(['success' => false, 'message' => 'Missing receiver_id']);
                exit();
            }
            
            if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit();
            }
    
            $senderId = $_SESSION['user_id'];
            $receiverId = (int)$data['receiver_id'];
    
            $messages = $this->chatModel->getMessages($senderId, $receiverId);
    
            echo json_encode([
                'success' => true,
                'messages' => $messages
            ]);
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            if (empty($data['message_id'])) {
                echo json_encode(['success' => false, 'message' => 'Missing message_id']);
                exit();
            }
            
            if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                exit();
            }
            
            $result = $this->chatModel->deleteMessage(
                $data['message_id'],
                $_SESSION['user_id']
            );
            
            echo json_encode(['success' => $result]);
            exit();
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        exit();
    }

    public function settings()
    {
        $data = [];

        $this->view('supplier_manager/v_settings', $data);
    }


}
?>


