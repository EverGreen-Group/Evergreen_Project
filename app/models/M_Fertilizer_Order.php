<?php
class M_Fertilizer_Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllOrders() {
        $this->db->query("SELECT * FROM fertilizer_orders");
        return $this->db->resultset();
    }

    public function getOrderById($order_id) {
        $this->db->query("SELECT * FROM fertilizer_orders WHERE order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    public function getOrdersBySupplier($supplier_id) {
        $this->db->query("SELECT * FROM fertilizer_orders WHERE supplier_id = :supplier_id");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultset();
    }

    public function createOrder($data) {
        $this->db->query("INSERT INTO fertilizer_orders (supplier_id, total_amount, notes) 
                         VALUES (:supplier_id, :total_amount, :notes)");
        
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    public function updateStatus($order_id, $status) {
        $this->db->query("UPDATE fertilizer_orders SET status = :status WHERE order_id = :order_id");
        $this->db->bind(':status', $status);
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }

    public function updatePaymentStatus($order_id, $payment_status) {
        $this->db->query("UPDATE fertilizer_orders SET payment_status = :payment_status WHERE order_id = :order_id");
        $this->db->bind(':payment_status', $payment_status);
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }
}
?> 