
<?php
class M_Salary {
    private $db;
    public function construct() {
    $this->db = new Database;
    }
    // Get all salaries
    public function getAllSalaries() {
    $this->db->query('SELECT s., u.first_name, u.last_name, u.employee_id, u.department
    FROM salaries s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.payment_date DESC');
    return $this->db->resultSet();
    }
    // Get salary statistics
    public function getSalaryStats() {
        $stats = [
            'total_payroll' => 0,
            'avg_salary' => 0,
            'pending_payments' => 0
            ];
            // Get total monthly payroll
            $this->db->query('SELECT SUM(net_salary) as total FROM salaries
            WHERE MONTH(payment_date) = MONTH(CURRENT_DATE())');
            $stats['total_payroll'] = $this->db->single()->total ?? 0;
            // Get average salary
            $this->db->query('SELECT AVG(basic_salary) as avg FROM salaries');
            $stats['avg_salary'] = $this->db->single()->avg ?? 0;
            // Get pending payments count
            $this->db->query('SELECT COUNT() as count FROM salaries
            WHERE payment_status = "Pending"');
            $stats['pending_payments'] = $this->db->single()->count;
            return $stats;
            }
            // Add/Update salary
            public function updateSalary($data) {
            $this->db->query('INSERT INTO salaries (user_id, basic_salary, allowances,
            deductions, payment_date, payment_status)
            VALUES (:user_id, :basic_salary, :allowances,
:deductions, :payment_date, :payment_status)
ON DUPLICATE KEY UPDATE
basic_salary = :basic_salary,
allowances = :allowances,
deductions = :deductions');
$this->db->bind(':user_id', $data['user_id']);
$this->db->bind(':basic_salary', $data['basic_salary']);
$this->db->bind(':allowances', $data['allowances']);
$this->db->bind(':deductions', $data['deductions']);
$this->db->bind(':payment_date', $data['payment_date']);
$this->db->bind(':payment_status', $data['payment_status']);
return $this->db->execute();
}
// Get salary by ID
public function getSalaryById($id) {
$this->db->query('SELECT s., u.first_name, u.last_name, u.employee_id,
u.department
FROM salaries s
JOIN users u ON s.user_id = u.id
WHERE s.id = :id');