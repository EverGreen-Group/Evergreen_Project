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

    public function getTotalProducts() {
        $this->db->query('SELECT COUNT(*) as total FROM products WHERE status = "active"');
        return $this->db->single()->total;
    }

    public function addProduct($data) {
        $this->db->beginTransaction();

        try {
            // Insert product
            $this->db->query('
                INSERT INTO products (
                    category_id, product_name, slug, sku, location, 
                    details, price, profit, margin, quantity, 
                    min_quantity, featured, status
                ) VALUES (
                    :category_id, :product_name, :slug, :sku, :location,
                    :details, :price, :profit, :margin, :quantity,
                    :min_quantity, :featured, :status
                )
            ');

            $this->db->bind(':category_id', $data['category_id']);
            $this->db->bind(':product_name', $data['product_name']);
            $this->db->bind(':slug', $this->createSlug($data['product_name']));
            $this->db->bind(':sku', $data['sku']);
            $this->db->bind(':location', $data['location']);
            $this->db->bind(':details', $data['details']);
            $this->db->bind(':price', $data['price']);
            $this->db->bind(':profit', $data['profit']);
            $this->db->bind(':margin', $data['margin']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':min_quantity', $data['min_quantity'] ?? 5);
            $this->db->bind(':featured', $data['featured'] ?? 0);
            $this->db->bind(':status', 'active');

            $this->db->execute();
            $productId = $this->db->lastInsertId();

            // Handle product images
            if (!empty($data['images'])) {
                foreach ($data['images'] as $index => $image) {
                    $this->db->query('
                        INSERT INTO product_images (
                            product_id, image_path, is_primary, sort_order
                        ) VALUES (
                            :product_id, :image_path, :is_primary, :sort_order
                        )
                    ');

                    $this->db->bind(':product_id', $productId);
                    $this->db->bind(':image_path', $image);
                    $this->db->bind(':is_primary', $index === 0 ? 1 : 0);
                    $this->db->bind(':sort_order', $index);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return $productId;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Product creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function updateProduct($id, $data) {
        $this->db->beginTransaction();

        try {
            $this->db->query('
                UPDATE products 
                SET category_id = :category_id,
                    product_name = :product_name,
                    slug = :slug,
                    sku = :sku,
                    location = :location,
                    details = :details,
                    price = :price,
                    profit = :profit,
                    margin = :margin,
                    quantity = :quantity,
                    min_quantity = :min_quantity,
                    featured = :featured,
                    status = :status
                WHERE id = :id
            ');

            $this->db->bind(':id', $id);
            $this->db->bind(':category_id', $data['category_id']);
            $this->db->bind(':product_name', $data['product_name']);
            $this->db->bind(':slug', $this->createSlug($data['product_name'], $id));
            $this->db->bind(':sku', $data['sku']);
            $this->db->bind(':location', $data['location']);
            $this->db->bind(':details', $data['details']);
            $this->db->bind(':price', $data['price']);
            $this->db->bind(':profit', $data['profit']);
            $this->db->bind(':margin', $data['margin']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':min_quantity', $data['min_quantity'] ?? 5);
            $this->db->bind(':featured', $data['featured'] ?? 0);
            $this->db->bind(':status', $data['status']);

            $this->db->execute();

            // Handle image updates if provided
            if (!empty($data['images'])) {
                // Remove existing images
                $this->db->query('DELETE FROM product_images WHERE product_id = :product_id');
                $this->db->bind(':product_id', $id);
                $this->db->execute();

                // Add new images
                foreach ($data['images'] as $index => $image) {
                    $this->db->query('
                        INSERT INTO product_images (
                            product_id, image_path, is_primary, sort_order
                        ) VALUES (
                            :product_id, :image_path, :is_primary, :sort_order
                        )
                    ');

                    $this->db->bind(':product_id', $id);
                    $this->db->bind(':image_path', $image);
                    $this->db->bind(':is_primary', $index === 0 ? 1 : 0);
                    $this->db->bind(':sort_order', $index);
                    $this->db->execute();
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Product update failed: " . $e->getMessage());
            return false;
        }
    }

    public function deleteProduct($id) {
        $this->db->query('UPDATE products SET status = "inactive" WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getLowStockProducts() {
        $this->db->query('
            SELECT p.*, c.name as category_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.status = "active" 
            AND p.quantity <= p.min_quantity
            ORDER BY p.quantity ASC
        ');
        return $this->db->resultSet();
    }

    private function createSlug($string, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string)));
        
        // Check if slug exists
        $this->db->query('SELECT id FROM products WHERE slug = :slug AND id != :id');
        $this->db->bind(':slug', $slug);
        $this->db->bind(':id', $id ?? 0);
        
        if ($this->db->single()) {
            // Append number to make slug unique
            $i = 1;
            do {
                $newSlug = $slug . '-' . $i;
                $this->db->query('SELECT id FROM products WHERE slug = :slug AND id != :id');
                $this->db->bind(':slug', $newSlug);
                $this->db->bind(':id', $id ?? 0);
                $i++;
            } while ($this->db->single());
            
            return $newSlug;
        }
        
        return $slug;
    }

    // ... Additional methods for admin functionality ...
} 