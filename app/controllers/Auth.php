<?php

require_once APPROOT . '/helpers/auth_middleware.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends Controller
{
    private $userModel;
    private $applicationModel;

    public function __construct()
    {
        $this->userModel = $this->model('M_User');
    }

    public function register()
    {
        // Redirect if already logged in
        $this->preventLoginAccess();

        $data = [
            'email' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data['email'] = trim($_POST['email']);
            $data['password'] = trim($_POST['password']);

            // Validate data
            if (empty($data['email']) || empty($data['password'])) {
                $data['error'] = 'Please fill in all fields';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email';
            } elseif (strlen($data['password']) < 8) { // at least 8 characters
                $data['error'] = 'Password must be at least 8 characters long';
            } elseif (!preg_match('/[A-Z]/', $data['password'])) { // at least one uppercase letter
                $data['error'] = 'Password must contain at least one uppercase letter';
            } elseif (!preg_match('/[a-z]/', $data['password'])) { // at least one lowercase letter
                $data['error'] = 'Password must contain at least one lowercase letter';
            } elseif (!preg_match('/[0-9]/', $data['password'])) { // at least one number
                $data['error'] = 'Password must contain at least one number';
            } elseif (!preg_match('/[\W_]/', $data['password'])) { //at least one special character
                $data['error'] = 'Password must contain at least one special character';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['error'] = 'Email is already registered';
            } else {
                try {
                    // hashing
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    $data['role_id'] = RoleHelper::getRoleByTitle('Website User'); 
                    $data['account_status'] = 'Active'; // Default status

                    // Register user
                    if ($this->userModel->registerUser($data)) {
                        redirect('auth/login');
                    } else {
                        $data['error'] = 'Registration failed. Please try again.';
                    }
                } catch (PDOException $e) {
                    $data['error'] = 'Registration failed. Please check your information.';
                }
            }
        }

        $this->view('auth/v_register', $data);
    }

    private function isOlderThan18($dateOfBirth)
    {
        $dob = new DateTime($dateOfBirth);
        $today = new DateTime();
        $age = $today->diff($dob)->y; 
        return $age >= 18; 
    }

    public function login()
    {
        $this->preventLoginAccess();

        $data = [
            'username' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST);

            $data['username'] = trim($_POST['username']);
            $data['password'] = trim($_POST['password']);

            // Validate
            if (empty($data['username']) || empty($data['password'])) {
                $data['error'] = 'Please fill in all fields';
            } else {
                $user = $this->userModel->findUserByEmail($data['username']);

                if ($user) {
                    // Verify password
                    if (password_verify($data['password'], $user->password)) {
                        // Check role-specific conditions
                        $canLogin = true;
                        $loginErrorMessage = '';

                        // For drivers, check if theyre marked as deleted
                        if ($user->role_id == RoleHelper::DRIVER) {
                            $driverId = $this->userModel->getDriverId($user->user_id);
                            if (!$driverId) {
                                $canLogin = false;
                                $loginErrorMessage = 'This driver account is currently inactive. Please contact the administrator';
                            } else {
                                $_SESSION['driver_id'] = $driverId->driver_id;
                            }
                        } 
                        elseif ($user->role_id == RoleHelper::MANAGER) {
                            $managerId = $this->userModel->getManagerId($user->user_id);
                            if ($managerId) {
                                $_SESSION['manager_id'] = $managerId->manager_id;
                            }
                        } elseif ($user->role_id == RoleHelper::SUPPLIER) {
                            $supplierId = $this->userModel->getSupplierId($user->user_id);
                            if ($supplierId) {
                                $_SESSION['supplier_id'] = $supplierId->supplier_id;
                            } else {
                                $canLogin = false;
                                $loginErrorMessage = 'Supplier record not found.';
                            }
                        } elseif ($user->role_id == RoleHelper::ADMIN) {
                            $managerId = $this->userModel->getManagerId($user->user_id);
                            if ($managerId) {
                                $_SESSION['manager_id'] = $managerId->manager_id;
                            }  $loginErrorMessage = 'Supplier record not found.';
                            
                        }

                        // If all checks pass, complete login
                        if ($canLogin) {
                            $_SESSION['user_id'] = $user->user_id;
                            $_SESSION['email'] = $user->email;
                            $_SESSION['role_id'] = $user->role_id;

                            // After successful login, redirect based on role
                            switch (RoleHelper::getRole()) {
                                case RoleHelper::DRIVER:
                                    header('Location: ' . URLROOT . '/vehicledriver/');
                                    break;
                                case RoleHelper::VEHICLE_MANAGER:
                                    header('Location: ' . URLROOT . '/manager/');
                                    break;
                                case RoleHelper::SUPPLIER:
                                    header('Location: ' . URLROOT . '/supplier/');
                                    break;
                                case RoleHelper::ADMIN:
                                    header('Location: ' . URLROOT . '/manager/');
                                    break;
                                case RoleHelper::INVENTORY_MANAGER:
                                    header('Location: ' . URLROOT . '/inventory/');
                                    break;
                                default:
                                    header('Location: ' . URLROOT . '/');
                            }
                            exit();
                        } else {
                            $data['error'] = $loginErrorMessage;
                        }
                    } else {
                        $data['error'] = 'Invalid credentials';
                    }
                } else {
                    $data['error'] = 'Invalid credentials';
                }
            }
        }

        $this->view('auth/v_login', $data);
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['first_name']);
        unset($_SESSION['last_name']);
        unset($_SESSION['email']);
        unset($_SESSION['role_id']);
        session_destroy();

        header('Location: ' . URLROOT);
        exit();
    }

    public function supplier_register()
    {
        // 1. Check if user is logged in
        if (!isLoggedIn()) {
            redirect('auth/login');
            return;
        }

        // 2. Check if user has already applied
        $supplierApplicationModel = $this->model('M_SupplierApplication');
        if ($supplierApplicationModel->hasApplied($_SESSION['user_id'])) {
            redirect('pages/supplier_application_status');
            return;
        }

        // 3. Process form submission if POST request
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Collect profile data
                $profileData = [
                    'user_id' => $_SESSION['user_id'],
                    'first_name' => trim($_POST['first_name']),
                    'last_name' => trim($_POST['last_name']),
                    'nic' => trim($_POST['nic_number']),
                    'date_of_birth' => trim($_POST['date_of_birth']),
                    'contact_number' => trim($_POST['contact_number']),
                    'emergency_contact' => !empty($_POST['emergency_contact']) ? trim($_POST['emergency_contact']) : null,
                    'address_line1' => trim($_POST['address_line1']),
                    'address_line2' => !empty($_POST['address_line2']) ? trim($_POST['address_line2']) : null,
                    'city' => trim($_POST['city'])
                ];

                // Collect application data
                $applicationData = [
                    'user_id' => $_SESSION['user_id'],
                    'location' => [
                        'latitude' => $_POST['latitude'],
                        'longitude' => $_POST['longitude']
                    ],
                    'cultivation' => [
                        'tea_cultivation_area' => $_POST['teaCultivationArea'],
                        'plant_age' => $_POST['plant_age'],
                        'monthly_production' => $_POST['monthly_production']
                    ],
                    'bank_info' => [
                        'account_holder_name' => $_POST['accountHolderName'],
                        'bank_name' => $_POST['bankName'],
                        'branch_name' => $_POST['branchName'],
                        'account_number' => $_POST['accountNumber'],
                        'account_type' => $_POST['accountType']
                    ]
                ];

                // Basic validation
                if (!isset($_FILES['profile_photo']) || $_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
                    throw new Exception("Profile photo is required");
                }

                // Process documents
                $documents = [];
                $requiredDocs = ['nic_document', 'ownership_proof'];
                foreach ($requiredDocs as $doc) {
                    if (!isset($_FILES[$doc]) || $_FILES[$doc]['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception("Document $doc is required");
                    }
                    $documents[$doc] = $_FILES[$doc];
                }

                // Rename document keys for the model
                $processedDocuments = [];
                foreach ($documents as $key => $value) {
                    $newKey = ($key === 'nic_document') ? 'nic' : $key;
                    $processedDocuments[$newKey] = $value;
                }

                // Save application
                $result = $supplierApplicationModel->createProfileAndApplication(
                    $profileData,
                    $applicationData,
                    $_FILES['profile_photo'],
                    $processedDocuments
                );

                if (!$result) {
                    throw new Exception('Failed to save application');
                }

                // Redirect to status page on success
                redirect('pages/supplier_application_status?submitted=true');
                
            } catch (Exception $e) {
                // Set error message and continue to display the form
                $data = [
                    'title' => 'Supplier Registration',
                    'error' => $e->getMessage()
                ];
                $this->view('auth/v_supplier_register', $data);
                return;
            }
        }

        // 4. Display the form for GET requests
        $data = [
            'title' => 'Supplier Registration'
        ];
        $this->view('auth/v_supplier_register', $data);
    }

    private function getFileUploadError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file exceeds the upload_max_filesize directive in php.ini";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload";
            default:
                return "Unknown upload error";
        }
    }

    public function verify()
    {
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            if ($this->userModel->verifyEmail($code)) {
                // Email verified successfully
                $this->view('auth/v_verify'); // Load the verification success view
            } else {
                // Invalid verification code
                echo "Invalid verification code.";
            }
        } else {
            // No code provided
            echo "No verification code provided.";
        }
    }

    public function forgotPassword()
    {
        $data = [
            'email' => '',
            'error' => '',
            'success' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST);
            $data['email'] = trim($_POST['email']);

            // Validate email
            if (empty($data['email'])) {
                $data['error'] = 'Please enter your email address.';
            } elseif (!$this->userModel->findUserByEmail($data['email'])) {
                $data['error'] = 'No account found with that email address.';
            } else {
                // Generate a password reset token
                $resetToken = bin2hex(random_bytes(16)); // Generate a random token
                $this->userModel->storeResetToken($data['email'], $resetToken); // Store the token in the database

                // Create reset link
                $resetLink = URLROOT . "/auth/resetPassword?token=" . $resetToken;

                // Send email using PHPMailer
                $mail = new PHPMailer(true);
                try {
                    //Server settings
                    $mail->isSMTP();                                            // Send using SMTP
                    $mail->Host       = 'smtp.gmail.com';                     // Set the SMTP server to send through
                    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                    $mail->Username   = 'simaakniyaz@gmail.com';               // SMTP username
                    $mail->Password   = 'yslhjwsnmozojika';                    // SMTP password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;       // Enable TLS encryption
                    $mail->Port       = 587;                                   // TCP port to connect to

                    //Recipients
                    $mail->setFrom('your_email@example.com', 'Password Reset');
                    $mail->addAddress($data['email']);                         // Add a recipient

                    // Content
                    $mail->isHTML(true);                                       // Set email format to HTML
                    $mail->Subject = 'Password Reset Request';
                    $mail->Body    = "Please click the following link to reset your password: <a href='$resetLink'>$resetLink</a>";

                    $mail->send();
                    $data['success'] = 'A password reset link has been sent to your email address.';
                } catch (Exception $e) {
                    $data['error'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        }

        $this->view('auth/v_forgot_password', $data);
    }

    public function resetPassword()
    {
        $data = [
            'token' => '',
            'password' => '',
            'confirm_password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST);
            $data['token'] = trim($_POST['token']);
            $data['password'] = trim($_POST['password']);
            $data['confirm_password'] = trim($_POST['confirm_password']);

            // Validate token and passwords
            if (empty($data['token']) || empty($data['password']) || empty($data['confirm_password'])) {
                $data['error'] = 'Please fill in all fields.';
            } elseif ($data['password'] !== $data['confirm_password']) {
                $data['error'] = 'Passwords do not match.';
            } elseif (strlen($data['password']) < 8) { // Minimum length check
                $data['error'] = 'Password must be at least 8 characters long.';
            } elseif (!preg_match('/[A-Za-z]/', $data['password'])) { // Check for letters
                $data['error'] = 'Password must contain at least one letter.';
            } elseif (!preg_match('/[0-9]/', $data['password'])) { // Check for numbers
                $data['error'] = 'Password must contain at least one number.';
            } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $data['password'])) { // Check for special characters
                $data['error'] = 'Password must contain at least one special character.';
            } else {
                // Verify the token
                if ($this->userModel->verifyResetToken($data['token'])) {
                    // Hash the new password
                    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
                    // Update the password in the database
                    $this->userModel->updatePassword($data['token'], $hashedPassword);
                    
                    // Redirect to login page after successful password reset
                    header('Location: ' . URLROOT . '/auth/login');
                    exit();
                } else {
                    $data['error'] = 'Invalid or expired token.';
                }
            }
        } else {
            // If GET request, retrieve the token from the URL
            if (isset($_GET['token'])) {
                $data['token'] = $_GET['token'];
            }
        }

        $this->view('auth/v_reset_password', $data);
    }

}
?>