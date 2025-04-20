<?php
class M_FertilizerOrder {
    private $db;

    public function __construct() {
       
        $this->db = new Database();
    }

    // new fertilizer order
    public function createOrder($data) {
        $this->db->query('INSERT INTO fertilizer_orders (supplier_id, total_amount, notes) VALUES (:supplier_id, :total_amount, :notes)');
        
        // Bind values to query
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':notes', $data['notes']);

        // Execute 
        if ($this->db->execute()) {
            return true;
        }
        return false;
    }

}
?>
