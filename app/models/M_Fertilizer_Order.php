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

    public function getAllOrders() {                       // bug free function
        $this->db->query("SELECT fo.*, f.description as fertilizer_name, f.fertilizer_id
                          FROM fertilizer_orders fo
                          JOIN Fertilizer f ON fo.fertilizer_id = f.type_id 
                          ORDER BY order_date DESC, order_time DESC LIMIT 10");
        return $this->db->resultset();
    }

    public function getOrderById($order_id) {                       // bug free function
        $this->db->query("SELECT fo.*, f.fertilizer_name 
                          FROM fertilizer_orders fo 
                          JOIN Fertilizer f ON fo.fertilizer_id = f.id 
                          WHERE fo.order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    public function getOrdersBySupplier($supplier_id) {                       // bug free function
        $this->db->query("SELECT fo.*, f.fertilizer_name 
                          FROM fertilizer_orders fo
                          JOIN Fertilizer f ON fo.fertilizer_id = f.id 
                          WHERE fo.supplier_id = :supplier_id
                          ORDER BY order_date DESC, order_time DESC LIMIT 10");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultSet();
    }


    // private $error = null;
    // public function getError() {                       // bug free function
    //     return $this->error;
    // }


    public function createOrder($data) {                       // bug free function
        try {
            // Start transaction
            $this->db->beginTransaction();
    
            // Validate data
            if (empty($data['supplier_id']) || empty($data['fertilizer_id']) || empty($data['quantity'])) {
                throw new Exception('Missing required fields');
            }
    
            // Validate amount
            if ($data['quantity'] <= 0 || $data['quantity'] > 100) {
                throw new Exception('Invalid amount. Must be between 1 and 100');
            }
    
            // Get current date and time
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
            
            $this->db->query(
                "INSERT INTO fertilizer_orders 
                (supplier_id, order_date, order_time, total_amount, fertilizer_id, quantity, status, payment_status)
                VALUES 
                (:supplier_id, :order_date, :order_time, :total_amount, :fertilizer_id, :quantity, :status, :payment_status)"
            );
            
            $this->db->bind(':supplier_id', $data['supplier_id']);
            $this->db->bind(':order_date', $currentDate);
            $this->db->bind(':order_time', $currentTime);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':fertilizer_id', $data['fertilizer_id']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':payment_status', $data['payment_status']);
    
            $result = $this->db->execute();
            
            if (!$result) {
                throw new Exception('Failed to insert order');
            }
    
            // Commit transaction
            $this->db->commit();
            return true;
    
        } catch (Exception $e) {
            error_log("Error deleting fertilizer order: " . $e->getMessage());
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function updateOrder($order_id, $data) {
        
            $this->db->query(
                "UPDATE fertilizer_orders 
                SET type_id = :type_id,
                    fertilizer_name = :fertilizer_name,
                    total_amount = :total_amount,
                    unit = :unit,
                    price_per_unit = :price_per_unit,
                    total_price = :total_price,
                    order_time = :last_modified
                WHERE order_id = :order_id "
                //AND status != 'accepted' 
                //AND status != 'completed'
                //AND payment_status != 'paid'
            );
    
            $this->db->bind(':order_id', $order_id);
            $this->db->bind(':type_id', $data['type_id']);
            $this->db->bind(':fertilizer_name', $data['fertilizer_name']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':unit', $data['unit']);
            $this->db->bind(':price_per_unit', $data['price_per_unit']);
            $this->db->bind(':total_price', $data['total_price']);
            $this->db->bind(':last_modified', $data['last_modified']);
    
            return $this->db->execute();
        }

    public function getFertilizerOrderById($orderId) {
        $this->db->query('SELECT fo.*, ft.name as fertilizer_name 
                          FROM fertilizer_orders fo 
                          LEFT JOIN fertilizer_types ft ON fo.type_id = ft.type_id 
                          WHERE fo.order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }

    public function deleteFertilizerOrder($orderId) {
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
        $this->db->query("SELECT type_id, name,description,recommended_usage, unit_price_kg, unit_price_packs, unit_price_box FROM fertilizer_types");
        return $this->db->resultset();
    }

    public function getFertilizerPrice($type_id) {
        $this->db->query('SELECT price_per_unit FROM fertilizer_types WHERE type_id = :type_id');
        $this->db->bind(':type_id', $type_id);
        return $this->db->single();
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

    
    public function getfertilizerorderforInventory(){
        $this->db->query("SELECT fo.order_id, pr.first_name, sup.address, fo.order_date,fo.status FROM fertilizer_orders fo LEFT JOIN suppliers sup ON fo.supplier_id=sup.supplier_id JOIN profiles pr ON sup.profile_id = pr.profile_id
        ");
        return $this->db->resultset();
    }

    public function updateFertilizerByStatus($id, $status) {
        $this->db->query("UPDATE fertilizer_orders SET status = :status WHERE order_id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}
    
    
    

?> 