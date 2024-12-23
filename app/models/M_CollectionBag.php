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


}

