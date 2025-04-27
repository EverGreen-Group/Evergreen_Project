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
