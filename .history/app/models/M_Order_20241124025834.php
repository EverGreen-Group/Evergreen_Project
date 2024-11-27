<?php
class M_Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createOrder($userId, $total, $paymentId = null) {
        try {
            $this->db->beginTransaction();

            // Create order
            $this->db->query('INSERT INTO orders (user_id, total_amount, payment_id, status) VALUES (:user_id, :total, :payment_id, :status)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':total', $total);
            $this->db->bind(':payment_id', $paymentId);
            $this->db->bind(':status', 'pending');
            $this->db->execute();

            $orderId = $this->db->lastInsertId();

            // Get cart items
            $cartModel = new M_Cart();
            $cartItems = $cartModel->getCartItems($userId);

            // Create order items
            foreach ($cartItems as $item) {
                $this->db->query('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':price', $item->price);
                $this->db->execute();
            }

            // Clear cart
            $cartModel->clearCart($userId);

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function getOrder($orderId) {
        $this->db->query('
            SELECT o.*, u.name as customer_name 
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }

    public function getOrderItems($orderId) {
        $this->db->query('
            SELECT oi.*, p.product_name
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }
}
