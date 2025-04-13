<?php
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;

// Require all model files
require_once '../app/models/M_VehicleManager.php';
require_once '../app/models/M_Route.php';
require_once '../app/models/M_Vehicle.php';
require_once '../app/models/M_Shift.php';
require_once '../app/models/M_CollectionSchedule.php';
require_once '../app/models/M_Staff.php';
require_once '../app/models/M_Driver.php';
require_once '../app/models/M_Collection.php';
require_once '../app/models/M_CollectionSupplierRecord.php';
require_once '../app/models/M_User.php';
require_once '../app/models/M_Employee.php';
require_once '../app/models/M_CollectionBag.php';

// Require helper files
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/UserHelper.php';

class Admin extends Controller
{
    //----------------------------------------
    // PROPERTIES
    //----------------------------------------
    private $vehicleManagerModel;
    private $routeModel;
    private $vehicleModel;
    private $shiftModel;
    private $scheduleModel;
    private $driverModel;
    private $staffModel;
    private $userHelper;
    private $collectionModel;
    private $collectionSupplierRecordModel;
    private $userModel;
    private $employeeModel;
    private $bagModel;
    private $roleModel;

    //----------------------------------------
    // CONSTRUCTOR
    //----------------------------------------
    public function __construct()
    {
        // Check if user is logged in
        requireAuth();

        // Check if user has Vehicle Manager OR Admin role
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::MANAGER])) {
            // Redirect unauthorized access
            setFlashMessage('Unauthorized acess');
            redirect('');
            exit();
        }

        // Initialize models
        $this->vehicleManagerModel = new M_VehicleManager();
        $this->routeModel = new M_Route();
        $this->vehicleModel = new M_Vehicle();
        $this->shiftModel = new M_Shift();
        $this->scheduleModel = new M_CollectionSchedule();
        $this->driverModel = new M_Driver();
        $this->staffModel = $this->model('M_Staff');
        $this->userHelper = new UserHelper();
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
        $this->userModel = $this->model('M_User');
        $this->employeeModel = $this->model('M_Employee');
        $this->bagModel = $this->model('M_CollectionBag');
        $this->roleModel = $this->model('M_Role');
    }

    //----------------------------------------
    // DASHBOARD METHODS
    //----------------------------------------
    public function index()
    {
        // Get dashboard stats from the model

        // Pass the stats and data for the dropdowns to the view
        $this->view('admin/v_dashboard', [

        ]);
    }

    public function users() {
        // Retrieve filter parameters from the GET request
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        $first_name = isset($_GET['first_name']) ? $_GET['first_name'] : null;
        $last_name = isset($_GET['last_name']) ? $_GET['last_name'] : null;
        $role_id = isset($_GET['role']) ? $_GET['role'] : null;
    
        // Fetch users based on filters
        if ($email || $first_name || $last_name || $role_id) {
            $data['allUsers'] = $this->userModel->getFilteredUsers($email, $first_name, $last_name, $role_id);
        } else {
            // Otherwise, fetch all users
            $data['allUsers'] = $this->userModel->getAllUsers();
        }
    
        // Fetch all roles for the dropdown
        $data['allRoles'] = $this->roleModel->getAllRoles();
    
        // Load the view and pass the data
        $this->view('admin/v_role', $data);
    }


    public function manageUser($userId){
        $allRoles = $this->roleModel->getAllRoles();
        $user = $this->userModel->getUserById($userId);

        $data = [
            'allRoles' => $allRoles,
            'user' => $user
        ];
        $this->view('admin/v_manage_user', $data);

    }

    public function updateUser() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize and get the data from the form
            $data = [
                'user_id' => trim($_POST['user_id']),
                'email' => trim($_POST['email']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'nic' => trim($_POST['nic']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'role' => trim($_POST['role'])
            ];

            // Update the user in the database
            if ($this->userModel->updateUser($data)) {
                // Redirect to the user management page or show a success message
                setFlashMessage('User updated successfully');
                redirect('admin/users'); // Adjust the redirect as necessary
            } else {

                setFlashMessage('User updating failed!', 'error');
                redirect('admin/manageUser/' . $data['user_id']); // Redirect back to the edit page
            }
        } else {
            // If not a POST request, redirect to the user management page
            redirect('admin/users');
        }
    }

}
?>
