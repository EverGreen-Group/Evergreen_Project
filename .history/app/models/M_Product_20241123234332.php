<?php
class M_Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getFeaturedProducts() {
        $this->db->query('
            SELECT p.*, 
                   COALESCE(AVG(r.rating), 0) as rating,
                   COUNT(r.id) as review_count
            FROM products p
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.is_featured = 1
            GROUP BY p.id
            ORDER BY p.created_at DESC
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
            SELECT p.*, 
                   c.name as category_name,
                   GROUP_CONCAT(pi.image_path) as images,
                   (SELECT image_path FROM product_images 
                    WHERE product_id = p.id AND is_primary = 1 
                    LIMIT 1) as primary_image
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_images pi ON p.id = pi.product_id
            WHERE p.id = :id
            GROUP BY p.id
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
            ORDER BY is_primary DESC, created_at ASC
        ');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    public function addProductImage($productId, $imagePath, $isPrimary = 0) {
        $this->db->query('
            INSERT INTO product_images (product_id, image_path, is_primary) 
            VALUES (:product_id, :image_path, :is_primary)
        ');
        
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':image_path', $imagePath);
        $this->db->bind(':is_primary', $isPrimary);
        
        return $this->db->execute();
    }

    public function updatePrimaryImages($productId) {
        $this->db->query('
            UPDATE product_images 
            SET is_primary = 0 
            WHERE product_id = :product_id
        ');
        
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $this->db->query('
            SELECT p.*, 
                   (SELECT image_path FROM product_images 
                    WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM products p
            WHERE p.category_id = :category_id 
            AND p.id != :product_id
            AND p.status = "active"
            AND p.stock > 0
            ORDER BY RAND()
            LIMIT :limit
        ');
        
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }

    public function resetPrimaryImages($productId) {
        $this->db->query('
            UPDATE product_images 
            SET is_primary = 0 
            WHERE product_id = :product_id
        ');
        
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }
} 