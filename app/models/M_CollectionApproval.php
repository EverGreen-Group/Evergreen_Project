<?php

class M_CollectionApproval
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }



    public function addreport($data)
    {
        $sql = "UPDATE stockvalidate SET collection_id = :collection_id, status = :status, report = :report";

        $this->db->query($sql);
        $this->db->bind(':collection_id', $data['collection_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':report', $data['report']);
        try {
            return $this->db->execute();
        } catch (PDOException $e) {
            // Log the error for debugging
            error_log("Database Error: " . $e->getMessage());
            return false;
        }
    }

    public function getAwaitingInventoryCollections() {
        $this->db->query("
        SELECT * FROM collections c

        JOIN collection_schedules cs ON cs.schedule_id = c.schedule_id
        WHERE status = 'Awaiting Inventory Addition'");
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

    public function getCollectionDetails($collectionId) {
        // Fetch collection details
        $this->db->query("SELECT * FROM collections WHERE collection_id = :collection_id");
        $this->db->bind(':collection_id', $collectionId);
        $collection = $this->db->single(); // Assuming single() returns a single object

        // Fetch suppliers associated with the collection, including user full names
        $this->db->query("
            SELECT s.supplier_id, CONCAT(u.first_name, ' ', u.last_name) AS full_name, csr.quantity, csr.status, csr.notes, csr.approval_status 
            FROM collection_supplier_records csr
            JOIN suppliers s ON s.supplier_id = csr.supplier_id
            JOIN users u ON u.user_id = s.user_id
            WHERE csr.collection_id = :collection_id
        ");
        $this->db->bind(':collection_id', $collectionId);
        $suppliers = $this->db->resultSet(); // Assuming resultSet() returns an array of objects

        return [
            'collection_id' => $collection->collection_id,
            'status' => $collection->status,
            'total_quantity' => $collection->total_quantity,
            'start_time' => $collection->start_time,
            'end_time' => $collection->end_time,
            'suppliers' => $suppliers,
        ];
    }

    public function getBagsBySupplier($supplierId, $collectionId) {
        $this->db->query("
        SELECT bag_usage_history.*, leaf_types.name 
        FROM bag_usage_history 
        JOIN leaf_types ON leaf_types.leaf_type_id = bag_usage_history.leaf_type_id
        WHERE supplier_id = :supplier_id AND collection_id = :collection_id");
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

    public function getBagDetails($bagId) {
        $this->db->query("SELECT * FROM bag_usage_history WHERE bag_id = :bag_id");
        $this->db->bind(':bag_id', $bagId);
        return $this->db->single(); // Assuming single() returns a single object
    }


    public function insertBagInspection($collectionId, $bagId, $supplierId, $leafTypeId, $quantityKg, $leafAge, $moistureLevel, $inspectionStatus, $inspectionNotes, $inspectedBy, $inspectedAt) {
        $this->db->query("INSERT INTO bag_inspections (collection_id, bag_id, supplier_id, leaf_type_id, quantity_kg, leaf_age, moisture_level, inspection_status, inspection_notes, inspected_by, inspected_at) VALUES (:collection_id, :bag_id, :supplier_id, :leaf_type_id, :quantity_kg, :leaf_age, :moisture_level, :inspection_status, :inspection_notes, :inspected_by, :inspected_at)");
        $this->db->bind(':collection_id', $collectionId);
        $this->db->bind(':bag_id', $bagId);
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':leaf_type_id', $leafTypeId);
        $this->db->bind(':quantity_kg', $quantityKg);
        $this->db->bind(':leaf_age', $leafAge);
        $this->db->bind(':moisture_level', $moistureLevel);
        $this->db->bind(':inspection_status', $inspectionStatus);
        $this->db->bind(':inspection_notes', $inspectionNotes);
        $this->db->bind(':inspected_by', $inspectedBy);
        $this->db->bind(':inspected_at', $inspectedAt);
        return $this->db->execute();
    }

    public function updateBagUsageHistory($bagId, $action) {
        $this->db->query("UPDATE bag_usage_history SET action = :action WHERE bag_id = :bag_id");
        $this->db->bind(':action', $action);
        $this->db->bind(':bag_id', $bagId);
        return $this->db->execute();
    }

    public function checkSupplierBags($supplierId) {
        $this->db->query("SELECT COUNT(*) as count FROM bag_usage_history WHERE supplier_id = :supplier_id AND action = 'added'");
        $this->db->bind(':supplier_id', $supplierId);
        $result = $this->db->single();
        return $result['count'] > 0; // Returns true if there are more bags with action 'added'
    }

    public function updateCollectionSupplierApprovalStatus($supplierId, $status) {
        $this->db->query("UPDATE collection_supplier_records SET approval_status = :status WHERE supplier_id = :supplier_id");
        $this->db->bind(':status', $status);
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->execute();
    }

    public function checkSupplierBagsInCollection($supplierId, $collectionId) {
        $this->db->query("SELECT COUNT(*) as count FROM bag_usage_history WHERE supplier_id = :supplier_id AND collection_id = :collection_id AND action = 'added'");
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->single(); // Assuming this returns a single object

        // Access the count property using object syntax
        return $result->count > 0; // Returns true if there are more bags with action 'added'
    }

    public function checkAllSuppliersApproved($collectionId) {
        $this->db->query("SELECT COUNT(*) as count FROM collection_supplier_records WHERE collection_id = :collection_id AND approval_status != 'APPROVED'");
        $this->db->bind(':collection_id', $collectionId);
        $result = $this->db->single(); // Assuming this returns a single object
        return $result->count == 0; // Returns true if all suppliers are approved
    }

    public function updateCollectionStatus($collectionId, $status) {
        $this->db->query("UPDATE collections SET status = :status WHERE collection_id = :collection_id");
        $this->db->bind(':status', $status);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->execute();
    }




}
