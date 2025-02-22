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
            SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available_vehicles,
            SUM(CASE WHEN status != 'Available' THEN 1 ELSE 0 END) as in_use
            FROM vehicles");
        $stats['vehicles'] = $this->db->single();

        // Get driver stats
        $this->db->query("SELECT 
            COUNT(*) as total_drivers,
            SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available_drivers
            FROM drivers");
        $stats['drivers'] = $this->db->single();

        // Get collection stats for today
        $this->db->query("SELECT 
            COUNT(*) as total_collections,
            SUM(CASE WHEN status = 'In Progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'Completed' AND DATE(collection_completed_at) = CURDATE() THEN 1 ELSE 0 END) as completed_today
            FROM collections
            WHERE DATE(start_time) = CURDATE()");
        $stats['collections'] = $this->db->single();

        // Get next upcoming collection schedules for today
        $this->db->query("SELECT 
            cs.*, 
            s.start_time,
            r.route_name 
            FROM collection_schedules cs
            JOIN collection_shifts s ON cs.shift_id = s.shift_id
            JOIN routes r ON cs.route_id = r.route_id
            WHERE cs.is_active = 1 
            AND cs.is_deleted = 0 
            AND cs.day = DAYNAME(CURDATE()) 
            AND CONCAT(CURDATE(), ' ', s.start_time) > NOW() 
            ORDER BY CONCAT(CURDATE(), ' ', s.start_time) ASC 
            LIMIT 1");
        $stats['next_schedule'] = $this->db->single();

        // Get collection bags stats
        $this->db->query("SELECT 
            COUNT(*) as total_bags,
            SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_bags,
            SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_bags
            FROM collection_bags");
        $stats['bags'] = $this->db->single();

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
}
