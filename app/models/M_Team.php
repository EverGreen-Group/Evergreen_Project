<?php
// app/models/M_Team.php
class M_Team {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllTeams() {
        $this->db->query('SELECT t.*, 
                          CONCAT(ud.first_name, " ", ud.last_name) as driver_full_name, 
                          CONCAT(up.first_name, " ", up.last_name) as partner_full_name
                          FROM teams t 
                          LEFT JOIN drivers d ON t.driver_id = d.driver_id 
                          LEFT JOIN driving_partners p ON t.partner_id = p.partner_id
                          LEFT JOIN employees ed ON d.employee_id = ed.employee_id
                          LEFT JOIN employees ep ON p.employee_id = ep.employee_id
                          LEFT JOIN users ud ON ud.user_id = ed.user_id
                          LEFT JOIN users up ON up.user_id = ep.user_id
                          WHERE t.is_visible = 1');
        return $this->db->resultSet();
    }

    public function getTeamStatistics() {
        // Get total teams count
        $this->db->query('SELECT COUNT(*) as total FROM teams WHERE is_visible = 1');
        $totalTeams = $this->db->single()->total;

        // Get on duty (active) teams count
        $this->db->query('SELECT COUNT(*) as active FROM teams WHERE status = "Active" AND is_visible = 1');
        $activeTeams = $this->db->single()->active;

        // Get unassigned (inactive) teams count
        $this->db->query('SELECT COUNT(*) as inactive FROM teams WHERE status = "Inactive" AND is_visible = 1');
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
                d2.file_path AS partner_image_url  -- Partner image URL
            FROM teams t
            LEFT JOIN drivers d ON t.driver_id = d.driver_id
            LEFT JOIN employees ed ON d.employee_id = ed.employee_id  -- Get employee details for driver
            LEFT JOIN users ud ON ed.user_id = ud.user_id  -- Get user details for driver
            LEFT JOIN driving_partners p ON t.partner_id = p.partner_id
            LEFT JOIN employees ep ON p.employee_id = ep.employee_id  -- Get employee details for partner
            LEFT JOIN users up ON ep.user_id = up.user_id  -- Get user details for partner
            LEFT JOIN documents d1 ON ed.user_id = d1.user_id AND d1.document_type = "Photo"  -- Get driver image
            LEFT JOIN documents d2 ON ep.user_id = d2.user_id AND d2.document_type = "Photo"  -- Get partner image
            LEFT JOIN collection_schedules c ON t.team_id = c.team_id  -- Join collections to count and sum total quantity
            WHERE t.is_visible = 1  -- Add condition for visible teams only
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
                d.*,
                e.contact_number,
                CONCAT(u.first_name, " ", u.last_name) AS driver_name
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
        try {
            $this->db->query('SELECT COUNT(*) as total_teams FROM teams WHERE is_in_collection = 1');
            $result = $this->db->single();
            return $result->total_teams ?? 0; // Add null coalescing operator
        } catch (PDOException $e) {
            error_log("Error getting total teams in collection: " . $e->getMessage());
            return 0; // Return 0 instead of false
        }
    }

    public function updateTeam($data) {
        $this->db->query('UPDATE teams SET 
            team_name = :team_name,
            driver_id = :driver_id,
            partner_id = :partner_id,
            status = :status
            WHERE team_id = :team_id');

        // Bind values
        $this->db->bind(':team_id', $data['team_id']);
        $this->db->bind(':team_name', $data['team_name']);
        $this->db->bind(':driver_id', $data['driver_id'] ?: null);  // Convert empty string to null
        $this->db->bind(':partner_id', $data['partner_id'] ?: null); // Convert empty string to null
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function createTeam($data) {
        $this->db->query('INSERT INTO teams (
            team_name, 
            driver_id, 
            partner_id, 
            manager_id,
            status,
            is_visible
        ) VALUES (
            :team_name, 
            :driver_id, 
            :partner_id, 
            :manager_id,
            :status,
            1
        )');

        // Bind values
        $this->db->bind(':team_name', $data['team_name']);
        $this->db->bind(':driver_id', $data['driver_id'] ?: null);  // Convert empty string to null
        $this->db->bind(':partner_id', $data['partner_id'] ?: null); // Convert empty string to null
        $this->db->bind(':manager_id', $data['manager_id']); // Assuming manager_id is the logged-in user's ID
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    // Modify getTeams to include current team members
    public function getTeams() {
        $this->db->query("
            SELECT t.*, 
                d.driver_id as current_driver_id, 
                d.driver_name as current_driver_name,
                p.partner_id as current_partner_id,
                p.partner_name as current_partner_name
            FROM teams t
            LEFT JOIN drivers d ON t.driver_id = d.driver_id
            LEFT JOIN partners p ON t.partner_id = p.partner_id
            WHERE t.is_visible = 1
        ");
        
        return $this->db->resultSet();
    }

    public function setTeamVisibility($teamId, $isVisible) {
        $this->db->query('UPDATE teams SET is_visible = :is_visible, driver_id = NULL, partner_id = NULL WHERE team_id = :team_id');
        $this->db->bind(':is_visible', $isVisible);
        $this->db->bind(':team_id', $teamId);
        
        return $this->db->execute();
    }

    public function getTeamById($teamId) {
        $this->db->query("
            SELECT 
                t.team_id,
                t.team_name,
                t.status,
                t.created_at,
                CONCAT(ud.first_name, ' ', ud.last_name) as driver_name,
                CONCAT(up.first_name, ' ', up.last_name) as partner_name,
                CONCAT(um.first_name, ' ', um.last_name) as manager_name
            FROM teams t
            LEFT JOIN drivers d ON t.driver_id = d.driver_id
            LEFT JOIN employees ed ON d.employee_id = ed.employee_id
            LEFT JOIN users ud ON ed.user_id = ud.user_id
            LEFT JOIN driving_partners dp ON t.partner_id = dp.partner_id
            LEFT JOIN employees ep ON dp.employee_id = ep.employee_id
            LEFT JOIN users up ON ep.user_id = up.user_id
            LEFT JOIN employees em ON t.manager_id = em.employee_id
            LEFT JOIN users um ON em.user_id = um.user_id
            WHERE t.team_id = :team_id
            AND t.is_visible = 1
            LIMIT 1
        ");

        $this->db->bind(':team_id', $teamId);
        return $this->db->single();
    }

    public function getAllDrivers() {
        $this->db->query('
            SELECT 
                d.driver_id,
                CONCAT(ud.first_name, " ", ud.last_name) AS driver_name,
                e.contact_number,
                d.status
            FROM drivers d
            JOIN employees e ON d.employee_id = e.employee_id
            JOIN users ud ON e.user_id = ud.user_id
            WHERE d.is_deleted = 0
            ORDER BY d.driver_id ASC
        ');

        return $this->db->resultSet();
    }
}
?>
