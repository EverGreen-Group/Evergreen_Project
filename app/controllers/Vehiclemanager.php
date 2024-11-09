<?php
require_once '../app/models/M_VehicleManager.php';
require_once '../app/models/M_Route.php';      // Add Route model
require_once '../app/models/M_Team.php';       // Add Team model
require_once '../app/models/M_Vehicle.php';    // Add Vehicle model
require_once '../app/models/M_Shift.php';      // Add Shift model
require_once '../app/models/M_CollectionSkeleton.php';  // Add CollectionSkeleton model
require_once '../app/helpers/auth_middleware.php';

class VehicleManager extends Controller {
    private $vehicleManagerModel;
    private $routeModel;       // Declare a variable for Route model
    private $teamModel;        // Declare a variable for Team model
    private $vehicleModel;     // Declare a variable for Vehicle model
    private $shiftModel;       // Declare a variable for Shift model
    private $skeletonModel;     // Declare a variable for CollectionSkeleton model
    

    public function __construct() {
        // Check if user is logged in
        requireAuth();
        
        // Check if user has Vehicle Manager OR Admin role
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            // Redirect unauthorized access
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        // Initialize models
        $this->vehicleManagerModel = new M_VehicleManager();
        $this->routeModel = new M_Route();        // Instantiate Route model
        $this->teamModel = new M_Team();          // Instantiate Team model
        $this->vehicleModel = new M_Vehicle();    // Instantiate Vehicle model
        $this->shiftModel = new M_Shift();        // Instantiate Shift model
        $this->skeletonModel = new M_CollectionSkeleton();  // Instantiate CollectionSkeleton model
    }

    public function index() {
        // Get dashboard stats from the model
        $stats = $this->vehicleManagerModel->getDashboardStats();

        // Fetch all necessary data for the dropdowns
        $routes = $this->routeModel->getAllRoutes();
        $teams = $this->teamModel->getAllTeams();
        $vehicles = $this->vehicleModel->getAllVehicles();
        $shifts = $this->shiftModel->getAllShifts();
        $skeletons = $this->skeletonModel->getAllSkeletons();

        // Pass the stats and data for the dropdowns to the view
        $this->view('vehicle_manager/v_dashboard', [
            'stats' => $stats,
            'routes' => $routes,
            'teams' => $teams,
            'vehicles' => $vehicles,
            'shifts' => $shifts,
            'skeletons' => $skeletons
        ]);
    }

    // Add method to handle the assignment of collections
    public function createSkeleton() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $route_id = $_POST['route_id'];
            $team_id = $_POST['team_id'];
            $vehicle_id = $_POST['vehicle_id'];
            $shift_id = $_POST['shift_id'];
    
            // Call the model to create a new skeleton
            $result = $this->collectionsModel->createSkeleton($route_id, $team_id, $vehicle_id, $shift_id);
    
