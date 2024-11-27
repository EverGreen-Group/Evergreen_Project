

<?php
//M_Order.php
class M_Order {
    private $db;
    private $stripeSecretKey;

    public function __construct() {
        $this->db = new Database;
        
        // Initialize Stripe
        $this->stripeSecretKey = STRIPE_SECRET_KEY;
        require_once dirname(dirname(__DIR__)) . '/vendor/autoload.php';
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
    }

    public function createOrder($data) {
        try {
            $this->db->beginTransaction();

            // Insert order
            $this->db->query('
                INSERT INTO orders (
                    user_id,
                    subtotal,
                    shipping_fee,
                    tax_amount,
                    total_amount,
                    shipping_address,
                    shipping_method,
                    payment_method,
                    status,
                    notes,
                    created_at
                ) VALUES (
                    :user_id,
                    :subtotal,
                    :shipping_fee,
                    :tax_amount,
                    :total_amount,
                    :shipping_address,
                    :shipping_method,
                    :payment_method,
                    :status,
                    :notes,
                    NOW()
                )
            ');

            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':subtotal', $data['subtotal']);
            $this->db->bind(':shipping_fee', $data['shipping_fee']);
            $this->db->bind(':tax_amount', $data['tax_amount']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':shipping_address', $data['shipping_address']);
            $this->db->bind(':shipping_method', $data['shipping_method']);
            $this->db->bind(':payment_method', $data['payment_method']);
            $this->db->bind(':status', $data['status']);
            $this->db->bind(':notes', $data['notes']);

            $this->db->execute();
            $orderId = $this->db->lastInsertId();

            // Insert order items
            foreach ($data['items'] as $item) {
                $this->db->query('
                    INSERT INTO order_items (
                        order_id,
                        product_id,
                        quantity,
                        price
                    ) VALUES (
                        :order_id,
                        :product_id,
                        :quantity,
                        :price
                    )
                ');

                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':product_id', $item->product_id);
                $this->db->bind(':quantity', $item->quantity);
                $this->db->bind(':price', $item->price);
                $this->db->execute();
            }

            $this->db->commit();
            return $orderId;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getOrderById($id) {
        $this->db->query('
            SELECT * FROM orders 
            WHERE id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
}
