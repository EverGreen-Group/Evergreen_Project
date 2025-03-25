<?php

require_once APPROOT . '/helpers/auth_middleware.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends Controller
{
    private $userModel;

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
            'title' => '',
            'first_name' => '',
            'last_name' => '',
            'nic' => '',
            'date_of_birth' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'nic' => trim($_POST['nic']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'password' => trim($_POST['password']),
                'error' => ''
            ];

            // Validate data
            if (
                empty($data['email']) ||
                empty($data['first_name']) || empty($data['last_name']) ||
                empty($data['nic']) ||
                empty($data['date_of_birth']) || empty($data['password'])
            ) {
                $data['error'] = 'Please fill in all fields';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email';
            } elseif (strlen($data['first_name']) < 2) {
                $data['error'] = 'First name must be at least 2 characters';
            } elseif (strlen($data['last_name']) < 2) {
                $data['error'] = 'Last name must be at least 2 characters';
            } elseif (!preg_match('/^[0-9]{8,}[XxVv]?$/', $data['nic'])) { // NIC validation for more than 7 digits with optional ending X or V
                $data['error'] = 'NIC must contain more than 7 digits and may optionally end with X, x, V, or v';
            } elseif (strlen($data['password']) < 8) { // Password must be at least 8 characters
                $data['error'] = 'Password must be at least 8 characters long';
            } elseif (!preg_match('/[A-Z]/', $data['password'])) { // At least one uppercase letter
                $data['error'] = 'Password must contain at least one uppercase letter';
            } elseif (!preg_match('/[a-z]/', $data['password'])) { // At least one lowercase letter
                $data['error'] = 'Password must contain at least one lowercase letter';
            } elseif (!preg_match('/[0-9]/', $data['password'])) { // At least one number
                $data['error'] = 'Password must contain at least one number';
            } elseif (!preg_match('/[\W_]/', $data['password'])) { // At least one special character
                $data['error'] = 'Password must contain at least one special character';
            } elseif (!$this->isOlderThan18($data['date_of_birth'])) { // Check if user is older than 18
                $data['error'] = 'You must be at least 18 years old to register.';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['error'] = 'Email is already registered';
            } elseif ($this->userModel->findUserByNIC($data['nic'])) {
                $data['error'] = 'NIC is already registered';
            } else {
                try {
                    // Hash password
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                    // Set default role_id and approval_status
                    $data['role_id'] = RoleHelper::getRoleByTitle('Website User'); // Website User role
                    $data['approval_status'] = 'None'; // Default status

                    // Register user
                    if ($this->userModel->register($data)) {
                        // Redirect to login with success message
                        flash('register_success', 'You are registered! Please log in');
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

    // Helper function to check if the user is older than 18
    private function isOlderThan18($dateOfBirth)
    {
        $dob = new DateTime($dateOfBirth);
        $today = new DateTime();
        $age = $today->diff($dob)->y; // Calculate age in years
        return $age >= 18; // Return true if 18 or older
    }

    public function login()
    {
        // Redirect if already logged in
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
                    // Removed email verification check
                    if (password_verify($data['password'], $user->password)) {
                        $_SESSION['user_id'] = $user->user_id;
                        $_SESSION['first_name'] = $user->first_name;
                        $_SESSION['last_name'] = $user->last_name;
                        $_SESSION['email'] = $user->email;
                        $_SESSION['role_id'] = $user->role_id;

                        // Retrieve additional IDs based on role
                        if ($user->role_id == RoleHelper::DRIVER) {
                            $driverId = $this->userModel->getDriverId($user->user_id);
                            $employeeId = $this->userModel->getEmployeeId($user->user_id);
                            if ($driverId) {
                                $_SESSION['driver_id'] = $driverId->driver_id;
                            }
                            if ($employeeId) {
                                $_SESSION['employee_id'] = $employeeId->employee_id;
                            }
                        } elseif (in_array($user->role_id, [RoleHelper::VEHICLE_MANAGER, RoleHelper::INVENTORY_MANAGER, RoleHelper::SUPPLIER_MANAGER])) {
                            $managerId = $this->userModel->getManagerId($user->user_id);
                            $employeeId = $this->userModel->getEmployeeId($user->user_id);
                            if ($managerId) {
                                $_SESSION['manager_id'] = $managerId->manager_id;
                            }
                            if ($employeeId) {
                                $_SESSION['employee_id'] = $employeeId->employee_id;
                            }
                        } elseif ($user->role_id == RoleHelper::SUPPLIER) {
                            $supplierId = $this->userModel->getSupplierId($user->user_id);
                            if ($supplierId) {
                                $_SESSION['supplier_id'] = $supplierId->supplier_id;
                            }
                        }

                        // After successful login, redirect based on role
                        switch (RoleHelper::getRole()) {
                            case RoleHelper::DRIVER:
                                header('Location: ' . URLROOT . '/vehicledriver/');
                                break;
                            case RoleHelper::VEHICLE_MANAGER:
                                header('Location: ' . URLROOT . '/vehiclemanager/');
                                break;
                            case RoleHelper::SUPPLIER:
                                header('Location: ' . URLROOT . '/supplier/');
                                break;
                            case RoleHelper::ADMIN:
                                header('Location: ' . URLROOT . '/vehiclemanager/');
                                break;
                            case RoleHelper::INVENTORY_MANAGER:
                                header('Location: ' . URLROOT . '/inventory/');
                                break;
                            default:
                                header('Location: ' . URLROOT . '/');
                        }
                        exit();
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
        // First, check login status
        if (!isLoggedIn()) {
            redirect('auth/login');
            return; // Add return to ensure the function stops
        }

        // Move this up before any other processing
        $supplierApplicationModel = $this->model('M_SupplierApplication');

        // Add debug logging
        error_log("Checking application status for user: " . $_SESSION['user_id']);

        // Check if user has already applied and redirect if true
        if ($supplierApplicationModel->hasApplied($_SESSION['user_id'])) {
            error_log("User has already applied, redirecting to status page");
            redirect('pages/supplier_application_status');
            return; // Add return to ensure the function stops
        }

        // Start output buffering only if we're continuing with the registration
        ob_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Clear any existing output
                ob_clean();

                // Temporarily disable error display
                ini_set('display_errors', 0);
                error_reporting(E_ALL);



                // Validate postal code length 
                if (strlen($_POST['postalCode']) > 6) {
                    throw new Exception('Postal code must be between 5 and 10 characters');
                }

                // Prepare application data
                $applicationData = [
                    'user_id' => $_SESSION['user_id'],
                    'primary_phone' => $_POST['primaryPhone'],
                    'secondary_phone' => !empty($_POST['secondaryPhone']) ? $_POST['secondaryPhone'] : null,
                    'whatsapp_number' => !empty($_POST['whatsappNumber']) ? $_POST['whatsappNumber'] : null,

                    'address' => [
                        'line1' => $_POST['line1'],
                        'line2' => !empty($_POST['line2']) ? $_POST['line2'] : null,
                        'city' => $_POST['city'],
                        'district' => $_POST['district'],
                        'postal_code' => $_POST['postalCode'],
                        'latitude' => $_POST['latitude'],
                        'longitude' => $_POST['longitude']
                    ],

                    'teaVarieties' => isset($_POST['tea_varieties']) ? $_POST['tea_varieties'] : [],

                    'ownership' => [
                        'ownership_type' => $_POST['ownership_type'],
                        'ownership_duration' => $_POST['ownership_duration']
                    ],

                    'tea_details' => [
                        'plant_age' => $_POST['plant_age'],
                        'monthly_production' => $_POST['monthly_production']
                    ],

                    'property' => [
                        'total_land_area' => $_POST['totalLandArea'],
                        'tea_cultivation_area' => $_POST['teaCultivationArea'],
                        'elevation' => $_POST['elevation'],
                        'slope' => $_POST['slope']
                    ],

                    'infrastructure' => [
                        'water_source' => isset($_POST['water_source']) ? $_POST['water_source'] : [],
                        'access_road' => $_POST['access_road'],
                        'vehicle_access' => $_POST['vehicle_access'],
                        'structures' => isset($_POST['structures']) ? $_POST['structures'] : []
                    ],

                    'bank_info' => [
                        'account_holder_name' => $_POST['accountHolderName'],
                        'bank_name' => $_POST['bankName'],
                        'branch_name' => $_POST['branchName'],
                        'account_number' => $_POST['accountNumber'],
                        'account_type' => $_POST['accountType']
                    ]
                ];

                // Debug log to check the data
                error_log("Application Data: " . print_r($applicationData, true));

                // Validate file uploads
                $documents = [];
                $requiredDocs = ['nic', 'ownership_proof', 'tax_receipts', 'bank_passbook', 'grama_cert'];

                foreach ($requiredDocs as $doc) {
                    // Check if file exists and there are no upload errors
                    if (!isset($_FILES[$doc]) || !is_array($_FILES[$doc])) {
                        throw new Exception("Missing upload for: {$doc}");
                    }

                    // Check for specific upload errors
                    if ($_FILES[$doc]['error'] !== UPLOAD_ERR_OK) {
                        $errorMessage = $this->getFileUploadError($_FILES[$doc]['error']);
                        throw new Exception("Error uploading {$doc}: {$errorMessage}");
                    }

                    // Validate file type (add allowed types as needed)
                    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                    if (!in_array($_FILES[$doc]['type'], $allowedTypes)) {
                        throw new Exception("{$doc} must be a JPG, PNG, or PDF file");
                    }

                    // Validate file size (e.g., 5MB limit)
                    $maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if ($_FILES[$doc]['size'] > $maxSize) {
                        throw new Exception("{$doc} must be less than 5MB");
                    }

                    $documents[$doc] = $_FILES[$doc];
                }

                // Add validation for latitude and longitude before the file validation
                if (empty($_POST['latitude']) || empty($_POST['longitude'])) {
                    throw new Exception('Location coordinates are required');
                }

                // Validate coordinate ranges for Sri Lanka
                $lat = floatval($_POST['latitude']);
                $lng = floatval($_POST['longitude']);

                if ($lat < 5.9 || $lat > 9.9 || $lng < 79.5 || $lng > 81.9) {
                    throw new Exception('Location must be within Sri Lanka');
                }

                // Try to save the application
                $result = $supplierApplicationModel->createApplication($applicationData, $documents);

                if (!$result) {
                    throw new Exception('Failed to save application');
                }

                // Clear any output before sending JSON
                ob_clean();

                // Send JSON response
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Application submitted successfully',
                    'redirect' => URLROOT . '/pages/supplier_application_status?submitted=true'
                ]);

                // End output buffering and exit
                ob_end_flush();
                exit();

            } catch (Exception $e) {
                // Log the error
                error_log("Application submission error: " . $e->getMessage());

                // Clear any output before sending JSON
                ob_clean();

                // Send JSON error response
                header('Content-Type: application/json');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Server error: ' . $e->getMessage(),
                    'debug_info' => [
                        'time' => date('Y-m-d H:i:s'),
                        'user_id' => $_SESSION['user_id']
                    ]
                ]);
                exit();
            }
        }

        // For GET requests
        $data = [
            'title' => 'Supplier Registration'
        ];

        $this->view('auth/v_supplier_register', $data);
    }

    private function getFileUploadError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
            case UPLOAD_ERR_FORM_SIZE:
                return 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form';
            case UPLOAD_ERR_PARTIAL:
                return 'The uploaded file was only partially uploaded';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Missing a temporary folder';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Failed to write file to disk';
            case UPLOAD_ERR_EXTENSION:
                return 'A PHP extension stopped the file upload';
            default:
                return 'Unknown upload error';
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