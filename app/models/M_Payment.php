<?php

class M_Payment {
    private $db;
    private $error;

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
            s.supplier_id as supplier_number,
            u.first_name,
            u.last_name,
            u.email
            FROM collection_bags cb
            LEFT JOIN suppliers s ON cb.supplier_id = s.id
            LEFT JOIN users u ON s.user_id = u.id
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
        // Add error logging
        error_log("Fetching supplier details for ID: " . $supplier_id);

        $this->db->query("SELECT 
            u.user_id,
            u.email,
            u.first_name,
            u.last_name
            FROM users u
            WHERE u.user_id = (
                SELECT s.user_id
                FROM suppliers s
                WHERE s.supplier_id = :supplier_id"
            );
            
        $this->db->bind(':supplier_id', $supplier_id);
        
        $result = $this->db->single();
        
        if (!$result) {
            $this->error = "No supplier found with ID: " . $supplier_id;
            error_log($this->error);
            // Return default values instead of false
            return (object)[
                'first_name' => ' ',
                'last_name' => ' ',
                'email' => ' ',
                'supplier_number' => ' '
            ];
        }
        
        return $result;
    }

    public function getError() {
        return $this->error;
    }

}
