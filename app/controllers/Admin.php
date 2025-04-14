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
    public function index() {
        $email = isset($_GET['email']) ? $_GET['email'] : null;
        $first_name = isset($_GET['first_name']) ? $_GET['first_name'] : null;
        $last_name = isset($_GET['last_name']) ? $_GET['last_name'] : null;
        $nic = isset($_GET['nic']) ? $_GET['nic'] : null; 
        $role_id = isset($_GET['role']) ? $_GET['role'] : null;
    
        if ($email || $first_name || $last_name || $nic || $role_id) {
            $data['allUsers'] = $this->userModel->getFilteredUsers($email, $first_name, $last_name, $nic, $role_id);
        } else {
            $data['allUsers'] = $this->userModel->getAllUsers();
        }
    
        // Get user statistics
        $data['totalUsers'] = $this->userModel->getTotalUsersCount();
        $data['normalUsers'] = $this->userModel->getNormalUsersCount();
        
        // Get data for charts
        $data['monthlyRegistration'] = $this->userModel->getMonthlyRegistrations();
        $data['roleDistribution'] = $this->userModel->getUserRoleDistribution();
        
        $data['allRoles'] = $this->userModel->getAllUniqueRoles();
    
        $this->view('admin/v_role', $data);
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

    public function payments() {

        $paymentModel = $this->model('M_Payment');
        $paymentSummary = $paymentModel->getPaymentSummary();
    
    
    
        $data = [
            'payment_summary' => $paymentSummary
        ];
    
        $this->view('admin/v_payments_2', $data);
    }
    
    public function createPaymentReport() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $year = $_POST['year'];
            $month = $_POST['month'];
            $normalLeafRate = $_POST['normal_leaf_rate'];
            $superLeafRate = $_POST['super_leaf_rate'];
    
            // Add validation
            if (empty($year) || empty($month) || empty($normalLeafRate) || empty($superLeafRate)) {
                setFlashMessage('Please enter the year, month, normal leaf rate, and super leaf rate to generate the report', 'error');
                redirect('admin/payments');
                return;
            }
    
            // Validate for negative values
            if ($normalLeafRate < 0 || $superLeafRate < 0) {
                setFlashMessage('Normal leaf rate and super leaf rate must be non-negative values.', 'error');
                redirect('admin/payments');
                return;
            }
    
            $paymentModel = $this->model('M_Payment');
            
            try {
                $result = $paymentModel->generateMonthlyPayment($year, $month, $normalLeafRate, $superLeafRate);
                
                if ($result) {
                    setFlashMessage('Payment report created successfully!');
                } else {
                    setFlashMessage('Payment report generation failed!', 'error');
                }
            } catch (Exception $e) {
                setFlashMessage('Error when generating the report, Error: ' . $e);
            }
            
            redirect('admin/payments');
        } else {
            redirect('admin/payments');
        }
    }

    public function publishPaymentReport($paymentId) {
        $paymentModel = $this->model('M_Payment');
        $result = $paymentModel->publishPaymentReport($paymentId);
        if ($result == 1 ){
            setFlashMessage("Payment report published to the suppliers!");
        } elseif ($result == 0) {
            setFlashMessage("This report was already published!", 'error');
        } else {
            setFlashMessage("Couldnt publish this payment report!", 'error');
        }

        redirect('admin/payments');
    }
    
    
    
    public function deletePaymentReport($payment_id) {
        // Load payment model
        $paymentModel = $this->model('M_Payment');
        
        try {
    
            
            $result = $paymentModel->deletePayment($payment_id);
            
            
            if ($result) {
                setFlashMessage('Payment report deleted successfully!');
            } else {
                setFlashMessage('Payment report deletion failed!', 'error');
            }
        } catch (Exception $e) {
    
            setFlashMessage('Error when deleting the report: ' . $e->getMessage(), 'error');
        }
        
        redirect('admin/payments');
    }
    
    
    public function viewPaymentReport($payment_id) {
        $paymentModel = $this->model('M_Payment');
    
        $paymentDetails = $paymentModel->getPaymentDetailsByPaymentId($payment_id); 
    
        $data = [
            'payment_details' => $paymentDetails 
        ];
    
        $this->view('inventory/v_view_payment_report', $data);
    }

}
?>
