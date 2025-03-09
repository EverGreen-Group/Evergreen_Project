<?php

class M_User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {
        $sql = "INSERT INTO users (email, first_name, last_name, nic, date_of_birth, password, role_id, approval_status) 
                VALUES (:email, :first_name, :last_name, :nic, :date_of_birth, :password, :role_id, :approval_status)";
        
        $this->db->query($sql);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':approval_status', $data['approval_status']);
    
        return $this->db->execute();
    }

    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        return $this->db->single();
    }

    public function login($email, $password) {
        $user = $this->findUserByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function findUserByNIC($nic) {
        $this->db->query("SELECT * FROM users WHERE nic = :nic");
        $this->db->bind(':nic', $nic);
        return $this->db->single();
    }

    public function getUserById($user_id) {
        $this->db->query("SELECT
            user_id, 
            email,
            first_name,
            last_name,
            nic,
            date_of_birth,
            u.role_id,
            r.role_name,
            approval_status,
            created_at,
            nic
            FROM users u
            JOIN roles r ON r.role_id = u.role_id  
            WHERE user_id = :user_id");
        
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        
        // Return false if no user found
        if (!$result) {
            return false;
        }

        return $result;
        
    }

    public function getAllUnassignedUsers() {
        $this->db->query("
        SELECT u.user_id,u.email,u.first_name,u.last_name,u.nic,u.date_of_birth,u.role_id FROM users u WHERE u.user_id NOT IN (SELECT user_id FROM drivers) AND role_id = :role_id
        ");
        $this->db->bind(':role_id', 7);
        return $this->db->resultSet();
    }

    public function updateUserRole($user_id, $role_id) {
        $this->db->query("UPDATE users SET role_id = :role_id WHERE user_id = :user_id");
        $this->db->bind(':role_id', $role_id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }

    public function getAllUserDrivers() {
        $this->db->query("SELECT u.user_id,u.email,u.first_name,u.last_name,u.nic,u.date_of_birth,u._role_id FROM users u WHERE u.user_id IN (SELECT user_id FROM drivers) AND role_id = :role_id");
        $this->db->bind(':role_id', 6);
        return $this->db->resultSet();
    }

    public function getDriverId($userId) {
        $this->db->query("SELECT driver_id FROM drivers WHERE user_id = :user_id AND is_deleted = 0");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function getEmployeeId($userId) {
        $this->db->query("SELECT employee_id FROM employees WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function getManagerId($userId) {
        $this->db->query("SELECT manager_id FROM managers WHERE employee_id = (SELECT employee_id FROM employees WHERE user_id = :user_id)");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function getSupplierId($userId) {
        $this->db->query("SELECT supplier_id FROM suppliers WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function storeVerificationCode($email, $code)
    {
        $this->db->query("UPDATE users SET verification_code = :code WHERE email = :email");
        $this->db->bind(':code', $code);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function verifyEmail($code)
    {
        $this->db->query("SELECT * FROM users WHERE verification_code = :code");
        $this->db->bind(':code', $code);
        $this->db->execute();
        if ($this->db->rowCount() > 0) {
            // Update user to set verified status
            $this->db->query("UPDATE users SET verified = 1 WHERE verification_code = :code");
            $this->db->bind(':code', $code);
            $this->db->execute();
            return true;
        }
        return false;
    }

    public function storeResetToken($email, $token)
    {
        $this->db->query("UPDATE users SET reset_token = :token WHERE email = :email");
        $this->db->bind(':token', $token);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function verifyResetToken($token)
    {
        $this->db->query("SELECT * FROM users WHERE reset_token = :token");
        $this->db->bind(':token', $token);
        $this->db->execute();
        return $this->db->rowCount() > 0; // Returns true if token exists
    }

    public function updatePassword($token, $newPassword)
    {
        $this->db->query("UPDATE users SET password = :password, reset_token = NULL WHERE reset_token = :token");
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':token', $token);
        return $this->db->execute();
    }

    public function getAllUsers() {
        $this->db->query("SELECT 
            user_id,
            email,
            first_name,
            last_name,
            nic,
            date_of_birth,
            role_id,
            approval_status,
            created_at
            FROM users");
        
        // Execute the query and return the result set
        $results = $this->db->resultSet();
        
        return $results;
    }

    public function updateUser($data) {
        $this->db->query("UPDATE users SET 
            email = :email,
            first_name = :first_name,
            last_name = :last_name,
            nic = :nic,
            date_of_birth = :date_of_birth,
            role_id = :role_id
            WHERE user_id = :user_id");

        // Bind parameters
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':role_id', $data['role']);
        $this->db->bind(':user_id', $data['user_id']);

        // Execute the query
        return $this->db->execute();
    }

    public function getFilteredUsers($email = null, $first_name = null, $last_name = null, $role_id = null) {
        $sql = "SELECT 
                    user_id,
                    email,
                    first_name,
                    last_name,
                    nic,
                    date_of_birth,
                    role_id,
                    approval_status,
                    created_at
                FROM users WHERE 1=1"; // Start with a base query

        // Build the query based on provided filters
        if (!empty($email)) {
            $sql .= " AND email LIKE :email";
        }
        if (!empty($first_name)) {
            $sql .= " AND first_name LIKE :first_name";
        }
        if (!empty($last_name)) {
            $sql .= " AND last_name LIKE :last_name";
        }
        if (!empty($role_id)) {
            $sql .= " AND role_id = :role_id";
        }

        // Prepare the statement
        $this->db->query($sql);

        // Bind parameters if they were set
        if (!empty($email)) {
            $this->db->bind(':email', '%' . $email . '%'); // Use LIKE for partial matches
        }
        if (!empty($first_name)) {
            $this->db->bind(':first_name', '%' . $first_name . '%'); // Use LIKE for partial matches
        }
        if (!empty($last_name)) {
            $this->db->bind(':last_name', '%' . $last_name . '%'); // Use LIKE for partial matches
        }
        if (!empty($role_id)) {
            $this->db->bind(':role_id', $role_id);
        }

        // Execute the query and return the results
        return $this->db->resultSet();
    }
}
?>