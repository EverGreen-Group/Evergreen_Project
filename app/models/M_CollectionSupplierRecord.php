<?php

class M_CollectionSupplierRecord {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSupplierRecords($collectionId) {
        $sql = "SELECT 
                    csr.*,
                    s.supplier_id,
                    s.latitude,
                    s.longitude,
                    CONCAT(u.first_name, ' ', u.last_name) as supplier_name
                FROM collection_supplier_records csr
                JOIN suppliers s ON csr.supplier_id = s.supplier_id
                JOIN users u ON s.user_id = u.user_id
                WHERE csr.collection_id = :collection_id
                ORDER BY csr.collection_time ASC";
        
        $this->db->query($sql);
        $this->db->bind(':collection_id', $collectionId);
        return $this->db->resultSet();
    }

    public function updateSupplierRecord($data) {
        $sql = "UPDATE collection_supplier_records 
                SET status = :status,
                    quantity = :quantity,
                    collection_time = :collection_time,
                    notes = :notes
                WHERE record_id = :record_id";
        
        $this->db->query($sql);
        $this->db->bind(':status', $data->status);
        $this->db->bind(':quantity', $data->quantity ?? 0);
        $this->db->bind(':collection_time', $data->collection_time);
        $this->db->bind(':notes', $data->notes ?? null);
        $this->db->bind(':record_id', $data->record_id);
        
        return $this->db->execute();
    }

    public function addSupplierRecord($data) {
        $sql = "INSERT INTO collection_supplier_records 
                (collection_id, supplier_id, status, is_scheduled)
                VALUES (:collection_id, :supplier_id, :status, :is_scheduled)";
        
        $this->db->query($sql);
        $this->db->bind(':collection_id', $data->collection_id);
        $this->db->bind(':supplier_id', $data->supplier_id);
        $this->db->bind(':status', $data->status);
        $this->db->bind(':is_scheduled', $data->is_scheduled);
        
        return $this->db->execute();
    }

    public function updateSupplierStatus($recordId, $status) {
        try {
            // Validate status against enum values
            $validStatuses = ['Collected', 'No Show', 'Added', 'Skipped'];
            if (!in_array($status, $validStatuses)) {
                throw new PDOException("Invalid status value");
            }

            $this->db->query('UPDATE collection_supplier_records 
                             SET status = :status 
                             WHERE record_id = :record_id');
            
            $this->db->bind(':status', $status);
            $this->db->bind(':record_id', $recordId);

            return $this->db->execute();
            
        } catch (PDOException $e) {
            error_log("Error updating supplier status: " . $e->getMessage());
            return false;
        }
    }

    public function removeCollectionSupplier($recordId) {
        $sql = "DELETE FROM collection_supplier_records WHERE record_id = :record_id AND status = 'Added'";
        $this->db->query($sql);
        $this->db->bind(':record_id', $recordId);
        return $this->db->execute();
    }
} 
