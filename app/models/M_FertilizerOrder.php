<?php
class M_FertilizerOrder {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Fetch fertilizer types where quantity > fertilizer_lower_limit
    public function getAvailableFertilizerTypes() {
        // Fetch the fertilizer_lower_limit from inventory_config (assuming one row)
        $this->db->query('SELECT fertilizer_lower_limit FROM inventory_config LIMIT 1');
        $config = $this->db->single();
        $lower_limit = $config ? $config->fertilizer_lower_limit : 0;

        // Fetch fertilizers where quantity > lower_limit
        $this->db->query('
            SELECT id, fertilizer_name AS name, price, quantity, unit
            FROM fertilizer
            WHERE quantity > :lower_limit
        ');
        $this->db->bind(':lower_limit', $lower_limit);
        return $this->db->resultSet();
    }

    // Fetch fertilizer by type ID (for validation)
    public function getFertilizerByTypeId($type_id) {
        $this->db->query('
            SELECT id AS type_id, fertilizer_name AS name, price, quantity, unit
            FROM fertilizer
            WHERE id = :type_id
        ');
        $this->db->bind(':type_id', $type_id);
        return $this->db->single();
    }

    public function getOrdersBySupplier($supplier_id) {
        $this->db->query('
            SELECT fo.*, f.fertilizer_name
            FROM fertilizer_orders fo
            JOIN fertilizer f ON fo.fertilizer_id = f.id
            WHERE fo.supplier_id = :supplier_id
            ORDER BY fo.order_date DESC, fo.order_time DESC
        ');
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultSet();
    }

    // Fetch a single order by ID
    public function getOrderById($order_id) {
        $this->db->query('
            SELECT fo.*, f.fertilizer_name
            FROM fertilizer_orders fo
            JOIN fertilizer f ON fo.fertilizer_id = f.id
            WHERE fo.order_id = :order_id
        ');
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    // Create a new fertilizer order with default "Pending" status
    public function createOrder($data) {
        $this->db->query('
            INSERT INTO fertilizer_orders (supplier_id, order_date, order_time, status, total_amount, payment_status, fertilizer_id, quantity)
            VALUES (:supplier_id, :order_date, :order_time, :status, :total_amount, :payment_status, :fertilizer_id, :quantity)
        ');

        // Bind values to query
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':order_date', date('Y-m-d'));
        $this->db->bind(':order_time', date('H:i:s'));
        $this->db->bind(':status', 'Pending'); // Default status
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':payment_status', 'pending'); // Default payment status
        $this->db->bind(':fertilizer_id', $data['type_id']);
        $this->db->bind(':quantity', $data['quantity']);

        // Execute
        return $this->db->execute();
    }

    // Update an existing order
    public function updateOrder($order_id, $data) {
        $this->db->query('
            UPDATE fertilizer_orders
            SET fertilizer_id = :type_id,
                quantity = :quantity,
                total_amount = :total_amount
            WHERE order_id = :order_id
        ');

        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':order_id', $order_id);

        return $this->db->execute();
    }

    // Delete an order
    public function deleteFertilizerOrder($order_id) {
        $this->db->query('DELETE FROM fertilizer_orders WHERE order_id = :order_id');
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }

    // Update order with delivery date and change status to "Placed"
    public function updateOrderWithDeliveryDate($order_id, $delivery_date, $status = 'Placed') {
        $this->db->query('
            UPDATE fertilizer_orders
            SET delivery_date = :delivery_date, status = :status
            WHERE order_id = :order_id
        ');
        $this->db->bind(':delivery_date', $delivery_date);
        $this->db->bind(':status', $status);
        $this->db->bind(':order_id', $order_id);

        return $this->db->execute();
    }
}