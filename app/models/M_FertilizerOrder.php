<?php
class M_FertilizerOrder {
    private $db;

    public function __construct() {
        // Initialize the database connection
        $this->db = new Database();
    }

    // Add a new fertilizer order
    public function createOrder($data) {
        $this->db->query('INSERT INTO fertilizer_orders (supplier_id, total_amount, notes) VALUES (:supplier_id, :total_amount, :notes)');
        
        // Bind the values to the query
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':notes', $data['notes']);

        // Execute the query and return true if successful
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

    // Add more methods for interacting with the fertilizer orders as needed
}
?>
