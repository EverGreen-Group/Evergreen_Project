<?php
require_once '../app/models/M_User.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';

class Profile extends Controller {
    private $userModel;
    private $driverModel;
    private $logModel;

    public function __construct() {
        $this->userModel = $this->model('M_User');
        $this->driverModel = $this->model('M_Driver');
        $this->logModel = $this->model('M_Log');
    }

    public function index() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Get basic user info
        $userInfo = $this->userModel->getUserById($_SESSION['user_id']);
        
        // Initialize data array
        $data = [
            'title' => 'Profile',
            'userInfo' => $userInfo,
            'driverDetails' => null,
            'financial' => [] // Initialize if needed
        ];

        // If user is a driver, get driver-specific details
        if (RoleHelper::hasRole(RoleHelper::DRIVER)) {
            $driverDetails = $this->driverModel->getDriverDetails($_SESSION['user_id']);
            $data['driverDetails'] = $driverDetails;
        }

        $this->view('pages/profile', $data);
    }

    public function driver($user_id) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Get basic user info
        $userInfo = $this->driverModel->getDriverAndEmployeeDetails($user_id);
        
        // Initialize data array
        $data = [
            'userInfo' => $userInfo
        ];

        $this->view('pages/profile/driver_profile', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {

                // Sanitize POST data
                // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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

                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Updated the profile",
                        $_SERVER['REQUEST_URI'],     
                        http_response_code()     
                    );
                    setFlashMessage('Profile updated successfully!');
                } else {
                    throw new Exception('Error updating profile');
                }

                redirect('profile');

            } catch (Exception $e) {
                setFlashMessage('Profile update failed!', 'error');
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

                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Updated the account password",
                        $_SERVER['REQUEST_URI'],     
                        http_response_code()     
                    );
                    setFlashMessage('Password updated successfully!');
                } else {
                    throw new Exception('Error updating password');
                }

                redirect('profile');

            } catch (Exception $e) {
                setFlashMessage('Password update failed: ' . $e, 'error');
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
            redirect('profile');
            return;
        }
        // Driver-specific update logic
    }

    public function updateSupplierInfo() {
        if (!RoleHelper::hasRole(RoleHelper::SUPPLIER)) {
            redirect('profile');
            return;
        }
        // Supplier-specific update logic
    }
}
?>