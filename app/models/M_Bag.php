<?php

class M_Bag {

    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function updateBag($bagId, $actualWeight, $leafTypeId, $leafAge, $moistureLevel, $notes, $supplierId, $collectionId) {
        $this->db->query("
            UPDATE bag_usage_history SET 
            actual_weight_kg = :actual_weight_kg,
            leaf_type_id = :leaf_type_id,
            leaf_age = :leaf_age,
            moisture_level = :moisture_level,
            deduction_notes = :notes,
            supplier_id = :supplier_id,
            collection_id = :collection_id
            WHERE bag_id = :bag_id
            ");
    
        // Bind the parameters
        $this->db->bind(':actual_weight_kg', $actualWeight);
        $this->db->bind(':leaf_type_id', $leafTypeId);
        $this->db->bind(':leaf_age', $leafAge);
        $this->db->bind(':moisture_level', $moistureLevel);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':bag_id', $bagId);

        return $this->db->execute();
    }


    public function getBagById($bagId) {
        $this->db->query("SELECT * FROM bag_usage_history WHERE bag_id = :bag_id");
        
        // Bind the parameters
        $this->db->bind(':bag_id', $bagId);
        
        return $this->db->single();
    }

    public function getBagsByCollectionId($collectionId) {
        $this->db->query("SELECT * FROM bag_usage_history WHERE collectionId = :collectionId");
        
        // Bind the parameters
        $this->db->bind(':collectionId', $collectionId);
        
        return $this->db->single();
    }


    public function deleteBagById($bagId) {
        // Start a transaction
        $this->db->beginTransaction();
        
        try {
            // Prepare the SQL query to delete from bag_usage_history
            $this->db->query("DELETE FROM bag_usage_history WHERE bag_id = :bag_id");
            
            // Bind the parameters
            $this->db->bind(':bag_id', $bagId);
            
            // Execute the delete query
            $deleteResult = $this->db->execute(); // Returns true on success, false on failure

            // If the delete was successful, update the status in collection_bags
            if ($deleteResult) {
                $this->db->query("UPDATE collection_bags SET status = 'active' WHERE bag_id = :bag_id");
                $this->db->bind(':bag_id', $bagId);
                $this->db->execute(); // Execute the update query
            }

            // Commit the transaction
            $this->db->commit();

            return $deleteResult; // Return the result of the delete operation
        } catch (Exception $e) {
            // Rollback the transaction in cagetBagsByCollectionSupplierse of an error
            $this->db->rollBack();
            error_log("Error deleting bag: " . $e->getMessage());
            return false; // Return false on failure
        }
    }


    public function getBagsByCollectionSupplier($collectionId, $supplierId) {
        $this->db->query('
            SELECT *
            FROM bag_usage_history
            WHERE collection_id = :collection_id
            AND supplier_id = :supplier_id
        ');


        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }


    // CARE!!! THIS FUNCTION IS FOR SETTING THE SUPPLIER APPROVED TO 1 WHICH IS DONE BY THE SUPPLIER
    public function approveSupplierBags($supplierId, $collectionId) {
        // Prepare the SQL query to update supplier_approved
        $this->db->query("UPDATE collection_supplier_records SET supplier_approved = 1 WHERE supplier_id = :supplier_id AND collection_id = :collection_id");
    
        // Bind the parameters
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':collection_id', $collectionId);
    
        // Execute the query and return the result
        return $this->db->execute(); // Returns true on success, false on failure
    }


} 