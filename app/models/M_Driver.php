<?php
class M_Driver{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }

    public function getDriverDetails($user_id) {
        $this->db->query("SELECT 
            d.license_no,
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
        LEFT JOIN collection_skeletons cs ON cs.team_id = t.team_id
        LEFT JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
        LEFT JOIN user_photos pp ON pp.user_id = up.user_id
        LEFT JOIN vehicle_documents vd ON vd.vehicle_id = v.vehicle_id
        WHERE u.user_id = :user_id
        LIMIT 1;
        ");
        
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }


}