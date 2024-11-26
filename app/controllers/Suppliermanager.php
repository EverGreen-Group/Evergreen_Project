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
    }

    public function index() {
        // Get dashboard stats from the model
        $data =[];
        $this->view('suppliermanager/applications', $data);
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
        $ownershipDetails = $supplierApplicationModel->getOwnershipDetails($applicationId);

        // Prepare data array
        $data = [
            'application' => [
                'application_id' => $application->application_id,
                'user_id' => $application->user_id,
                'status' => $application->status,
                'primary_phone' => $application->primary_phone,
                'secondary_phone' => $application->secondary_phone,
                'preferred_days' => $application->preferred_days,
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
            'address' => $address,
            'ownership' => $ownershipDetails
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

    public function confirmSupplierRole($applicationId) {
        try {
            // Get application details first
            $application = $this->supplierApplicationModel->getApplicationById($applicationId);
            
            if (!$application) {
                throw new Exception('Application not found');
            }
            
            $this->supplierApplicationModel->confirmSupplierRole($applicationId);
            $userId = $application->user_id;
            $contactNumber = $application->primary_phone;
            $latitude = $application->latitude;
            $longitude = $application->longitude;
            $isActive = 1;
            $isDeleted = 0;
            $numberOfCollections = 0;
            $avgCollectionAmount = 0;
            $totalCollections = 0;
            $this->supplierApplicationModel->insertSupplier($applicationId, $userId, $contactNumber, $latitude, $longitude, $isActive, $isDeleted, $numberOfCollections, $avgCollectionAmount, $totalCollections);

            
            redirect('suppliermanager/applications');
            
        } catch (Exception $e) {
            error_log("Error confirming supplier role: " . $e->getMessage());
            flash('application_message', 'Error confirming supplier role: ' . $e->getMessage(), 'alert alert-danger');
            redirect('suppliermanager/applications');
        }
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



}
?>


