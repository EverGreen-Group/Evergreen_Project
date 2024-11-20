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
        $stmt = $this->db->prepare(
            "INSERT INTO fertilizer_orders 
            (type_id, fertilizer_name, total_amount, unit, price_per_unit, total_price, order_date, order_time) 
            VALUES 
            (:type_id, :fertilizer_name, :total_amount, :unit, :price_per_unit, :total_price, CURRENT_DATE, CURRENT_TIME)"
        );
    
        //$stmt->bindValue(':supplier_id', $data['supplier_id'], PDO::PARAM_INT);
        $stmt->bindValue(':type_id', $data['type_id'], PDO::PARAM_INT);
        $stmt->bindValue(':fertilizer_name', $data['fertilizer_name'], PDO::PARAM_STR);
        $stmt->bindValue(':total_amount', $data['total_amount'], PDO::PARAM_STR);
        $stmt->bindValue(':unit', $data['unit'], PDO::PARAM_STR);
        $stmt->bindValue(':price_per_unit', $data['price_per_unit'], PDO::PARAM_STR);
        $stmt->bindValue(':total_price', $data['total_price'], PDO::PARAM_STR);
    
        return $stmt->execute();
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

    public function updateOrder($order_id, $data) {
        $this->db->query("UPDATE fertilizer_orders SET total_amount = :total_amount WHERE order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        $this->db->bind(':total_amount', $data['total_amount']);
        
        return $this->db->execute();
    }
    
    public function deleteOrder($id) {
        $this->db->query("DELETE FROM fertilizer_orders WHERE order_id = :order_id");
        $this->db->bind(':order_id', $id);
    
        return $this->db->execute();
    }
    
    public function getFertilizerByTypeId($type_id) {
        $stmt = $this->db->prepare("SELECT type_id, name, 
            unit_price_kg as price_kg, 
            unit_price_packs as price_packs, 
            unit_price_box as price_box 
            FROM fertilizer_types 
            WHERE type_id = :id");
        $stmt->bindValue(':id', $type_id, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllFertilizerTypes() {
        $this->db->query("SELECT type_id, name, unit_price_kg, unit_price_packs, unit_price_box FROM fertilizer_types");
        return $this->db->resultset();
    }

    public function getFertilizerPrice($type_id) {
        $this->db->query('SELECT price_per_unit FROM fertilizer_types WHERE type_id = :type_id');
        $this->db->bind(':type_id', $type_id);
        return $this->db->single();
    }

    public function getFertilizerName($type_id) {
        $this->db->query('SELECT fertilizer_name FROM fertilizer_types WHERE type_id = :type_id');
        $this->db->bind(':type_id', $type_id);
        return $this->db->single();
    }
    
    
}
?> 