<?php
// app/models/M_Team.php
class M_Team {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllTeams() {
        $this->db->query("SELECT * FROM teams");
        return $this->db->resultset();
    }

    public function getTeamStatistics() {
        // Get total teams count
        $this->db->query('SELECT COUNT(*) as total FROM teams');
        $totalTeams = $this->db->single()->total;

        // Get on duty (active) teams count
        $this->db->query('SELECT COUNT(*) as active FROM teams WHERE status = "Active"');
        $activeTeams = $this->db->single()->active;

        // Get unassigned (inactive) teams count
        $this->db->query('SELECT COUNT(*) as inactive FROM teams WHERE status = "Inactive"');
        $inactiveTeams = $this->db->single()->inactive;

        return [
            'total' => $totalTeams,
            'active' => $activeTeams,
            'inactive' => $inactiveTeams
        ];
    }

    public function getTeamsWithMembers() {
        $this->db->query('
            SELECT 
                t.team_id, 
                t.team_name,
                t.driver_id,
                t.partner_id,
                t.status,
                CONCAT(ud.first_name, " ", ud.last_name) AS driver_full_name,
                CONCAT(up.first_name, " ", up.last_name) AS partner_full_name,
                d1.file_path AS driver_image_url,  -- Driver image URL
                d2.file_path AS partner_image_url,  -- Partner image URL
                COUNT(c.collection_id) AS number_of_collections,  -- Count of collections
                COALESCE(SUM(c.total_quantity), 0) AS total_quantity_collected  -- Sum of total quantity from collections
            FROM teams t
            LEFT JOIN drivers d ON t.driver_id = d.driver_id
            LEFT JOIN employees ed ON d.employee_id = ed.employee_id  -- Get employee details for driver
            LEFT JOIN users ud ON ed.user_id = ud.user_id  -- Get user details for driver
            LEFT JOIN driving_partners p ON t.partner_id = p.partner_id
            LEFT JOIN employees ep ON p.employee_id = ep.employee_id  -- Get employee details for partner
            LEFT JOIN users up ON ep.user_id = up.user_id  -- Get user details for partner
            LEFT JOIN documents d1 ON ed.user_id = d1.user_id AND d1.document_type = "Photo"  -- Get driver image
            LEFT JOIN documents d2 ON ep.user_id = d2.user_id AND d2.document_type = "Photo"  -- Get partner image
            LEFT JOIN collections c ON t.team_id = c.team_id  -- Join collections to count and sum total quantity
            GROUP BY t.team_id  -- Group by team ID to aggregate results
            ORDER BY t.team_id DESC;
        ');

        return $this->db->resultSet();
    }

    public function getAllTeamsWithImages() {
        $this->db->query('
            SELECT 
                t.team_id, 
                t.team_name,
                t.driver_id,
                t.partner_id,
                t.status,
                CONCAT(ud.first_name, " ", ud.last_name) as driver_first_name,
                CONCAT(ud.last_name) as driver_last_name,
                CONCAT(up.first_name, " ", up.last_name) as partner_first_name,
                CONCAT(up.last_name) as partner_last_name,
                ud.image_url as driver_image_url,  -- Assuming you have an image_url field in users table
                up.image_url as partner_image_url    -- Assuming you have an image_url field in users table
            FROM teams t
            LEFT JOIN drivers d ON t.driver_id = d.driver_id
            LEFT JOIN users ud ON d.employee_id = ud.user_id
            LEFT JOIN driving_partners p ON t.partner_id = p.partner_id
            LEFT JOIN users up ON p.employee_id = up.user_id
            ORDER BY t.team_id DESC
        ');

        return $this->db->resultSet();
    }

    public function getUnassignedDrivers() {
        $this->db->query('
            SELECT 
                d.driver_id,
                CONCAT(ud.first_name, " ", ud.last_name) AS driver_name,
                doc.file_path AS driver_image_url
            FROM drivers d
            LEFT JOIN teams t ON d.driver_id = t.driver_id
            JOIN employees e ON d.employee_id = e.employee_id
            JOIN users ud ON e.user_id = ud.user_id
            LEFT JOIN documents doc ON e.user_id = doc.user_id AND doc.document_type = "Photo"
            WHERE t.driver_id IS NULL  -- This ensures we only get drivers not in any team
            ORDER BY d.driver_id ASC
            LIMIT 0, 25
        ');

        return $this->db->resultSet();
    }

    public function getUnassignedPartner() {
        $this->db->query('
            SELECT 
                dp.partner_id,
                CONCAT(ud.first_name, " ", ud.last_name) AS partner_name,
                doc.file_path AS partner_image_url
            FROM driving_partners dp
            LEFT JOIN teams t ON dp.partner_id = t.partner_id
            JOIN employees e ON dp.employee_id = e.employee_id
            JOIN users ud ON e.user_id = ud.user_id
            LEFT JOIN documents doc ON e.user_id = doc.user_id AND doc.document_type = "Photo"
            WHERE t.partner_id IS NULL  -- This ensures we only get partners not in any team
            ORDER BY dp.partner_id ASC
            LIMIT 0, 25
        ');
    
        return $this->db->resultSet();
    }

    public function getTotalTeamsInCollection() {
        $this->db->query('
            SELECT COUNT(*) AS total_teams 
            FROM teams
            LEFT JOIN collection_skeletons c ON teams.team_id = c.team_id
            WHERE c.skeleton_id IS NOT NULL;

        ');
        return $this->db->single()->total_teams;
    }
}
?>
