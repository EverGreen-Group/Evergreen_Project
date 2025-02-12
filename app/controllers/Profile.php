<?php
require_once '../app/models/M_User.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';

class Profile extends Controller {
    private $userModel;
    private $driverModel;

    public function __construct() {
        $this->userModel = $this->model('M_User');
        $this->driverModel = $this->model('M_Driver');
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