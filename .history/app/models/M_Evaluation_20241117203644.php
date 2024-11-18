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

    public function getPendingEvaluations() {
        $this->db->query("SELECT e.*, 
                         emp.first_name as employee_first_name,
                         emp.last_name as employee_last_name,
                         rev.first_name as reviewer_first_name,
                         rev.last_name as reviewer_last_name
                         FROM evaluations e
                         JOIN employees emp ON e.employee_id = emp.employee_id
                         LEFT JOIN employees rev ON e.reviewer_id = rev.employee_id
                         WHERE e.status = 'Pending'
                         ORDER BY e.review_date ASC
                         LIMIT 5");
        
        return $this->db->resultSet();
    }

    public function getEmployeeEvaluations($employeeId) {
        $this->db->query("SELECT e.*,
                         rev.first_name as reviewer_first_name,
                         rev.last_name as reviewer_last_name,
                         ec.criteria_name,
                         ec.rating as criteria_rating,
                         ec.comments as criteria_comments
                         FROM evaluations e
                         LEFT JOIN employees rev ON e.reviewer_id = rev.employee_id
                         LEFT JOIN evaluation_criteria ec ON e.id = ec.evaluation_id
                         WHERE e.employee_id = :employee_id
                         ORDER BY e.review_date DESC");
        
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->resultSet();
    }

    public function createEvaluation($data) {
        $this->db->beginTransaction();

        try {
            // Insert main evaluation
            $this->db->query("INSERT INTO evaluations 
                            (employee_id, evaluation_period, rating, review_date, reviewer_id, status, comments)
                            VALUES 
                            (:employee_id, :period, :rating, :review_date, :reviewer_id, :status, :comments)");

            $this->db->bind(':employee_id', $data['employee_id']);
            $this->db->bind(':period', $data['period']);
            $this->db->bind(':rating', $data['rating']);
            $this->db->bind(':review_date', $data['review_date']);
            $this->db->bind(':reviewer_id', $data['reviewer_id']);
            $this->db->bind(':status', 'Pending');
            $this->db->bind(':comments', $data['comments']);

            $this->db->execute();
            $evaluationId = $this->db->lastInsertId();

            // Insert evaluation criteria
            if (!empty($data['criteria'])) {
                foreach ($data['criteria'] as $criterion) {
                    $this->db->query("INSERT INTO evaluation_criteria 
                                    (evaluation_id, criteria_name, rating, comments)
                                    VALUES 
                                    (:evaluation_id, :name, :rating, :comments)");

                    $this->db->bind(':evaluation_id', $evaluationId);
                    $this->db->bind(':name', $criterion['name']);
                    $this->db->bind(':rating', $criterion['rating']);
                    $this->db->bind(':comments', $criterion['comments']);

                    $this->db->execute();
                }
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function updateEvaluationStatus($evaluationId, $status, $reviewerId) {
        $this->db->query("UPDATE evaluations 
                         SET status = :status,
                             reviewer_id = :reviewer_id,
                             updated_at = CURRENT_TIMESTAMP
                         WHERE id = :evaluation_id");

        $this->db->bind(':status', $status);
        $this->db->bind(':reviewer_id', $reviewerId);
        $this->db->bind(':evaluation_id', $evaluationId);

        return $this->db->execute();
    }
} 