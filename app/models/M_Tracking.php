<?php
class M_Tracking {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTrackingByCode($code) {
        $this->db->query('SELECT * FROM order_tracking WHERE tracking_code = :code');
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    public function addStatusHistory($trackingCode, $status, $location) {
        // First get current status history
        $tracking = $this->getTrackingByCode($trackingCode);
        $history = json_decode($tracking->status_history ?? '[]');
        
        // Add new status
        $history[] = [
            'status' => $status,
            'location' => $location,
            'timestamp' => date('Y-m-d H:i:s')
        ];

        // Update the tracking record
        $this->db->query('
            UPDATE order_tracking 
            SET status_history = :history,
                status = :status,
                current_location = :location,
                updated_at = NOW()
            WHERE tracking_code = :tracking_code
        ');

        $this->db->bind(':history', json_encode($history));
        $this->db->bind(':status', $status);
        $this->db->bind(':location', $location);
        $this->db->bind(':tracking_code', $trackingCode);

        return $this->db->execute();
    }

    public function createTracking($orderId) {
        $trackingCode = 'TRK' . strtoupper(uniqid());
        
        $this->db->query('
            INSERT INTO order_tracking 
            (order_id, tracking_code, status, status_history) 
            VALUES 
            (:order_id, :tracking_code, "pending", :status_history)
        ');

        $initialHistory = json_encode([
            [
                'status' => 'pending',
                'location' => 'Order Received',
                'timestamp' => date('Y-m-d H:i:s')
            ]
        ]);

        $this->db->bind(':order_id', $orderId);
        $this->db->bind(':tracking_code', $trackingCode);
        $this->db->bind(':status_history', $initialHistory);

        if($this->db->execute()) {
            return $trackingCode;
        }
        return false;
    }

    public function getTrackingByOrderId($orderId) {
        $this->db->query('SELECT * FROM order_tracking WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        return $this->db->single();
    }
} 