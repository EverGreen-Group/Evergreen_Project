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

class Collections extends Controller {
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
        $this->teamModel = new M_Team();          // Instantiate Team model
        $this->vehicleModel = new M_Vehicle();    // Instantiate Vehicle model
        $this->shiftModel = new M_Shift();        // Instantiate Shift model
        $this->scheduleModel = new M_CollectionSchedule();  // Instantiate CollectionSchedule model
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
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
}