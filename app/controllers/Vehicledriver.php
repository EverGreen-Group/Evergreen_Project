<?php
class VehicleDriver extends controller {
    private $collectionScheduleModel;
    private $driverModel;
    private $teamModel;
    private $vehicleModel;
    private $routeModel;

    public function __construct() {
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::DRIVER])) {
            // Redirect unauthorized access
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
        $this->driverModel = $this->model('M_Driver');
        $this->teamModel = $this->model('M_Team');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->routeModel = $this->model('M_Route');
    }

    public function index() {
        $data = [];  // Pass any necessary data here
        $this->view('vehicle_driver/v_dashboard', $data);
    }

    public function profile() {
        $data = [];
        $this->view('pages/profile', $data);
    }

    public function team() {
        $data = [];
        $this->view('vehicle_driver/v_team', $data);
    }

    public function route() {
        $data = [];
        $this->view('vehicle_driver/v_route', $data);
    }

    public function shift() {
        $driverModel = $this->model('M_Driver');
        $scheduleModel = $this->model('M_CollectionSchedule');
        
        // Get driver's team ID
        $driverDetails = $driverModel->getDriverDetails($_SESSION['user_id']);
        $teamId = $driverDetails->team_id ?? null;
    
        if (!$teamId) {
            $data = [
                'upcomingShifts' => [],
                'message' => 'No team assigned'
            ];
        } else {
            // Get upcoming schedules for the team
            $upcomingShifts = $scheduleModel->getUpcomingSchedules($teamId);
            $data = [
                'upcomingShifts' => $upcomingShifts,
                'currentTeam' => $driverDetails->current_team
            ];
        }
    
        $this->view('vehicle_driver/v_shift', $data);
    }
    
    public function scheduleDetails($scheduleId) {
        $schedule = $this->model('M_CollectionSchedule')->getScheduleById($scheduleId);
        if (!$schedule) {
            redirect('vehicledriver/shift');
        }

        $route = $this->model('M_Route')->getRouteById($schedule->route_id);
        $team = $this->model('M_Team')->getTeamById($schedule->team_id);
        $vehicle = $this->model('M_Vehicle')->getVehicleById($schedule->vehicle_id);

        $currentUserId = $_SESSION['user_id'];
        $userRole = RoleHelper::hasRole(RoleHelper::DRIVER) ? 'driver' : 
                   (RoleHelper::hasRole(RoleHelper::DRIVING_PARTNER) ? 'driving_partner' : null);

        $data = [
            'schedule' => $schedule,
            'route' => $route,
            'team' => $team,
            'vehicle' => $vehicle,
            'userRole' => $userRole,
            'isReady' => $this->model('M_CollectionSchedule')->isUserReady($scheduleId, $currentUserId)
        ];

        // Add this to get route suppliers
        $routeSuppliers = $this->routeModel->getRouteSuppliers($data['route']->route_id);
        $data['routeSuppliers'] = $routeSuppliers;

        $this->view('vehicle_driver/v_schedule_details', $data);
    }

    public function setReady($scheduleId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentUserId = $_SESSION['user_id'];
            
            // First check if collection exists, if not create it
            $collection = $this->collectionScheduleModel->getCollectionByScheduleId($scheduleId);
            if (!$collection) {
                $this->collectionScheduleModel->createInitialCollection($scheduleId);
            }
            
            // Then set the user as ready
            if ($this->collectionScheduleModel->setUserReady($scheduleId, $currentUserId)) {
                flash('schedule_message', 'You are marked as ready for this collection.');
            } else {
                flash('schedule_message', 'Something went wrong', 'alert alert-danger');
            }
            
            redirect('vehicledriver/scheduleDetails/' . $scheduleId);
        }
        redirect('vehicledriver/shift');
    }

    public function staff() {
        $data = [];
        $this->view('vehicle_driver/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('vehicle_driver/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('vehicle_driver/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }
}

?>
