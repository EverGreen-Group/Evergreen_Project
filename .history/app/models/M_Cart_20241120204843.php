<?php
class M_Cart {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function addItem($userId, $productId, $quantity) {
        // Check if item already exists in cart
        $this->db->query('SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        
        $existing = $this->db->single();

        if ($existing) {
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
            SELECT c.*, p.product_name, p.price, p.quantity as stock
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getCartTotal($userId) {
        $this->db->query('
            SELECT SUM(c.quantity * p.price) as total
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->single()->total;
    }

    public function clearCart($userId) {
        $this->db->query('DELETE FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
} 