<?php
class M_Cart {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getCartItems($userId) {
        $this->db->query('
            SELECT c.*, p.product_name, p.price, p.quantity as stock,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_image
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
            ORDER BY c.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getCartTotal($userId) {
        $this->db->query('
            SELECT SUM(c.quantity * p.price) as total
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getCartCount($userId) {
        $this->db->query('
            SELECT COUNT(*) as count
            FROM cart
            WHERE user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->single()->count;
    }

    public function addItem($userId, $productId, $quantity) {
        $this->db->beginTransaction();

        try {
            // Check if item already exists in cart
            $this->db->query('
                SELECT * FROM cart 
                WHERE user_id = :user_id AND product_id = :product_id
            ');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':product_id', $productId);
            $existing = $this->db->single();

            if ($existing) {
                // Update quantity
                $this->db->query('
                    UPDATE cart 
                    SET quantity = quantity + :quantity,
                        updated_at = CURRENT_TIMESTAMP
                    WHERE user_id = :user_id AND product_id = :product_id
                ');
            } else {
                // Add new item
                $this->db->query('
                    INSERT INTO cart (user_id, product_id, quantity)
                    VALUES (:user_id, :product_id, :quantity)
                ');
            }

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':product_id', $productId);
            $this->db->bind(':quantity', $quantity);

            $result = $this->db->execute();
            $this->db->commit();
            return $result;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateQuantity($userId, $productId, $quantity) {
        if ($quantity <= 0) {
            return $this->removeItem($userId, $productId);
        }

        $this->db->query('
            UPDATE cart 
            SET quantity = :quantity,
                updated_at = CURRENT_TIMESTAMP
            WHERE user_id = :user_id AND product_id = :product_id
        ');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $this->db->bind(':quantity', $quantity);

        return $this->db->execute();
    }

    public function removeItem($userId, $productId) {
        $this->db->query('
            DELETE FROM cart 
            WHERE user_id = :user_id AND product_id = :product_id
        ');

        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);

        return $this->db->execute();
    }

    public function clearCart($userId) {
        $this->db->query('DELETE FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }

    public function validateCartItems($userId) {
        $this->db->query('
            SELECT c.product_id, c.quantity, p.quantity as stock, p.price, p.status
            FROM cart c
            JOIN products p ON c.product_id = p.id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        $items = $this->db->resultSet();

        $errors = [];
        foreach ($items as $item) {
            if ($item->status !== 'active') {
                $errors[] = "Product is no longer available";
            }
            if ($item->quantity > $item->stock) {
                $errors[] = "Insufficient stock available";
            }
        }

        return empty($errors) ? true : $errors;
    }
} 