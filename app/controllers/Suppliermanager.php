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
require_once '../app/models/M_Complaint.php';
require_once '../app/models/M_LandInspection.php';

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
    private $complaintModel;
    private $landInspectionModel;

    public function __construct() {
        // Check if user is logged in
        // requireAuth();
        

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
        $this->complaintModel = $this->model('M_Complaint');
        $this->landInspectionModel = new M_LandInspection();
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

        // Temporary supplier ID (replace with session after login implementation)
        $supplier_id = 2;

        // Get all applications
        $applications = $this->model('M_SupplierApplication')->getAllApplications();
        
        // Get approved applications pending role assignment
        $approvedPendingRole = $this->model('M_SupplierApplication')->getApprovedPendingRoleApplications();

        $inspections = $this->landInspectionModel->getPreviousInspectionRequests($supplier_id);

        $data = [
            'applications' => $applications,
            'approved_pending_role' => $approvedPendingRole,
            'previous_inspections' => $inspections
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
            redirect('suppliermanager/applications');
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
        $this->view('supplier_manager/v_view_application', $data);
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

    public function updateSupplier() {
        $data = [
            
        ];

        $this->view('supplier_manager/v_update_supplier', $data);
    }

    public function deleteSupplier() {

        $data = [
            
        ];

        $this->view('supplier_manager/v_delete_supplier', $data);
    }

    public function complaints() {
        try {
            if (!$this->complaintModel) {
                error_log("ComplaintModel not initialized!");
                throw new Exception("Complaint model not initialized");
            }
    
            // Get all complaints with error checking
            $complaints = $this->complaintModel->getAllComplaints();
            error_log("Complaints fetched in controller: " . count($complaints));
    
            // Get other data with error checking
            $unviewedCount = $this->complaintModel->getUnviewedComplaintsCount();
            $newLastWeek = $this->complaintModel->getNewComplaintsLastWeek();
            $viewedCount = $this->complaintModel->getViewedComplaintsCount();
            $complaintTypes = $this->complaintModel->getComplaintTypeStats();
            
            // Get missed collections
            $missedCollections = $this->collectionSupplierRecordModel->getMissedCollections();
    
            $data = [
                'unviewed_count' => $unviewedCount,
                'new_last_week' => $newLastWeek,
                'viewed_count' => $viewedCount,
                'complaints' => $complaints,
                'complaint_types' => $complaintTypes,
                'missed_collections' => $missedCollections
            ];
    
            $this->view('supplier_manager/v_complaints', $data);
        } catch (Exception $e) {
            error_log("Error in complaints controller: " . $e->getMessage());
            $data = ['error' => 'An error occurred while loading complaints'];
            $this->view('supplier_manager/v_complaints', $data);
        }
    }
    
    public function markComplaintViewed() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complaint_id'])) {
            $complaintId = $_POST['complaint_id'];
            $success = $this->complaintModel->markComplaintAsViewed($complaintId);
            
            echo json_encode(['success' => $success]);
            exit;
        }
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


    public function chat()
    {
        $data = [];

        $this->view('supplier_manager/v_chat', $data);
    }

    public function settings()
    {
        $data = [];

        $this->view('supplier_manager/v_settings', $data);
    }

    public function scheduleInspection() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];
            $scheduled_date = $_POST['date'];
            $scheduled_time = $_POST['time'];
    
            $result = $this->landInspectionModel->scheduleInspection(
                $request_id,
                $scheduled_date,
                $scheduled_time
            );
    
            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        }
    }

    public function updateInspectionStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $request_id = $_POST['request_id'];
            $status = $_POST['status'];
    
            $result = $this->landInspectionModel->updateInspectionStatus(
                $request_id,
                $status
            );
    
            if ($result) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        }
    }

    public function getSupplierDetails() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['supplier_id'])) {
            $supplier = $this->supplierModel->getSupplierById($_POST['supplier_id']);
            
            if ($supplier) {
                // Get additional statistics
                $totalQuantity = $this->supplierModel->getTotalCollectionQuantity($supplier->supplier_id);
                $collectionDays = $this->supplierModel->getCollectionDaysCount($supplier->supplier_id);
                $performanceRate = $this->supplierModel->calculatePerformanceRate($totalQuantity, $collectionDays);
                
                // Get recent collections
                $recentCollections = $this->supplierModel->getRecentCollections($supplier->supplier_id);
    
                echo json_encode([
                    'status' => 'success',
                    'data' => [
                        'supplier' => $supplier,
                        'stats' => [
                            'totalQuantity' => number_format($totalQuantity, 2),
                            'collectionDays' => $collectionDays,
                            'performanceRate' => $performanceRate
                        ],
                        'recentCollections' => $recentCollections
                    ]
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'No supplier found'
                ]);
            }
            exit;
        }
    }

}
?>


