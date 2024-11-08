<?php
require_once '../app/models/M_User.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';

class Profile extends Controller {
    private $userModel;

    public function __construct() {
        // Check if user is logged in
        requireAuth();
        
        // Initialize user model
        $this->userModel = new M_User();
    }

    public function index() {
        // Get user data
        $userId = $_SESSION['user_id'];
        // $userData = $this->userModel->getUserById($userId);
        
        $data = [];
        // Get role-specific data
        // $roleSpecificData = $this->getRoleSpecificData($userData->role);

        // $data = [
        //     'userData' => $userData,
        //     'roleData' => $roleSpecificData,
        //     'userRole' => RoleHelper::getRole(),
        //     'isAdmin' => RoleHelper::isAdmin(),
        // ];

        // Load the appropriate view based on user role
        $this->view('pages/profile', $data);
    }

    private function getRoleSpecificData($role) {
        switch($role) {
            case RoleHelper::VEHICLE_MANAGER:
                return [
                    'vehicles' => $this->userModel->getAssignedVehicles($_SESSION['user_id']),
                    'teams' => $this->userModel->getAssignedTeams($_SESSION['user_id'])
                ];
            case RoleHelper::SUPPLIER:
                return [
                    'collections' => $this->userModel->getSupplierCollections($_SESSION['user_id']),
                    'statistics' => $this->userModel->getSupplierStats($_SESSION['user_id'])
                ];
            case RoleHelper::DRIVER:
                return [
                    'assignedVehicle' => $this->userModel->getDriverVehicle($_SESSION['user_id']),
                    'assignedTeam' => $this->userModel->getDriverTeam($_SESSION['user_id']),
                    'collections' => $this->userModel->getDriverCollections($_SESSION['user_id'])
                ];
            case RoleHelper::PARTNER:
                return [
                    'assignedTeam' => $this->userModel->getPartnerTeam($_SESSION['user_id']),
                    'collections' => $this->userModel->getPartnerCollections($_SESSION['user_id'])
                ];
            case RoleHelper::ADMIN:
                return [
                    'systemStats' => $this->userModel->getSystemStats(),
                    'userManagement' => $this->userModel->getUserManagementStats()
                ];
            default:
                return [];
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Check if user has permission to update profile
                if (!RoleHelper::canUpdateProfile()) {
                    throw new Exception('You do not have permission to update this profile');
                }

                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                $data = [
                    'user_id' => $_SESSION['user_id'],
                    'name' => trim($_POST['name']),
                    'email' => trim($_POST['email']),
                    'phone' => trim($_POST['phone']),
                    // Add other fields as needed
                ];

                // Validate data
                if (empty($data['name']) || empty($data['email'])) {
                    throw new Exception('Please fill in all required fields');
                }

                // Update user profile
                if ($this->userModel->updateUser($data)) {
                    flash('profile_message', 'Profile Updated Successfully', 'alert alert-success');
                } else {
                    throw new Exception('Error updating profile');
                }

                redirect('profile');

            } catch (Exception $e) {
                flash('profile_message', $e->getMessage(), 'alert alert-danger');
                redirect('profile');
            }
        } else {
            redirect('profile');
        }
    }

    public function updatePassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'user_id' => $_SESSION['user_id'],
                    'current_password' => $_POST['current_password'],
                    'new_password' => $_POST['new_password'],
                    'confirm_password' => $_POST['confirm_password']
                ];

                // Validate passwords
                if ($data['new_password'] !== $data['confirm_password']) {
                    throw new Exception('New passwords do not match');
                }

                // Update password
                if ($this->userModel->updatePassword($data)) {
                    flash('profile_message', 'Password Updated Successfully', 'alert alert-success');
                } else {
                    throw new Exception('Error updating password');
                }

                redirect('profile');

            } catch (Exception $e) {
                flash('profile_message', $e->getMessage(), 'alert alert-danger');
                redirect('profile');
            }
        }
    }

    public function uploadProfileImage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
            try {
                $file = $_FILES['profile_image'];
                
                // Configure upload settings
                $upload_dir = 'uploads/profile_images/';
                $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                $file_name = uniqid() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                // Validate file type
                $allowed_types = ['jpg', 'jpeg', 'png'];
                if (!in_array($file_extension, $allowed_types)) {
                    throw new Exception('Invalid file type');
                }
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $file_path)) {
                    // Save to database
                    if ($this->userModel->updateProfileImage($_SESSION['user_id'], $file_path)) {
                        echo json_encode(['success' => true, 'file_path' => $file_path]);
                    } else {
                        throw new Exception('Failed to update profile image in database');
                    }
                } else {
                    throw new Exception('Failed to upload file');
                }

            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
        }
    }

    public function updateDriverInfo() {
        if (!RoleHelper::hasRole(RoleHelper::DRIVER)) {
            flash('profile_message', 'Unauthorized access', 'alert alert-danger');
            redirect('profile');
            return;
        }
        // Driver-specific update logic
    }

    public function updateSupplierInfo() {
        if (!RoleHelper::hasRole(RoleHelper::SUPPLIER)) {
            flash('profile_message', 'Unauthorized access', 'alert alert-danger');
            redirect('profile');
            return;
        }
        // Supplier-specific update logic
    }
}
?>