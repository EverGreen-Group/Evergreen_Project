<?php
class M_Staff {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getUpcomingLeaves() {
        $this->db->query('
            SELECT 
                lr.request_id,
                lr.employee_id,
                CONCAT(u.first_name, " ", u.last_name) as staff_name,
                lt.name as leave_type,
                lr.start_date,
                lr.end_date,
                CASE 
                    WHEN lr.approved_by_vehicle_manager = 1 AND lr.approved_by_employee_manager = 1 THEN "approved"
                    WHEN lr.status = "rejected" THEN "rejected"
                    ELSE "pending"
                END as status
            FROM leave_requests lr
            JOIN employees e ON lr.employee_id = e.employee_id
            JOIN users u ON e.user_id = u.user_id
            JOIN leave_types lt ON lr.leave_type = lt.id
            LEFT JOIN drivers d ON e.employee_id = d.employee_id
            LEFT JOIN driving_partners dp ON e.employee_id = dp.employee_id
            WHERE lr.start_date >= CURRENT_DATE()
            AND (d.driver_id IS NOT NULL OR dp.partner_id IS NOT NULL)
            AND lr.approved_by_vehicle_manager = 1
            ORDER BY lr.start_date ASC
            LIMIT 10
        ');
        
        return $this->db->resultSet();
    }

    public function getPendingLeaves() {
        $this->db->query('
            SELECT 
                lr.request_id as id,
                lr.employee_id,
                CONCAT(u.first_name, " ", u.last_name) as staff_name,
                lt.name as leave_type,
                lr.start_date,
                lr.end_date,
                DATEDIFF(lr.end_date, lr.start_date) + 1 as total_days,
                lr.status
            FROM leave_requests lr
            JOIN employees e ON lr.employee_id = e.employee_id
            JOIN users u ON e.user_id = u.user_id
            JOIN leave_types lt ON lr.leave_type = lt.id
            WHERE lr.status = "pending"
            AND lr.approved_by_vehicle_manager = 0
            ORDER BY lr.start_date ASC
        ');
        
        return $this->db->resultSet();
    }

    public function updateLeaveStatus($requestId, $status, $managerId) {
        try {
            // Log incoming parameters
            error_log("Attempting to update leave - Request ID: $requestId, Status: $status, Manager ID: $managerId");

            $this->db->query('
                UPDATE leave_requests 
                SET 
                    status = :status,
                    approved_by_vehicle_manager = :approved,
                    vehicle_manager_id = :manager_id
                WHERE request_id = :request_id
            ');

            // Status should match the ENUM values exactly
            $newStatus = $status === 'approved' ? 'pending' : 'rejected';
            $isApproved = $status === 'approved' ? 1 : 0;

            // Log the values being bound
            error_log("Binding values - New Status: $newStatus, Approved: $isApproved, Manager ID: $managerId, Request ID: $requestId");

            $this->db->bind(':status', $newStatus);
            $this->db->bind(':approved', $isApproved, PDO::PARAM_INT);
            $this->db->bind(':manager_id', $managerId, PDO::PARAM_INT);
            $this->db->bind(':request_id', $requestId, PDO::PARAM_INT);

            $result = $this->db->execute();
            
            if (!$result) {
                error_log("Database update failed for leave request ID: $requestId");
                return false;
            }

            return true;

        } catch (Exception $e) {
            error_log("Exception in updateLeaveStatus: " . $e->getMessage());
            return false;
        }
    }

    public function getAllDrivers() {
        $this->db->query('
            SELECT 
                d.driver_id,
                d.employee_id,
                d.license_no,
                d.status,
                d.experience_years
            FROM drivers d
            JOIN employees e ON d.employee_id = e.employee_id
            WHERE d.status != "deleted"
            ORDER BY d.driver_id DESC
        ');
        
        return $this->db->resultSet();
    }

    public function getAllPartners() {
        $this->db->query('
            SELECT 
                dp.partner_id,
                dp.employee_id,
                dp.status
            FROM driving_partners dp
            JOIN employees e ON dp.employee_id = e.employee_id
            WHERE dp.status != "deleted"
            ORDER BY dp.partner_id DESC
        ');
        
        return $this->db->resultSet();
    }

    public function getAllManagers() {
        $this->db->query('
            SELECT 
                vm.manager_id,
                vm.employee_id,
                vm.manager_type,
                vm.status
            FROM vehicle_managers vm
            JOIN employees e ON vm.employee_id = e.employee_id
            WHERE vm.status != "deleted"
            ORDER BY vm.manager_id DESC
        ');
        
        return $this->db->resultSet();
    }

    public function getTotalDrivers() {
        $this->db->query('
            SELECT COUNT(*) as total_drivers 
            FROM drivers 
            WHERE status = "active"
        ');
        
        return $this->db->single();
    }

    public function getTotalPartners() {
        $this->db->query('
            SELECT COUNT(*) as total_partners 
            FROM driving_partners 
            WHERE status = "active"
        ');
        
        return $this->db->single();
    }

    public function getTotalUnavailableDriver() {
        $this->db->query('
            SELECT COUNT(*) as total_drivers_unavailable 
            FROM drivers 
            WHERE status = "unavailable"
        ');
        return $this->db->single();
    }

    public function getTotalUnavailablePartner() {
        $this->db->query('
            SELECT COUNT(*) as total_partners_unavailable 
            FROM driving_partners 
            WHERE status = "unavailable"
        ');
        return $this->db->single();
    }

    public function getManagerIdByUserId($userId) {
        $this->db->query('
            SELECT m.manager_id 
            FROM managers m 
            LEFT JOIN employees e ON m.employee_id = e.employee_id 
            JOIN users u ON e.user_id = u.user_id 
            WHERE u.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        
        // Add debug logging
        $result = $this->db->single();
        error_log("getManagerIdByUserId for user $userId returned: " . print_r($result, true));
        
        return $result ? $result->manager_id : null;
    }

    public function getLeaveTypeDistribution() {
        $this->db->query('
            SELECT 
                lt.name,
                COUNT(*) as count
            FROM leave_requests lr
            JOIN leave_types lt ON lr.leave_type = lt.id
            WHERE lr.status = "approved"
            GROUP BY lt.id, lt.name
            ORDER BY count DESC
        ');
        
        return $this->db->resultSet();
    }

    public function getMonthlyLeaveDistribution() {
        $this->db->query('
            SELECT 
                MONTH(start_date) as month,
                COUNT(*) as count
            FROM leave_requests
            WHERE status = "approved" 
            AND YEAR(start_date) = YEAR(CURRENT_DATE())
            GROUP BY MONTH(start_date)
            ORDER BY month
        ');
        return $this->db->resultSet();
    }

    // Add other necessary methods here
} 