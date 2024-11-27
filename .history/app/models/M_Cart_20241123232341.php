<?php
class M_Cart {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getCartItems($userId) {
        $this->db->query('
            SELECT 
                c.id as cart_id,
                c.product_id,
                c.quantity,
                p.product_name,
                p.price,
                p.quantity as stock,
                COALESCE(pi.image, "default.jpg") as product_image
            FROM cart c
            JOIN products p ON c.product_id = p.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE c.user_id = :user_id
            ORDER BY c.created_at DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function addToCart($userId, $productId, $quantity = 1) {
        // Check if product already exists in cart
        $this->db->query('SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $existingItem = $this->db->single();

        if ($existingItem) {
            // Update quantity
            $this->db->query('UPDATE cart SET quantity = quantity + :quantity WHERE user_id = :user_id AND product_id = :product_id');
        } else {
            // Add new item
            $this->db->query('INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, :quantity)');
        }

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }

    public function updateCartItem($userId, $productId, $quantity) {
        // Validate quantity and stock
        if (!$this->checkProductAvailability($productId, $quantity)) {
            return false;
        }

        $this->db->query('
            UPDATE cart 
            SET quantity = :quantity, updated_at = NOW()
            WHERE user_id = :user_id AND product_id = :product_id
        ');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }

    public function removeFromCart($userId, $productId) {
        $this->db->query('
            DELETE FROM cart 
            WHERE user_id = :user_id AND product_id = :product_id
        ');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);

        return $this->db->execute();
    }

    public function clearCart($userId) {
        $this->db->query('DELETE FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function getCartCount($userId) {
        $this->db->query('
            SELECT COUNT(*) as count 
            FROM cart 
            WHERE user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->count;
    }

    public function getCartTotal($userId) {
        $this->db->query('
            SELECT SUM(c.quantity * p.price) as total
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    private function checkProductAvailability($productId, $requestedQuantity) {
        $this->db->query('
            SELECT quantity 
            FROM products 
            WHERE id = :product_id AND status = "active"
        ');
        $this->db->bind(':product_id', $productId);
        $product = $this->db->single();

        return $product && $product->quantity >= $requestedQuantity;
    }

    public function getShippingMethods() {
        $this->db->query('
            SELECT * FROM shipping_methods 
            WHERE status = "active" 
            ORDER BY price ASC
        ');
        return $this->db->resultSet();
    }
} 