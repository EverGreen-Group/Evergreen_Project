<?php
class M_Fertilizer_Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
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
        return $this->db->resultset();
    }


    private $error = null;
    public function getError() {                       // bug free function
        return $this->error;
    }


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
            // Rollback transaction on error
            $this->db->rollBack();
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function updateOrder($order_id, $data) {                       // bug free function
        
            $this->db->query(
                "UPDATE fertilizer_orders 
                 SET fertilizer_id = :fertilizer_id,
                    quantity = :quantity,
                    total_amount = :total_amount,
                    order_time = :last_modified
                 WHERE order_id = :order_id AND status = 'Pending'
            ");
    
            $this->db->bind(':order_id', $order_id);
            $this->db->bind(':fertilizer_id', $data['fertilizer_id']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':last_modified', $data['last_modified']);
    
            return $this->db->execute();
        
    }

    public function getFertilizerOrderById($orderId) {                       // bug free function
        $this->db->query('SELECT fo.*, f.fertilizer_name 
                          FROM fertilizer_orders fo 
                          LEFT JOIN Fertilizer f ON fo.type_id = f.type_id 
                          WHERE fo.order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }

    public function deleteFertilizerOrder($orderId) {                       // bug free function
        try {
            $this->db->query('DELETE FROM fertilizer_orders 
                              WHERE order_id = :order_id');
            $this->db->bind(':order_id', $orderId);
            $result = $this->db->execute();
            
            if (!$result) {
                error_log("Failed to delete fertilizer order: " . print_r($this->db->errorInfo(), true));
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error deleting fertilizer order: " . $e->getMessage());
            return false;
        }
    }

    public function getAllFertilizerTypes() {                       // bug free function
        $this->db->query("SELECT id as fertilizer_id, fertilizer_name, company_name, details, code, price, quantity FROM Fertilizer WHERE quantity > 0");
        return $this->db->resultset();
    }
  

    public function updateStatus($order_id, $status) {                       // bug free function
        $this->db->query("UPDATE fertilizer_orders SET status = :status WHERE order_id = :order_id");
        $this->db->bind(':status', $status);
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }


	//UNUSED FUNCTIONS

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
	
    public function updatePaymentStatus($order_id, $payment_status) {
        $this->db->query("UPDATE fertilizer_orders SET payment_status = :payment_status WHERE order_id = :order_id");
        $this->db->bind(':payment_status', $payment_status);
        $this->db->bind(':order_id', $order_id);
        return $this->db->execute();
    }

    
    public function getfertilizerorderforInventory(){
        $this->db->query("SELECT fo.order_id, pr.first_name, sup.address, fo.order_date,fo.status, fo.fertilizer_id, fo.quantity FROM fertilizer_orders fo LEFT JOIN suppliers sup ON fo.supplier_id=sup.supplier_id JOIN profiles pr ON sup.profile_id = pr.profile_id
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