<?php
class M_Driver{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }

    public function getDriverDetails($user_id) {
        $this->db->query("SELECT 
            d.*,
            e.hire_date as registration_date,
            s.shift_name as current_shift,
            t.team_name as current_team,
            t.team_id as team_id,
            v.vehicle_id as vehicle_id,
            v.vehicle_type as vehicle_type,
            v.status as vehicle_status,
            s.shift_id as shift_id,
            dp.partner_id as partner_id,
            up.first_name as partner_name,
            ep.contact_number as partner_phone,
            pp.file_name as partner_photo,
            vd.file_path as vehicle_image,
            pp.file_type as partner_image_type

        FROM users u
        JOIN employees e ON u.user_id = e.user_id
        JOIN drivers d ON e.employee_id = d.employee_id
        LEFT JOIN shifts s ON e.shift_id = s.shift_id
        LEFT JOIN teams t ON t.driver_id = d.driver_id
        LEFT JOIN driving_partners dp ON dp.partner_id = t.partner_id
        LEFT JOIN employees ep ON ep.employee_id = dp.employee_id
        LEFT JOIN users up ON up.user_id = ep.user_id
        LEFT JOIN collection_schedules cs ON cs.team_id = t.team_id
        LEFT JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
        LEFT JOIN user_photos pp ON pp.user_id = up.user_id
        LEFT JOIN vehicle_documents vd ON vd.vehicle_id = v.vehicle_id
        WHERE u.user_id = :user_id
        AND cs.is_active = 1 
        AND cs.is_deleted = 0
        LIMIT 1;
        ");
        
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
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

}