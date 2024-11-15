<?php
class M_Fertilizer_Order {
    private $db;

    public function __construct() {
        // Initialize the database connection
        $this->db = new Database();
    }

    // Get all fertilizer orders
    public function getOrders() {
        $this->db->query('SELECT * FROM fertilizer_orders');
        return $this->db->resultSet();
    }

    // Add a new fertilizer order
    public function createOrder($data) {
        $this->db->query('INSERT INTO fertilizer_orders (supplier_id, amount, address, email, phone) VALUES (:supplier_id, :amount, :address, :email, :phone)');
        
        // Bind the values to the query
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);

        // Execute the query and return true if successful
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    // Add more methods for interacting with the fertilizer orders as needed
}
?>
