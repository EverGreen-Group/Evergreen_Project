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
            s.is_active,
            u.first_name,
            u.last_name,
            sp.profile_image
        FROM suppliers s 
        JOIN users u ON s.user_id = u.user_id 
        LEFT JOIN supplier_photos sp ON s.supplier_id = sp.supplier_id
        WHERE s.is_active = 1
        ');
        
        return $this->db->resultSet();
    }

    public function getAllUnallocatedSuppliers() {
        $this->db->query('SELECT * FROM suppliers WHERE is_active = 1 AND supplier_id NOT IN (SELECT supplier_id FROM routes)');
        return $this->db->resultSet();
    }
} 