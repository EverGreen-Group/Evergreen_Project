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

            $lastInsertId = $this->db->lastInsertId(); // Get the last inserted ID

            $this->db->commit(); 
            return $lastInsertId; // Return the last inserted ID
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

    public function updateCollectionBag($bagId, $capacityKg, $bagWeightKg, $status) {
        $this->db->query("UPDATE collection_bags SET capacity_kg = :capacity_kg, bag_weight_kg = :bag_weight_kg, status = :status WHERE bag_id = :bag_id");
        $this->db->bind(':capacity_kg', $capacityKg);
        $this->db->bind(':bag_weight_kg', $bagWeightKg);
        $this->db->bind(':status', $status);
        $this->db->bind(':bag_id', $bagId);
        return $this->db->execute(); // Execute the update
    }

    public function removeCollectionBag($bagId) {
        $this->db->query("DELETE FROM collection_bags WHERE bag_id = :bag_id");
        $this->db->bind(':bag_id', $bagId);
        return $this->db->execute(); // Execute the delete
    }

    public function isBagInUse($bagId) {
        $this->db->query("SELECT COUNT(*) as count FROM bag_usage_history WHERE bag_id = :bag_id");
        $this->db->bind(':bag_id', $bagId);
        $result = $this->db->single(); // Fetch the count
        return $result->count > 0; // Return true if the bag is in use
    }

    // public function saveQRCodePath($bagId, $qrCodePath) {
    //     $this->db->query("UPDATE collection_bags SET qr_code_path = :qr_code_path WHERE bag_id = :bag_id");
    //     $this->db->bind(':qr_code_path', $qrCodePath);
    //     $this->db->bind(':bag_id', $bagId);
    //     return $this->db->execute(); // Execute the update
    // }

}

