<?php

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
                'title' => trim($_POST['title']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'nic' => trim($_POST['nic']),
                'gender' => trim($_POST['gender']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'password' => trim($_POST['password']),
                'error' => ''
            ];

            // Validate data
            if (empty($data['email']) || empty($data['title']) || 
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
                    $data['role_id'] = 7; // Website User role
                    $data['approval_status'] = 'Pending'; // Default status

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
                    
                    header('Location: ' . URLROOT . '/');
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

}
?>