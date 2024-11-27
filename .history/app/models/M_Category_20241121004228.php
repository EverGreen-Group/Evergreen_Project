<?php
class M_Category {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllCategories() {
        $this->db->query('
            SELECT c.*, 
                   (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = "active") as product_count
            FROM categories c
            WHERE c.status = "active"
            ORDER BY c.name ASC
        ');
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query('
            SELECT c.*, 
                   (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = "active") as product_count
            FROM categories c
            WHERE c.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getCategoryTree() {
        $this->db->query('
            SELECT c.*, 
                   p.name as parent_name,
                   (SELECT COUNT(*) FROM products WHERE category_id = c.id AND status = "active") as product_count
            FROM categories c
            LEFT JOIN categories p ON c.parent_id = p.id
            WHERE c.status = "active"
            ORDER BY c.parent_id, c.name
        ');
        return $this->db->resultSet();
    }

    // Admin methods
    public function addCategory($data) {
        $this->db->query('
            INSERT INTO categories (name, description, image, parent_id)
            VALUES (:name, :description, :image, :parent_id)
        ');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':parent_id', $data['parent_id']);

        return $this->db->execute();
    }

    public function updateCategory($id, $data) {
        $this->db->query('
            UPDATE categories
            SET name = :name,
                description = :description,
                image = :image,
                parent_id = :parent_id,
                status = :status
            WHERE id = :id
        ');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':parent_id', $data['parent_id']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function getAllCategoriesWithProducts() {
        $this->db->query('
            SELECT c.*, 
                   COUNT(p.id) as product_count,
                   (SELECT image FROM category_images WHERE category_id = c.id AND is_primary = 1 LIMIT 1) as image
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id AND p.status = "active"
            WHERE c.status = "active"
            GROUP BY c.id
            ORDER BY c.name ASC
        ');
        return $this->db->resultSet();
    }

    public function getFeaturedCategories() {
        $this->db->query('
            SELECT c.*, 
                   COUNT(p.id) as product_count,
                   (SELECT image FROM category_images WHERE category_id = c.id AND is_primary = 1 LIMIT 1) as image
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id AND p.status = "active"
            WHERE c.status = "active" AND c.featured = 1
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
            LIMIT 4
        ');
        return $this->db->resultSet();
    }
} 