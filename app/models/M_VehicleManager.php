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
}
