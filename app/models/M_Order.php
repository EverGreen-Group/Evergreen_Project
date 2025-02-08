<?php

class M_Order {

    private $db;

    public function __construct()
    {
        $this ->db =new Database();
    }
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
}
