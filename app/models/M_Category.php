<?php
class M_Category {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllCategories() {
        $this->db->query('
            SELECT 
                c1.*, 
                COUNT(DISTINCT p.id) as product_count,
                GROUP_CONCAT(
                    DISTINCT CONCAT(
                        c2.id, "::",
                        c2.name, "::",
                        COALESCE(c2.image, "default.jpg"), "::",
                        COALESCE((
                            SELECT COUNT(*)
                            FROM products
                            WHERE category_id = c2.id
                        ), 0)
                    ) SEPARATOR "||"
                ) as subcategories
            FROM categories c1
            LEFT JOIN categories c2 ON c2.parent_id = c1.id
            LEFT JOIN products p ON p.category_id = c1.id
            WHERE c1.parent_id IS NULL 
            AND c1.status = "active"
            GROUP BY c1.id
            ORDER BY c1.sort_order ASC, c1.name ASC
        ');
        return $this->db->resultSet();
    }

    public function getAllCategoriesWithProducts() {
        $this->db->query('
            SELECT 
                c.*, 
                COUNT(DISTINCT p.id) as product_count,
                (
                    SELECT COUNT(*)
                    FROM categories sub
                    WHERE sub.parent_id = c.id
                    AND sub.status = "active"
                ) as subcategory_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE c.status = "active"
            AND c.parent_id IS NULL
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
        ');
        return $this->db->resultSet();
    }

    public function getFeaturedCategories() {
        $this->db->query('
            SELECT 
                c.*, 
                COUNT(DISTINCT p.id) as product_count,
                (
                    SELECT COUNT(*)
                    FROM categories sub
                    WHERE sub.parent_id = c.id
                    AND sub.status = "active"
                ) as subcategory_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE c.status = "active" 
            AND c.featured = 1
            AND c.parent_id IS NULL
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
            LIMIT 4
        ');
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query('
            SELECT 
                c.*, 
                COUNT(DISTINCT p.id) as product_count,
                parent.name as parent_name,
                parent.id as parent_id
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            LEFT JOIN categories parent ON c.parent_id = parent.id
            WHERE c.id = :id
            GROUP BY c.id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getSubcategories($parentId) {
        $this->db->query('
            SELECT 
                c.*, 
                COUNT(p.id) as product_count
            FROM categories c
            LEFT JOIN products p ON c.id = p.category_id
            WHERE c.parent_id = :parent_id
            AND c.status = "active"
            GROUP BY c.id
            ORDER BY c.sort_order ASC, c.name ASC
        ');
        $this->db->bind(':parent_id', $parentId);
        return $this->db->resultSet();
    }
} 