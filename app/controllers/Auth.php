<?php

require_once APPROOT . '/helpers/auth_middleware.php';

class Auth extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('M_User');
    }

    public function register() {
        // Redirect if already logged in
        $this->preventLoginAccess();

        $data = [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'password' => '',
            'confirm_password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'email' => trim($_POST['email']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'error' => ''
            ];

            // Validate data
            if (empty($data['email']) || 
                empty($data['first_name']) || 
                empty($data['last_name']) || 
                empty($data['password']) ||
                empty($data['confirm_password'])) {
                $data['error'] = 'Please fill in all fields';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email';
            } elseif ($this->userModel->findUserByEmail($data['email'])) {
                $data['error'] = 'Email is already registered';
            } elseif (strlen($data['password']) < 6) {
                $data['error'] = 'Password must be at least 6 characters long';
            } elseif ($data['password'] !== $data['confirm_password']) {
                $data['error'] = 'Passwords do not match';
            } else {
                try {
                    // Hash password
                    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

                    
                    $data['password'] = $hashedPassword;


                    // Set default role_id and approval_status
                    $data['role_id'] = RoleHelper::getRoleByTitle('Website User');
                    $data['approval_status'] = 'None';

                    // Remove confirm_password before saving
                    unset($data['confirm_password']);

                    // Register user
                    if ($this->userModel->register($data)) {
                        $_SESSION['success_message'] = 'Registration successful! Please login.';
                        header('Location: ' . URLROOT . '/auth/login');
                        exit();
                    } else {
                        $data['error'] = 'Registration failed. Please try again.';
                    }
                } catch (PDOException $e) {
                    $data['error'] = 'Registration failed. Please check your information.';
                    // Log the error for debugging
                    error_log("Registration Error: " . $e->getMessage());
                }
            }
        }

        $this->view('auth/v_register', $data);
    }

    public function login() {
        // Redirect if already logged in
        $this->preventLoginAccess();

        $data = [
            'username' => '',
            'password' => '',
            'error' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data['username'] = trim($_POST['username']);
            $data['password'] = trim($_POST['password']);

            // Validate
            if (empty($data['username']) || empty($data['password'])) {
                $data['error'] = 'Please fill in all fields';
            } else {
                $user = $this->userModel->findUserByEmail($data['username']);
                
                echo "<pre>";
                echo "=== Login Attempt Debug ===\n";
                echo "Email attempting login: \n";
                var_dump($data['username']);
                echo "Password submitted: \n";
                var_dump($data['password']);
                
                if ($user) {
                    echo "User found in database:\n";
                    var_dump($user);
                    echo "Password verify result: \n";
                    var_dump(password_verify($data['password'], $user->password));
                    
                    // Test a direct hash comparison
                    $testHash = password_hash($data['password'], PASSWORD_DEFAULT);
                    echo "Test hash of input password: \n";
                    var_dump($testHash);
                    echo "Would verify with test hash: \n";
                    var_dump(password_verify($data['password'], $testHash));
                } else {
                    echo "No user found with this email\n";
                }
                echo "=== End Debug ===\n";
                echo "</pre>";
                
                if ($user && password_verify($data['password'], $user->password)) {
                    $_SESSION['user_id'] = $user->user_id;
                    $_SESSION['first_name'] = $user->first_name;
                    $_SESSION['last_name'] = $user->last_name;
                    $_SESSION['email'] = $user->email;
                    $_SESSION['role_id'] = $user->role_id;
                    
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
                            header('Location: ' . URLROOT . '/collections');
                            break;
                        case RoleHelper::DRIVING_PARTNER:
                            header('Location: ' . URLROOT . '/drivingpartner/');
                            break;
                        default:
                            header('Location: ' . URLROOT . '/');
                    }
                    exit();
                } else {
                    $data['error'] = 'Invalid credentials';
                }
            }
        }

        $this->view('auth/v_login', $data);
    }

    public function logout() {
        unset($_SESSION['user_id']);
        unset($_SESSION['first_name']);
        unset($_SESSION['last_name']);
        unset($_SESSION['email']);
        unset($_SESSION['role_id']);
        session_destroy();
        
        header('Location: ' . URLROOT);
        exit();
    }


    public function supplier_register() {
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
                    'primary_phone' => $_POST['primary_phone'],
                    'secondary_phone' => !empty($_POST['secondary_phone']) ? $_POST['secondary_phone'] : null,
                    
                    'address' => [
                        'line1' => $_POST['line1'],
                        'line2' => !empty($_POST['line2']) ? $_POST['line2'] : null,
                        'city' => $_POST['city'],
                        'district' => $_POST['district'],
                        'postal_code' => $_POST['postal_code'],
                        'latitude' => $_POST['latitude'],
                        'longitude' => $_POST['longitude']
                    ],
                    
                    'property' => [
                        'total_land_area' => $_POST['total_land_area'],
                        'tea_cultivation_area' => $_POST['tea_cultivation_area'],
                        'elevation' => $_POST['elevation'],
                        'slope' => $_POST['slope']
                    ],
                    
                    'infrastructure' => [
                        'water_sources' => isset($_POST['water_sources']) ? $_POST['water_sources'] : [],
                        'vehicle_access' => $_POST['vehicle_access'],
                        'structures' => isset($_POST['structures']) ? $_POST['structures'] : []
                    ],
                    
                    'bank_info' => [
                        'account_holder_name' => $_POST['account_holder_name'],
                        'bank_name' => $_POST['bank_name'],
                        'branch_name' => $_POST['branch_name'],
                        'account_number' => $_POST['account_number'],
                        'account_type' => $_POST['account_type']
                    ]
                ];

                // Debug log to check the data
                error_log("Application Data: " . print_r($applicationData, true));

                // Validate file uploads
                $documents = [];
                $requiredDocs = [
                    'land_deed',
                    'tax_receipt', 
                    'tea_cultivation_certificate',
                    'id_proof',
                    'bank_statement'
                ];

                // Check if documents array was submitted
                if (!isset($_FILES['documents']) || !is_array($_FILES['documents'])) {
                    throw new Exception("No documents were uploaded");
                }

                foreach ($requiredDocs as $doc) {
                    // Check if this document exists in the uploaded files
                    if (!isset($_FILES['documents']['name'][$doc])) {
                        throw new Exception("Missing upload for: {$doc}");
                    }

                    // Check for specific upload errors
                    if ($_FILES['documents']['error'][$doc] !== UPLOAD_ERR_OK) {
                        $errorMessage = $this->getFileUploadError($_FILES['documents']['error'][$doc]);
                        throw new Exception("Error uploading {$doc}: {$errorMessage}");
                    }

                    // Validate file type
                    $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
                    if (!in_array($_FILES['documents']['type'][$doc], $allowedTypes)) {
                        throw new Exception("{$doc} must be a JPG, PNG, or PDF file");
                    }

                    // Validate file size (5MB limit)
                    $maxSize = 5 * 1024 * 1024;
                    if ($_FILES['documents']['size'][$doc] > $maxSize) {
                        throw new Exception("{$doc} must be less than 5MB");
                    }

                    // Create individual file array for each document
                    $documents[$doc] = [
                        'name' => $_FILES['documents']['name'][$doc],
                        'type' => $_FILES['documents']['type'][$doc],
                        'tmp_name' => $_FILES['documents']['tmp_name'][$doc],
                        'error' => $_FILES['documents']['error'][$doc],
                        'size' => $_FILES['documents']['size'][$doc]
                    ];
                }

                // Add validation for latitude and longitude before the file validation
                if (empty($_POST['latitude']) || empty($_POST['longitude'])) {
                    throw new Exception('Location coordinates are required');
                }

                // Validate coordinate ranges for Sri Lanka
                $lat = floatval($_POST['latitude']);
                $lng = floatval($_POST['longitude']);

                // if ($lat < 5.9 || $lat > 9.9 || $lng < 79.5 || $lng > 81.9) {
                //     throw new Exception('Location must be within Sri Lanka');
                // }

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

    private function getFileUploadError($errorCode) {
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

}
?>