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


    public function getOrderDetails($orderId) {
        $this->db->query('
            SELECT *
            FROM fertilizer_orders
            WHERE order_id = 18
        ');

        // $this->db->bind(':order_id', $orderId);
        return $this->db->single();
        
    }
}
