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
            redirect('vehiclemanager/dashboard');
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

        // If there are any errors, display them and redirect
        if (!empty($errors)) {
            foreach ($errors as $error) {
                flash('schedule_create_error', $error, 'alert alert-danger');
            }
            redirect('vehiclemanager/');
            return;
        }



        // Create schedule for this day
        if (!$this->collectionScheduleModel->create($data)) {
            flash('schedule_create_error', "Failed to create schedule for {$data['day']}", 'alert alert-danger');
            redirect('vehiclemanager/');
            return;
        }

        flash('schedule_create_success', 'Collection schedule created successfully!', 'alert alert-success');
        redirect('vehiclemanager/');
    }

    public function toggleActive() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schedule_id = $_POST['schedule_id'];
            
            if ($this->collectionScheduleModel->toggleActive($schedule_id)) {
                flash('schedule_success', 'Schedule status updated successfully');
            } else {
                flash('schedule_error', 'Failed to update schedule status');
            }
            
            redirect('vehiclemanager/');
        }
    }

    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $schedule_id = $_POST['schedule_id'];
            
            if ($this->collectionScheduleModel->delete($schedule_id)) {
                flash('schedule_success', 'Schedule deleted successfully');
            } else {
                flash('schedule_error', 'Failed to delete schedule');
            }
            
            redirect('vehiclemanager/');
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
                flash('schedule_error', "Schedule conflict detected for the selected route", 'alert alert-danger');
                redirect('vehiclemanager/');
                return;
            }
    
            if ($this->collectionScheduleModel->update($data)) {
                flash('schedule_success', 'Collection schedule updated successfully');
            } else {
                flash('schedule_error', 'Failed to update collection schedule');
            }
    
            redirect('vehiclemanager/');
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