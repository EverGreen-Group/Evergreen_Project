<?php
//M_Order.php
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

    public function createOrder($data) {
        try {
            $this->db->beginTransaction();

            // Insert order with shipping address
            $this->db->query('
                INSERT INTO orders (
                    user_id, 
                    subtotal, 
                    shipping_fee, 
                    tax, 
                    total_amount,
                    shipping_address,
                    status,
                    created_at
                ) VALUES (
                    :user_id, 
                    :subtotal, 
                    :shipping, 
                    :tax, 
                    :total,
                    :shipping_address,
                    "pending",
                    NOW()
                )
            ');

            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':subtotal', $data['subtotal']);
            $this->db->bind(':shipping', $data['shipping']);
            $this->db->bind(':tax', $data['tax']);
            $this->db->bind(':total', $data['total']);
            $this->db->bind(':shipping_address', json_encode($data['shipping_address']));

            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Create payment intent
            if ($data['payment_method'] === 'stripe') {
                $paymentIntent = $this->createPaymentIntent($orderId, $data['total']);
                if (!$paymentIntent) {
                    throw new Exception('Payment intent creation failed');
                }
            }

            // Create order items
            foreach ($data['items'] as $item) {
                $this->db->query('
                    INSERT INTO order_items (
                        order_id, product_id, quantity, price, subtotal
                    ) VALUES (
                        :order_id, :product_id, :quantity, :price, :subtotal
                    )
                ');

                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':price', $item['price']);
                $this->db->bind(':subtotal', $item['quantity'] * $item['price']);

                $this->db->execute();

                // Update product stock
                $this->db->query('
                    UPDATE products 
                    SET quantity = quantity - :quantity
                    WHERE id = :product_id
                ');
                
                $this->db->bind(':product_id', $item['product_id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->execute();
            }

            // Create initial tracking record
            $this->createTrackingRecord($orderId, 'pending', 'Order placed successfully');

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Order creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function getUserOrders($userId) {
        $this->db->query('
            SELECT o.*, 
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            WHERE o.user_id = :user_id
            ORDER BY o.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        $orders = $this->db->resultSet();

        // Get items and tracking for each order
        foreach ($orders as $order) {
            $order->items = $this->getOrderItems($order->id);
            $order->tracking = $this->getOrderTracking($order->id);
        }

        return $orders;
    }

    public function getOrderDetails($orderId) {
        // Get order info
        $this->db->query('
            SELECT o.*, u.first_name, u.last_name, u.email, u.phone
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            WHERE o.id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        $order = $this->db->single();

        if ($order) {
            $order->items = $this->getOrderItems($orderId);
            $order->tracking = $this->getOrderTracking($orderId);
        }

        return $order;
    }

    private function getOrderItems($orderId) {
        $this->db->query('
            SELECT oi.*, p.product_name, p.sku,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_image
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    private function getOrderTracking($orderId) {
        $this->db->query('
            SELECT * FROM order_tracking
            WHERE order_id = :order_id
            ORDER BY created_at DESC
        ');
        $this->db->bind(':order_id', $orderId);
        return $this->db->resultSet();
    }

    public function updateOrderStatus($orderId, $status, $comment = '') {
        $this->db->beginTransaction();

        try {
            // Update order status
            $this->db->query('
                UPDATE orders 
                SET order_status = :status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :order_id
            ');
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':status', $status);
            $this->db->execute();

            // Add tracking record
            $this->createTrackingRecord($orderId, $status, $comment);

            // If order is cancelled, restore product quantities
            if ($status === 'cancelled') {
                $this->restoreProductQuantities($orderId);
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Order status update failed: " . $e->getMessage());
            return false;
        }
    }

    private function restoreProductQuantities($orderId) {
        $this->db->query('
            SELECT product_id, quantity FROM order_items
            WHERE order_id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        $items = $this->db->resultSet();

        foreach ($items as $item) {
            $this->db->query('
                UPDATE products 
                SET quantity = quantity + :quantity
                WHERE id = :product_id
            ');
            $this->db->bind(':product_id', $item->product_id);
            $this->db->bind(':quantity', $item->quantity);
            $this->db->execute();
        }
    }

    public function updatePaymentStatus($orderId, $status) {
        $this->db->query('
            UPDATE orders 
            SET payment_status = :status,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    private function createTrackingRecord($orderId, $status, $comment) {
        $this->db->query('
            INSERT INTO order_tracking (order_id, status, comment)
            VALUES (:order_id, :status, :comment)
        ');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':status', $status);
        $this->db->bind(':comment', $comment);
        return $this->db->execute();
    }

    private function generateOrderNumber() {
        return 'ORD-' . date('Ymd') . '-' . substr(uniqid(), -5);
    }

    // Admin Methods
    public function getAllOrders($limit = null, $offset = 0) {
        $sql = '
            SELECT o.*, 
                   u.first_name, u.last_name, u.email,
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            JOIN users u ON o.user_id = u.user_id
            ORDER BY o.created_at DESC
        ';

        if ($limit !== null) {
            $sql .= ' LIMIT :limit OFFSET :offset';
        }

        $this->db->query($sql);

        if ($limit !== null) {
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
        }

        return $this->db->resultSet();
    }

    public function getOrderStats() {
        $this->db->query('
            SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN order_status = "pending" THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN order_status = "processing" THEN 1 ELSE 0 END) as processing_orders,
                SUM(CASE WHEN order_status = "shipped" THEN 1 ELSE 0 END) as shipped_orders,
                SUM(CASE WHEN order_status = "delivered" THEN 1 ELSE 0 END) as delivered_orders,
                SUM(CASE WHEN order_status = "cancelled" THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(grand_total) as total_revenue,
                SUM(CASE WHEN payment_status = "paid" THEN grand_total ELSE 0 END) as received_revenue
            FROM orders
            WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
        ');
        return $this->db->single();
    }

    public function getDailyRevenue($days = 30) {
        $this->db->query('
            SELECT 
                DATE(created_at) as date,
                COUNT(*) as orders_count,
                SUM(grand_total) as revenue
            FROM orders
            WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL :days DAY)
            AND order_status != "cancelled"
            GROUP BY DATE(created_at)
            ORDER BY date DESC
        ');
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    public function getPopularProducts($limit = 5) {
        $this->db->query('
            SELECT 
                p.id, p.product_name,
                COUNT(DISTINCT o.id) as order_count,
                SUM(oi.quantity) as total_quantity,
                SUM(oi.subtotal) as total_revenue
            FROM products p
            JOIN order_items oi ON p.id = oi.product_id
            JOIN orders o ON oi.order_id = o.id
            WHERE o.order_status != "cancelled"
            GROUP BY p.id
            ORDER BY total_quantity DESC
            LIMIT :limit
        ');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getTotalOrders() {
        $this->db->query('SELECT COUNT(*) as count FROM orders');
        return $this->db->single()->count;
    }

    public function getTotalRevenue() {
        $this->db->query('
            SELECT COALESCE(SUM(grand_total), 0) as total_revenue 
            FROM orders 
            WHERE order_status = :status 
            AND payment_status = :payment_status
        ');
        
        $this->db->bind(':status', 'completed');
        $this->db->bind(':payment_status', 'paid');
        
        $result = $this->db->single();
        return $result->total_revenue ?? 0;
    }

    public function getPendingOrdersCount() {
        $this->db->query('
            SELECT COUNT(*) as count 
            FROM orders 
            WHERE order_status = "pending"
        ');
        return $this->db->single()->count;
    }

    public function getShippingMethods() {
        $this->db->query('
            SELECT id, name, description, price, status
            FROM shipping_methods
            WHERE status = "active"
            ORDER BY price ASC
        ');
        return $this->db->resultSet();
    }

    public function getOrderById($orderId, $userId) {
        $this->db->query('
            SELECT * FROM orders 
            WHERE id = :order_id AND user_id = :user_id
        ');
        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }

    public function updateOrder($orderId, $userId, $data) {
        // Only allow updating certain fields and only if order is in 'pending' or 'processing' status
        $this->db->query('
            UPDATE orders 
            SET 
                shipping_address = :shipping_address,
                shipping_method = :shipping_method,
                notes = :notes,
                updated_at = NOW()
            WHERE id = :order_id 
            AND user_id = :user_id 
            AND order_status IN ("pending", "processing")
        ');

        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':shipping_address', $data['shipping_address']);
        $this->db->bind(':shipping_method', $data['shipping_method']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    public function cancelOrder($orderId, $userId) {
        // Only allow cancellation of pending orders
        $this->db->query('
            UPDATE orders 
            SET 
                order_status = "cancelled",
                updated_at = NOW()
            WHERE id = :order_id 
            AND user_id = :user_id 
            AND order_status = "pending"
        ');

        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':user_id', $userId);

        return $this->db->execute();
    }

    public function deleteOrder($orderId) {
        // First delete related records in order_items
        $this->db->query('DELETE FROM order_items WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        $this->db->execute();
        
        // Then delete the order
        $this->db->query('DELETE FROM orders WHERE id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->execute();
    }

    public function createPaymentIntent($orderId, $amount) {
        try {
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $orderId
                ]
            ]);

            // Update order with payment intent ID
            $this->db->query('
                UPDATE orders 
                SET payment_intent_id = :payment_intent_id
                WHERE id = :order_id
            ');
            
            $this->db->bind(':payment_intent_id', $paymentIntent->id);
            $this->db->bind(':order_id', $orderId);
            $this->db->execute();

            return [
                'clientSecret' => $paymentIntent->client_secret,
                'paymentIntentId' => $paymentIntent->id
            ];
        } catch (Exception $e) {
            error_log("Payment intent creation failed: " . $e->getMessage());
            return false;
        }
    }

    public function handleStripeWebhook($payload, $sigHeader) {
        $endpointSecret = STRIPE_WEBHOOK_SECRET;
        
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );

            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $paymentIntent = $event->data->object;
                    $this->updateOrderPaymentStatus(
                        $paymentIntent->metadata->order_id, 
                        'paid'
                    );
                    break;
                    
                case 'payment_intent.payment_failed':
                    $paymentIntent = $event->data->object;
                    $this->updateOrderPaymentStatus(
                        $paymentIntent->metadata->order_id, 
                        'failed'
                    );
                    break;
            }

            return true;
        } catch (Exception $e) {
            error_log("Webhook error: " . $e->getMessage());
            return false;
        }
    }

    public function updateOrderPaymentStatus($orderId, $status) {
        $this->db->beginTransaction();

        try {
            // Update payment status
            $this->db->query('
                UPDATE orders 
                SET payment_status = :status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :order_id
            ');
            
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':status', $status);
            $this->db->execute();

            // Add tracking record
            $comment = $status === 'paid' 
                ? 'Payment received successfully' 
                : 'Payment failed';
            
            $this->createTrackingRecord($orderId, $status, $comment);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Payment status update failed: " . $e->getMessage());
            return false;
        }
    }

    public function processRefund($orderId, $amount = null) {
        try {
            // Get payment intent ID from order
            $this->db->query('
                SELECT payment_intent_id, grand_total 
                FROM orders 
                WHERE id = :order_id
            ');
            $this->db->bind(':order_id', $orderId);
            $order = $this->db->single();

            if (!$order->payment_intent_id) {
                throw new Exception('No payment intent found for this order');
            }

            // Process refund through Stripe
            $refund = \Stripe\Refund::create([
                'payment_intent' => $order->payment_intent_id,
                'amount' => $amount ? ($amount * 100) : null // If null, full refund
            ]);

            // Update order status
            $this->db->query('
                UPDATE orders 
                SET payment_status = "refunded",
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :order_id
            ');
            
            $this->db->bind(':order_id', $orderId);
            $this->db->execute();

            // Add tracking record
            $refundAmount = $amount ?? $order->grand_total;
            $this->createTrackingRecord(
                $orderId, 
                'refunded', 
                "Refund processed: $" . number_format($refundAmount, 2)
            );

            return true;
        } catch (Exception $e) {
            error_log("Refund processing failed: " . $e->getMessage());
            return false;
        }
    }
}

    // ... More methods to follow in the next part ...
