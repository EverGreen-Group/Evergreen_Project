<?php
class M_Driver{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }


    public function getTotalDrivers() {
        $this->db->query("SELECT COUNT(*) as total FROM drivers");
        return $this->db->single()->total; 
    }
    
    public function getDriversOnDuty() {
        $this->db->query("SELECT COUNT(*) as total FROM drivers WHERE status = 'On Route'");
        return $this->db->single()->total; 
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
            LEFT JOIN collection_schedules ON drivers.driver_id = collection_schedules.driver_id
            WHERE drivers.is_deleted = 0 AND collection_schedules.driver_id IS NULL
        "); 

        return $this->db->resultSet(); 
    }

    public function addDriver($data) {
        $this->db->query("INSERT INTO drivers (employee_id, user_id, status, is_deleted) 
                          VALUES (:employee_id, :user_id, :status, :is_deleted)");
        
        // Bind values
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':is_deleted', $data['is_deleted']);
        
        return $this->db->execute(); // Execute the query and return the result
    }


    public function getAllDrivers() {
        $this->db->query('
            SELECT 
                d.driver_id,
                CONCAT(ud.first_name, " ", ud.last_name) AS driver_name,
                e.contact_number,
                d.status,
                e.user_id
            FROM drivers d
            JOIN employees e ON d.employee_id = e.employee_id
            JOIN users ud ON e.user_id = ud.user_id
            WHERE d.is_deleted = 0
            ORDER BY d.driver_id ASC
        ');

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

}