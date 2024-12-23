<?php
class M_Supplier {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllSuppliers() {
        $this->db->query('
            SELECT 
                s.supplier_id,
                s.user_id,
                s.is_active,
                s.latitude,
                s.longitude,
                u.first_name,
                u.last_name,
                u.email,
                u.nic,
                u.gender,
                u.date_of_birth
            FROM suppliers s 
            JOIN users u ON s.user_id = u.user_id 
            WHERE s.is_active = 1
        ');
        
        return $this->db->resultSet();
    }


    public function getSupplierById($supplierId) {
        $this->db->query('
            SELECT 
                s.supplier_id,
                s.user_id,
                s.is_active,
                s.latitude,
                s.longitude,
                u.first_name,
                u.last_name,
                u.email,
                u.nic,
                u.gender,
                u.date_of_birth
            FROM suppliers s 
            JOIN users u ON s.user_id = u.user_id 
            WHERE s.supplier_id = :supplier_id
        ');
        
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->single();
    }


    public function getTotalCollectionQuantity($supplierId) {
        $this->db->query('SELECT SUM(quantity) as total_quantity 
                         FROM collection_supplier_records 
                         WHERE supplier_id = :supplier_id');
        $this->db->bind(':supplier_id', $supplierId);
        $result = $this->db->single();
        return $result->total_quantity ?? 0;
    }

    public function getCollectionDaysCount($supplierId) {
        $this->db->query('SELECT COUNT(DISTINCT DATE(collection_time)) as collection_days 
                         FROM collection_supplier_records 
                         WHERE supplier_id = :supplier_id 
                         AND MONTH(collection_time) = MONTH(CURRENT_DATE())');
        $this->db->bind(':supplier_id', $supplierId);
        $result = $this->db->single();
        return $result->collection_days ?? 0;
    }

    public function calculatePerformanceRate($totalQuantity, $collectionDays, $dailyTarget = 300) {
        if ($collectionDays == 0) return 0;
        $actualDaily = $totalQuantity / $collectionDays;
        return round(($actualDaily / $dailyTarget) * 100, 2);
    }

    public function getSupplierByName($searchTerm) {
        $this->db->query('SELECT s.*, u.first_name, u.last_name 
                         FROM suppliers s 
                         JOIN users u ON s.user_id = u.user_id 
                         WHERE CONCAT(u.first_name, " ", u.last_name) LIKE :search 
                         AND s.is_active = 1');
        $this->db->bind(':search', "%$searchTerm%");
        return $this->db->resultSet();
    }


} 