<?php
require_once '../app/models/M_Route.php';
require_once '../app/models/M_Vehicle.php';
require_once '../app/models/M_Supplier.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/RoleHelper.php';

class Routes extends Controller {
    private $routeModel;
    private $vehicleModel;
    private $supplierModel;

    public function __construct() {
        requireAuth();
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::VEHICLE_MANAGER])) {
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->routeModel = $this->model('M_Route');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->supplierModel = $this->model('M_Supplier');
    }

    public function index() {
        $allRoutes = $this->routeModel->getAllRoutes();
        $totalRoutes = $this->routeModel->getTotalRoutes();
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();
        $unallocatedSuppliers = $this->routeModel->getUnallocatedSuppliers();
        
        // Format suppliers for the map/dropdown
        $suppliersForMap = array_map(function($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'name' => $supplier->full_name, // Changed from supplier_name to full_name
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
            'unassignedSuppliersList' => $unallocatedSuppliers
        ];

        $this->view('vehicle_manager/v_route', $data);
    }



    private function formatSuppliersForMap($suppliers) {
        return array_map(function($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'name' => $supplier->full_name,
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ]
            ];
        }, $suppliers);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            $vehicles = $this->vehicleModel->getUnassignedVehicles();
            $suppliers = $this->supplierModel->getAllUnallocatedSuppliers();
            $data = [
                'title' => 'Create Route',
                'vehicles' => $vehicles,
                'suppliers' => $suppliers
            ];
            $this->view('routes/v_create_route', $data);
        } 
        elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get POST data
            $data = json_decode(file_get_contents('php://input'));
            
            // Validate data
            if (!isset($data->route_name) || !isset($data->vehicle_id) || empty($data->suppliers)) {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                return;
            }

            try {
                $routeId = $this->routeModel->createRouteWithSuppliers([
                    'route_name' => $data->route_name,
                    'vehicle_id' => $data->vehicle_id,
                    'status' => $data->status,
                    'suppliers' => $data->suppliers
                ]);

                echo json_encode(['success' => true, 'route_id' => $routeId]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Get all active routes
            $routes = $this->routeModel->getAllRoutes();
            // Get all vehicles
            $vehicles = $this->vehicleModel->getAllVehicles();
            // Get unassigned suppliers
            $unassignedSuppliers = $this->supplierModel->getAllUnallocatedSuppliers();

            $data = [
                'title' => 'Update Route',
                'routes' => $routes,
                'vehicles' => $vehicles,
                'suppliers' => $unassignedSuppliers  // These are the unassigned suppliers
            ];

            $this->view('routes/v_update_route', $data);
        }
    }

    public function getDetails($routeId) {
        header('Content-Type: application/json');
        ob_clean();
        
        try {
            if (!$routeId) {
                throw new Exception('Route ID is required');
            }

            $routeDetails = $this->routeModel->getRouteById($routeId);
            if (!$routeDetails) {
                throw new Exception('Route not found');
            }

            $routeSuppliers = $this->routeModel->getRouteSuppliers($routeId);
            $response = [
                'success' => true,
                'route' => [
                    'id' => $routeId,
                    'name' => $routeDetails->route_name,
                    'status' => $routeDetails->status,
                    'suppliers' => array_map(function($supplier) {
                        return [
                            'id' => $supplier->supplier_id,
                            'name' => $supplier->full_name,
                            'coordinates' => [
                                'lat' => (float)$supplier->latitude,
                                'lng' => (float)$supplier->longitude
                            ]
                        ];
                    }, $routeSuppliers)
                ]
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            error_log("Error in getRouteDetails: " . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function getSuppliers($routeId) {
        header('Content-Type: application/json');
        
        try {
            $route = $this->routeModel->getRouteById($routeId);
            if (!$route) {
                throw new Exception('Route not found');
            }

            $suppliers = $this->routeModel->getRouteSuppliers($routeId);
            
            $response = [
                'success' => true,
                'data' => [
                    'route' => [
                        'id' => $route->route_id,
                        'name' => $route->route_name,
                        'status' => $route->status,
                        'date' => $route->date,
                        'vehicle_id' => $route->vehicle_id,
                        'number_of_suppliers' => $route->number_of_suppliers
                    ],
                    'suppliers' => array_map(function($supplier) {
                        return [
                            'id' => $supplier->supplier_id,
                            'location' => [
                                'lat' => $supplier->latitude,
                                'lng' => $supplier->longitude
                            ],
                            'stop_order' => $supplier->stop_order,
                            'number_of_collections' => $supplier->number_of_collections,
                            'avg_collection' => $supplier->avg_collection
                        ];
                    }, $suppliers)
                ]
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            error_log('Error in getRouteSuppliers: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function delete($routeId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Invalid request method');
        }

        try {
            if ($this->routeModel->setRouteVisibility($routeId, 0)) {
                flash('route_message', 'Route deleted successfully', 'alert alert-success');
            } else {
                flash('route_message', 'Failed to delete route', 'alert alert-danger');
            }
        } catch (Exception $e) {
            flash('route_message', 'Error: ' . $e->getMessage(), 'alert alert-danger');
        }
        redirect('routes');
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            
            try {
                if (!isset($data->route_id) || !isset($data->status)) {
                    throw new Exception('Missing required fields');
                }

                $success = $this->routeModel->updateRouteStatus($data->route_id, $data->status);
                echo json_encode(['success' => $success]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function updateSuppliers() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            
            try {
                if (!isset($data->route_id) || empty($data->suppliers)) {
                    throw new Exception('Missing required fields');
                }

                $success = $this->routeModel->updateRouteSuppliers($data->route_id, $data->suppliers);
                echo json_encode(['success' => $success]);
            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    public function getUnallocatedSuppliers() {
        header('Content-Type: application/json');
        try {
            $suppliers = $this->routeModel->getUnallocatedSuppliers();
            echo json_encode([
                'success' => true,
                'suppliers' => $this->formatSuppliersForMap($suppliers)
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    public function validateRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents('php://input'));
            
            try {
                $validation = $this->routeModel->validateRouteConfiguration([
                    'route_name' => $data->route_name ?? '',
                    'vehicle_id' => $data->vehicle_id ?? null,
                    'suppliers' => $data->suppliers ?? []
                ]);

                echo json_encode([
                    'success' => true,
                    'isValid' => $validation['isValid'],
                    'messages' => $validation['messages']
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }
    }
}