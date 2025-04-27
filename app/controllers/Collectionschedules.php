<?php
class Collectionschedules extends Controller {
    private $collectionScheduleModel;
    private $logModel;

    public function __construct() {
        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
        $this->logModel = $this->model('M_Log');
    }


    public function viewSchedule($scheduleId) {
        // Fetch the specific collection schedule details by its ID
        $schedule = $this->collectionScheduleModel->getScheduleDetails($scheduleId);
        

        // Pass the schedule data to the view
        $this->view('vehicle_manager/v_collection_schedule', [
            'schedule' => $schedule
        ]);
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
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Schedule with ID {$schedule_id} deleted successfully.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage('Schedule status deleted successfully');
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

            if ($this->collectionScheduleModel->checkConflict($data)) {
                setFlashMessage('Cannot update the schedule, a conflict exists!', 'error');
                redirect('manager/');
                return;
            }
    
            if ($this->collectionScheduleModel->update($data)) {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Schedule with ID {$data['schedule_id']} updated successfully.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
                setFlashMessage('Schedule updated successfully');
            } else {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Failed to update schedule with ID {$data['schedule_id']}.",
                    $_SERVER['REQUEST_URI'],     
                    http_response_code()     
                );
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