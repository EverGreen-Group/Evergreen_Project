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
}
