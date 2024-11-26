<?php

class M_Partner {

    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }
    public function softDeletePartner($partnerId) {
        $this->db->query('UPDATE driving_partners SET is_deleted = 1 WHERE partner_id = :partner_id');
        $this->db->bind(':partner_id', $partnerId);
        return $this->db->execute();
    }

    public function getPartnerDetails($user_id) {
        $this->db->query("
        SELECT 
            dp.*,
            e.hire_date as registration_date,
            s.shift_name as current_shift,
            t.team_name as current_team,
            t.team_id as team_id,
            v.vehicle_id as vehicle_id,
            v.vehicle_type as vehicle_type,
            v.status as vehicle_status,
            s.shift_id as shift_id,
            d.driver_id as driver_id,
            ud.first_name as driver_name,
            ed.contact_number as driver_phone,
            pd.file_name as driver_photo,
            vd.file_path as vehicle_image,
            pd.file_type as driver_image_type
    
        FROM users u
        JOIN employees e ON u.user_id = e.user_id
        JOIN driving_partners dp ON e.employee_id = dp.employee_id
        LEFT JOIN shifts s ON e.shift_id = s.shift_id
        LEFT JOIN teams t ON t.partner_id = dp.partner_id
        LEFT JOIN drivers d ON d.driver_id = t.driver_id
        LEFT JOIN employees ed ON ed.employee_id = d.employee_id
        LEFT JOIN users ud ON ud.user_id = ed.user_id
        LEFT JOIN collection_schedules cs ON cs.team_id = t.team_id
        LEFT JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
        LEFT JOIN user_photos pd ON pd.user_id = ud.user_id
        LEFT JOIN vehicle_documents vd ON vd.vehicle_id = v.vehicle_id
        WHERE u.user_id = :user_id
        AND cs.is_active = 1 
        AND cs.is_deleted = 0
        LIMIT 1;
        ");
        
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
} 