<?php
class M_Leave {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getLeaveTypes() {
        $this->db->query("SELECT * FROM leave_types");
        return $this->db->resultSet();
    }

    public function getRecentLeaveRequests() {
        $this->db->query("SELECT l.*, 
                         u.first_name, 
                         u.last_name,
                         lt.name as leave_type_name
                         FROM leaves l
                         JOIN users u ON l.user_id = u.user_id
                         JOIN leave_types lt ON l.leave_type_id = lt.id
                         WHERE l.status = 'Pending'
                         ORDER BY l.created_at DESC
                         LIMIT 5");
        
        return $this->db->resultSet();
    }

    public function getLeaveBalance($employeeId) {
        $sql = "SELECT lt.name, lt.max_days_per_year - COALESCE(SUM(
            CASE 
                WHEN lr.status = 'approved' 
                THEN DATEDIFF(lr.end_date, lr.start_date) + 1 
                ELSE 0 
            END
        ), 0) as remaining_days
        FROM leave_types lt
        LEFT JOIN leave_requests lr ON lr.leave_type = lt.id 
            AND lr.employee_id = :employee_id 
            AND YEAR(lr.start_date) = YEAR(CURRENT_DATE)
        GROUP BY lt.id, lt.name, lt.max_days_per_year";
        
        $this->db->query($sql);
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->resultSet();
    }

    public function getPendingLeaveCount($employeeId) {
        $this->db->query("SELECT COUNT(*) as count FROM leave_requests WHERE employee_id = :employee_id AND status = 'pending'");
        $this->db->bind(':employee_id', $employeeId);
        $result = $this->db->single();
        return $result->count;
    }

    public function getLeaveHistory($employeeId) {
        $this->db->query("SELECT lr.*, lt.name as leave_type_name 
            FROM leave_requests lr
            JOIN leave_types lt ON lr.leave_type = lt.id
            WHERE lr.employee_id = :employee_id
            ORDER BY lr.start_date DESC");
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->resultSet();
    }

    public function createLeaveRequest($data) {
        $this->db->query("INSERT INTO leave_requests (employee_id, start_date, end_date, leave_type, status) 
            VALUES (:employee_id, :start_date, :end_date, :leave_type, 'pending')");
        
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':leave_type', $data['leave_type']);
        
        return $this->db->execute();
    }

    public function cancelLeaveRequest($requestId, $employeeId) {
        $this->db->query("DELETE FROM leave_requests WHERE request_id = :id AND employee_id = :employee_id AND status = 'pending'");
        $this->db->bind(':id', $requestId);
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->execute();
    }

    public function getAvailableSwapUsers($userId, $roleId) {
        $this->db->query("SELECT user_id as id, CONCAT(first_name, ' ', last_name) as name 
            FROM users 
            WHERE role_id = :role_id 
            AND user_id != :user_id 
            AND approval_status = 'Approved'");
        $this->db->bind(':role_id', $roleId);
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getSwapRequests($userId) {
        $this->db->query("SELECT lr.*, CONCAT(u.first_name, ' ', last_name) as requester_name 
            FROM leave_requests lr
            JOIN users u ON lr.employee_id = u.user_id
            WHERE lr.swap_with_employee_id = :user_id
            AND lr.status = 'pending'
            ORDER BY lr.start_date ASC");
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function updateSwapRequest($requestId, $action, $userId) {
        $status = $action === 'approve' ? 'approved' : 'rejected';
        
        $this->db->query("UPDATE leave_requests 
            SET status = :status 
            WHERE request_id = :id 
            AND swap_with_employee_id = :user_id");
        
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $requestId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }

    public function getEmployeeLeaves($id) {
        $this->db->query("SELECT l.*, 
                         lt.name as leave_type_name
                         FROM leaves l
                         JOIN leave_types lt ON l.leave_type_id = lt.id
                         WHERE l.user_id = :id
                         ORDER BY l.start_date DESC");
        
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function getLeaveBalance($employeeId) {
        $this->db->query("SELECT 
                         lt.name as leave_type,
                         lb.total_days,
                         lb.used_days,
                         (lb.total_days - lb.used_days) as remaining_days
                         FROM leave_balances lb
                         JOIN leave_types lt ON lb.leave_type_id = lt.id
                         WHERE lb.user_id = :employee_id
                         AND lb.year = YEAR(CURRENT_DATE())");
        
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->resultSet();
    }
} 
?> 