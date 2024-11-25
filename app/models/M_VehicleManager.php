<?php
// app/models/M_VehicleManager.php
class M_VehicleManager {
    private $db;

    public function __construct() {
        // Instantiate the Database class
        $this->db = new Database;
    }

    public function getDashboardStats() {
        $stats = [];

        // Get vehicle stats
        $this->db->query("SELECT 
            COUNT(*) as total_vehicles,
            SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available_vehicles
            FROM vehicles");
        $stats['vehicles'] = $this->db->single();

        // Get driver stats
        $this->db->query("SELECT 
            COUNT(*) as total_drivers,
            SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available_drivers
            FROM drivers");
        $stats['drivers'] = $this->db->single();

        // Get partner stats
        $this->db->query("SELECT 
            COUNT(*) as total_partners,
            SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available_partners
            FROM driving_partners");
        $stats['partners'] = $this->db->single();

        return $stats;
    }


    public function assignCollection($route_id, $team_id, $vehicle_id, $shift_id) {
        // Prepare your SQL statement
        $this->db->query("INSERT INTO collections (route_id, team_id, vehicle_id, shift_id, start_time, end_time, status, total_quantity) VALUES (:route_id, :team_id, :vehicle_id, :shift_id, NOW(), NOW(), 'Pending', 0)");

        // Bind parameters
        $this->db->bind(':route_id', $route_id);
        $this->db->bind(':team_id', $team_id);
        $this->db->bind(':vehicle_id', $vehicle_id);
        $this->db->bind(':shift_id', $shift_id);

        // Execute the query and return true on success, false on failure
        return $this->db->execute();
    }

    // Add these transaction methods
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    public function commit() {
        return $this->db->commit();
    }

    public function rollBack() {
        return $this->db->rollBack();
    }

    public function createUser($data) {
        try {
            // Debug log to see what data we're receiving
            error_log("Attempting to create user with data: " . print_r($data, true));
            
            // Check if role_id is set and valid
            if (!isset($data['role_id']) || empty($data['role_id'])) {
                error_log("Role ID is missing or invalid: " . ($data['role_id'] ?? 'null'));
                return false;
            }

            $this->db->query('INSERT INTO users (first_name, last_name, email, password, role_id, approval_status) 
                             VALUES (:first_name, :last_name, :email, :password, :role_id, :approval_status)');
            
            $this->db->bind(':first_name', $data['first_name']);
            $this->db->bind(':last_name', $data['last_name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':password', $data['password']);
            $this->db->bind(':role_id', $data['role_id']);
            $this->db->bind(':approval_status', $data['approval_status']);

            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            }
            
            error_log("Database execution failed for user creation");
            return false;
        } catch (Exception $e) {
            error_log("Create User Error: " . $e->getMessage());
            return false;
        }
    }

    public function createUserAddress($userId, $data) {
        $this->db->query("INSERT INTO user_addresses (user_id, address_line1, address_line2, city, postal_code, province, district) 
                VALUES (:user_id, :address_line1, :address_line2, :city, :postal_code, :province, :district)");
                
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':address_line1', $data['address_line1']);
        $this->db->bind(':address_line2', $data['address_line2']);
        $this->db->bind(':city', $data['city']);
        $this->db->bind(':postal_code', $data['postal_code']);
        $this->db->bind(':province', $data['province']);
        $this->db->bind(':district', $data['district']);

        return $this->db->execute();
    }

    public function createUserContacts($userId, $data) {
        try {
            $this->db->query('INSERT INTO user_contacts (user_id, contact_type, contact_value) VALUES (:user_id, :type, :value)');
            
            // Insert primary phone
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':type', 'primary_phone');
            $this->db->bind(':value', $data['primary_phone']);
            $this->db->execute();

            // Insert secondary phone if provided
            if (!empty($data['secondary_phone'])) {
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':type', 'secondary_phone');
                $this->db->bind(':value', $data['secondary_phone']);
                $this->db->execute();
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function createEmployee($userId, $data) {
        $this->db->query('INSERT INTO employees (user_id, nic, gender, hire_date) VALUES (:user_id, :nic, :gender, :hire_date)');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':gender', $data['gender']);
        $this->db->bind(':hire_date', $data['hire_date']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function updateEmployeePhoto($employeeId, $photoPath) {
        $this->db->query('UPDATE employees SET profile_photo = :photo_path WHERE employee_id = :employee_id');
        $this->db->bind(':photo_path', $photoPath);
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->execute();
    }

    public function createDriver($employeeId, $data) {
        $this->db->query('INSERT INTO drivers (employee_id, license_no, status) VALUES (:employee_id, :license_no, "Available")');
        
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':license_no', $data['license_no']);
        
        return $this->db->execute();
    }

    public function createPartner($employeeId, $data) {
        $this->db->query('INSERT INTO partners (employee_id, status) VALUES (:employee_id, :status)');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':status', 'Active');
        
        return $this->db->execute();
    }

    public function createManager($employeeId, $data) {
        $this->db->query('INSERT INTO managers (employee_id, manager_type) VALUES (:employee_id, :manager_type)');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':manager_type', $data['manager_type']);
        
        return $this->db->execute();
    }
}
