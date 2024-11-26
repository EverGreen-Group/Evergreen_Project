<?php
require_once '../app/models/M_Shift.php';
require_once '../app/models/M_CollectionSchedule.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';

class Shifts extends Controller {
    private $shiftModel;
    private $scheduleModel;

    public function __construct() {
        requireAuth();
        
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->shiftModel = new M_Shift();
        $this->scheduleModel = new M_CollectionSchedule();
    }

    public function index() {
        // Handle POST request for creating new shift
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Validate and sanitize input
                $data = [
                    'shift_name' => trim($_POST['shift_name']),
                    'start_time' => trim($_POST['start_time']),
                    'end_time' => trim($_POST['end_time'])
                ];

                // Check for duplicate shift name
                if ($this->shiftModel->isShiftNameDuplicate($data['shift_name'])) {
                    flash('shift_error', 'Shift name already exists', 'alert alert-danger');
                    redirect('vehiclemanager/shift');
                    return;
                }

                // Use addShift instead of createShift
                if ($this->shiftModel->addShift($data)) {
                    flash('shift_success', 'New shift created successfully');
                } else {
                    // Get specific error message from model
                    flash('shift_error', $this->shiftModel->getError() ?? 'Failed to create shift');
                }
                redirect('vehiclemanager/shift');
                return;
            } catch (Exception $e) {
                flash('shift_error', 'Error: ' . $e->getMessage());
                redirect('vehiclemanager/shift');
                return;
            }
        }

        // GET request - display shifts page
        $shifts = $this->shiftModel->getAllShifts();
        $totalShifts = $this->shiftModel->getTotalShifts();
        // $totalTeamsInCollection = $this->teamModel->getTotalTeamsInCollection();
        $totalTeamsInCollection = 2;
        
        // Initialize the schedules array
        $schedules = [];

        // Define the date range for the next 7 days
        $startDate = date('Y-m-d');
        $endDate = date('Y-m-d', strtotime('+6 days'));

        // Fetch schedules for each shift within the date range
        foreach ($shifts as $shift) {
            // Fetch schedules for the specific shift
            $shiftSchedules = $this->scheduleModel->getSchedulesByShiftIdAndDate($shift->shift_id, $startDate, $endDate);
            
            // Organize schedules by date
            foreach ($shiftSchedules as $schedule) {
                $date = date('Y-m-d', strtotime($schedule->created_at)); // Assuming schedule has a created_at field
                if (!isset($schedules[$shift->shift_id][$date])) {
                    $schedules[$shift->shift_id][$date] = [];
                }
                $schedules[$shift->shift_id][$date][] = $schedule; // Add the schedule to the appropriate date
            }
        }

        // Prepare data to pass to the view
        $data = [
            'shifts' => $shifts,
            'totalShifts' => $totalShifts,
            'totalTeamsInCollection' => $totalTeamsInCollection,
            'schedules' => $schedules // Pass the organized schedules to the view
        ];
        
        // Load the view with the data
        $this->view('vehicle_manager/v_shift', $data);
    }


    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $data = [
                    'shift_name' => trim($_POST['shift_name']),
                    'start_time' => trim($_POST['start_time']),
                    'end_time' => trim($_POST['end_time'])
                ];

                if ($this->shiftModel->isShiftNameDuplicate($data['shift_name'])) {
                    flash('shift_error', 'Shift name already exists', 'alert alert-danger');
                    redirect('shifts');
                    return;
                }

                if ($this->shiftModel->addShift($data)) {
                    flash('shift_success', 'New shift created successfully');
                } else {
                    flash('shift_error', $this->shiftModel->getError() ?? 'Failed to create shift');
                }
                redirect('shifts');
                return;
            } catch (Exception $e) {
                flash('shift_error', 'Error: ' . $e->getMessage());
                redirect('shifts');
                return;
            }
        }
        
        $this->view('vehicle_manager/v_shift_create');
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                if ($this->shiftModel->deleteShift($id)) {
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false]);
                }
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }
} 