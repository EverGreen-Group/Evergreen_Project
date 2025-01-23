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
        $this->db->query("SELECT * FROM bag_usage_history WHERE supplier_id = :supplier_id AND collection_id = :collection_id");
        $this->db->bind(':supplier_id', $supplierId);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

    public function getBagDetails($bagId) {
        $this->db->query("SELECT * FROM bag_usage_history WHERE bag_id = :bag_id");
        $this->db->bind(':bag_id', $bagId);
        return $this->db->single(); // Assuming single() returns a single object
    }




}
