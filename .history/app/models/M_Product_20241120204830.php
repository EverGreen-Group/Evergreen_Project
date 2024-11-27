<?php
class M_Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProducts() {
        $this->db->query('SELECT * FROM products ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query('SELECT * FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateStock($id, $quantity) {
        $this->db->query('UPDATE products SET quantity = quantity - :quantity WHERE id = :id AND quantity >= :quantity');
        $this->db->bind(':id', $id);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }
} 