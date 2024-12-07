<?php
class M_Task {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get all tasks
    public function getAllTasks() {
        $this->db->query('SELECT t.*, u.first_name, u.last_name, u.employee_id 
                         FROM tasks t 
                         JOIN users u ON t.assigned_to = u.id 
                         ORDER BY t.due_date ASC');
        return $this->db->resultSet();
    }

    // Get task statistics
    public function getTaskStats() {
        $stats = [
            'pending' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'overdue' => 0
        ];

        // Get pending count
        $this->db->query('SELECT COUNT(*) as count FROM tasks WHERE status = "Pending"');
        $stats['pending'] = $this->db->single()->count;

        // Get in progress count
        $this->db->query('SELECT COUNT(*) as count FROM tasks WHERE status = "In Progress"');
        $stats['in_progress'] = $this->db->single()->count;

        // Get completed count
        $this->db->query('SELECT COUNT(*) as count FROM tasks WHERE status = "Completed"');
        $stats['completed'] = $this->db->single()->count;

        // Get overdue count
        $this->db->query('SELECT COUNT(*) as count FROM tasks 
                         WHERE due_date < CURRENT_DATE() 
                         AND status NOT IN ("Completed", "Cancelled")');
        $stats['overdue'] = $this->db->single()->count;

        return $stats;
    }

    // Add new task
    public function addTask($data) {
        $this->db->query('INSERT INTO tasks (title, description, assigned_to, priority, 
                         due_date, status, progress) 
                         VALUES (:title, :description, :assigned_to, :priority, 
                         :due_date, :status, :progress)');
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':assigned_to', $data['assigned_to']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':progress', $data['progress']);

        return $this->db->execute();
    }

    // Update task
    public function updateTask($data) {
        $this->db->query('UPDATE tasks 
                         SET title = :title, 
                             description = :description, 
                             assigned_to = :assigned_to, 
                             priority = :priority, 
                             due_date = :due_date, 
                             status = :status, 
                             progress = :progress 
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':assigned_to', $data['assigned_to']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':progress', $data['progress']);

        return $this->db->execute();
    }

    // Get task by ID
    public function getTaskById($id) {
        $this->db->query('SELECT t.*, u.first_name, u.last_name, u.employee_id 
                         FROM tasks t 
                         JOIN users u ON t.assigned_to = u.id 
                         WHERE t.id = :id');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get employee tasks
    public function getEmployeeTasks($userId) {
        $this->db->query('SELECT * FROM tasks 
                         WHERE assigned_to = :user_id 
                         ORDER BY due_date ASC');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
} 