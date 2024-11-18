<?php
class Collectionschedules extends Controller {
    private $collectionScheduleModel;

    public function __construct() {
        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('vehiclemanager/dashboard');
        }

        // Get and sanitize POST data
        $data = [
            'route_id' => trim($_POST['route_id']),
            'team_id' => trim($_POST['team_id']),
            'vehicle_id' => trim($_POST['vehicle_id']),
            'shift_id' => trim($_POST['shift_id']),
            'week_number' => trim($_POST['week_number']),
            'days_of_week' => isset($_POST['days_of_week']) ? $_POST['days_of_week'] : []
        ];

        // Validation rules
        $errors = [];

        // Check if team is already assigned for the maximum allowed days
        if ($this->collectionScheduleModel->getTeamAssignmentCount($data['team_id']) >= 6) {
            $errors[] = "Teams cannot be assigned to more than 6 days per week";
        }

        // Check if vehicle is already assigned for the maximum allowed days
        if ($this->collectionScheduleModel->getVehicleAssignmentCount($data['vehicle_id']) >= 6) {
            $errors[] = "Vehicles cannot be assigned to more than 6 days per week";
        }

        // Validate days selection
        if (empty($data['days_of_week'])) {
            $errors[] = "Please select at least one day of the week";
        }

        // Check if selected days would exceed maximum days for team or vehicle
        $existingTeamDays = $this->collectionScheduleModel->getTeamAssignedDays($data['team_id']);
        $existingVehicleDays = $this->collectionScheduleModel->getVehicleAssignedDays($data['vehicle_id']);
        $newTotalTeamDays = count(array_unique(array_merge($existingTeamDays, $data['days_of_week'])));
        $newTotalVehicleDays = count(array_unique(array_merge($existingVehicleDays, $data['days_of_week'])));

        if ($newTotalTeamDays > 6) {
            $errors[] = "This assignment would exceed the maximum of 6 days per week for the team";
        }
        if ($newTotalVehicleDays > 6) {
            $errors[] = "This assignment would exceed the maximum of 6 days per week for the vehicle";
        }

        // If there are any errors, display them and redirect
        if (!empty($errors)) {
            foreach ($errors as $error) {
                flash('schedule_create_error', $error, 'alert alert-danger');
            }
            redirect('vehiclemanager/');
            return;
        }

        // Check for schedule conflicts
        foreach ($data['days_of_week'] as $day) {
            $checkData = array_merge($data, ['day' => $day]);
            if ($this->collectionScheduleModel->checkConflict($checkData)) {
                flash('schedule_create_error', "Schedule conflict detected for $day", 'alert alert-danger');
                redirect('vehiclemanager/');
                return;
            }
        }

        // Attempt to create schedule
        if ($this->collectionScheduleModel->create($data)) {
            flash('schedule_create_success', 'Collection schedule created successfully!', 'alert alert-success');
        } else {
            flash('schedule_create_error', 'Failed to create collection schedule', 'alert alert-danger');
        }

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
                'team_id' => $_POST['team_id'],
                'vehicle_id' => $_POST['vehicle_id'],
                'shift_id' => $_POST['shift_id'],
                'week_number' => $_POST['week_number'],
                'days_of_week' => isset($_POST['days_of_week']) ? $_POST['days_of_week'] : []
            ];

            // Validate days selection
            if (empty($data['days_of_week'])) {
                flash('schedule_error', 'Please select at least one day of the week', 'alert alert-danger');
                redirect('vehiclemanager/');
                return;
            }

            // Check for schedule conflicts (excluding current schedule)
            foreach ($data['days_of_week'] as $day) {
                $checkData = array_merge($data, ['day' => $day]);
                if ($this->collectionScheduleModel->checkConflict($checkData)) {
                    flash('schedule_error', "Schedule conflict detected for $day", 'alert alert-danger');
                    redirect('vehiclemanager/');
                    return;
                }
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

} 