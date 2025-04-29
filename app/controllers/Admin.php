<?php
require_once '../app/helpers/auth_middleware.php';

class Admin extends Controller
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
    private $roleModel;
    private $logModel;

    //----------------------------------------
    // CONSTRUCTOR
    //----------------------------------------
    public function __construct()
    {

        requireAuth();
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN])) {
            setFlashMessage('Unauthorized access', 'error');
            redirect('');
            exit();
        }

        // Initialize models

        $this->routeModel = $this->model('M_Route');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->scheduleModel = $this->model('M_CollectionSchedule');
        $this->driverModel = $this->model('M_Driver');
        $this->collectionModel = $this->model('M_Collection');
        $this->collectionSupplierRecordModel = $this->model('M_CollectionSupplierRecord');
        $this->userModel = $this->model('M_User');
        $this->bagModel = $this->model('M_CollectionBag');
        $this->roleModel = $this->model('M_Role');
        $this->logModel = $this->model('M_Log');
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


    public function userLogs() {
        // Get filter parameters (optional)
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
        $email = isset($_GET['email']) ? $_GET['email'] : null;
    

        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; // Number of logs per page
        $offset = ($page - 1) * $limit;

        $userLogs = $this->logModel->getFilteredUserLogs($userId, $email, $limit, $offset);

        $totalLogs = $this->logModel->getTotalUserLogs($userId, $email);
        $totalPages = ceil($totalLogs / $limit);
    
        $data = [
            'userLogs'    => $userLogs,
            'currentPage' => $page,
            'totalPages'  => $totalPages,
            'user_id'     => $userId,
            'email'       => $email
        ];
    
        $this->view('admin/v_user_log', $data);
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


            if ($this->userModel->updateUser($data)) {
                setFlashMessage('User updated successfully');
                redirect('admin/users'); 
            } else {

                setFlashMessage('User updating failed!', 'error');
                redirect('admin/manageUser/' . $data['user_id']); 
            }
        } else {
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
    
            // validation but i have put it in the view also
            if (empty($year) || empty($month) || empty($normalLeafRate) || empty($superLeafRate)) {
                setFlashMessage('Please enter the year, month, normal leaf rate, and super leaf rate to generate the report', 'error');
                redirect('admin/payments');
                return;
            }
    
            //validate for negative values
            if ($normalLeafRate < 0 || $superLeafRate < 0) {
                setFlashMessage('Normal leaf rate and super leaf rate must be non-negative values.', 'error');
                redirect('admin/payments');
                return;
            }
    
            $paymentModel = $this->model('M_Payment');

            $reportExist = $paymentModel->checkIfAlreadyExists($year, $month);
            if($reportExist) {
                setFlashMessage('Report for this year and month already exists!', 'warning');
                redirect('admin/payments');
            }
            
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


    public function config() {

        $data = $this->userModel->getFactoryConfigurations();
        
        if (isset($_SESSION['success'])) {
            $data['success'] = $_SESSION['success'];
            unset($_SESSION['success']);
        }
        
        if (isset($_SESSION['error'])) {
            $data['error'] = $_SESSION['error'];
            unset($_SESSION['error']);
        }
        
        $this->view('admin/v_config', $data);
    }

    public function updateFactoryLocation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            
            $result = $this->userModel->updateFactoryLocation($id, $latitude, $longitude);
            
            if ($result) {
                setFlashMessage('Factory location updated successfully');
            } else {
                setFlashMessage('Failed to update factory location', 'error');
            }
        }
        
        redirect('admin/config');
    }
    
    public function updateMoistureDeductions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ids = $_POST['id'];
            $deductions = $_POST['deduction'];
            
            $result = true;
            for ($i = 0; $i < count($ids); $i++) {
                $updateResult = $this->userModel->updateDeduction($ids[$i], $deductions[$i]);
                if (!$updateResult) {
                    $result = false;
                }
            }
            
            if ($result) {
                setFlashMessage('Moisture deductions updated successfully');
            } else {
                setFlashMessage('Failed to update some moisture deductions', 'error');
            }
        }
        
        redirect('admin/config');
    }
    
    public function updateLeafAgeDeductions() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ids = $_POST['id'];
            $deductions = $_POST['deduction'];
            
            $result = true;
            for ($i = 0; $i < count($ids); $i++) {
                $updateResult = $this->userModel->updateDeduction($ids[$i], $deductions[$i]);
                if (!$updateResult) {
                    $result = false;
                }
            }
            
            if ($result) {
                setFlashMessage('Leaf age deductions updated successfully');
            } else {
                setFlashMessage('Failed to update some leaf age deductions', 'error');
            }
        }
        
        redirect('admin/config');
    }

}
?>