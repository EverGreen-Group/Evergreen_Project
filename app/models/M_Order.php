<?php

class Order {
    // Function to create an order
    public function create($product, $quantity) {
        $db = Database::connect();
        $query = $db->prepare("INSERT INTO orders (product, quantity) VALUES (?, ?)");
        $query->execute([$product, $quantity]);
    }

    // Function to get all orders
    public function getAll() {
        $db = Database::connect();
        $query = $db->query("SELECT * FROM orders");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getProductDetails($productId) {
        $this->db->query('
            SELECT fot.item_id, fot.order_id, fot.fertilizer_type_id,fot.quantity,fot.supplier_id, CONCAT(u.first_name, " ", u.last_name) AS `supplier_name`, fot.route_id, fot.is_schedule, fo.order_date, fo.order_time
            FROM fertilizer_order_items fot
            JOIN fertilizer_orders fo ON fot.order_id = fo.order_id
            JOIN suppliers s ON s.supplier_id = fot.supplier_id
            JOIN users u ON s.user_id = u.user_id
            WHERE fot.order_id = 21
        ');

        // $this->db->bind(':order_id', $orderId);
        return $this->db->single();
        
    }

    public function getOrderByNumber($orderNumber, $userId) {
        $this->db->query('
            SELECT o.*, 
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            WHERE o.order_number = :order_number 
            AND o.user_id = :user_id
        ');
        
        $this->db->bind(':order_number', $orderNumber);
        $this->db->bind(':user_id', $userId);
        
        $order = $this->db->single();
        
        if ($order) {
            $order->items = $this->getOrderItems($order->id);
            $order->tracking = $this->getOrderTracking($order->id);
        }
        
        return $order;
    }

    public function getUserActiveOrders($userId) {
        $this->db->query('
            SELECT o.*, 
                   (SELECT COUNT(*) FROM order_items WHERE order_id = o.id) as items_count
            FROM orders o
            WHERE o.user_id = :user_id
            AND o.order_status IN ("processing", "shipped", "out_for_delivery")
            ORDER BY o.created_at DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        $orders = $this->db->resultSet();
        
        foreach ($orders as $order) {
            $order->items = $this->getOrderItems($order->id);
            $order->tracking = $this->getOrderTracking($order->id);
        }
        
        return $orders;
    }
}

