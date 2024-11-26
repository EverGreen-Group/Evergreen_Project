<?php
require_once '../app/models/M_Vehicle.php';
require_once '../app/models/M_CollectionSchedule.php';
require_once '../app/models/M_Collection.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';

class Vehicles extends Controller {
    private $vehicleModel;
    private $scheduleModel;
    private $collectionModel;

    public function __construct() {
        requireAuth();
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->vehicleModel = $this->model('M_Vehicle');
        $this->scheduleModel = $this->model('M_CollectionSchedule');
        $this->collectionModel = $this->model('M_Collection');
    }

    // Main vehicle listing/dashboard
    public function index() {
        $data = [
            'totalVehicles' => $this->vehicleModel->getTotalVehicles(),
            'availableVehicles' => $this->vehicleModel->getAvailableVehicles(),
            'vehicles' => $this->vehicleModel->getVehicleDetails(),
            'vehicleTypeStats' => $this->vehicleModel->getVehicleTypeStats()
        ];

        $this->view('vehicle_manager/v_vehicle', $data);
    }

    // Vehicle CRUD operations
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->handleVehicleSubmission();
        } else {
            $this->view('vehicle_manager/v_add_vehicle', ['title' => 'Add New Vehicle']);
        }
    }

    public function getById($id) {
        $vehicle = $this->vehicleModel->getVehicleById($id);
        echo json_encode($vehicle ?: ['error' => 'Vehicle not found']);
    }

    public function delete($id) {
        header('Content-Type: application/json');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            if ($this->vehicleModel->deleteVehicle($id)) {
                echo json_encode(['success' => true, 'message' => 'Vehicle deleted successfully']);
            } else {
                throw new Exception('Failed to delete vehicle');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Vehicle image handling
    public function uploadImage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['vehicle_image'])) {
            $vehicle_id = $_POST['vehicle_id'];
            $file = $_FILES['vehicle_image'];
            
            $upload_dir = 'uploads/vehicles/';
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            if (!in_array($file_extension, ['jpg', 'jpeg', 'png'])) {
                echo json_encode(['error' => 'Invalid file type']);
                return;
            }
            
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                $result = $this->vehicleModel->saveVehicleDocument(
                    $vehicle_id,
                    'Image',
                    $file_path
                );
                echo json_encode(['success' => true, 'file_path' => $file_path]);
            } else {
                echo json_encode(['error' => 'Failed to upload file']);
            }
        }
    }

    // Vehicle usage check
    public function checkUsage($id) {
        $schedules = $this->scheduleModel->getSchedulesByVehicleId($id);
        $collections = $this->collectionModel->getCollectionsByVehicleId($id);

        echo json_encode([
            'inUse' => !empty($schedules) || !empty($collections),
            'schedules' => !empty($schedules),
            'collections' => !empty($collections)
        ]);
    }

    // Private helper methods
    private function handleVehicleSubmission() {
        try {
            $vehicleData = [
                'license_plate' => $_POST['license_plate'],
                'vehicle_type' => $_POST['vehicle_type'],
                'engine_number' => $_POST['engine_number'],
                'chassis_number' => $_POST['chassis_number'],
                'status' => $_POST['status'],
                'condition' => $_POST['condition'],
                'make' => $_POST['make'],
                'model' => $_POST['model'],
                'manufacturing_year' => $_POST['manufacturing_year'],
                'color' => $_POST['color'],
                'fuel_type' => $_POST['fuel_type'],
                'mileage' => $_POST['mileage'],
                'capacity' => $_POST['capacity'],
                'seating_capacity' => $_POST['seating_capacity'],
                'owner_name' => $_POST['owner_name'],
                'owner_contact' => $_POST['owner_contact'],
                'registration_date' => $_POST['registration_date'],
                'last_serviced_date' => $_POST['last_serviced_date'],
                'last_maintenance' => $_POST['last_maintenance'],
                'next_maintenance' => $_POST['next_maintenance']
            ];

            if (isset($_FILES['vehicle_image'])) {
                $this->handleVehicleImage($_FILES['vehicle_image'], $_POST['license_plate']);
            }

            if ($this->vehicleModel->createVehicle($vehicleData)) {
                flash('vehicle_message', 'Vehicle added successfully', 'alert-success');
                redirect('vehicles');
            } else {
                throw new Exception('Failed to add vehicle');
            }
        } catch (Exception $e) {
            flash('vehicle_message', 'Error: ' . $e->getMessage(), 'alert-danger');
            redirect('vehicles/create');
        }
    }

    private function handleVehicleImage($file, $licensePlate) {
        try {
            if ($file['error'] === UPLOAD_ERR_OK) {
                $uploadDir = UPLOADROOT . '/vehicle_photos/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileName = $licensePlate . '.jpg';
                $targetPath = $uploadDir . $fileName;

                return is_uploaded_file($file['tmp_name']) && 
                       move_uploaded_file($file['tmp_name'], $targetPath);
            }
            return false;
        } catch (Exception $e) {
            error_log("Error uploading vehicle image: " . $e->getMessage());
            return false;
        }
    }

    public function addVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'title' => 'Add New Vehicle'
            ];
            $this->view('vehicle_manager/v_add_vehicle', $data);
        } else {
            // Handle POST request
            $this->handleVehicleSubmission();
        }
    }

    public function updateVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $data = [
                'title' => 'Add New Vehicle'
            ];
            $this->view('vehicle_manager/v_update_vehicle', $data);
        } else {
            // Handle POST request
            $this->handleVehicleSubmission();
        }
    }
} 