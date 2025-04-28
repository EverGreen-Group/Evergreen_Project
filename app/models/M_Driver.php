<?php
class M_Driver{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }


    public function getTotalDrivers() {
        $this->db->query('SELECT COUNT(*) as count FROM drivers WHERE is_deleted = 0');
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getDriversOnDuty() {
        $this->db->query('SELECT COUNT(*) as count FROM drivers WHERE status = "Active" AND is_deleted = 0');
        $result = $this->db->single();
        return $result->count;
    }
    
    public function getUnassignedDriversCount() {
        $this->db->query('
            SELECT COUNT(*) as total 
            FROM drivers d
            LEFT JOIN collection_schedules cs ON d.driver_id = cs.driver_id
            WHERE cs.driver_id IS NULL
        ');
        return $this->db->single()->total; 
    }

    public function softDeleteDriver($driverId) {
        $this->db->query('UPDATE drivers SET is_deleted = 1 WHERE driver_id = :driver_id');
        $this->db->bind(':driver_id', $driverId);
        return $this->db->execute();
    }

    public function getUnassignedDrivers() {
        $this->db->query("
            SELECT drivers.*, users.first_name
            FROM drivers
            INNER JOIN users ON drivers.user_id = users.user_id
            LEFT JOIN collection_schedules ON drivers.driver_id = collection_schedules.driver_id AND collection_schedules.is_deleted = 0
            WHERE drivers.is_deleted = 0 AND collection_schedules.driver_id IS NULL
        "); 

        return $this->db->resultSet(); 
    }

    public function addDriver($data) {
        $this->db->query("INSERT INTO drivers (employee_id, user_id, status, is_deleted) 
                          VALUES (:employee_id, :user_id, :status, :is_deleted)");
        
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':is_deleted', $data['is_deleted']);
        
        return $this->db->execute(); 
    }


    public function getAllDrivers() {   
        $this->db->query("
            SELECT d.*, p.first_name, p.last_name, p.nic, p.date_of_birth, p.contact_number, u.email, CONCAT(p.first_name, ' ', p.last_name) AS full_name
            FROM drivers d
            JOIN profiles p ON d.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE d.is_deleted = 0
            AND d.Status = 'Active'
            ORDER BY d.driver_id DESC
        ");
        
        return $this->db->resultSet();
    }

    public function getUnassignedDriversList() {
        $this->db->query('
            SELECT 
                d.*,
                e.contact_number,
                CONCAT(u.first_name, " ", u.last_name) AS driver_name,
                u.user_id
            FROM drivers d
            JOIN users u ON d.user_id = u.user_id
            JOIN employees e ON d.employee_id = e.employee_id
            LEFT JOIN collection_schedules cs ON d.driver_id = cs.driver_id
            WHERE cs.driver_id IS NULL  -- This ensures we only get drivers not in any collection schedule
            ORDER BY d.driver_id ASC
            LIMIT 0, 25
        ');
    
        return $this->db->resultSet();
    }

    public function getAllUserDrivers() {
        $this->db->query("SELECT * FROM users WHERE user_id IN (SELECT user_id FROM drivers) AND role_id = :role_id");
        $this->db->bind(':role_id', 6);
        return $this->db->resultSet();
    }

    public function getDriverAndEmployeeDetails($user_id) {
        $this->db->query('
            SELECT 
                d.driver_id,
                d.status AS driver_status,
                e.contact_number,
                e.emergency_contact,
                e.address_line1,
                e.address_line2,
                e.city,
                u.first_name,
                u.last_name,
                u.email,
                u.nic,
                u.date_of_birth,
                u.gender,
                u.user_id
            FROM drivers d
            JOIN employees e ON d.employee_id = e.employee_id
            JOIN users u ON e.user_id = u.user_id
            WHERE u.user_id = :user_id AND d.is_deleted = 0
        ');

        $this->db->bind(':user_id', $user_id);
        return $this->db->single(); // Return a single record
    }




    public function getDriverDetails($driverId) {   // tested
        $this->db->query('
            SELECT d.*,p.*
            FROM drivers d
            JOIN profiles p on d.profile_id = p.profile_id
            JOIN users u ON
            p.user_id = u.user_id
            WHERE d.driver_id = :driver_id
        ');

        $this->db->bind(':driver_id', $driverId);
        return $this->db->single();
        
    }


    public function updateDriverProfile($driverId, $data) {
        $this->db->query('SELECT profile_id FROM drivers WHERE driver_id = :driver_id');
        $this->db->bind(':driver_id', $driverId);
        $driver = $this->db->single();
    
        if (!$driver) {
            throw new Exception('Driver not found.');
        }
    
        $profileId = $driver->profile_id;
    
        $this->db->query('UPDATE profiles SET 
            first_name = :first_name, 
            last_name = :last_name, 
            nic = :nic, 
            date_of_birth = :date_of_birth, 
            contact_number = :contact_number, 
            image_path = :image_path,
            updated_at = CURRENT_TIMESTAMP()  -- updating the timestamp
            WHERE profile_id = :profile_id');
    
        // Bind the parameters
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':date_of_birth', $data['date_of_birth']);
        $this->db->bind(':contact_number', $data['contact_number']);
        $this->db->bind(':image_path', $data['image_path']);
        $this->db->bind(':profile_id', $profileId);
    
        // Execute the query and return the result
        return $this->db->execute();
    }

    public function updateDriverInfo($driverId, $data) {
        $this->db->query('UPDATE drivers SET 
            license_number = :license_number, 
            hire_date = :hire_date, 
            status = :status 
            WHERE driver_id = :driver_id');
    
        // Bind the parameters
        $this->db->bind(':license_number', $data['license_number']);
        $this->db->bind(':hire_date', $data['hire_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':driver_id', $driverId);
    
        // Execute the query and return the result
        return $this->db->execute();
    }


    public function getFilteredDrivers($driver_id = null, $name = null, $nic = null, $contact_number = null, $license_number = null, $driver_status = null) {
        // Start the base query
        $sql = "SELECT d.*, p.first_name, p.last_name, p.nic, p.date_of_birth, p.contact_number, u.email
                FROM drivers d
                JOIN profiles p ON d.profile_id = p.profile_id
                JOIN users u ON p.user_id = u.user_id
                WHERE d.is_deleted = 0";

        if (!empty($driver_id)) {
            $sql .= " AND d.driver_id = :driver_id";
        }
        if (!empty($name)) {
            $sql .= " AND (p.first_name LIKE :name OR p.last_name LIKE :name)";
        }
        if (!empty($nic)) {
            $sql .= " AND p.nic LIKE :nic";
        }
        if (!empty($contact_number)) {
            $sql .= " AND p.contact_number LIKE :contact_number";
        }
        if (!empty($license_number)) {
            $sql .= " AND d.license_number LIKE :license_number";
        }
        if (!empty($driver_status)) {
            $sql .= " AND d.status = :driver_status";
        }

        // Finalize the query
        $sql .= " ORDER BY d.driver_id DESC";

        // Prepare the statement
        $this->db->query($sql);

        // Bind parameters
        if (!empty($driver_id)) {
            $this->db->bind(':driver_id', $driver_id);
        }
        if (!empty($name)) {
            $this->db->bind(':name', '%' . $name . '%');
        }
        if (!empty($nic)) {
            $this->db->bind(':nic', '%' . $nic . '%');
        }
        if (!empty($contact_number)) {
            $this->db->bind(':contact_number', '%' . $contact_number . '%');
        }
        if (!empty($license_number)) {
            $this->db->bind(':license_number', '%' . $license_number . '%');
        }
        if (!empty($driver_status)) {
            $this->db->bind(':driver_status', $driver_status);
        }

        return $this->db->resultSet();
    }

    public function getUnallocatedDriversByDayAndShift($day, $shift_id) {
        $this->db->query("
            SELECT d.*, u.first_name, CONCAT(u.first_name, ' ', u.last_name) as full_name
            FROM drivers d
            JOIN users u ON u.user_id = d.user_id
            WHERE d.is_deleted = 0
            AND d.driver_id NOT IN (
                SELECT cs.driver_id
                FROM collection_schedules cs
                WHERE cs.day = :day
                AND cs.shift_id = :shift_id
                AND cs.is_deleted = 0
                AND cs.is_active = 1
            )
        ");
        
        $this->db->bind(':day', $day);
        $this->db->bind(':shift_id', $shift_id);
        return $this->db->resultSet();
    }

    public function getDriverById($driver_id) {
        $this->db->query('
            SELECT d.*, p.*,u.email
            FROM drivers d
            JOIN profiles p ON d.profile_id = p.profile_id
            JOIN users u ON p.user_id = u.user_id
            WHERE d.driver_id = :driver_id AND d.is_deleted = 0
        ');
        
        $this->db->bind(':driver_id', $driver_id);
        return $this->db->single();
    }

    public function getDriverCollectionHistory($driver_id) {
        $this->db->query("SELECT 
                          c.collection_id, 
                          c.schedule_id, 
                          c.status, 
                          c.start_time, 
                          c.end_time, 
                          c.total_quantity, 
                          c.bags, 
                          c.collection_completed_at 
                      FROM collections c 
                      JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id 
                      WHERE cs.driver_id = :driver_id");
        $this->db->bind(':driver_id', $driver_id);
        return $this->db->resultSet();
    }

    public function getUpcomingSchedulesForDriver($driver_id) {
        $this->db->query("
        SELECT cs.*, r.vehicle_id FROM collection_schedules cs
        JOIN routes r on cs.route_id = r.route_id
        WHERE driver_id = :driver_id 
        AND is_active = 1 
        AND cs.is_deleted = 0 
        AND r.is_deleted = 0

                          "); 
                          //        AND day >= DAYNAME(CURDATE()) must test this again
        $this->db->bind(':driver_id', $driver_id);
        return $this->db->resultSet();
    }

    public function createDriver($data) {
        $this->db->query('INSERT INTO drivers (profile_id,license_number, hire_date) 
                          VALUES (:profile_id, :license_number, :hire_date)');
        
        $this->db->bind(':profile_id', $data['profile_id']);
        $this->db->bind(':license_number', $data['license_number']);
        $this->db->bind(':hire_date', $data['hire_date']);
        
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    public function findDriverByLicenseNumber($license_number) {
        $this->db->query('SELECT * FROM drivers WHERE license_number = :license_number AND is_deleted = 0');
        $this->db->bind(':license_number', $license_number);
        
        $row = $this->db->single();
        
        if($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    public function updateDriver($data) {
        // Start with the basic fields to update
        $sql = 'UPDATE drivers SET status = :status, license_expiry_date = :license_expiry_date';
        
        // Add image_path to the query if it exists in the data
        if (isset($data['image_path'])) {
            $sql .= ', image_path = :image_path';
        }
        
        // Complete the query with WHERE clause
        $sql .= ' WHERE driver_id = :driver_id';
        
        $this->db->query($sql);
        
        // Bind the parameters
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':license_expiry_date', $data['license_expiry_date']);
        $this->db->bind(':driver_id', $data['driver_id']);
        
        // Bind image_path if it exists
        if (isset($data['image_path'])) {
            $this->db->bind(':image_path', $data['image_path']);
        }
        
        return $this->db->execute();
    }

    public function markDriverAsDeleted($id) {
        $this->db->query('UPDATE drivers SET is_deleted = 1 WHERE driver_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

}