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
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
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
            password
            FROM users u
            WHERE user_id = :user_id");
        
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        
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

        $this->db->query("SELECT profile_id FROM profiles WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $profile = $this->db->single();
        

        if ($profile) {
            $this->db->query("SELECT driver_id FROM drivers WHERE profile_id = :profile_id AND is_deleted = 0");
            $this->db->bind(':profile_id', $profile->profile_id);
            return $this->db->single();
        }
        
        return null; 
    }

    public function getProfile($userId) {
        
        $this->db->query("SELECT * FROM profiles WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $profileData = $this->db->single();
        
        $this->db->query("SELECT email FROM users WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $user = $this->db->single();
        
        
        return [
            'profile' => $profileData,
            'user' => $user
        ];
    }

    public function getManagerId($userId) {

        $this->db->query("SELECT profile_id FROM profiles WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $profile = $this->db->single();
        
        if ($profile) {
            $this->db->query("SELECT manager_id FROM managers WHERE profile_id = :profile_id");
            $this->db->bind(':profile_id', $profile->profile_id);
            return $this->db->single();
        }
        
        return null; 
    }

    public function getSupplierId($userId) {
        // First, get the profile_id associated with the user_id
        $this->db->query("SELECT profile_id FROM profiles WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $profile = $this->db->single();
        
        // If a profile is found, get the supplier_id using the profile_id
        if ($profile) {
            $this->db->query("SELECT supplier_id FROM suppliers WHERE profile_id = :profile_id");
            $this->db->bind(':profile_id', $profile->profile_id);
            return $this->db->single();
        }
        
        return null; // Return null if no profile is found
    }

    // For password reset functionality
    public function storeResetToken($email, $token, $expiry)
    {
        // First, get the user ID
        $this->db->query('SELECT user_id FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        $user = $this->db->single();
        
        if (!$user) {
            return false;
        }
        
        // Check if a token already exists for this user
        $this->db->query('SELECT * FROM password_reset_tokens WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user->user_id);
        
        if ($this->db->rowCount() > 0) {
            // Update existing token
            $this->db->query('UPDATE password_reset_tokens SET 
                            token = :token, 
                            expiry = :expiry,
                            created_at = NOW()
                            WHERE user_id = :user_id');
        } else {
            // Insert new token
            $this->db->query('INSERT INTO password_reset_tokens (user_id, token, expiry) 
                            VALUES (:user_id, :token, :expiry)');
        }
        
        $this->db->bind(':user_id', $user->user_id);
        $this->db->bind(':token', $token);
        $this->db->bind(':expiry', $expiry);
        
        return $this->db->execute();
    }

    public function verifyResetToken($token)
    {
        $this->db->query('SELECT * FROM password_reset_tokens 
                          WHERE token = :token AND expiry > NOW() AND is_used = 0');
        $this->db->bind(':token', $token);
        
        $token = $this->db->single();
        
        return ($this->db->rowCount() > 0) ? $token : false;
    }

    public function updatePasswordByToken($token, $newPassword)
    {
        // Get user ID from token
        $this->db->query('SELECT user_id FROM password_reset_tokens WHERE token = :token');
        $this->db->bind(':token', $token);
        $result = $this->db->single();
        
        if (!$result) {
            return false;
        }
        
        // Update the password
        $this->db->query('UPDATE users SET password = :password WHERE user_id = :user_id');
        $this->db->bind(':password', $newPassword);
        $this->db->bind(':user_id', $result->user_id);
        
        return $this->db->execute();
    }
    
    public function invalidateToken($token)
    {
        $this->db->query('UPDATE password_reset_tokens SET is_used = 1 WHERE token = :token');
        $this->db->bind(':token', $token);
        
        return $this->db->execute();
    }


    public function updateProfilePhoto($data) {
        $this->db->beginTransaction();
        
        try {
            
            if (isset($data['image_path']) && !empty($data['image_path'])) {
                $this->db->query("UPDATE profiles SET image_path = :image_path WHERE profile_id = :profile_id");
                $this->db->bind(':image_path', $data['image_path']);
                $this->db->bind(':profile_id', $data['profile_id']);
                $this->db->execute();
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
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
        
        $results = $this->db->resultSet();
        
        return $results;
    }


    public function getAllManagers() {
        $this->db->query("
            SELECT *
            FROM managers m
            JOIN profiles p ON m.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
        ");
        
        return $this->db->resultSet();
    }

    public function getUserIdBySupplierId($supplierId) {
        $this->db->query("SELECT p.user_id FROM suppliers s INNER JOIN profiles p ON s.profile_id = p.profile_id WHERE supplier_id = :supplier_id");
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single()->user_id ?? null;
    }

    public function getUserIdByDriverId($driverId) {
        $this->db->query("SELECT p.user_id FROM drivers d INNER JOIN profiles p ON d.profile_id = p.profile_id WHERE driver_id = :driver_id");
        $this->db->bind(':supplier_id', $driverId);
        return $this->db->single()->user_id ?? null;
    }

    public function getUserIdByManagerId($managerId) {
        $this->db->query("SELECT p.user_id FROM managers m INNER JOIN profiles p ON m.profile_id = p.profile_id WHERE manager_id = :manager_id");
        $this->db->bind(':supplier_id', $managerId);
        return $this->db->single()->user_id ?? null;
    }

    public function getSupplierIdsByScheduleId($scheduleId) {
        $this->db->query("
            SELECT rs.supplier_id
            FROM collection_schedules cs
            INNER JOIN routes r ON cs.route_id = r.route_id
            INNER JOIN route_suppliers rs ON rs.route_id = r.route_id
            WHERE cs.schedule_id = :schedule_id
        ");
        $this->db->bind(':schedule_id', $scheduleId);
        return array_column($this->db->resultSet(), 'supplier_id');
    }


    public function getSuppliersByRouteId($routeId) {
        $this->db->query("
            SELECT rs.supplier_id
            FROM route_suppliers rs
            INNER JOIN routes r ON rs.route_id = r.route_id
            WHERE r.is_deleted = 0 AND rs.is_deleted = 0 AND rs.route_id = :route_id
        ");
        $this->db->bind(':route_id', $routeId);
        return array_column($this->db->resultSet(), 'supplier_id');
    }

    public function getUserName($userId) {
        $this->db->query("SELECT CONCAT(p.first_name, ' ', p.last_name) AS full_name FROM users u INNER JOIN profiles p ON u.user_id = p.user_id WHERE u.user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->single()->full_name ?? null;
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
            $this->db->bind(':email', '%' . $email . '%'); 
        }
        if (!empty($first_name)) {
            $this->db->bind(':first_name', '%' . $first_name . '%'); 
        }
        if (!empty($last_name)) {
            $this->db->bind(':last_name', '%' . $last_name . '%'); 
        }
        if (!empty($role_id)) {
            $this->db->bind(':role_id', $role_id);
        }

        return $this->db->resultSet();
    }

    public function registerUser($data) {
        $this->db->query('INSERT INTO users (email, password, role_id, account_status) VALUES (:email, :password, :role_id, :account_status)');
        
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':account_status', $data['account_status']);
        
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function createProfile($data)
    {
        $this->db->query('INSERT INTO profiles (user_id, first_name, last_name, nic, date_of_birth, contact_number) 
                          VALUES (:user_id, :first_name, :last_name, :nic, :date_of_birth, :contact_number)');
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':contact_number', $data['contact_number']);
        
        // Execute
        return $this->db->execute();
    }
    
    public function findProfileByNIC($nic) {
        $this->db->query('SELECT * FROM profiles WHERE nic = :nic');
        $this->db->bind(':nic', $nic);
        
        $row = $this->db->single();
        
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function getProfileById($id) {
        $this->db->query('SELECT * FROM profiles WHERE profile_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    public function getProfileByUserId($user_id) {
        $this->db->query('SELECT * FROM profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->single();
    }

    public function findProfileByContactNumber($contactNumber)
    {
        $this->db->query('SELECT * FROM profiles WHERE contact_number = :contact_number');
        $this->db->bind(':contact_number', $contactNumber);
        
        $row = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function updateProfile($data) {
        $this->db->query('UPDATE profiles SET 
                          first_name = :first_name, 
                          last_name = :last_name,
                          date_of_birth = :date_of_birth,
                          contact_number = :contact_number
                          WHERE profile_id = :profile_id');
        
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':profile_id', $data['profile_id']);
        
        return $this->db->execute();
    }

    public function updateRole($userId, $roleId) {
        $this->db->query("
            UPDATE users SET role_id = :role_id WHERE user_id = :user_id
        ");
        $this->db->bind(':role_id', $userId);
        $this->db->bind(':user_id', $roleId);

        return $this->db->execute();
    }

    public function updatePasswordByUserId($userId, $hashedPassword)
    {
        $this->db->query("UPDATE users SET password = :password WHERE user_id = :id");
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $userId);
        $this->db->execute();
    }
    
}
?>