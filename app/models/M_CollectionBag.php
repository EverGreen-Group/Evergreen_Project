<?php
class M_CollectionBag{
    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }

    public function createCollectionBag($data) {
        try {
            $this->db->beginTransaction(); // Start transaction


            $this->db->query("INSERT INTO collection_bags (
                capacity_kg, 
                bag_weight_kg
            ) VALUES (
                :capacity_kg,
                :bag_weight_kg
            )");


            $this->db->bind(':capacity_kg', (float)$data['capacity_kg']);
            $this->db->bind(':bag_weight_kg', (float)$data['bag_weight_kg'] ?? null);

            $this->db->execute();

            $this->db->commit(); 
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack(); 
            error_log("Error creating collection bag: " . $e->getMessage()); 
            return false; 
        }
    }


    public function getAllBags() {
        $this->db->query("SELECT bag_id, capacity_kg, bag_weight_kg FROM collection_bags");
        return $this->db->resultSet();
    }

    public function getTotalBags() {
        $this->db->query("SELECT COUNT(*) as total FROM collection_bags");
        return $this->db->single()->total; 
    }

    public function getBagDetails($bagId) {
        $this->db->query("SELECT * FROM collection_bags WHERE bag_id = :bag_id");
        $this->db->bind(':bag_id', $bagId);
        return $this->db->single(); // Fetch the bag details
    }

}

