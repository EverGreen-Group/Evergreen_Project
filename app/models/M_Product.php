<?php
class M_Product {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all featured products
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

    // Get new arrivals (latest products)
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
            WHERE p.status = "active"
            ORDER BY p.created_at DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    // Get best-selling products
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
            WHERE p.status = "active"
            GROUP BY p.id
            ORDER BY order_count DESC
            LIMIT 8
        ');
        return $this->db->resultSet();
    }

    // Get a product by ID
    public function getProductById($id) {
        $this->db->query('
            SELECT 
                p.*,
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

    // Get products by category
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
            WHERE p.category_id = :category_id AND p.status = "active"
            ORDER BY p.created_at DESC
        ');
        $this->db->bind(':category_id', $categoryId);
        return $this->db->resultSet();
    }

    // Get total number of products
    public function getTotalProducts() {
        $this->db->query('SELECT COUNT(*) as total FROM products');
        $result = $this->db->single();
        return $result->total;
    }

    // Search products by query
    public function searchProducts($query) {
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
            WHERE (
                p.product_name LIKE :query 
                OR p.description LIKE :query 
                OR c.name LIKE :query
                OR p.tags LIKE :query
            )
            AND p.status = "active"
            ORDER BY p.product_name ASC
        ');
        $this->db->bind(':query', "%{$query}%");
        return $this->db->resultSet();
    }

    // Update product stock (deduct quantity)
    public function deductStock($productId, $quantity) {
        $this->db->query('
            UPDATE products 
            SET stock = stock - :quantity 
            WHERE id = :id AND stock >= :quantity
        ');
        $this->db->bind(':id', $productId);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    // Restore product stock (add quantity)
    public function restoreStock($productId, $quantity) {
        $this->db->query('
            UPDATE products 
            SET stock = stock + :quantity 
            WHERE id = :id
        ');
        $this->db->bind(':id', $productId);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    // Get product images
    public function getProductImages($productId) {
        $this->db->query('
            SELECT * FROM product_images 
            WHERE product_id = :product_id 
            ORDER BY is_primary DESC, created_at ASC
        ');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    // Add a product image
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

    // Update primary images for a product
    public function updatePrimaryImages($productId) {
        $this->db->query('
            UPDATE product_images 
            SET is_primary = 0 
            WHERE product_id = :product_id
        ');
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
    }

    // Get related products
    public function getRelatedProducts($productId, $categoryId, $limit = 4) {
        $this->db->query('
            SELECT 
                p.*,
                (SELECT image_path FROM product_images 
                 WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM products p
            WHERE p.category_id = :category_id 
            AND p.id != :product_id
            AND p.status = "active"
            ORDER BY RAND()
            LIMIT :limit
        ');
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        return $this->db->resultSet();
    }

    // Get product with detailed information
    public function getProductWithDetails($id) {
        $this->db->query('
            SELECT 
                p.*,
                c.name as category_name,
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
            // Fetch product images
            $product->images = $this->getProductImages($id);

            // Fetch product reviews
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