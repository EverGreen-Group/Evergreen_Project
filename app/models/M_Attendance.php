<?php
class M_Attendance {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Get attendance for a specific date
    public function getAttendanceByDate($date) {
        $this->db->query('SELECT a.*, u.first_name, u.last_name, u.employee_id, u.department 
                         FROM attendance a 
                         RIGHT JOIN users u ON a.user_id = u.id 
                         AND DATE(a.date) = :date');
        
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

    // Get attendance statistics
    public function getAttendanceStats($date) {
        $stats = [
            'present' => 0,
            'absent' => 0,
            'late' => 0,
            'on_leave' => 0
        ];

        // Get present count
        $this->db->query('SELECT COUNT(*) as count FROM attendance 
                         WHERE DATE(date) = :date AND status = "Present"');
        $this->db->bind(':date', $date);
        $stats['present'] = $this->db->single()->count;

        // Get late count
        $this->db->query('SELECT COUNT(*) as count FROM attendance 
                         WHERE DATE(date) = :date AND status = "Late"');
        $this->db->bind(':date', $date);
        $stats['late'] = $this->db->single()->count;

        // Get on leave count
        $this->db->query('SELECT COUNT(*) as count FROM leaves 
                         WHERE :date BETWEEN start_date AND end_date 
                         AND status = "Approved"');
        $this->db->bind(':date', $date);
        $stats['on_leave'] = $this->db->single()->count;

        // Calculate absent
        $this->db->query('SELECT COUNT(*) as total FROM users WHERE status = "Active"');
        $totalEmployees = $this->db->single()->count;
        $stats['absent'] = $totalEmployees - ($stats['present'] + $stats['late'] + $stats['on_leave']);

        return $stats;
    }

    // Mark attendance
    public function markAttendance($userId, $date, $type) {
        if ($type == 'check_in') {
            $this->db->query('INSERT INTO attendance (user_id, date, check_in_time) 
                             VALUES (:user_id, :date, CURRENT_TIME())');
        } else {
            $this->db->query('UPDATE attendance 
                             SET check_out_time = CURRENT_TIME() 
                             WHERE user_id = :user_id AND DATE(date) = :date');
        }
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':date', $date);
        
        return $this->db->execute();
    }

    // Get employee attendance history
    public function getAttendanceHistory($userId) {
        $this->db->query('SELECT * FROM attendance 
                         WHERE user_id = :user_id 
                         ORDER BY date DESC');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getTodayAttendance() {
        $this->db->query("SELECT a.*, 
                         e.first_name, 
                         e.last_name,
                         e.employee_id as emp_id
                         FROM attendance a
                         JOIN employees e ON a.employee_id = e.employee_id
                         WHERE DATE(a.date) = CURDATE()
                         ORDER BY a.check_in DESC");
        
        return $this->db->resultSet();
    }

    public function getEmployeeAttendance($employeeId) {
        $this->db->query("SELECT * FROM attendance 
                         WHERE employee_id = :employee_id 
                         ORDER BY date DESC 
                         LIMIT 30");
        
        $this->db->bind(':employee_id', $employeeId);
        return $this->db->resultSet();
    }

    public function getMonthlyStats() {
        $this->db->query("SELECT 
                         COUNT(CASE WHEN status = 'Present' THEN 1 END) as present_count,
                         COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent_count,
                         COUNT(CASE WHEN status = 'Late' THEN 1 END) as late_count,
                         COUNT(CASE WHEN status = 'Half-day' THEN 1 END) as halfday_count
                         FROM attendance 
                         WHERE MONTH(date) = MONTH(CURRENT_DATE())
                         AND YEAR(date) = YEAR(CURRENT_DATE())");
        
        return $this->db->single();
    }
} 