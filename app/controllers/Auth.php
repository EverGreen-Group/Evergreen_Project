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
            'title' => '',
            'first_name' => '',
            'last_name' => '',
            'nic' => '',
            'gender' => '',
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
                'gender' => trim($_POST['gender']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'password' => trim($_POST['password']),
                'error' => ''
            ];

            // Validate data
            if (empty($data['email']) || 
                empty($data['first_name']) || empty($data['last_name']) || 
                empty($data['nic']) || empty($data['gender']) || 
                empty($data['date_of_birth']) || empty($data['password'])) {
                $data['error'] = 'Please fill in all fields';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email';
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
                        header('Location: ' . URLROOT . '/auth/login');
                        exit();
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
                
                // Add these debug lines
                var_dump($user); // Check if user is found
                var_dump($data['password']); // Check the submitted password
                var_dump($user->password); // Check the stored hashed password
                
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
                            header('Location: ' . URLROOT . '/');
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
        // Start output buffering to prevent unwanted output
        ob_start();
        
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Clear any existing output
                ob_clean();
                
                // Temporarily disable error display
                ini_set('display_errors', 0);
                error_reporting(E_ALL);
                
                $supplierApplicationModel = $this->model('M_SupplierApplication');
                
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
                        'postal_code' => $_POST['postalCode']
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