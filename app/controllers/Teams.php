<?php
require_once '../app/models/M_Team.php';
require_once '../app/models/M_Driver.php';
require_once '../app/models/M_Partner.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';
require_once '../app/helpers/UserHelper.php';

class Teams extends Controller {
    private $teamModel;
    private $driverModel;
    private $partnerModel;
    private $userHelper;

    public function __construct() {
        requireAuth();
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->teamModel = $this->model('M_Team');
        $this->driverModel = $this->model('M_Driver');
        $this->partnerModel = $this->model('M_Partner');
        $this->userHelper = new UserHelper();
    }

    public function index() {
        $data = [
            'teamStats' => $this->teamModel->getTeamStatistics(),
            'teams' => $this->teamModel->getTeamsWithMembers(),
            'unassigned_drivers' => $this->teamModel->getUnassignedDrivers(),
            'unassigned_partners' => $this->teamModel->getUnassignedPartner()
        ];
        
        $this->view('vehicle_manager/v_team', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'team_id' => trim($_POST['team_id']),
                'team_name' => trim($_POST['team_name']),
                'driver_id' => trim($_POST['driver_id']),
                'partner_id' => trim($_POST['partner_id']),
                'status' => trim($_POST['status'])
            ];

            if (empty($data['team_name'])) {
                flash('team_message', 'Please enter team name', 'alert alert-danger');
                redirect('teams');
                return;
            }

            if ($this->teamModel->updateTeam($data)) {
                flash('team_message', 'Team updated successfully', 'alert alert-success');
            } else {
                flash('team_message', 'Failed to update team', 'alert alert-danger');
            }
            redirect('teams');
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $manager_id = $this->userHelper->getManagerId($_SESSION['user_id']);
            if (!$manager_id) {
                die('Invalid manager access');
            }

            $data = [
                'team_name' => trim($_POST['team_name']),
                'driver_id' => !empty($_POST['driver_id']) ? trim($_POST['driver_id']) : null,
                'partner_id' => !empty($_POST['partner_id']) ? trim($_POST['partner_id']) : null,
                'status' => trim($_POST['status']),
                'manager_id' => $manager_id
            ];

            if (empty($data['team_name'])) {
                flash('team_message', 'Please enter team name', 'alert alert-danger');
                redirect('teams');
                return;
            }

            if ($this->teamModel->createTeam($data)) {
                flash('team_message', 'Team created successfully', 'alert alert-success');
            } else {
                flash('team_message', 'Failed to create team', 'alert alert-danger');
            }
            redirect('teams');
        } else {
            $this->view('teams', [
                'title' => 'Create Team'
            ]);
        }
    }

    public function delete($teamId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request method');
        }

        if ($this->teamModel->setTeamVisibility($teamId, 0)) {
            flash('team_message', 'Team deleted successfully', 'alert alert-success');
        } else {
            flash('team_message', 'Failed to delete team', 'alert alert-danger');
        }
        redirect('teams');
    }
} 