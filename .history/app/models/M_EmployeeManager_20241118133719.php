<?php
class M_EmployeeManager {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getDashboardStats() {
        $stats = [];

        // Get total employees
        $this->db->query("SELECT 
            COUNT(*) as total_employees,
            SUM(CASE WHEN gender = 'Male' THEN 1 ELSE 0 END) as male_count,
            SUM(CASE WHEN gender = 'Female' THEN 1 ELSE 0 END) as female_count
            FROM users 
            WHERE role_id = 3 AND approval_status = 'Approved'");
        $stats['employees'] = $this->db->single();

        // Get leave requests
        $this->db->query("SELECT 
                COUNT(*) as total_requests,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_requests
            FROM leave_requests 
            WHERE MONTH(start_date) = MONTH(CURRENT_DATE())");
        $stats['leaves'] = $this->db->single();


        // Get attendance stats for today
        $this->db->query("SELECT 
                COUNT(*) as total_present,
                SUM(CASE WHEN status = 'Late' THEN 1 ELSE 0 END) as late_count
            FROM attendance 
            WHERE DATE(check_in) = CURRENT_DATE");
        $stats['attendance'] = $this->db->single();


        // Get pending evaluations
        $this->db->query("SELECT COUNT(*) as pending_evaluations 
            FROM employee_evaluations 
            WHERE status = 'Pending'");
        $stats['evaluations'] = $this->db->single();

        return $stats;
    }


    // Leave management methods
    public function getLeaveRequests() {
        $this->db->query("SELECT l.*, u.first_name, u.last_name, lt.name as leave_type_name
            FROM leaves l
            JOIN users u ON l.user_id = u.user_id
            JOIN leave_types lt ON l.leave_type_id = lt.id
            ORDER BY l.created_at DESC");
        return $this->db->resultSet();
    }

    public function updateLeaveStatus($leaveId, $status, $managerId) {
        $this->db->query("UPDATE leaves 
            SET status = :status, 
                reviewed_by = :manager_id,
                reviewed_at = CURRENT_TIMESTAMP
            WHERE id = :leave_id");
        
        $this->db->bind(':status', $status);
        $this->db->bind(':manager_id', $managerId);
        $this->db->bind(':leave_id', $leaveId);
        
        return $this->db->execute();
    }

    public function getRecentLeaves() {
        $this->db->query("SELECT 
                        l.*,
                        lt.name as leave_type,
                        u.first_name,
                        u.last_name,
                        u.department,  // Added department
                        DATEDIFF(l.end_date, l.start_date) + 1 as days,  // Added days calculation
                        rev.first_name as reviewer_first_name,
                        rev.last_name as reviewer_last_name
                        FROM leaves l
                        LEFT JOIN leave_types lt ON l.leave_type_id = lt.id
                        LEFT JOIN users u ON l.user_id = u.user_id
                        LEFT JOIN users rev ON l.reviewed_by = rev.user_id
                        WHERE l.status = 'Pending'
                        ORDER BY l.created_at DESC
                        LIMIT 5");
        
        return $this->db->resultSet();
    }

    public function getLeaveStats() {
        try {
            $this->db->query("SELECT 
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'Approved' 
                    AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                    AND YEAR(created_at) = YEAR(CURRENT_DATE()) 
                    THEN 1 ELSE 0 END) as approved,
                SUM(CASE WHEN status = 'Rejected' 
                    AND MONTH(created_at) = MONTH(CURRENT_DATE()) 
                    AND YEAR(created_at) = YEAR(CURRENT_DATE()) 
                    THEN 1 ELSE 0 END) as rejected
                FROM leaves");
            
            $result = $this->db->single();
            
            return [
                'stats' => [
                    'pending' => (int)($result->pending ?? 0),
                    'approved' => (int)($result->approved ?? 0),
                    'rejected' => (int)($result->rejected ?? 0)
                ]
            ];
            
        } catch (Exception $e) {
            error_log('Error in getLeaveStats: ' . $e->getMessage());
            return [
                'stats' => [
                    'pending' => 0,
                    'approved' => 0,
                    'rejected' => 0
                ]
            ];
        }
    }



    // Attendance management methods
    public function markAttendance($userId, $type) {
        $this->db->query("INSERT INTO attendance 
            (user_id, check_in_time, status) 
            VALUES (:user_id, CURRENT_TIMESTAMP, :status)");
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':status', $type);
        
        return $this->db->execute();
    }

    public function getAttendanceStats() {
        try {
            $this->db->query("SELECT 
                COUNT(CASE WHEN status = 'Present' THEN 1 END) as present,
                COUNT(CASE WHEN status = 'Absent' THEN 1 END) as absent,
                COUNT(CASE WHEN status = 'Half-day' THEN 1 END) as halfday,
                COUNT(CASE WHEN status = 'Leave' THEN 1 END) as onleave,
                COUNT(*) as total
                FROM attendance 
                WHERE DATE(date) = CURDATE()");
            
            $result = $this->db->single();
            
            // If no results, return default values
            if (!$result) {
                return [
                    'stats' => [  // Added 'stats' key to match view expectations
                        'present' => 0,
                        'absent' => 0,
                        'halfday' => 0,
                        'onleave' => 0,
                        'total' => 0
                    ]
                ];
            }
            
            return [
                'stats' => [  // Added 'stats' key to match view expectations
                    'present' => $result->present ?? 0,
                    'absent' => $result->absent ?? 0,
                    'halfday' => $result->halfday ?? 0,
                    'onleave' => $result->onleave ?? 0,
                    'total' => $result->total ?? 0
                ]
            ];
            
        } catch (Exception $e) {
            // Return default values if any error occurs
            return [
                'stats' => [  // Added 'stats' key to match view expectations
                    'present' => 0,
                    'absent' => 0,
                    'halfday' => 0,
                    'onleave' => 0,
                    'total' => 0
                ]
            ];
        }
    }
    
    // Optional: Add method to get detailed attendance info
    public function getTodayAttendance() {
        $this->db->query("SELECT 
            a.*,
            e.first_name,
            e.last_name,
            TIME_FORMAT(a.check_in, '%h:%i %p') as formatted_check_in,
            TIME_FORMAT(a.check_out, '%h:%i %p') as formatted_check_out
            FROM attendance a
            JOIN employees e ON a.employee_id = e.employee_id
            WHERE DATE(a.date) = CURDATE()
            ORDER BY a.check_in ASC");
        
        return $this->db->resultSet();
    }

    // Employee registration methods
    public function registerEmployee($data) {
        $this->db->query("INSERT INTO users 
            (email, password, first_name, last_name, nic, date_of_birth, gender, role_id) 
            VALUES (:email, :password, :first_name, :last_name, :nic, :dob, :gender, 3)");
        
        // Bind values
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':nic', $data['nic']);
        $this->db->bind(':dob', $data['date_of_birth']);
        $this->db->bind(':gender', $data['gender']);
        
        return $this->db->execute();
    }

    // Task management methods
    public function getEmployeeTasks($userId) {
        $this->db->query("SELECT * FROM tasks 
            WHERE assigned_to = :user_id 
            ORDER BY due_date ASC");
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Evaluation methods
    public function getEmployeeEvaluations($userId) {
        $this->db->query("SELECT * FROM employee_evaluations 
            WHERE employee_id = :user_id 
            ORDER BY evaluation_date DESC");
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Salary management methods
    public function calculateSalary($userId, $month, $year) {
        // Get base salary
        $this->db->query("SELECT base_salary FROM employee_salary 
            WHERE employee_id = :user_id");
        $this->db->bind(':user_id', $userId);
        $baseSalary = $this->db->single()->base_salary;

        // Calculate attendance-based deductions
        $this->db->query("SELECT COUNT(*) as absent_days 
            FROM attendance 
            WHERE user_id = :user_id 
            AND MONTH(check_in_time) = :month 
            AND YEAR(check_in_time) = :year 
            AND status = 'Absent'");
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':month', $month);
        $this->db->bind(':year', $year);
        
        $absentDays = $this->db->single()->absent_days;
        $deductions = $absentDays * ($baseSalary / 30); // Assuming 30 days month

        return [
            'base_salary' => $baseSalary,
            'deductions' => $deductions,
            'net_salary' => $baseSalary - $deductions
        ];
    }

    public function getAllEmployees($filters = []) {
        $sql = "SELECT 
                e.employee_id,
                e.first_name,
                e.last_name,
                e.email,
                e.phone,
                e.department,
                e.position,
                e.status
                FROM employees e
                WHERE 1=1";
        
        // Add filters
        if (!empty($filters['department'])) {
            $sql .= " AND e.department = :department";
        }
        
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = :status";
        } else {
            $sql .= " AND e.status = 'Active'";
        }
        
        $sql .= " ORDER BY e.first_name, e.last_name";
        
        $this->db->query($sql);
        
        // Bind parameters if filters are set
        if (!empty($filters['department'])) {
            $this->db->bind(':department', $filters['department']);
        }
        if (!empty($filters['status'])) {
            $this->db->bind(':status', $filters['status']);
        }
        
        return $this->db->resultSet();
    }
} 