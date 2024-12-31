<?php

class M_Payment {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getSupplierCollections($supplier_id, $month = null) {
        // If no month is specified, use current month
        if (!$month) {
            $month = date('Y-m');
        }

        $this->db->query("SELECT 
            cb.bag_id,
            cb.assigned_at,
            cb.capacity_kg,
            cb.leaf_type,
            cb.leaf_age,
            cb.moisture_level,
            cb.deductions,
            cb.bag_weight_kg,
            cb.status,
            s.name as supplier_name,
            s.address as supplier_address,
            s.reg_no as supplier_number
            FROM collection_bags cb
            LEFT JOIN suppliers s ON cb.supplier_id = s.id
            WHERE cb.supplier_id = :supplier_id 
            AND DATE_FORMAT(cb.assigned_at, '%Y-%m') = :month
            AND cb.status = 'Completed'
            ORDER BY cb.assigned_at ASC");

        $this->db->bind(':supplier_id', $supplier_id);
        $this->db->bind(':month', $month);

        return $this->db->resultSet();
    }

    public function getFertilizerOrders($supplier_id, $month) {
        $this->db->query("SELECT 
            fo.*,
            ft.name as fertilizer_name
            FROM fertilizer_orders fo
            LEFT JOIN fertilizer_types ft ON fo.type_id = ft.type_id
            WHERE fo.supplier_id = :supplier_id 
            AND DATE_FORMAT(fo.order_date, '%Y-%m') = :month
            AND fo.status = 'Completed'");
        
        $this->db->bind(':supplier_id', $supplier_id);
        $this->db->bind(':month', $month);

        return $this->db->resultSet();
    }

    public function getSupplierDetails($supplier_id) {
        $this->db->query("SELECT * FROM suppliers WHERE id = :supplier_id");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->single();
    }

}
