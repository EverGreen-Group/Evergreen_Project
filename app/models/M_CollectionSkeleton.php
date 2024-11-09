<?php
class M_CollectionSkeleton {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all collection skeletons
    public function getAllSkeletons() {
        $sql = "SELECT * FROM collection_skeletons ORDER BY created_at DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    // Create new collection skeleton
    public function create($data) {
        $sql = "INSERT INTO collection_skeletons (route_id, team_id, vehicle_id, shift_id) 
                VALUES (:route_id, :team_id, :vehicle_id, :shift_id)";
        
        $this->db->query($sql);
        $this->db->bind(':route_id', $data['route_id']);
        $this->db->bind(':team_id', $data['team_id']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':shift_id', $data['shift_id']);
        
        return $this->db->execute();
    }

    // Check for duplicates
    public function checkDuplicate($route_id, $team_id, $vehicle_id, $shift_id) {
        $sql = "SELECT COUNT(*) as count 
                FROM collection_skeletons 
                WHERE route_id = :route_id 
                AND team_id = :team_id 
                AND vehicle_id = :vehicle_id 
                AND shift_id = :shift_id 
                AND is_active = 1";
        
        $this->db->query($sql);
        $this->db->bind(':route_id', $route_id);
        $this->db->bind(':team_id', $team_id);
        $this->db->bind(':vehicle_id', $vehicle_id);
        $this->db->bind(':shift_id', $shift_id);
        
        $result = $this->db->single();
        return $result->count > 0;
    }

    public function toggleActive($skeleton_id) {
        $sql = "UPDATE collection_skeletons 
                SET is_active = NOT is_active 
                WHERE skeleton_id = :skeleton_id";
        
        $this->db->query($sql);
        $this->db->bind(':skeleton_id', $skeleton_id);
        
        return $this->db->execute();
    }

    public function delete($skeleton_id) {
        $sql = "DELETE FROM collection_skeletons WHERE skeleton_id = :skeleton_id";
        
        $this->db->query($sql);
        $this->db->bind(':skeleton_id', $skeleton_id);
        
        return $this->db->execute();
    }

    public function update($data) {
        $sql = "UPDATE collection_skeletons 
                SET route_id = :route_id,
                    team_id = :team_id,
                    vehicle_id = :vehicle_id,
                    shift_id = :shift_id
                WHERE skeleton_id = :skeleton_id";
        
        $this->db->query($sql);
        $this->db->bind(':skeleton_id', $data['skeleton_id']);
        $this->db->bind(':route_id', $data['route_id']);
        $this->db->bind(':team_id', $data['team_id']);
        $this->db->bind(':vehicle_id', $data['vehicle_id']);
        $this->db->bind(':shift_id', $data['shift_id']);
        
        return $this->db->execute();
    }
}