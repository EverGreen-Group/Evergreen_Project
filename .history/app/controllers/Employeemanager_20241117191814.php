<?php
require_once '../app/models/M_User.php';
require_once '../app/models/M_Leave.php';
require_once '../app/models/M_Attendance.php';
require_once '../app/models/M_Task.php';
require_once '../app/models/M_Evaluation.php';

class EmployeeManager extends Controller {
    private $userModel;
    private $leaveModel;
    private $attendanceModel;
    private $taskModel;
    private $evaluationModel;

    public function __construct() {
        requireAuth();

        // Check if user has Employee Manager role
        if (!RoleHelper::hasRole(RoleHelper::EMPLOYEE_MANAGER)) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        // Initialize models
        $this->userModel = new M_User();
        $this->leaveModel = new M_Leave();
        $this->attendanceModel = new M_Attendance();
        $this->taskModel = new M_Task();
        $this->evaluationModel = new M_Evaluation();
    }

    public function index() {
        // Fetch data for dashboard
        $totalEmployees = $this->userModel->getTotalEmployees();
        $pendingLeaves = $this->leaveModel->getPendingLeaves();
        $tasks = $this->taskModel->getAllTasks();

        $data = [
            'totalEmployees' => $totalEmployees,
            'pendingLeaves' => $pendingLeaves,
            'tasks' => $tasks
        ];

        $this->view('employee_manager/v_dashboard', $data);
    }

    public function requestLeave() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'leave_type' => $_POST['leave_type'],
                'start_date' => $_POST['start_date'],
                'end_date' => $_POST['end_date'],
                'reason' => $_POST['reason']
            ];

            $success = $this->leaveModel->requestLeave($data);
            echo json_encode(['success' => $success]);
        }
    }

    public function viewAttendance() {
        $attendanceRecords = $this->attendanceModel->getAttendanceRecords($_SESSION['user_id']);
        $this->view('employee_manager/v_attendance', ['attendanceRecords' => $attendanceRecords]);
    }

    public function viewTasks() {
        $tasks = $this->taskModel->getAllTasks();
        $this->view('employee_manager/v_tasks', ['tasks' => $tasks]);
    }

    public function completeEvaluation() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'evaluation_score' => $_POST['evaluation_score'],
                'comments' => $_POST['comments']
            ];

            $success = $this->evaluationModel->submitEvaluation($data);
            echo json_encode(['success' => $success]);
        }
    }

    public function markAttendance() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'date' => date('Y-m-d'),
                'status' => $_POST['status']
            ];

            $success = $this->attendanceModel->markAttendance($data);
            echo json_encode(['success' => $success]);
        }
    }

    public function registerEmployee() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'email' => $_POST['email'],
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'first_name' => $_POST['first_name'],
                'last_name' => $_POST['last_name'],
                'nic' => $_POST['nic'],
                'date_of_birth' => $_POST['date_of_birth'],
                'gender' => $_POST['gender'],
                'role_id' => $_POST['role_id']
            ];

            $success = $this->userModel->registerEmployee($data);
            echo json_encode(['success' => $success]);
        }
    }
}
?>