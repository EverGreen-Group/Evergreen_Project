<?php
class M_Fertilizer_Order {
    private $db;
    private $error = null;

    public function __construct() {
        $this->db = new Database();
    }

    public function getError() {
        return $this->error;
    }

    public function getAllOrders() {
        $this->db->query("SELECT * FROM fertilizer_orders ORDER BY order_date DESC, order_time DESC LIMIT 10");
        return $this->db->resultSet();
    }

    public function getOrderById($order_id) {
        $this->db->query("
            SELECT 
                fo.order_id, 
                fo.supplier_id, 
                fo.type_id, 
                fo.unit_type AS unit, 
                fo.quantity, 
                fo.total_amount AS total_price, 
                fo.status, 
                fo.payment_status, 
                fo.created_at, 
                fo.delivery_date, 
                ft.name AS fertilizer_name, 
                ft.unit_price_kg, 
                ft.unit_price_packs, 
                ft.unit_price_box,
                CASE 
                    WHEN fo.unit_type = 'kg' THEN ft.unit_price_kg
                    WHEN fo.unit_type = 'packs' THEN ft.unit_price_packs
                    WHEN fo.unit_type = 'box' THEN ft.unit_price_box
                    ELSE 0
                END AS price_per_unit
            FROM fertilizer_orders fo
            JOIN fertilizer_types ft ON fo.type_id = ft.type_id
            WHERE fo.order_id = :order_id
        ");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    public function getOrdersBySupplier($supplier_id) {
        $this->db->query("
            SELECT fo.order_id, fo.type_id, fo.unit_type, fo.quantity, fo.total_amount, fo.status, fo.payment_status, ft.name, fo.created_at, fo.delivery_date
            FROM fertilizer_orders fo
            JOIN fertilizer_types ft ON fo.type_id = ft.type_id
            WHERE fo.supplier_id = :supplier_id
            ORDER BY fo.order_id DESC
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultSet();
    }

    public function createOrder($data) {
        $this->db->query("
            INSERT INTO fertilizer_orders (supplier_id, type_id, unit_type, quantity, total_amount, status, payment_status)
            VALUES (:supplier_id, :type_id, :unit_type, :quantity, :total_amount, 'Pending', 'pending')
        ");
        $this->db->bind(':supplier_id', $data['supplier_id']);
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':unit_type', $data['unit_type']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':total_amount', $data['total_amount']);
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }

    public function updateOrder($data) {
        $this->db->query("
            UPDATE fertilizer_orders 
            SET type_id = :type_id, unit_type = :unit_type, quantity = :quantity, total_amount = :total_amount 
            WHERE order_id = :order_id
        ");
        $this->db->bind(':type_id', $data['type_id']);
        $this->db->bind(':unit_type', $data['unit_type']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':order_id', $data['order_id']);
        return $this->db->execute();
    }

    public function deleteOrder($order_id) {
        try {
            $this->db->query('DELETE FROM fertilizer_orders WHERE order_id = :order_id');
            $this->db->bind(':order_id', $order_id);
            return $this->db->execute() ?: throw new Exception('Failed to delete order');
        } catch (Exception $e) {
            error_log("Error deleting fertilizer order: " . $e->getMessage());
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function getAvailableFertilizerTypes() {
        $this->db->query("SELECT * FROM fertilizer_types WHERE available_quantity > 0");
        return $this->db->resultSet();
    }

    public function getFertilizerByTypeId($type_id) {
        $this->db->query("SELECT * FROM fertilizer_types WHERE type_id = :type_id");
        $this->db->bind(':type_id', $type_id);
        return $this->db->single();
    }

    public function getFertilizerTypes() {
        $this->db->query("
            SELECT type_id, name, unit_price_kg, unit_price_packs, unit_price_box, available_quantity
            FROM fertilizer_types
        ");
        return $this->db->resultSet();
    }

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

    public function addAcceptedOrderToCart($order_id) {
        $order = $this->getOrderById($order_id);
        if ($order && $order->status === 'Accepted') {
            $this->db->query("
                SELECT unit_price_kg, unit_price_packs, unit_price_box
                FROM fertilizer_types
                WHERE type_id = :type_id
            ");
            $this->db->bind(':type_id', $order->type_id);
            $fertilizer = $this->db->single();

            $price = 0;
            if ($order->unit_type === 'kg') $price = $fertilizer->unit_price_kg;
            elseif ($order->unit_type === 'packs') $price = $fertilizer->unit_price_packs;
            elseif ($order->unit_type === 'box') $price = $fertilizer->unit_price_box;

            $this->db->query("
                INSERT INTO cart (supplier_id, fertilizer_id, unit_type, quantity, price)
                VALUES (:supplier_id, :fertilizer_id, :unit_type, :quantity, :price)
                ON DUPLICATE KEY UPDATE quantity = quantity + :quantity
            ");
            $this->db->bind(':supplier_id', $order->supplier_id);
            $this->db->bind(':fertilizer_id', $order->type_id);
            $this->db->bind(':unit_type', $order->unit_type);
            $this->db->bind(':quantity', $order->quantity);
            $this->db->bind(':price', $price);
            return $this->db->execute();
        }
        return false;
    }

    public function getCartItems($supplier_id) {
        $this->db->query("
            SELECT c.cart_id, c.supplier_id, c.fertilizer_id, c.unit_type, c.quantity, c.price, ft.name, ft.unit_price_kg, ft.unit_price_packs, ft.unit_price_box
            FROM cart c
            JOIN fertilizer_types ft ON c.fertilizer_id = ft.type_id
            WHERE c.supplier_id = :supplier_id
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultSet();
    }

    public function addToCart($supplier_id, $fertilizer_id, $unit_type, $quantity, $price) {
        try {
            $this->db->query("
                INSERT INTO cart (supplier_id, fertilizer_id, unit_type, quantity, price)
                VALUES (:supplier_id, :fertilizer_id, :unit_type, :quantity, :price)
                ON DUPLICATE KEY UPDATE quantity = quantity + :quantity
            ");
            $this->db->bind(':supplier_id', $supplier_id);
            $this->db->bind(':fertilizer_id', $fertilizer_id);
            $this->db->bind(':unit_type', $unit_type);
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':price', $price);
            return $this->db->execute();
        } catch (Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function removeFromCart($cart_id) {
        $this->db->query("DELETE FROM cart WHERE cart_id = :cart_id");
        $this->db->bind(':cart_id', $cart_id);
        return $this->db->execute();
    }

    public function clearCart($supplier_id) {
        $this->db->query("DELETE FROM cart WHERE supplier_id = :supplier_id");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->execute();
    }

    public function updateCartItem($cart_id, $quantity) {
        $this->db->query("UPDATE cart SET quantity = :quantity WHERE cart_id = :cart_id");
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':cart_id', $cart_id);
        return $this->db->execute();
    }

    public function getCartTotal($supplier_id) {
        $this->db->query("
            SELECT SUM(c.quantity * c.price) as total
            FROM cart c
            WHERE c.supplier_id = :supplier_id
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function createFinalOrder($supplier_id, $orderData) {
        $this->db->query("
            INSERT INTO final_orders (supplier_id, total_amount, shipping_fee, tax_amount, grand_total, delivery_date, shipping_address)
            VALUES (:supplier_id, :total_amount, :shipping_fee, :tax_amount, :grand_total, :delivery_date, :shipping_address)
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        $this->db->bind(':total_amount', $orderData['total_amount']);
        $this->db->bind(':shipping_fee', $orderData['shipping_fee']);
        $this->db->bind(':tax_amount', $orderData['tax_amount']);
        $this->db->bind(':grand_total', $orderData['grand_total']);
        $this->db->bind(':delivery_date', $orderData['delivery_date']);
        $this->db->bind(':shipping_address', $orderData['shipping_address']);

        if ($this->db->execute()) {
            $order_id = $this->db->lastInsertId();
            foreach ($orderData['items'] as $item) {
                $this->db->query("
                    INSERT INTO final_order_items (order_id, fertilizer_id, unit_type, quantity, price)
                    VALUES (:order_id, :fertilizer_id, :unit_type, :quantity, :price)
                ");
                $this->db->bind(':order_id', $order_id);
                $this->db->bind(':fertilizer_id', $item['fertilizer_id']);
                $this->db->bind(':unit_type', $item['unit_type']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':price', $item['price']);
                $this->db->execute();
            }
            return $order_id;
        }
        return false;
    }

    public function getOrderByDetails($supplier_id, $type_id, $quantity, $unit_type) {
        $this->db->query("
            SELECT * FROM fertilizer_orders
            WHERE supplier_id = :supplier_id AND type_id = :type_id AND quantity = :quantity AND unit_type = :unit_type AND status = 'Accepted'
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        $this->db->bind(':type_id', $type_id);
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':unit_type', $unit_type);
        return $this->db->single();
    }

    public function updateOrderStatus($order_id, $status) {
        $this->db->query("UPDATE fertilizer_orders SET status = :status WHERE order_id = :order_id");
        $this->db->bind(':status', $status);
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }

    public function checkout($supplier_id) {
        $this->db->query("
            SELECT fertilizer_id, unit_type, quantity, price 
            FROM cart 
            WHERE supplier_id = :supplier_id
        ");
        $this->db->bind(':supplier_id', $supplier_id);
        $cart_items = $this->db->resultSet();

        if (empty($cart_items)) return true;

        $this->db->query("
            INSERT INTO fertilizer_orders (supplier_id, type_id, unit_type, quantity, total_amount, status, payment_status, created_at) 
            VALUES (:supplier_id, :type_id, :unit_type, :quantity, :total_amount, 'Pending', 'pending', NOW())
        ");

        $success = true;
        foreach ($cart_items as $item) {
            $total_amount = $item->quantity * $item->price;
            $this->db->bind(':supplier_id', $supplier_id);
            $this->db->bind(':type_id', $item->fertilizer_id);
            $this->db->bind(':unit_type', $item->unit_type);
            $this->db->bind(':quantity', $item->quantity);
            $this->db->bind(':total_amount', $total_amount);

            if (!$this->db->execute()) {
                $success = false;
                break;
            }
        }

        if ($success) {
            $this->clearCart($supplier_id);
        }

        return $success;
    }

    public function getSupplierApplication($supplier_id) {
        $this->db->query("SELECT * FROM supplier_applications WHERE user_id = :user_id AND status = 'approved' LIMIT 1");
        $this->db->bind(':user_id', $supplier_id);
        return $this->db->single();
    }
}