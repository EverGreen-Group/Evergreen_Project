<?php
class M_Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllProducts() {
        $this->db->query('
            SELECT p.*, c.name as category_name, 
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = "active"
            ORDER BY p.created_at DESC
        ');
        return $this->db->resultSet();
    }

    public function getFeaturedProducts() {
        $this->db->query('
            SELECT p.*, c.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.featured = 1 AND p.status = "active"
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    public function getNewArrivals() {
        $this->db->query('
            SELECT p.*, c.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = "active"
            ORDER BY p.created_at DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    public function getBestSellers() {
        $this->db->query('
            SELECT p.*, c.name as category_name,
                   COUNT(oi.id) as total_sales,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN order_items oi ON p.id = oi.product_id
            LEFT JOIN orders o ON oi.order_id = o.id
            WHERE p.status = "active" AND o.order_status != "cancelled"
            GROUP BY p.id
            ORDER BY total_sales DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query('
            SELECT p.*, c.name as category_name,
                   (SELECT GROUP_CONCAT(image_path) FROM product_images WHERE product_id = p.id ORDER BY is_primary DESC, sort_order ASC) as images
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getProductsByCategory($categoryId) {
        $this->db->query('
            SELECT p.*, c.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = :category_id AND p.status = "active"
            ORDER BY p.created_at DESC
        ');
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    public function searchProducts($query, $category = null, $sort = 'newest') {
        $sql = '
            SELECT p.*, c.name as category_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = "active"
            AND (p.product_name LIKE :query OR p.details LIKE :query)
        ';

        if ($category) {
            $sql .= ' AND p.category_id = :category_id';
        }

        switch ($sort) {
            case 'price_low':
                $sql .= ' ORDER BY p.price ASC';
                break;
            case 'price_high':
                $sql .= ' ORDER BY p.price DESC';
                break;
            case 'popular':
                $sql .= ' ORDER BY p.featured DESC, p.created_at DESC';
                break;
            default:
                $sql .= ' ORDER BY p.created_at DESC';
        }

        $this->db->query($sql);
        $this->db->bind(':query', "%$query%");
        
        if ($category) {
            $this->db->bind(':category_id', $category);
        }

        return $this->db->resultSet();
    }

    // ... Additional methods for admin functionality ...
} 