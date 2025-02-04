<?php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/UserHelper.php';

class Distribution extends Controller {
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
    private $orderModel;
    

    public function __construct() {
        // Check if user is logged in
        requireAuth();
        
        // Check if user has Vehicle Manager OR Admin role
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            // Redirect unauthorized access
            redirect('');
            exit();
        }
        

        // Initialize models
        $this->vehicleManagerModel = $this->model('M_VehicleManager');
        $this->routeModel = $this->model('M_Route');
        $this->teamModel = $this->model('M_Team');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->shiftModel = $this->model('M_Shift');
        $this->scheduleModel = $this->model('M_CollectionSchedule');
        $this->driverModel = $this->model('M_Driver');
        $this->partnerModel = $this->model('M_Partner');
        $this->staffModel = $this->model('M_Staff');
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
        $this->userModel = $this->model('M_User');
        $this->employeeModel = $this->model('M_Employee');
        $this->bagModel = $this->model('M_CollectionBag');
        $this->orderModel = $this->model('M_Order');
    }

    // private function isAjaxRequest() { I added this in the controllr class
    //     return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    //            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    // }

    public function index() {


        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_distribution', [

        ]);
    }

    public function getOrderDetails($orderId) {
        // Set header to return JSON
        header('Content-Type: application/json');
    
        try {
            $order = $this->orderModel->getOrderDetails($orderId);
            
            if ($order) {
                echo json_encode($order);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Order not found']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error']);
        }
    }


}
?>