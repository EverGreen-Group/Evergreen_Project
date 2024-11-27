<?php
class M_Cart {
    private $db;

    public function __construct() {
        $this->db = new Database;
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

    public function getCartItems($userId) {
        $this->db->query('
            SELECT c.*, p.name, p.price, p.stock,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as image
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function updateQuantity($userId, $productId, $quantity) {
        $this->db->query('UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);
        return $this->db->execute();
    }

    public function removeItem($userId, $productId) {
        $this->db->query('DELETE FROM cart WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        return $this->db->execute();
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

    public function getCartCount($userId) {
        $this->db->query('SELECT COUNT(*) as count FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->count ?? 0;
    }
} 