            if ($result) {
                // Redirect or display success message
                header('Location: /vehicle_manager/createSkeleton'); // Redirect back to the form
                exit;
            } else {
                // Handle the error
                echo "Error creating skeleton collection";
            }
        } else {
            // Show the form or handle appropriately
            $routes = $this->vehicleManagerModel->getRoutes();
            $teams = $this->vehicleManagerModel->getTeams();
            $vehicles = $this->vehicleManagerModel->getVehicles();
            $shifts = $this->vehicleManagerModel->getShifts();
            
            // Render the view
            $this->view('vehicle_manager/v_create_collection_skeleton', [
                'routes' => $routes,
                'teams' => $teams,
                'vehicles' => $vehicles,
                'shifts' => $shifts
            ]);
        }
    }
    
    

    // Other methods remain unchanged
    public function vehicle() {
        $data = [
            'totalVehicles' => $this->vehicleModel->getTotalVehicles(),
            'availableVehicles' => $this->vehicleModel->getAvailableVehicles(),
            'vehicles' => $this->vehicleModel->getVehicleDetails(),
            'vehicleTypeStats' => $this->vehicleModel->getVehicleTypeStats()
        ];

        $this->view('vehicle_manager/v_vehicle', $data);
    }

    public function team() {
        $teamStats = $this->teamModel->getTeamStatistics();
        $teams = $this->teamModel->getTeamsWithMembers();
        $unassignedDrivers = $this->teamModel->getUnassignedDrivers(); // Fetch unassigned drivers
        $unassignedPartners = $this->teamModel->getUnassignedPartner(); // Fetch unassigned partners

        $data = [
            'teamStats' => $teamStats,
            'teams' => $teams,
            'unassigned_drivers' => $unassignedDrivers, // Add unassigned drivers to the data array
            'unassigned_partners' => $unassignedPartners // Add unassigned partners to the data array
        ];
        
        $this->view('vehicle_manager/v_team', $data);
    }

    public function route() {
        $allRoutes = $this->routeModel->getAllRoutes();
        $totalRoutes = $this->routeModel->getTotalRoutes();
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();
        $unallocatedSuppliers = $this->routeModel->getUnallocatedSuppliers();
        $unassignedSuppliersList = $this->routeModel->getUnallocatedSupplierDetails();
        
        // Convert suppliers data to format expected by the map
        $suppliersForMap = array_map(function($supplier) {
            return [
                'id' => 'S' . $supplier->supplier_id, // Add 'S' prefix for consistency
                'name' => $supplier->supplier_name,
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ]
            ];
        }, $unallocatedSuppliers);
        
        $data = [
            'allRoutes' => $allRoutes,
            'totalRoutes' => $totalRoutes,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'unallocatedSuppliers' => $suppliersForMap,
            'unassignedSuppliersList' => $unassignedSuppliersList
        ];
        
        $this->view('vehicle_manager/v_route', $data);
    }

    public function shift() {
        $data = [];
        $this->view('vehicle_manager/v_shift', $data);
    }

    public function staff() {
        $data = [];
        $this->view('vehicle_manager/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('vehicle_manager/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('vehicle_manager/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }

    public function uploadVehicleImage() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['vehicle_image'])) {
            $vehicle_id = $_POST['vehicle_id'];
            $file = $_FILES['vehicle_image'];
            
            // Configure upload settings
            $upload_dir = 'uploads/vehicles/';
            $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $file_name = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $file_name;
            
            // Check file type
            $allowed_types = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_extension, $allowed_types)) {
                echo json_encode(['error' => 'Invalid file type']);
                return;
            }
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $file_path)) {
                // Save to database
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

    public function createVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

                // Create vehicle
                $result = $this->vehicleModel->createVehicle($_POST);
                
                if ($result === true) {
                    flash('vehicle_message', 'Vehicle Added Successfully', 'alert alert-success');
                    redirect('vehiclemanager/index');
                } else {
                    flash('vehicle_message', $result, 'alert alert-danger');
                    redirect('vehiclemanager/index');
                }
            } catch (Exception $e) {
                flash('vehicle_message', 'Error creating vehicle: ' . $e->getMessage(), 'alert alert-danger');
                redirect('vehiclemanager/index');
            }
        } else {
            redirect('vehiclemanager/index');
        }
    }

    public function updateVehicle() {
        // Prevent PHP errors from being output
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        
        // Set JSON header
        header('Content-Type: application/json');

        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method not allowed');
            }

            // Get JSON data from request body
            $json = file_get_contents('php://input');
            if (!$json) {
                throw new Exception('No data received');
            }

            $data = json_decode($json);
            if (!$data || !isset($data->vehicle_id)) {
                throw new Exception('Invalid data format');
            }

            // Log the received data for debugging
            error_log('Received vehicle update data: ' . print_r($data, true));

            $result = $this->vehicleModel->updateVehicle($data);
            
            if ($result === true) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Vehicle updated successfully'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => is_string($result) ? $result : 'Failed to update vehicle'
                ]);
            }

        } catch (Exception $e) {
            error_log('Vehicle Update Error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
        exit; // Ensure no additional output
    }

    public function getVehicleById($id) {
        $vehicle = $this->vehicleModel->getVehicleById($id);
        if ($vehicle) {
            echo json_encode($vehicle);
        } else {
            echo json_encode(['error' => 'Vehicle not found']);
        }
    }

    public function deleteVehicle() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $vehicleId = $_POST['vehicle_id'];
            
            if ($this->vehicleModel->deleteVehicle($vehicleId)) {
                flash('vehicle_message', 'Vehicle Deleted Successfully', 'alert alert-success');
            } else {
                flash('vehicle_message', 'Failed to delete vehicle', 'alert alert-danger');
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
    }

    public function createRoute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Log incoming data
                error_log('Received route creation request: ' . file_get_contents('php://input'));
                
                $json = file_get_contents('php://input');
                $routeData = json_decode($json);

                if (!$routeData) {
                    throw new Exception('Invalid JSON data received');
                }

                // Validate required fields
                if (empty($routeData->name)) {
                    throw new Exception('Route name is required');
                }

                if (empty($routeData->stops) || !is_array($routeData->stops)) {
                    throw new Exception('At least one supplier stop is required');
                }

                $result = $this->routeModel->createRoute($routeData);
                
                header('Content-Type: application/json');
                if ($result) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Route created successfully'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to create route in database'
                    ]);
                }

            } catch (Exception $e) {
                error_log('Route Creation Error: ' . $e->getMessage());
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
            exit;
        }
    }

    public function getRouteSuppliers($routeId) {
        error_log('getRouteSuppliers called with ID: ' . $routeId); // Debug log
        
        if (!$routeId) {
            error_log('No route ID provided');
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Route ID is required']);
            return;
        }

        try {
            $suppliers = $this->routeModel->getRouteSuppliers($routeId);
            error_log('Suppliers found: ' . print_r($suppliers, true)); // Debug log
            
            header('Content-Type: application/json');
            echo json_encode($suppliers);
        } catch (Exception $e) {
            error_log('Error in getRouteSuppliers: ' . $e->getMessage()); // Debug log
            header('Content-Type: application/json');
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>