<?php

require_once APPROOT . '/helpers/auth_middleware.php';
require_once APPROOT . '/controllers/Notifications.php';
require_once APPROOT . '/services/EmailService.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Auth extends Controller
{
    private $userModel;
    private $applicationModel;
    private $notificationModel;
    private $logModel;

    public function __construct()
    {
        $this->userModel = $this->model('M_User');
        $this->notificationModel = $this->model('M_Notification');
        $this->logModel = $this->model('M_Log');
    }

    public function register()
    {
        // Redirect if already logged in
        $this->preventLoginAccess();
    
        $data = [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'nic' => '',
            'date_of_birth' => '',
            'contact_number' => '',
            'password' => '',
            'error' => '',
            'otp_sent' => false
        ];
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sanitize POST data
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            // Check if this is an OTP verification submission
            if (isset($_POST['otp'])) {
                // Verify OTP
                $sessionOTP = isset($_SESSION['registration_otp']) ? $_SESSION['registration_otp'] : null;
                $sessionOTPExpiry = isset($_SESSION['registration_otp_expiry']) ? $_SESSION['registration_otp_expiry'] : 0;
                $enteredOTP = trim($_POST['otp']);
                
                // Get stored registration data
                foreach ($_SESSION['registration_data'] as $key => $value) {
                    $data[$key] = $value;
                }
                
                // Check if OTP has expired
                if (time() > $sessionOTPExpiry) {
                    $data['error'] = 'OTP has expired. Please request a new one.';
                    // Reset OTP session
                    unset($_SESSION['registration_otp']);
                    unset($_SESSION['registration_otp_expiry']);
                    $data['otp_sent'] = false;
                } 
                // Check if OTP matches
                elseif ($sessionOTP !== $enteredOTP) {
                    $data['error'] = 'Invalid OTP. Please try again.';
                    $data['otp_sent'] = true;
                } 
                // OTP is valid, proceed with registration
                else {
                    // Prepare user data
                    $userData = [
                        'email' => $data['email'],
                        'password' => $data['password'], 
                        'role_id' => RoleHelper::getRoleByTitle('Website User'),
                        'account_status' => 'Active'
                    ];

                    // Register user
                    $userId = $this->userModel->registerUser($userData);

                    if ($userId) {
                        // Prepare profile data
                        $profileData = [
                            'user_id' => $userId,
                            'first_name' => $data['first_name'],
                            'last_name' => $data['last_name'],
                            'nic' => $data['nic'],
                            'date_of_birth' => $data['date_of_birth'],
                            'contact_number' => $data['contact_number']
                        ];

                        // Create profile
                        if ($this->userModel->createProfile($profileData)) {
                            // Clear session data
                            unset($_SESSION['registration_otp']);
                            unset($_SESSION['registration_otp_expiry']);
                            unset($_SESSION['registration_data']);

                            $this->logModel->create(
                                $userId,
                                $data['email'],
                                $_SERVER['REMOTE_ADDR'],
                                "User registered as Website User with email {$data['email']}",
                                $_SERVER['REQUEST_URI'],     
                                http_response_code()     
                            );

                            // Set success message and redirect
                            setFlashMessage('Register Successful, you can now log in!');
                            redirect('auth/login');
                        } else {
                            $data['error'] = 'Profile creation failed. Please try again.';
                        }
                    } else {
                        $data['error'] = 'Registration failed. Please try again.';
                    }
                }

            } 
            // Initial form submission - validate data and send OTP
            else {
                // Extract data from POST
                $data['first_name'] = trim($_POST['first_name']);
                $data['last_name'] = trim($_POST['last_name']);
                $data['email'] = trim($_POST['email']);
                $data['nic'] = trim($_POST['nic']);
                $data['date_of_birth'] = trim($_POST['date_of_birth']);
                $data['contact_number'] = trim($_POST['contact_number']);
                $data['password'] = trim($_POST['password']);
    
                // Validate data
                if (empty($data['first_name']) || empty($data['last_name']) || empty($data['email']) || 
                    empty($data['nic']) || empty($data['date_of_birth']) || 
                    empty($data['contact_number']) || empty($data['password'])) {
                    $data['error'] = 'Please fill in all fields';
                } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $data['error'] = 'Please enter a valid email';
                } elseif (!$this->isOlderThan18($data['date_of_birth'])) {
                    $data['error'] = 'You must be at least 18 years old to register';
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
                } elseif ($this->userModel->findProfileByNic($data['nic'])) {
                    $data['error'] = 'NIC number is already registered';
                } elseif ($this->userModel->findProfileByContactNumber($data['contact_number'])) {
                    $data['error'] = 'Contact number is already registered';
                } else {
                    // Hash password before storing in session
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                    
                    // Generate OTP
                    $otp = rand(100000, 999999); // 6-digit OTP
                    $otpExpiry = time() + (10 * 60); // 10 minutes expiry
                    
                    // Store OTP and form data in session
                    $_SESSION['registration_otp'] = (string)$otp; // Store as string to match user input
                    $_SESSION['registration_otp_expiry'] = $otpExpiry;
                    $_SESSION['registration_data'] = $data;
                    
                    // Send OTP via email
                    if ($this->sendOTPEmail($data['email'], $otp)) {
                        $data['otp_sent'] = true;
                    } else {
                        $data['error'] = 'Failed to send OTP. Please try again.';
                    }
                }
            }
        }
    
        $this->view('auth/v_register', $data);
    }
    
    private function sendOTPEmail($email, $otp) {
        $emailService = new EmailService();
        return $emailService->sendOTP($email, $otp);
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
            // $_POST = filter_input_array(INPUT_POST);

            $data['username'] = trim($_POST['username']);
            $data['password'] = trim($_POST['password']);

            // Validate
            if (empty($data['username']) || empty($data['password'])) {
                $data['error'] = 'Please fill in all fields';
            } elseif (!filter_var($data['username'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = 'Please enter a valid email address';
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


                        if ($canLogin) {
                            $_SESSION['user_id'] = $user->user_id;
                            $_SESSION['email'] = $user->email;
                            $_SESSION['role_id'] = $user->role_id;
                            $_SESSION['full_name'] = $this->userModel->getUserName($_SESSION['user_id']);

                            // Fetch profile
                            $profile = $this->userModel->getProfileByUserId($user->user_id);

                            $_SESSION['profile_image_path'] = ($profile && !empty($profile->image_path)) ? $profile->image_path : null;


                            $this->logModel->create(
                                $user->user_id,
                                $user->email,
                                $_SERVER['REMOTE_ADDR'],
                                "User logged in successfully.",
                                $_SERVER['REQUEST_URI'],     
                                http_response_code()     
                            );


                            switch (RoleHelper::getRole()) {
                                case RoleHelper::DRIVER:
                                    header('Location: ' . URLROOT . '/vehicledriver/');
                                    break;
                                case RoleHelper::MANAGER:
                                    header('Location: ' . URLROOT . '/manager/');
                                    break;
                                case RoleHelper::SUPPLIER:
                                    header('Location: ' . URLROOT . '/supplier/');
                                    break;
                                case RoleHelper::ADMIN:
                                    header('Location: ' . URLROOT . '/admin/');
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
                            // Log failed login attempt
                            $this->logModel->create(
                                $user->user_id,
                                $user->email,
                                $_SERVER['REMOTE_ADDR'],
                                "Failed login attempt: {$loginErrorMessage}",
                                $_SERVER['REQUEST_URI'],     
                                http_response_code()     
                            );
                        }
                    } else {
                        $data['error'] = 'Invalid email or password';
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
        $userId = $_SESSION['user_id'];
        $userEmail = $_SESSION['email']; 

        if ($userId) {
            $this->logModel->create(
                $userId,
                $userEmail,
                $_SERVER['REMOTE_ADDR'],
                "User logged out successfully.",
                $_SERVER['REQUEST_URI'],     
                http_response_code()     
            );
        }

        unset($_SESSION['user_id']);
        unset($_SESSION['first_name']);
        unset($_SESSION['last_name']);
        unset($_SESSION['email']);
        unset($_SESSION['role_id']);
        unset($_SESSION['profile_image_path']);
        session_destroy();

        header('Location: ' . URLROOT);
        exit();
    }

    public function supplier_register()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
            return;
        }

        $supplierApplicationModel = $this->model('M_SupplierApplication');
        if ($supplierApplicationModel->hasApplied($_SESSION['user_id'])) {
            redirect('pages/supplier_application_status');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Collect profile data
                $profileData = [
                    'address' => trim($_POST['address'])
                ];

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
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Supplier application submitted.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );

                if (!$result) {
                    throw new Exception('Failed to save application');
                }

                $managers = $this->userModel->getAllManagers();
                foreach ($managers as $manager) {
                    $this->notificationModel->createNotification($manager->user_id, 'New supplier application submitted.', 'manager/viewApplications/' . $result);
                }

                redirect('pages/supplier_application_status?submitted=true');
                
            } catch (Exception $e) {
                // loggin error
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Error during supplier registration: " . $e->getMessage(),
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );

                $data = [
                    'title' => 'Supplier Registration',
                    'error' => $e->getMessage()
                ];
                $this->view('auth/v_supplier_register', $data);
                return;
            }
        }

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
                $this->view('auth/v_verify'); 
            } else {
                echo "Invalid verification code.";
            }
        } else {
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
            // $_POST = filter_input_array(INPUT_POST);
            $data['email'] = trim($_POST['email']);

            // Validate email
            if (empty($data['email'])) {
                $data['error'] = 'Please enter your email address.';
            } elseif (!$this->userModel->findUserByEmail($data['email'])) {
                $data['error'] = 'No account found with that email address.';
            } else {
                // Generate a password reset token
                $resetToken = bin2hex(random_bytes(16)); // Generate a random token
                $this->userModel->storeResetToken($data['email'], $resetToken, 5*60);

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


    public function profile()
    {
        $userId = $_SESSION['user_id'];


        if(isset($_SESSION['supplier_id'])) {
            $supplierModel = $this->model('M_Supplier');
            $profileData = $supplierModel->getSupplierProfile($userId);
            
        } else {
            $profileData =$this->userModel->getProfile($userId);
        }
        
        
        
        if (!$profileData) {
            setFlashMessage('Cannot load the profile, try again later!');
            redirect('/');
        }
        
        $data = $profileData;
        
        $this->view('auth/v_profile', $data);
    }


    public function updateProfile()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $supplierModel = $this->model('M_Supplier');

            $data = [
                'image_path' => ''
            ];
            

            if(isset($_SESSION['supplier_id'])) { // for the supplier only
                $profileData = $supplierModel->getSupplierProfile($userId);
                $data['supplier_id'] = $profileData['supplier']->supplier_id;
                $data['profile_id'] = $profileData['profile']->profile_id;
                $data['supplier_contact'] = trim($_POST['supplier_contact']);

                
            } else {
                $profileData =$this->userModel->getProfile($userId);
                $data['profile_id'] = $profileData['profile']->profile_id;
            }
            
            
            if (!empty($_FILES['profile_image']['name'])) {
                $uploadDir = 'uploads/profile_photos/';
                
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                $fileName = uniqid() . '_' . $_FILES['profile_image']['name'];
                $uploadPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uploadPath;
                    setFlashMessage('Image uploaded successfully!');
                } else {
                    setFlashMessage('Image upload failed, try again later!', 'warning');
                    redirect('auth/profile');
                }
            }
            if (RoleHelper::hasRole(5)) {
                $supplierModel->updateSupplierProfile($data);

                $this->logModel->create(
                    $userId,
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Supplier profile updated successfully.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage('Supplier Profile Updated Successfully!');
                redirect('');
            } else {
                $this->userModel->updateProfilePhoto($data);
                $this->logModel->create(
                    $userId,
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "User profile photo updated successfully.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage('Image upload failed, try again later!', 'error');
                redirect('');
            }
        } else {
            redirect('');
        }
    }

    // public function resetPassword()
    // {
    //     $data = [
    //         'token' => '',
    //         'password' => '',
    //         'confirm_password' => '',
    //         'error' => ''
    //     ];

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $_POST = filter_input_array(INPUT_POST);
    //         $data['token'] = trim($_POST['token']);
    //         $data['password'] = trim($_POST['password']);
    //         $data['confirm_password'] = trim($_POST['confirm_password']);

    //         // Validate token and passwords
    //         if (empty($data['token']) || empty($data['password']) || empty($data['confirm_password'])) {
    //             $data['error'] = 'Please fill in all fields.';
    //         } elseif ($data['password'] !== $data['confirm_password']) {
    //             $data['error'] = 'Passwords do not match.';
    //         } elseif (strlen($data['password']) < 8) { // Minimum length check
    //             $data['error'] = 'Password must be at least 8 characters long.';
    //         } elseif (!preg_match('/[A-Za-z]/', $data['password'])) { // Check for letters
    //             $data['error'] = 'Password must contain at least one letter.';
    //         } elseif (!preg_match('/[0-9]/', $data['password'])) { // Check for numbers
    //             $data['error'] = 'Password must contain at least one number.';
    //         } elseif (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $data['password'])) { // Check for special characters
    //             $data['error'] = 'Password must contain at least one special character.';
    //         } else {
    //             // Verify the token
    //             if ($this->userModel->verifyResetToken($data['token'])) {
    //                 // Hash the new password
    //                 $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
    //                 // Update the password in the database
    //                 $this->userModel->updatePassword($data['token'], $hashedPassword);
                    
    //                 // Redirect to login page after successful password reset
    //                 header('Location: ' . URLROOT . '/auth/login');
    //                 exit();
    //             } else {
    //                 $data['error'] = 'Invalid or expired token.';
    //             }
    //         }
    //     } else {
    //         // If GET request, retrieve the token from the URL
    //         if (isset($_GET['token'])) {
    //             $data['token'] = $_GET['token'];
    //         }
    //     }

    //     $this->view('auth/v_reset_password', $data);
    // }



    // MY VERSION, removed that token part because im not sure if the smtp will work very well
    public function resetPassword()
    {
        if (!isLoggedIn()) {
            redirect('auth/login');
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $currentPassword = trim($_POST['current_password']);
            $newPassword = trim($_POST['new_password']);
            $confirmPassword = trim($_POST['confirm_password']);


            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                setFlashMessage('Please fill all the password field', 'error');

            } elseif ($newPassword !== $confirmPassword) {
                setFlashMessage('Both the passwords do not match, please try again!', 'error');

            } elseif (strlen($newPassword) < 8) {
                setFlashMessage('Password must be at least 8 letters long', 'error');
            } elseif (!preg_match('/[A-Z]/', $newPassword)) { // At least one uppercase letter
                setFlashMessage('Password must have at least 1 upper case letter', 'error');
            } elseif (!preg_match('/[a-z]/', $newPassword)) { // At least one lowercase letter
                setFlashMessage('Password must have at least 1 simple letter', 'error');
            } elseif (!preg_match('/[0-9]/', $newPassword)) { // At least one number
                setFlashMessage('Password must have at least 1 number', 'error');
             } elseif (!preg_match('/[\W_]/', $newPassword)) { 
                setFlashMessage('Password must have at least 1 special character', 'error');
            } else {

                $userId = $_SESSION['user_id'];
                $user = $this->userModel->getUserById($userId);

                if (!$user || !password_verify($currentPassword, $user->password)) {

                    setFlashMessage('Entered password is incorrect!', 'error');
                } else {

                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    if ($this->userModel->updatePasswordByUserId($userId, $hashedPassword)) {

                        setFlashMessage('Password updated successfully!');
                    } else {

                        setFlashMessage('Password update failed, please try again later!', 'error');

                    }
                }
            }


            redirect('/');
            return; 

        } else {

            setFlashMessage('Invalid access', 'error');
            redirect('/'); 
            return; 
        }
    } 

}
?>