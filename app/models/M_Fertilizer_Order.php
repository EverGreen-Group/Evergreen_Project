<?php
class M_Fertilizer_Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllOrders() {
        $this->db->query("SELECT * FROM fertilizer_orders ORDER BY order_date DESC, order_time DESC LIMIT 10");
        return $this->db->resultset();
    }

    public function getOrderById($order_id) {
        $this->db->query("SELECT fo.*, ft.name as fertilizer_name 
                          FROM fertilizer_orders fo 
                          LEFT JOIN fertilizer_types ft ON fo.type_id = ft.type_id 
                          WHERE fo.order_id = :order_id");
        $this->db->bind(':order_id', $order_id);
        return $this->db->single();
    }

    public function getOrdersBySupplier($supplier_id) {
        $this->db->query("SELECT * FROM fertilizer_orders WHERE supplier_id = :supplier_id");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultset();
    }


    private $error = null;
    public function getError() {
        return $this->error;
    }
    public function createOrder($data) {
        try {
            // Start transaction
            $this->db->beginTransaction();
    
            // Validate data
            if (empty($data['supplier_id']) || empty($data['type_id']) || 
                empty($data['total_amount']) || empty($data['unit'])) {
                throw new Exception('Missing required fields');
            }
    
            // Validate amount
            if ($data['total_amount'] <= 0 || $data['total_amount'] > 50) {
                throw new Exception('Invalid amount. Must be between 1 and 50');
            }
    
            // Get current date and time
            $currentDate = date('Y-m-d');
            $currentTime = date('H:i:s');
    
            $this->db->query(
                "INSERT INTO fertilizer_orders 
                (supplier_id, type_id, fertilizer_name, total_amount, unit, 
                price_per_unit, total_price, order_date, order_time, 
                status, payment_status) 
                VALUES 
                (:supplier_id, :type_id, :fertilizer_name, :total_amount, :unit, 
                :price_per_unit, :total_price, :order_date, :order_time, 
                'pending', 'pending')"
            );
            
            $this->db->bind(':supplier_id', $data['supplier_id']);
            $this->db->bind(':type_id', $data['type_id']);
            $this->db->bind(':fertilizer_name', $data['fertilizer_name']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':unit', $data['unit']);
            $this->db->bind(':price_per_unit', $data['price_per_unit']);
            $this->db->bind(':total_price', $data['total_price']);
            $this->db->bind(':order_date', $currentDate);
            $this->db->bind(':order_time', $currentTime);
    
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

    public function getFertilizerName($type_id) {
        $this->db->query('SELECT fertilizer_name FROM fertilizer_types WHERE type_id = :type_id');
        $this->db->bind(':type_id', $type_id);
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

    // In M_Fertilizer_Order.php
    public function getRequestCounts($supplier_id) {
        try {
            // Get total requests
            $this->db->query("SELECT 
                COUNT(*) as total_requests,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as accepted_requests,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_requests
                FROM fertilizer_orders 
                WHERE supplier_id = :supplier_id");
                
            $this->db->bind(':supplier_id', $supplier_id);
            
            $result = $this->db->single();
            
            return [
                'total' => $result->total_requests ?? 0,
                'accepted' => $result->accepted_requests ?? 0,
                'rejected' => $result->rejected_requests ?? 0
            ];
        } catch (Exception $e) {
            error_log("Error getting request counts: " . $e->getMessage());
            return [
                'total' => 0,
                'accepted' => 0,
                'rejected' => 0
            ];
        }
    }

    public function getOrdersByMonth($supplier_id, $month) {
        try {
            $start_date = date('Y-m-01', strtotime($month));
            $end_date = date('Y-m-t', strtotime($month));
            
            $this->db->query(
                "SELECT fo.*, ft.name as fertilizer_name 
                 FROM fertilizer_orders fo 
                 LEFT JOIN fertilizer_types ft ON fo.type_id = ft.type_id 
                 WHERE fo.supplier_id = :supplier_id 
                 AND fo.order_date BETWEEN :start_date AND :end_date 
                 ORDER BY fo.order_date DESC"
            );
            
            $this->db->bind(':supplier_id', $supplier_id);
            $this->db->bind(':start_date', $start_date);
            $this->db->bind(':end_date', $end_date);
            
            return $this->db->resultset();
        } catch (Exception $e) {
            error_log("Error fetching orders by month: " . $e->getMessage());
            return [];
        }
    }

    public function getPendingOrdersBySupplier($supplier_id) {
        $this->db->query("SELECT fertilizer_name, order_date, total_amount, status 
                          FROM fertilizer_orders 
                          WHERE supplier_id = :supplier_id 
                          AND status = 'pending' 
                          ORDER BY order_date DESC 
                          LIMIT 5");
        $this->db->bind(':supplier_id', $supplier_id);
        return $this->db->resultset();
    }

    
    
}
?> 