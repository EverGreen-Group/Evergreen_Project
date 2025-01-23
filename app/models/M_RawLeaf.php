<?php
class M_RawLeaf{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    
    public function getAvailableRawLeafStock() {
        $this->db->query("SELECT leaf_type_id, name, quantity_kg AS total_stock, name FROM leaf_types WHERE is_active = 1");
        return $this->db->resultSet(); // Assuming resultSet() returns an array of objects
    }

}