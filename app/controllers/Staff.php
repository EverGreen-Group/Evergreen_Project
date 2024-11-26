<?php
require_once '../app/models/M_Staff.php';
require_once '../app/models/M_Driver.php';
require_once '../app/models/M_Partner.php';
require_once '../app/models/M_VehicleManager.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';
require_once '../app/helpers/UserHelper.php';

class Staff extends Controller {
    private $staffModel;
    private $driverModel;
    private $partnerModel;
    private $vehicleManagerModel;
    private $userHelper;

    public function __construct() {
        requireAuth();
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->staffModel = new M_Staff();
        $this->driverModel = new M_Driver();
        $this->partnerModel = new M_Partner();
        $this->vehicleManagerModel = new M_VehicleManager();
        $this->userHelper = new UserHelper();
    }

    public function index() {
        $manager_id = $this->staffModel->getManagerIdByUserId($_SESSION['user_id']);
        error_log("Controller got manager_id: " . $manager_id);
        
        $data = [
            'drivers' => $this->staffModel->getAllDrivers(),
            'partners' => $this->staffModel->getAllPartners(),
            'managers' => $this->staffModel->getAllManagers(),
            'totalDrivers' => $this->staffModel->getTotalDrivers(),
            'totalPartners' => $this->staffModel->getTotalPartners(),
            'totalUnavailableDriver' => $this->staffModel->getTotalUnavailableDriver(),
            'totalUnavailablePartner' => $this->staffModel->getTotalUnavailablePartner(),
            'currentLeaves' => $this->staffModel->getUpcomingLeaves(),
            'pendingLeaves' => $this->staffModel->getPendingLeaves(),
            'manager_id' => $manager_id,
            'leaveTypeStats' => $this->staffModel->getLeaveTypeDistribution(),
            'monthlyLeaveStats' => $this->staffModel->getMonthlyLeaveDistribution()
        ];
        
        error_log("Data being sent to view: " . print_r($data, true));
        $this->view('vehicle_manager/v_staff', $data);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'title' => 'Create Staff',
                'error' => '',
                'role' => '',
                'first_name' => '',
                // Initialize all other fields
            ];
            $this->view('vehicle_manager/v_staff_create', $data);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'role' => trim($_POST['role']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'email' => trim($_POST['email']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'primary_phone' => trim($_POST['primary_phone']),
                'secondary_phone' => !empty($_POST['secondary_phone']) ? trim($_POST['secondary_phone']) : null,
                'address_line1' => trim($_POST['address_line1']),
                'address_line2' => !empty($_POST['address_line2']) ? trim($_POST['address_line2']) : null,
                'city' => trim($_POST['city']),
                'postal_code' => trim($_POST['postal_code']),
                'province' => trim($_POST['province']),
                'district' => trim($_POST['district']),
                'nic' => trim($_POST['nic']),
                'gender' => trim($_POST['gender']),
                'hire_date' => trim($_POST['hire_date']),
                'license_no' => isset($_POST['license_no']) ? trim($_POST['license_no']) : null,
                'manager_type' => isset($_POST['manager_type']) ? trim($_POST['manager_type']) : null,
                'error' => ''
            ];

            if (!$this->validateStaffData($data)) {
                $this->view('vehicle_manager/v_staff_create', $data);
                return;
            }

            try {
                $this->vehicleManagerModel->beginTransaction();
                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                $data['role_id'] = RoleHelper::getRoleByTitle($data['role']);
                $data['approval_status'] = 'Approved';
                
                $userId = $this->vehicleManagerModel->createUser($data);
                if (!$userId) throw new Exception("Failed to create user account");
                
                if (!$this->vehicleManagerModel->createUserAddress($userId, $data)) {
                    throw new Exception("Failed to create user address");
                }
                
                if (!$this->vehicleManagerModel->createUserContacts($userId, $data)) {
                    throw new Exception("Failed to create user contacts");
                }
                
                $employeeId = $this->vehicleManagerModel->createEmployee($userId, $data);
                if (!$employeeId) throw new Exception("Failed to create employee record");
                
                if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === 0) {
                    $photoPath = $this->handleFileUpload($_FILES['profile_photo'], 'profile_photos');
                    if (!$this->vehicleManagerModel->updateEmployeePhoto($employeeId, $photoPath)) {
                        throw new Exception("Failed to update profile photo");
                    }
                }
                
                // Create role-specific records
                switch($data['role']) {
                    case 'Driver':
                        if (!$this->vehicleManagerModel->createDriver($employeeId, $data)) {
                            throw new Exception("Failed to create driver record");
                        }
                        break;
                    case 'Driving Partner':
                        if (!$this->vehicleManagerModel->createPartner($employeeId, $data)) {
                            throw new Exception("Failed to create partner record");
                        }
                        break;
                    case 'Vehicle Manager':
                        if (!$this->vehicleManagerModel->createManager($employeeId, $data)) {
                            throw new Exception("Failed to create manager record");
                        }
                        break;
                }
                
                $this->vehicleManagerModel->commit();
                flash('staff_message', 'Staff member registered successfully');
                redirect('staff');
                
            } catch (Exception $e) {
                $this->vehicleManagerModel->rollBack();
                $data['error'] = 'Registration failed: ' . $e->getMessage();
                $this->view('staff/create', $data);
            }
        }
    }

    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }

        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        if (!isset($data['staffId']) || !isset($data['role'])) {
            echo json_encode(['success' => false, 'error' => 'Invalid input']);
            return;
        }

        $success = false;
        if ($data['role'] === 'driver') {
            $success = $this->driverModel->softDeleteDriver($data['staffId']);
        } elseif ($data['role'] === 'partner') {
            $success = $this->partnerModel->softDeletePartner($data['staffId']);
        }

        echo json_encode(['success' => $success]);
    }

    public function updateLeaveStatus() {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        header('Content-Type: application/json');
        
        try {
            $rawInput = file_get_contents("php://input");
            error_log("Received raw input: " . $rawInput);
            
            $data = json_decode($rawInput);

            if (!$data || !isset($data->requestId) || !isset($data->status) || !isset($data->vehicle_manager_id)) {
                echo json_encode([
                    'success' => false, 
                    'message' => 'Missing required data',
                    'received' => $data
                ]);
                return;
            }

            if (!in_array($data->status, ['approved', 'rejected'])) {
                echo json_encode(['success' => false, 'message' => 'Invalid status value']);
                return;
            }

            $result = $this->staffModel->updateLeaveStatus(
                (int)$data->requestId,
                $data->status,
                (int)$data->vehicle_manager_id
            );

            echo json_encode([
                'success' => $result,
                'message' => $result ? 'Leave status updated successfully' : 'Failed to update leave status'
            ]);

        } catch (Exception $e) {
            error_log("Exception in update_leave_status: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ]);
        }
    }

    private function validateStaffData($data) {
        if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email'])) {
            return false;
        }

        if (strlen($data['password']) < 6 || $data['password'] !== $data['confirm_password']) {
            return false;
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($data['role'] === 'driver' && empty($data['license_no'])) {
            return false;
        }

        return true;
    }

    private function handleFileUpload($file, $directory) {
        // Implementation of file upload handling
        // This should be moved from the VehicleManager controller if it exists there
    }
} 