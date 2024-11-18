<?php
require_once '../app/models/M_EmployeeManager.php';
require_once '../app/models/M_Leave.php';
require_once '../app/models/M_Attendance.php';
require_once '../app/models/M_Task.php';
require_once '../app/models/M_Evaluation.php';
require_once '../app/models/M_Salary.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/UserHelper.php';

class EmployeeManager extends Controller {
    private $employeeManagerModel;
    private $leaveModel;
    private $attendanceModel;
    private $taskModel;
    private $evaluationModel;
    private $salaryModel;
    private $userHelper;

    public function __construct() {
        // Check if user is logged in
        requireAuth();
        
        // Check if user has Employee Manager OR Admin role
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::EMPLOYEE_MANAGER])) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        // Initialize models
        $this->employeeManagerModel = new M_EmployeeManager();
        $this->leaveModel = new M_Leave();
        $this->attendanceModel = new M_Attendance();
        $this->taskModel = new M_Task();
        $this->evaluationModel = new M_Evaluation();
        $this->salaryModel = new M_Salary();
        $this->userHelper = new UserHelper();
    }

    public function index() {
        // Get dashboard stats
        $stats = $this->employeeManagerModel->getDashboardStats();
        
        // Get recent activities
        $recentLeaves = $this->leaveModel->getRecentLeaveRequests();
        $todayAttendance = $this->attendanceModel->getTodayAttendance();
        $pendingEvaluations = $this->evaluationModel->getPendingEvaluations();

        $data = [
            'stats' => $stats,
            'recent_leaves' => $recentLeaves,
            'today_attendance' => $todayAttendance,
            'pending_evaluations' => $pendingEvaluations
        ];

        $this->view('employee_manager/dashboard', $data);
    }

    // Leave Management Methods
    public function leaves() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle leave request updates
            $requestId = $_POST['request_id'];
            $status = $_POST['status'];
            $managerId = $this->userHelper->getManagerId($_SESSION['user_id']);

            if ($this->employeeManagerModel->updateLeaveStatus($requestId, $status, $managerId)) {
                flash('leave_message', 'Leave request updated successfully');
            } else {
                flash('leave_message', 'Failed to update leave request', 'alert alert-danger');
            }
        }

        $leaveRequests = $this->employeeManagerModel->getLeaveRequests();
        $this->view('employee_manager/leaves', ['requests' => $leaveRequests]);
    }

    // Attendance Management Methods
    public function attendance() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle attendance marking
            $employeeId = $_POST['employee_id'];
            $type = $_POST['attendance_type'];

            if ($this->employeeManagerModel->markAttendance($employeeId, $type)) {
                flash('attendance_message', 'Attendance marked successfully');
            } else {
                flash('attendance_message', 'Failed to mark attendance', 'alert alert-danger');
            }
        }

        $attendanceData = $this->attendanceModel->getTodayAttendance();
        $this->view('employee_manager/attendance', ['attendance' => $attendanceData]);
    }

    // Employee Registration Methods
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Validate input
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'first_name' => trim($_POST['first_name']),
                'last_name' => trim($_POST['last_name']),
                'nic' => trim($_POST['nic']),
                'date_of_birth' => trim($_POST['date_of_birth']),
                'gender' => trim($_POST['gender']),
                'errors' => []
            ];

            // Validation checks
            if (empty($data['email'])) {
                $data['errors']['email'] = 'Please enter email';
            }
            if (empty($data['password'])) {
                $data['errors']['password'] = 'Please enter password';
            }
            if ($data['password'] !== $data['confirm_password']) {
                $data['errors']['confirm_password'] = 'Passwords do not match';
            }

            // If no errors, register employee
            if (empty($data['errors'])) {
                if ($this->employeeManagerModel->registerEmployee($data)) {
                    flash('register_success', 'Employee registered successfully');
                    redirect('employeemanager/employees');
                } else {
                    flash('register_error', 'Something went wrong', 'alert alert-danger');
                    $this->view('employee_manager/register', $data);
                }
            } else {
                $this->view('employee_manager/register', $data);
            }
        } else {
            // Initial form load
            $data = [
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'first_name' => '',
                'last_name' => '',
                'nic' => '',
                'date_of_birth' => '',
                'gender' => '',
                'errors' => []
            ];
            $this->view('employee_manager/register', $data);
        }
    }

    // Task Management Methods
    public function tasks() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle task assignment/updates
            $taskData = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'assigned_to' => $_POST['employee_id'],
                'due_date' => $_POST['due_date'],
                'priority' => $_POST['priority']
            ];

            if ($this->taskModel->createTask($taskData)) {
                flash('task_message', 'Task assigned successfully');
            } else {
                flash('task_message', 'Failed to assign task', 'alert alert-danger');
            }
        }

        $tasks = $this->taskModel->getAllTasks();
        $employees = $this->employeeManagerModel->getAllEmployees();
        $this->view('employee_manager/tasks', [
            'tasks' => $tasks,
            'employees' => $employees
        ]);
    }

    // Evaluation Methods
    public function evaluations() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle evaluation submission
            $evaluationData = [
                'employee_id' => $_POST['employee_id'],
                'rating' => $_POST['rating'],
                'comments' => $_POST['comments'],
                'evaluation_date' => date('Y-m-d'),
                'evaluator_id' => $_SESSION['user_id']
            ];

            if ($this->evaluationModel->submitEvaluation($evaluationData)) {
                flash('evaluation_message', 'Evaluation submitted successfully');
            } else {
                flash('evaluation_message', 'Failed to submit evaluation', 'alert alert-danger');
            }
        }

        $evaluations = $this->evaluationModel->getAllEvaluations();
        $this->view('employee_manager/evaluations', ['evaluations' => $evaluations]);
    }

    // Salary Management Methods
    public function salaries() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Handle salary processing
            $employeeId = $_POST['employee_id'];
            $month = $_POST['month'];
            $year = $_POST['year'];

            $salaryDetails = $this->employeeManagerModel->calculateSalary($employeeId, $month, $year);
            
            if ($this->salaryModel->processSalary($employeeId, $salaryDetails)) {
                flash('salary_message', 'Salary processed successfully');
            } else {
                flash('salary_message', 'Failed to process salary', 'alert alert-danger');
            }
        }

        $salaryData = $this->salaryModel->getAllSalaries();
        $this->view('employee_manager/salaries', ['salaries' => $salaryData]);
    }

    // Profile Management
    public function profile($id = null) {
        if ($id === null) {
            redirect('employeemanager');
        }

        $employee = $this->employeeManagerModel->getEmployeeById($id);
        if (!$employee) {
            flash('profile_message', 'Employee not found', 'alert alert-danger');
            redirect('employeemanager');
        }

        $data = [
            'employee' => $employee,
            'attendance' => $this->attendanceModel->getEmployeeAttendance($id),
            'leaves' => $this->leaveModel->getEmployeeLeaves($id),
            'evaluations' => $this->evaluationModel->getEmployeeEvaluations($id),
            'tasks' => $this->taskModel->getEmployeeTasks($id)
        ];

        $this->view('employee_manager/profile', $data);
    }

    // API Methods for AJAX Calls
    public function getAttendanceStats() {
        header('Content-Type: application/json');
        $stats = $this->attendanceModel->getMonthlyStats();
        echo json_encode($stats);
    }

    public function updateTaskStatus() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $result = $this->taskModel->updateStatus($data['task_id'], $data['status']);
            echo json_encode(['success' => $result]);
        }
    }

    public function getLeaveBalance($employeeId) {
        header('Content-Type: application/json');
        $balance = $this->leaveModel->getLeaveBalance($employeeId);
        echo json_encode($balance);
    }
}