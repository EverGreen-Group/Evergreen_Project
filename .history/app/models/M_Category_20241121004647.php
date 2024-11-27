<?php
class M_Category {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllCategories() {
        $this->db->query('
            SELECT * FROM categories 
            WHERE status = "active" 
            ORDER BY name ASC
        ');
        return $this->db->resultSet();
    }

    public function getAllCategoriesWithProducts() {
        $this->db->query('
            SELECT c.*, 
                   COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE c.status = "active"
            GROUP BY c.id
            ORDER BY c.name ASC
        ');
        return $this->db->resultSet();
    }

    public function getFeaturedCategories() {
        $this->db->query('
            SELECT c.*, 
                   COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE c.status = "active" 
            AND c.featured = 1
            GROUP BY c.id
            ORDER BY c.name ASC
            LIMIT 4
        ');
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
} 