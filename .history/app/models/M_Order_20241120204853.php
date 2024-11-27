<?php
class M_Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createOrderFromCart($userId, $shippingAddress) {
        $this->db->beginTransaction();

        try {
            // Get cart total
            $cartTotal = (new M_Cart())->getCartTotal($userId);

            // Create order
            $this->db->query('INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (:user_id, :total, :address)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':total', $cartTotal);
            $this->db->bind(':address', $shippingAddress);
            $this->db->execute();

            $orderId = $this->db->lastInsertId();

            // Get cart items
            $cartItems = (new M_Cart())->getCartItems($userId);

            // Create order items and update stock
            foreach ($cartItems as $item) {
                $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':price', $item->price);
                $this->db->execute();

                // Update product stock
                (new M_Product())->updateStock($item->product_id, $item->quantity);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getUserOrders($userId) {
        $this->db->query('
            SELECT o.*, oi.quantity, p.product_name
            FROM orders o
            JOIN order_items oi ON o.id = oi.order_id
            JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
} 