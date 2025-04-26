<?php
class M_Tasks {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all tasks by creator
    public function getTasksByCreator($user_id) {
        $this->db->query("
            SELECT task_id, title, description, priority, due_date, created_at, updated_at
            FROM tasks
            WHERE created_by = :user_id
            ORDER BY priority DESC, due_date ASC
        ");
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    // Get a single task by ID and creator
    public function getTaskById($task_id, $user_id) {
        $this->db->query("
            SELECT task_id, title, description, priority, due_date
            FROM tasks
            WHERE task_id = :task_id AND created_by = :user_id
        ");
        $this->db->bind(':task_id', $task_id);
        $this->db->bind(':user_id', $user_id);
        return $this->db->single();
    }
}