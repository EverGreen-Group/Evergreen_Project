<?php
require_once '../app/helpers/auth_middleware.php';
class Route extends Controller{
    private $collectionModel;
    private $routeModel;
    private $driverModel;
    private $vehicleModel;
    private $collectionSupplierRecordModel;
    private $collectionSchedulesModel;
    private $bagModel;


    public function __construct(){
        requireAuth();

        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::MANAGER])) {

            redirect('');
            exit();
        }

        $this->collectionModel = $this->model('M_Collection');
        $this->routeModel = $this->model('M_Route');
        $this->driverModel = $this->model('M_Driver');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->collectionSchedulesModel = $this->model('M_CollectionSchedule');
        $this->bagModel = $this->model('M_Bag');
    }

    public function index() {
        requireAuth();

        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::MANAGER])) {

            redirect('');
            exit();
        }
        redirect('/../route/route/');
    }


    public function route() {
        $allRoutes        = $this->routeModel->getAllUndeletedRoutes();
        $totalRoutes      = $this->routeModel->getTotalRoutes();
        $unassignedRoutes = $this->routeModel->getUnassignedRoutesCount();
        $totalActive      = $this->routeModel->getTotalActiveRoutes();
        $totalInactive    = $this->routeModel->getTotalInactiveRoutes();
        $availableVehicles= $this->vehicleModel->getAllAvailableVehicles();
    

        $data = [
            'allRoutes'         => $allRoutes,
            'totalRoutes'       => $totalRoutes,
            'totalActive'       => $totalActive,
            'totalInactive'     => $totalInactive,
            'unassignedRoutes'  => $unassignedRoutes,
            'availableVehicles' => $availableVehicles,
            'searchString'      => ''    
        ];
    
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['search'])) {
            $searchString = trim($_GET['search']);
            $searchResults = $this->routeModel->search($searchString);
        
            $data['allRoutes'] = $searchResults; 
            $data['searchString'] = $searchString; 
            $data['totalRoutes'] = count($searchResults);
        }
        
    
        $this->view('vehicle_manager/routes/v_route', $data);
    }
    

    //  =====================================================
    // VIEW FILES METHODS HERE
    // ===================================================== 

    public function manageRoute($routeId) {
        $routeDetails = $this->routeModel->getRouteById($routeId);  
        $vehicleId = $routeDetails->vehicle_id;
        $vehicleDetails = $this->vehicleModel->getVehicleByVehicleId($vehicleId);
        
        $unassignedSuppliers = $this->routeModel->getUnallocatedSuppliers();
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        $data = [
            'route_id' => $routeId,
            'route_name' => $routeDetails->route_name,
            'number_of_suppliers' => $routeDetails->number_of_suppliers,
            'vehicle_id' => $vehicleId,
            'vehicleDetails' => $vehicleDetails,
            'remainingCapacity' => $routeDetails->remaining_capacity,
            'unassignedSuppliers' => $unassignedSuppliers, 
            'routeSuppliers' => $routeSuppliers 
        ];

        $this->view('vehicle_manager/routes/v_manage_route', $data);
    }

    public function viewMap($routeId) {
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        usort($routeSuppliers, function($a, $b) {
            return $a->stop_order - $b->stop_order;
        });
    
        $data = [
            'route_id' => $routeId,
            'routeSuppliers' => $routeSuppliers
        ];
    
        $this->view('vehicle_manager/routes/v_route_map', $data);
    }
    


    public function getRouteSuppliers($routeId) {
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        header('Content-Type: application/json');
        echo json_encode($routeSuppliers);
        exit();
    }

    public function getUnassignedSuppliers() {
        
    }



    public function createRoute() { 
        $availableVehicles = $this->vehicleModel->getAllAvailableVehicles();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeName = trim($_POST['route_name']);
            $vehicleId = trim($_POST['vehicle_id']);

            if (empty($routeName) || empty($vehicleId)) {
                setFlashMessage('Please fill in all fields.', 'error');
                return;
            }

            if (!$this->routeModel->createRoute($routeName, $vehicleId)) {
                setFlashMessage('Route name already exists!', 'error');
                header('Location: ' . URLROOT . '/route/createRoute');
                return;
            }

            setFlashMessage('Route created successfully!');
            header('Location: ' . URLROOT . '/route/'); 
            exit();
        }

        $data = [
            'availableVehicles' => $availableVehicles
        ];

        $this->view('vehicle_manager/routes/v_create_route', $data);
    }

    public function editRoute($id = null) { 
        if (!$id) {
            setFlashMessage('Invalid route ID', 'error');
            redirect('route');
        }
        
        $route = $this->routeModel->getRouteById($id);
        
        if (!$route) {
            setFlashMessage('Route not found', 'error');
            redirect('route');
        }

        $isAssigned = $this->routeModel->isRouteAssigned($id);
        if ($isAssigned) {
            setFlashMessage('Cannot edit assigned route.', 'error');
            redirect('route');
        }
        
        $availableVehicles = $this->vehicleModel->getAllAvailableVehicles();
        $currentVehicle = $this->vehicleModel->getVehicleById($route->vehicle_id);
        
        $currentVehicleInList = false;
        foreach ($availableVehicles as $vehicle) {
            if ($vehicle->vehicle_id == $route->vehicle_id) {
                $currentVehicleInList = true;
                break;
            }
        }
        
        if (!$currentVehicleInList && $currentVehicle) {
            array_unshift($availableVehicles, $currentVehicle);
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeName = trim($_POST['route_name']);
            $vehicleId = trim($_POST['vehicle_id']);
            
            if (empty($routeName) || empty($vehicleId)) {
                setFlashMessage('Please fill in all fields.', 'error');
                $data = [
                    'route' => $route,
                    'availableVehicles' => $availableVehicles
                ];
                $this->view('vehicle_manager/routes/v_edit_route', $data);
                return;
            }
            
            if ($routeName !== $route->route_name && $this->routeModel->isDuplicateRouteName($routeName)) {
                setFlashMessage('Route name already exists!', 'error');
                $data = [
                    'route' => $route,
                    'availableVehicles' => $availableVehicles
                ];
                $this->view('vehicle_manager/routes/v_edit_route', $data);
                return;
            }
            
            if ($this->routeModel->editRoute($id, $routeName, $vehicleId)) {
                setFlashMessage('Route updated successfully!');
                redirect('route');
            } else {
                setFlashMessage('Selected vehicle does not have enough capacity for current suppliers.', 'error');
                $data = [
                    'route' => $route,
                    'availableVehicles' => $availableVehicles
                ];
                $this->view('vehicle_manager/routes/v_edit_route', $data);
            }
        }
        
        $data = [
            'route' => $route,
            'availableVehicles' => $availableVehicles
        ];
        
        $this->view('vehicle_manager/routes/v_edit_route', $data);
    }

    public function deleteRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $route_id = $_POST['route_id'];


            if ($this->routeModel->deleteRoute($route_id)) {
                setFlashMessage('Route deleted successfully!');
                redirect('route/');
            } else {

                redirect('route/');
            }
        }
    }

    public function getAvailableVehicles($day)
    {

        ob_clean(); 

        try {
            $vehicles = $this->vehicleModel->getAvailableVehiclesByDay($day);

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $vehicles,
                'message' => 'Vehicles retrieved successfully'
            ]);
            exit;
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }


    public function addSupplier() { 
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplierId = $_POST['supplier_id'];
            $routeId = $_POST['route_id'];

            if (!$supplierId) {
                setFlashMessage('Supplier not found', 'error');
                redirect('route/manageRoute/' . $routeId);
            }
            $supplierModel = $this->model('M_Supplier');
            $supplierDetails =$supplierModel->getSupplierById($supplierId);
            $routeDetails = $this->routeModel->getRouteById($routeId);
            $remainingCapacity = $routeDetails->remaining_capacity;

            if ($remainingCapacity < $supplierDetails->average_collection) {
                setFlashMessage('Cannot add supplier. Exceeding the capacity', 'error');
                redirect('route/manageRoute/' . $routeId);
            }

            $tempStopOrder = 22001913;
            if ($this->routeModel->addSupplierToRoute($routeId, $supplierId, $tempStopOrder)) {
                $this->routeModel->updateRemainingCapacity($routeId, 'add');
                $this->routeModel->optimizeRouteStopOrders($routeId);
                setFlashMessage('Supplier added successfully!');
                
                header('Location: ' . URLROOT . '/route/manageRoute/' . $routeId);
                exit();
            }
        }
    }
    
    public function removeSupplier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeId = $_POST['route_id'];
            $supplierId = $_POST['supplier_id'];
            
            if ($this->routeModel->removeSupplierFromRoute($routeId, $supplierId)) {
                $this->routeModel->updateRemainingCapacity($routeId, 'remove');
                $this->routeModel->optimizeRouteStopOrders($routeId);
                setFlashMessage('Removed supplier successfully!');
                
                header('Location: ' . URLROOT . '/route/manageRoute/' . $routeId);
                exit();
            }
        }
        
        header('Location: ' . URLROOT . '/route/manageRoute/' . $_POST['route_id']);
        exit();
    }

    







}





?>