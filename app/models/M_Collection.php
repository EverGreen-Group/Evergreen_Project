<?php

class M_Collection {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    public function getOngoingCollections() {
        $sql = "SELECT 
                    c.*,
                    cs.schedule_id,
                    r.route_name,
                    t.team_name,
                    v.license_plate,
                    s.shift_name
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                JOIN routes r ON cs.route_id = r.route_id
                JOIN teams t ON cs.team_id = t.team_id
                JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
                JOIN collection_shifts s ON cs.shift_id = s.shift_id
                WHERE c.status IN ('Pending', 'In Progress')
                AND cs.is_active = 1
                AND cs.is_deleted = 0
                ORDER BY c.start_time ASC";
        
        $this->db->query($sql);
        $result = $this->db->resultSet();
        
        // test debug
        // var_dump($sql);
        // var_dump($result);
        
        return $result;
    }

    public function getCollectionById($collectionId) {
        $sql = "SELECT 
                    c.*,
                    cs.schedule_id,
                    r.route_name,
                    t.team_name,
                    v.license_plate,
                    s.shift_name
                FROM collections c
                JOIN collection_schedules cs ON c.schedule_id = cs.schedule_id
                JOIN routes r ON cs.route_id = r.route_id
                JOIN teams t ON cs.team_id = t.team_id
                JOIN vehicles v ON cs.vehicle_id = v.vehicle_id
                JOIN collection_shifts s ON cs.shift_id = s.shift_id
                WHERE c.collection_id = :collection_id";
        
        $this->db->query($sql);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->single();
    }

    public function updateArrivalTime($data) {
        $this->db->query('UPDATE collection_supplier_records 
                          SET arrival_time = :arrival_time 
                          WHERE collection_id = :collection_id 
                          AND supplier_id = :supplier_id');
        
        $this->db->bind(':arrival_time', $data['arrival_time']);
        $this->db->bind(':collection_id', $data['collection_id']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        
        return $this->db->execute();
    }

    public function getCollectionsByVehicleId($vehicleId) {
        $this->db->query('SELECT * FROM collections WHERE vehicle_id = :vehicle_id AND status != "Completed"');
        $this->db->bind(':vehicle_id', $vehicleId);
        
        return $this->db->resultSet();
    }
} 