<?php
class Collectionschedules extends Controller {
    private $collectionScheduleModel;

    public function __construct() {
        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
    }


    public function viewSchedule($scheduleId) {
        // Fetch the specific collection schedule details by its ID
        $schedule = $this->collectionScheduleModel->getScheduleDetails($scheduleId);
        

        // Pass the schedule data to the view
        $this->view('vehicle_manager/v_collection_schedule', [
            'schedule' => $schedule
        ]);
    }    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('manager/dashboard');
        }

        // Get and sanitize POST data
        $data = [
            'route_id' => trim($_POST['route_id']),
            'driver_id' => trim($_POST['driver_id']),
            'shift_id' => trim($_POST['shift_id']),
            'day' => trim($_POST['day'])
        ];

        // Debug: Print data
        error_log(print_r($data, true));

        // Validation rules
        $errors = [];


        // Validate day selection
        if (empty($data['day'])) {
            $errors[] = "Please select a day of the week";
        }


        if (!empty($errors)) {
            foreach ($errors as $error) {
                setFlashMessage('Error when creating the schedule! Error: ' . $error, 'error');
            }
            redirect('manager/');
            return;
        }



        // Create schedule for this day
        if (!$this->collectionScheduleModel->create($data)) {
            setFlashMessage("Error when creating the schedule for {$data['day']}", 'error');
            redirect('manager/');
            return;
        }

        setFlashMessage('Schedule creation successful!');
        redirect('manager/');
    }

    public function toggleActive() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schedule_id = $_POST['schedule_id'];
            
            if ($this->collectionScheduleModel->toggleActive($schedule_id)) {
                setFlashMessage('Schedule status updated sucessfully');
            } else {
                setFlashMessage('Unable to update the schedle status!', 'error');
            }
            
            redirect('manager/');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schedule_id = $_POST['schedule_id'];
            
            if ($this->collectionScheduleModel->delete($schedule_id)) {
                setFlashMessage('Schedule status deleted sucessfully');
            } else {
                setFlashMessage('Unable to delete the schedule', 'error');
            }
            
            redirect('manager/');
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'schedule_id' => $_POST['schedule_id'],
                'route_id' => $_POST['route_id'],
                'driver_id' => $_POST['driver_id'],
                'shift_id' => $_POST['shift_id'],
            ];
    
            // Check for schedule conflicts (excluding current schedule)
            if ($this->collectionScheduleModel->checkConflict($data)) {
                setFlashMessage('Cannot update the schedule, a conflict exists!', 'error');
                redirect('manager/');
                return;
            }
    
            if ($this->collectionScheduleModel->update($data)) {
                setFlashMessage('Schedule updated sucessfully');
            } else {
                setFlashMessage('Failed to update the schedule');
            }
    
            redirect('manager/');
        }
    }

    public function getSchedulesByWeek($weekNumber = null) {
        if (!$weekNumber) {
            $weekNumber = date('W') % 2 ? '1' : '2'; // Determine current week number (1 or 2)
        }

        $schedules = $this->collectionScheduleModel->getSchedulesByWeek($weekNumber);
        
        header('Content-Type: application/json');
        echo json_encode($schedules);
    }

    public function getSchedule($id) {
        $schedule = $this->collectionScheduleModel->getScheduleById($id);
        
        header('Content-Type: application/json');
        echo json_encode($schedule);
    }


    public function showCollectionSchedules() {
        $schedules = $this->collectionScheduleModel->getSchedulesForNextWeek();
        
        // Pass the schedules to the view
        $data = [
            'schedules' => $schedules,
        ];
        
        $this->view('vehicle_manager/v_collection', $data);
    }

    public function getScheduleDetails($scheduleId) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $schedule = $this->collectionScheduleModel->getScheduleById($scheduleId);
            
            if ($schedule) {
                echo json_encode(['success' => true, 'schedule' => $schedule]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Schedule not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
        }
    }
} 