<?php

class M_User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function register($data) {   // tested
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

    public function findUserByEmail($email) {   // tested
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function login($email, $password) {  //tested
        $user = $this->findUserByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }

    public function findUserByNIC($nic) {   // tested
        $this->db->query("SELECT * FROM users WHERE nic = :nic");
        $this->db->bind(':nic', $nic);
        return $this->db->single();
    }

    public function getUserById($user_id) { // tested
        $this->db->query("SELECT
            u.user_id, 
            u.email,
            p.first_name,
            p.last_name,
            p.date_of_birth,
            p.nic,
            r.role_id,
            r.role_name
            FROM users u
            INNER JOIN profiles p ON p.user_id = u.user_id
            INNER JOIN roles r ON u.role_id = r.role_id
            WHERE u.user_id = :user_id");
        
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        
        if (!$result) {
            return false;
        }

        return $result;
        
    }

    public function getAllUnassignedUsers() {   // get the comming user, tested
        $this->db->query("
        SELECT u.user_id,u.email,u.first_name,u.last_name,u.nic,u.date_of_birth,u.role_id FROM users u WHERE u.user_id NOT IN (SELECT user_id FROM drivers) AND role_id = :role_id
        ");
        $this->db->bind(':role_id', 7);
        return $this->db->resultSet();
    }

    public function updateUserRole($user_id, $role_id) {    // tested
        $this->db->query("UPDATE users SET role_id = :role_id WHERE user_id = :user_id");
        $this->db->bind(':role_id', $role_id);
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->execute();
    }

    public function getDriverId($userId) {  // tested

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

    public function getProfile($userId) {   // tested
        
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

    public function getManagerId($userId) { // from user id, tested

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

    public function getSupplierId($userId) {    // from user_id, tested
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
        
        return null;
    }

    // For password reset functionality
    public function storeResetToken($email, $token, $expiry)// tested
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

    public function verifyResetToken($token)    // tested
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


    public function updateProfilePhoto($data) { // tested
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
    



    public function getAllUsers() { // tested
        $this->db->query("SELECT 
            u.user_id,
            u.email,
            p.first_name,
            p.last_name,
            p.nic,
            p.date_of_birth,
            u.role_id,
            r.role_name,
            u.created_at
            FROM users u 
            INNER JOIN profiles p on p.user_id = u.user_id
            INNER JOIN roles r on u.role_id = r.role_id
            ");
        
        $results = $this->db->resultSet();
        
        return $results;
    }


    public function getAllManagers() {  // tested
        $this->db->query("
            SELECT *
            FROM managers m
            JOIN profiles p ON m.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
        ");
        
        return $this->db->resultSet();
    }

    public function getUserIdBySupplierId($supplierId) {    // tested
        $this->db->query("SELECT p.user_id FROM suppliers s INNER JOIN profiles p ON s.profile_id = p.profile_id WHERE supplier_id = :supplier_id");
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single()->user_id ?? null;
    }

    public function getUserIdByDriverId($driverId) {    // tested
        $this->db->query("SELECT p.user_id FROM drivers d INNER JOIN profiles p ON d.profile_id = p.profile_id WHERE driver_id = :driver_id");
        $this->db->bind(':driver_id', $driverId);
        return $this->db->single()->user_id ?? null;
    }

    public function getUserIdByManagerId($managerId) {  // tested
        $this->db->query("SELECT p.user_id FROM managers m INNER JOIN profiles p ON m.profile_id = p.profile_id WHERE manager_id = :manager_id");
        $this->db->bind(':supplier_id', $managerId);
        return $this->db->single()->user_id ?? null;
    }

    public function getSupplierIdsByScheduleId($scheduleId) {   //tested
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


    public function getSuppliersByRouteId($routeId) {   // tested
        $this->db->query("
            SELECT rs.supplier_id
            FROM route_suppliers rs
            INNER JOIN routes r ON rs.route_id = r.route_id
            WHERE r.is_deleted = 0 AND rs.is_deleted = 0 AND rs.route_id = :route_id
        ");
        $this->db->bind(':route_id', $routeId);
        return array_column($this->db->resultSet(), 'supplier_id');
    }

    public function getUserName($userId) {  //tested
        $this->db->query("SELECT CONCAT(p.first_name, ' ', p.last_name) AS full_name FROM users u INNER JOIN profiles p ON u.user_id = p.user_id WHERE u.user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        return $this->db->single()->full_name ?? null;
    }


    


    

    public function updateUser($data) { // tested
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

    // app/models/M_User.php
    public function getFilteredUsers($email, $first_name, $last_name, $nic, $role_id) { //tested
        $query = "SELECT 
            u.user_id,
            u.email,
            p.first_name,
            p.last_name,
            p.nic,
            p.date_of_birth,
            u.role_id,
            r.role_name
            FROM users u 
            INNER JOIN profiles p ON p.user_id = u.user_id
            INNER JOIN roles r ON u.role_id = r.role_id
            WHERE 1=1"; // Start with a base query

        // Add filters based on provided parameters
        if ($email) {
            $query .= " AND u.email LIKE :email";
        }
        if ($first_name) {
            $query .= " AND p.first_name LIKE :first_name";
        }
        if ($last_name) {
            $query .= " AND p.last_name LIKE :last_name";
        }
        if ($nic) {
            $query .= " AND p.nic LIKE :nic";
        }
        if ($role_id) {
            $query .= " AND u.role_id = :role_id";
        }

        $this->db->query($query);

        // Bind parameters
        if ($email) {
            $this->db->bind(':email', '%' . $email . '%');
        }
        if ($first_name) {
            $this->db->bind(':first_name', '%' . $first_name . '%');
        }
        if ($last_name) {
            $this->db->bind(':last_name', '%' . $last_name . '%');
        }
        if ($nic) {
            $this->db->bind(':nic', '%' . $nic . '%');
        }
        if ($role_id) {
            $this->db->bind(':role_id', $role_id);
        }

        return $this->db->resultSet();
    }


    public function getAllUniqueRoles() {   // tested, for admin part
        $this->db->query("SELECT DISTINCT role_id, role_name FROM roles");
        return $this->db->resultSet();
    }

    public function registerUser($data) {   // tested
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

    public function createProfile($data)    // tested
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
    
    public function findProfileByNIC($nic) {    // tested
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
        $this->db->bind(':role_id', $roleId);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function updatePasswordByUserId($userId, $hashedPassword)
    {
        $this->db->query("UPDATE users SET password = :password WHERE user_id = :id");
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':id', $userId);
        $this->db->execute();
    }


    public function getTotalUsersCount() {
        $this->db->query("SELECT COUNT(*) as total FROM users");
        $result = $this->db->single();
        return $result->total;
    }
    
    public function getNormalUsersCount() {
        $this->db->query("SELECT COUNT(*) as total FROM users WHERE role_id = 7");
        $result = $this->db->single();
        return $result->total;
    }
    
    public function getMonthlyRegistrations() {
        $this->db->query("SELECT 
            DATE_FORMAT(created_at, '%m/%Y') as month_year, 
            COUNT(*) as count
            FROM users
            GROUP BY month_year
            ORDER BY MIN(created_at) ASC");
        
        return $this->db->resultSet();
    }
    
    public function getUserRoleDistribution() {
        $this->db->query("SELECT 
            r.role_name,
            COUNT(u.user_id) as count
            FROM users u
            INNER JOIN roles r ON u.role_id = r.role_id
            GROUP BY r.role_name");
        
        return $this->db->resultSet();
    }

    public function getDashboardStats() {
        $stats = [];

        $this->db->query("SELECT 
            COUNT(*) as total_collections,
            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'Completed' AND DATE(collection_completed_at) = CURDATE() THEN 1 ELSE 0 END) as completed_today,
            SUM(CASE WHEN DATE(start_time) = CURDATE() THEN 1 ELSE 0 END) as total_today,
            SUM(CASE WHEN status = 'In Progress' OR status = 'Awaiting Inventory Addition' THEN 1 ELSE 0 END) as total_ongoing
            FROM collections");
        $stats['collections'] = $this->db->single();
        

        return $stats;
    }


    public function getFactoryConfigurations() {
        $data = [];

        $this->db->query("SELECT * FROM factory_location ORDER BY id DESC LIMIT 1");
        $data['factory_location'] = $this->db->single();
        

        $this->db->query("SELECT * FROM deduction_configurations WHERE category = 'moisture' ORDER BY value");
        $data['moisture_deductions'] = $this->db->resultSet();
        
        $this->db->query("SELECT * FROM deduction_configurations WHERE category = 'leaf_age' ORDER BY value");
        $data['leaf_age_deductions'] = $this->db->resultSet();
        
        return $data;
    }

    public function updateFactoryLocation($id, $latitude, $longitude) {
        $this->db->query("UPDATE factory_location SET latitude = :latitude, longitude = :longitude WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':latitude', $latitude);
        $this->db->bind(':longitude', $longitude);
        
        return $this->db->execute();
    }
    
    public function updateDeduction($id, $deductionPercent) {
        $this->db->query("UPDATE deduction_configurations SET deduction_percent = :percent WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':percent', $deductionPercent);
        
        return $this->db->execute();
    }



    
}
?>