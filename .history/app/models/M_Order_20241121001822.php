<?php
class M_Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createOrder($userId, $orderData) {
        $this->db->beginTransaction();

        try {
            // Get cart total
            $cartModel = new M_Cart();
            $cartItems = $cartModel->getCartItems($userId);
            $subtotal = $cartModel->getCartTotal($userId);

            // Calculate shipping and tax
            $shippingFee = $this->getShippingFee($orderData['shipping_method']);
            $taxAmount = $this->calculateTax($subtotal);
            $grandTotal = $subtotal + $shippingFee + $taxAmount;

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $this->db->query('
                INSERT INTO orders (
                    user_id, order_number, total_amount, shipping_fee, 
                    tax_amount, grand_total, shipping_address, billing_address,
                    shipping_method, payment_method, notes
                ) VALUES (
                    :user_id, :order_number, :total_amount, :shipping_fee,
                    :tax_amount, :grand_total, :shipping_address, :billing_address,
                    :shipping_method, :payment_method, :notes
                )
            ');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':order_number', $orderNumber);
            $this->db->bind(':total_amount', $subtotal);
            $this->db->bind(':shipping_fee', $shippingFee);
            $this->db->bind(':tax_amount', $taxAmount);
            $this->db->bind(':grand_total', $grandTotal);
            $this->db->bind(':shipping_address', $orderData['shipping_address']);
            $this->db->bind(':billing_address', $orderData['shipping_address']); // Using same address for now
            $this->db->bind(':shipping_method', $orderData['shipping_method']);
            $this->db->bind(':payment_method', $orderData['payment_method']);
            $this->db->bind(':notes', $orderData['notes']);

            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Create order items and update stock
            foreach ($cartItems as $item) {
                $this->db->query('
                    INSERT INTO order_items (
                        order_id, product_id, quantity, price, subtotal
                    ) VALUES (
                        :order_id, :product_id, :quantity, :price, :subtotal
                    )
                ');

                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':price', $item->price);
                $this->db->bind(':subtotal', $item->price * $item->quantity);
                $this->db->execute();

                // Update product stock
                $this->db->query('
                    UPDATE products 
                    SET quantity = quantity - :quantity
                    WHERE id = :product_id
                ');
                $this->db->bind(':product_id', $item->product_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->execute();
            }

            // Create initial tracking record
            $this->db->query('
                INSERT INTO order_tracking (order_id, status, comment)
                VALUES (:order_id, :status, :comment)
            ');
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':status', 'pending');
            $this->db->bind(':comment', 'Order placed successfully');
            $this->db->execute();

            $this->db->commit();
            return $orderId;

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