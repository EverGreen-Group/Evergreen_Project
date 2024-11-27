<?php
class M_Order {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createOrder($userId, $orderData) {
        $this->db->beginTransaction();

        try {
            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order record
            $this->db->query('
                INSERT INTO orders (
                    user_id, order_number, total_amount, shipping_fee, 
                    tax_amount, grand_total, shipping_address, billing_address,
                    shipping_method, payment_method, payment_status, order_status, notes
                ) VALUES (
                    :user_id, :order_number, :total_amount, :shipping_fee,
                    :tax_amount, :grand_total, :shipping_address, :billing_address,
                    :shipping_method, :payment_method, :payment_status, :order_status, :notes
                )
            ');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':order_number', $orderNumber);
            $this->db->bind(':total_amount', $orderData['total_amount']);
            $this->db->bind(':shipping_fee', $orderData['shipping_fee']);
            $this->db->bind(':tax_amount', $orderData['tax_amount']);
            $this->db->bind(':grand_total', $orderData['grand_total']);
            $this->db->bind(':shipping_address', $orderData['shipping_address']);
            $this->db->bind(':billing_address', $orderData['billing_address'] ?? $orderData['shipping_address']);
            $this->db->bind(':shipping_method', $orderData['shipping_method']);
            $this->db->bind(':payment_method', $orderData['payment_method']);
            $this->db->bind(':payment_status', 'pending');
            $this->db->bind(':order_status', 'pending');
            $this->db->bind(':notes', $orderData['notes'] ?? null);

            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Create order items
            foreach ($orderData['items'] as $item) {
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
                   GROUP_CONCAT(oi.product_id) as product_ids,
                   GROUP_CONCAT(oi.quantity) as quantities,
                   GROUP_CONCAT(oi.price) as prices,
                   GROUP_CONCAT(p.product_name) as product_names,
                   GROUP_CONCAT(p.image) as product_images
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = :user_id
            GROUP BY o.id
            ORDER BY o.created_at DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
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
}

    // ... More methods to follow in the next part ...
