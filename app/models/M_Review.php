<?php
class M_Review {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getProductReviews($productId) {
        $this->db->query('
            SELECT r.*, u.first_name, u.last_name
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            WHERE r.product_id = :product_id
            AND r.status = "approved"
            ORDER BY r.created_at DESC
        ');
        $this->db->bind(':product_id', $productId);
        return $this->db->resultSet();
    }

    public function addReview($data) {
        $this->db->query('
            INSERT INTO reviews (product_id, user_id, rating, comment)
            VALUES (:product_id, :user_id, :rating, :comment)
        ');
        
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment']);

        return $this->db->execute();
    }

    public function getUserReviews($userId) {
        $this->db->query('
            SELECT r.*, p.product_name,
                   (SELECT image_path FROM product_images WHERE product_id = p.id AND is_primary = 1 LIMIT 1) as product_image
            FROM reviews r
            JOIN products p ON r.product_id = p.id
            WHERE r.user_id = :user_id
            ORDER BY r.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getAverageRating($productId) {
        $this->db->query('
            SELECT AVG(rating) as avg_rating, COUNT(*) as total_reviews
            FROM reviews
            WHERE product_id = :product_id
            AND status = "approved"
        ');
        $this->db->bind(':product_id', $productId);
        return $this->db->single();
    }

    // Admin methods
    public function getPendingReviews() {
        $this->db->query('
            SELECT r.*, u.first_name, u.last_name, p.product_name
            FROM reviews r
            JOIN users u ON r.user_id = u.user_id
            JOIN products p ON r.product_id = p.id
            WHERE r.status = "pending"
            ORDER BY r.created_at ASC
        ');
        return $this->db->resultSet();
    }

    public function updateReviewStatus($reviewId, $status) {
        $this->db->query('
            UPDATE reviews
            SET status = :status
            WHERE id = :id
        ');
        
        $this->db->bind(':id', $reviewId);
        $this->db->bind(':status', $status);

        return $this->db->execute();
    }
} 