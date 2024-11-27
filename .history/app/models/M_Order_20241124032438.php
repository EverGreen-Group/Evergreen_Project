<?php
class M_Order {
    private $db;
    private $stripeSecretKey;

    public function __construct() {
        $this->db = new Database;
        
        // Initialize Stripe
        $this->stripeSecretKey = STRIPE_SECRET_KEY;
        require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
    }

    public function createOrder($userId, $cartItems) {
        try {
            $this->db->beginTransaction();

            // Calculate totals
            $totalAmount = 0;
            foreach ($cartItems as $item) {
                $totalAmount += $item->price * $item->quantity;
            }

            $shippingFee = 500.00;
            $taxAmount = $totalAmount * 0.10; // 10% tax
            $grandTotal = $totalAmount + $shippingFee + $taxAmount;

            // Generate order number
            $orderNumber = 'ORD-' . date('Y') . '-' . sprintf('%03d', $this->getNextOrderNumber());

            // Create order
            $this->db->query('
                INSERT INTO orders (
                    user_id, order_number, total_amount, shipping_fee, 
                    tax_amount, grand_total, shipping_address, shipping_method, 
                    payment_method, payment_status, order_status
                ) VALUES (
                    :user_id, :order_number, :total_amount, :shipping_fee,
                    :tax_amount, :grand_total, :shipping_address, :shipping_method,
                    :payment_method, :payment_status, :order_status
                )
            ');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':order_number', $orderNumber);
            $this->db->bind(':total_amount', $totalAmount);
            $this->db->bind(':shipping_fee', $shippingFee);
            $this->db->bind(':tax_amount', $taxAmount);
            $this->db->bind(':grand_total', $grandTotal);
            $this->db->bind(':shipping_address', $_SESSION['user_address']); // Assuming address is stored in session
            $this->db->bind(':shipping_method', 'Standard');
            $this->db->bind(':payment_method', 'Credit Card');
            $this->db->bind(':payment_status', 'pending');
            $this->db->bind(':order_status', 'pending');

            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Create order items
            foreach ($cartItems as $item) {
                $this->db->query('
                    INSERT INTO order_items (
                        order_id, product_id, quantity, price
                    ) VALUES (
                        :order_id, :product_id, :quantity, :price
                    )
                ');

                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':price', $item->price);
                $this->db->execute();

                // Update product quantity
                $this->db->query('
                    UPDATE products 
                    SET quantity = quantity - :quantity 
                    WHERE id = :product_id
                ');
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->execute();
            }

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
            SELECT 
                oi.*,
                p.product_name,
                COALESCE(pi.image, "default.jpg") as product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            LEFT JOIN product_images pi ON p.id = pi.product_id AND pi.is_primary = 1
            WHERE oi.order_id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function updateOrderStatus($orderId, $status) {
        $this->db->query('
            UPDATE orders 
            SET order_status = :status, 
                updated_at = NOW() 
            WHERE id = :order_id
        ');
        $this->db->bind(':status', $status);
        $this->db->bind(':order_id', $orderId);
        return $this->db->execute();
    }

    public function cancelOrder($orderId) {
        try {
            $this->db->beginTransaction();

            // Get order items
            $items = $this->getOrderItems($orderId);

            // Restore product quantities
            foreach ($items as $item) {
                $this->db->query('
                    UPDATE products 
                    SET quantity = quantity + :quantity 
                    WHERE id = :product_id
                ');
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->execute();
            }

            // Update order status
            $this->updateOrderStatus($orderId, 'cancelled');

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Order cancellation failed: " . $e->getMessage());
            return false;
        }
    }

    private function getNextOrderNumber() {
        $this->db->query('
            SELECT MAX(CAST(SUBSTRING_INDEX(order_number, "-", -1) AS UNSIGNED)) as max_num 
            FROM orders 
            WHERE order_number LIKE CONCAT("ORD-", YEAR(CURRENT_DATE), "-%")
        ');
        $result = $this->db->single();
        return ($result->max_num ?? 0) + 1;
    }

    public function getUserOrders($userId) {
        $this->db->query('
            SELECT 
                o.*,
                (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as item_count
            FROM orders o
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
}
