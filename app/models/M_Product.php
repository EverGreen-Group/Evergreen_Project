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
            WHERE p.featured = 1 AND p.status = "active"
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

    public function searchProducts($query) {
        // Build search query with multiple conditions
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
                COALESCE(AVG(r.rating), 0) as rating
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE (
                p.product_name LIKE :query 
                OR p.description LIKE :query 
                OR c.name LIKE :query
                OR p.tags LIKE :query
            )
            AND p.status = "active"
            GROUP BY p.id
            ORDER BY 
                CASE 
                    WHEN p.product_name LIKE :exact_query THEN 1
                    WHEN p.product_name LIKE :start_query THEN 2
                    ELSE 3
                END,
                p.product_name ASC
        ');

        $searchTerm = "%{$query}%";
        $exactTerm = $query;
        $startTerm = "{$query}%";
        
        $this->db->bind(':query', $searchTerm);
        $this->db->bind(':exact_query', $exactTerm);
        $this->db->bind(':start_query', $startTerm);
        
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

    public function getProductWithDetails($id) {
        $this->db->query('
            SELECT 
                p.*,
                c.category_name,
                COALESCE(AVG(r.rating), 0) as average_rating,
                COUNT(DISTINCT r.id) as review_count
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN reviews r ON p.id = r.product_id
            WHERE p.id = :id AND p.status = "active"
            GROUP BY p.id
        ');
        
        $this->db->bind(':id', $id);
        $product = $this->db->single();

        if ($product) {
            // Get product images using existing getProductImages method
            $product->images = $this->getProductImages($id);

            // If no images found, add default image
            if (empty($product->images)) {
                $product->images = [
                    (object)[
                        'image' => 'default.jpg',
                        'is_primary' => 1
                    ]
                ];
            }

            // Get product specifications if they exist
            if ($product->specifications) {
                $product->specifications = json_decode($product->specifications);
            }

            // Get product reviews
            $this->db->query('
                SELECT 
                    r.*,
                    u.first_name,
                    u.last_name,
                    u.profile_image
                FROM reviews r
                JOIN users u ON r.user_id = u.id
                WHERE r.product_id = :product_id
                ORDER BY r.created_at DESC
                LIMIT 5
            ');
            $this->db->bind(':product_id', $id);
            $product->reviews = $this->db->resultSet();
        }

        return $product;
    }
} 