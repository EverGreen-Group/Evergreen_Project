<?php
class M_Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getFeaturedProducts() {
        $this->db->query('
            SELECT 
                p.*,
                c.name as category_name,
                COALESCE(
                    (SELECT image FROM product_images 
                     WHERE product_id = p.id 
                     AND is_primary = 1 
                     LIMIT 1),
                    "default.jpg"
                ) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.featured = 1
            ORDER BY p.created_at DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    public function getNewArrivals() {
        $this->db->query('
            SELECT 
                p.*,
                c.name as category_name,
                COALESCE(
                    (SELECT image FROM product_images 
                     WHERE product_id = p.id 
                     AND is_primary = 1 
                     LIMIT 1),
                    "default.jpg"
                ) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            ORDER BY p.created_at DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    public function getBestSellers() {
        $this->db->query('
            SELECT 
                p.*,
                c.name as category_name,
                COALESCE(
                    (SELECT image FROM product_images 
                     WHERE product_id = p.id 
                     AND is_primary = 1 
                     LIMIT 1),
                    "default.jpg"
                ) as primary_image,
                COUNT(o.id) as order_count
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN order_items o ON p.id = o.product_id
            GROUP BY p.id
            ORDER BY order_count DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    public function getProductById($id) {
        $this->db->query('
            SELECT 
                p.*,
                c.name as category_name,
                COALESCE(
                    (SELECT image FROM product_images 
                     WHERE product_id = p.id 
                     AND is_primary = 1 
                     LIMIT 1),
                    "default.jpg"
                ) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getProductsByCategory($categoryId) {
        $this->db->query('
            SELECT 
                p.*,
                c.name as category_name,
                COALESCE(
                    (SELECT image FROM product_images 
                     WHERE product_id = p.id 
                     AND is_primary = 1 
                     LIMIT 1),
                    "default.jpg"
                ) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.category_id = :category_id
            ORDER BY p.created_at DESC
        ');
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    public function getTotalProducts() {
        $this->db->query('SELECT COUNT(*) as total FROM products');
        $result = $this->db->single();
        return $result->total;
    }

    public function searchProducts($query, $category = null, $sort = 'newest') {
        $sql = '
            SELECT 
                p.*,
                c.name as category_name,
                COALESCE(
                    (SELECT image FROM product_images 
                     WHERE product_id = p.id 
                     AND is_primary = 1 
                     LIMIT 1),
                    "default.jpg"
                ) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE 1=1
        ';

        if (!empty($query)) {
            $sql .= ' AND (p.product_name LIKE :query OR p.details LIKE :query)';
        }

        if (!empty($category)) {
            $sql .= ' AND p.category_id = :category_id';
        }

        switch ($sort) {
            case 'price_low':
                $sql .= ' ORDER BY p.price ASC';
                break;
            case 'price_high':
                $sql .= ' ORDER BY p.price DESC';
                break;
            case 'oldest':
                $sql .= ' ORDER BY p.created_at ASC';
                break;
            default: // newest
                $sql .= ' ORDER BY p.created_at DESC';
        }

        $this->db->query($sql);

        if (!empty($query)) {
            $this->db->bind(':query', '%' . $query . '%');
        }

        if (!empty($category)) {
            $this->db->bind(':category_id', $category);
        }

        return $this->db->resultSet();
    }

    public function updateProductQuantity($productId, $quantity) {
        $this->db->query('
            UPDATE products 
            SET quantity = quantity - :quantity 
            WHERE id = :id AND quantity >= :quantity
        ');
        $this->db->bind(':id', $productId);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    public function getProductImages($productId) {
        $this->db->query('
            SELECT * FROM product_images 
            WHERE product_id = :product_id 
            ORDER BY is_primary DESC
        ');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }
} 