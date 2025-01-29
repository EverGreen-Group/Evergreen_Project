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
            email,
            first_name,
            last_name,
            nic,
            gender,
            date_of_birth,
            role_id,
            approval_status,
            created_at
            FROM users 
            WHERE user_id = :user_id");
        
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        
        // Return false if no user found
        if (!$result) {
            return false;
        }
        
        // Convert to array for easier handling in views
        return [
            'email' => $result->email,
            'first_name' => $result->first_name,
            'last_name' => $result->last_name,
            'nic' => $result->nic,
            'gender' => $result->gender,
            'date_of_birth' => $result->date_of_birth,
            'role_id' => $result->role_id,
            'approval_status' => $result->approval_status,
            'created_at' => $result->created_at
        ];
    }

    public function getAllUnassignedUsers() {
        $this->db->query("SELECT * FROM users WHERE user_id NOT IN (SELECT user_id FROM drivers) AND role_id = :role_id");
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
        $this->db->query("SELECT * FROM users WHERE user_id IN (SELECT user_id FROM drivers) AND role_id = :role_id");
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
}
?>