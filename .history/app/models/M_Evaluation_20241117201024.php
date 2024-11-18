<?php
class M_Evaluation {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all evaluations
    public function getAllEvaluations() {
        $this->db->query('SELECT e.*, u.first_name, u.last_name, u.employee_id, u.department, u.position 
                         FROM evaluations e 
                         JOIN users u ON e.user_id = u.id 
                         ORDER BY e.review_date DESC');
        return $this->db->resultSet();
    }

    // Get evaluation statistics
    public function getEvaluationStats() {
        $stats = [
            'pending' => 0,
            'completed' => 0,
            'avg_rating' => 0
        ];

        // Get pending count
        $this->db->query('SELECT COUNT(*) as count FROM evaluations WHERE status = "Pending"');
        $stats['pending'] = $this->db->single()->count;

        // Get completed count for current month
        $this->db->query('SELECT COUNT(*) as count FROM evaluations 
                         WHERE status = "Completed" 
                         AND MONTH(review_date) = MONTH(CURRENT_DATE())');
        $stats['completed'] = $this->db->single()->count;

        // Get average rating
        $this->db->query('SELECT AVG(rating) as avg FROM evaluations WHERE status = "Completed"');
        $stats['avg_rating'] = $this->db->single()->avg ?? 0;

        return $stats;
    }

    // Add new evaluation
    public function addEvaluation($data) {
        $this->db->query('INSERT INTO evaluations (user_id, evaluation_period, rating, 
                         review_date, reviewer_id, comments) 
                         VALUES (:user_id, :evaluation_period, :rating, 
                         :review_date, :reviewer_id, :comments)');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':evaluation_period', $data['evaluation_period']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review_date', $data['review_date']);
        $this->db->bind(':reviewer_id', $data['reviewer_id']);
        $this->db->bind(':comments', $data['comments']);

        return $this->db->execute();
    }

    // Update evaluation
    public function updateEvaluation($data) {
        $this->db->query('UPDATE evaluations 
                         SET rating = :rating, 
                             comments = :comments, 
                             status = :status, 
                             review_date = :review_date 
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comments', $data['comments']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':review_date', $data['review_date']);

        return $this->db->execute();
    }

    // Get evaluation by ID
    public function getEvaluationById($id) {
        $this->db->query('SELECT e.*, u.first_name, u.last_name, u.employee_id, 
                         u.department, u.position 
                         FROM evaluations e 
                         JOIN users u ON e.user_id = u.id 
                         WHERE e.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get employee evaluation history
    public function getEmployeeEvaluations($userId) {
        $this->db->query('SELECT * FROM evaluations 
                         WHERE user_id = :user_id 
                         ORDER BY review_date DESC');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
} 