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
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 5; 
        $offset = ($page - 1) * $limit;

        $allRoutes = $this->routeModel->getAllUndeletedRoutes($limit, $offset);
        $totalRoutes = $this->routeModel->getTotalRoutes();
        $unassignedRoutes = $this->routeModel->getUnassignedRoutesCount();
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();

        $totalPages = ceil($totalRoutes / $limit);

        $availableVehicles = $this->vehicleModel->getAllAvailableVehicles();

        $data = [
            'allRoutes' => $allRoutes,
            'totalRoutes' => $totalRoutes,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'unassignedRoutes' => $unassignedRoutes,
            'availableVehicles' => $availableVehicles,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ];

        $this->view('vehicle_manager/routes/v_create_route', $data);
    }

    //  =====================================================
    // VIEW FILES METHODS HERE
    // ===================================================== 

    public function manageRoute($routeId) {
        // Fetch the route details from the model
        $routeDetails = $this->routeModel->getRouteById($routeId);
        $vehicleId = $routeDetails->vehicle_id;
        $vehicleDetails = $this->vehicleModel->getVehicleByVehicleId($vehicleId);
        
        // Fetch unassigned suppliers
        $unassignedSuppliers = $this->routeModel->getUnallocatedSuppliers();

        // Fetch route suppliers
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        // Prepare the data to be passed to the view
        $data = [
            'route_id' => $routeId,
            'route_name' => $routeDetails->route_name,
            'number_of_suppliers' => $routeDetails->number_of_suppliers,
            'vehicle_id' => $vehicleId,
            'vehicleDetails' => $vehicleDetails,
            'remainingCapacity' => $routeDetails->remaining_capacity,
            'unassignedSuppliers' => $unassignedSuppliers, // Pass unassigned suppliers to the view
            'routeSuppliers' => $routeSuppliers // Pass route suppliers to the view
        ];

        // Load the view with the data
        $this->view('vehicle_manager/routes/v_manage_route', $data);
    }

    public function viewMap($routeId) {
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);
    
        // Sort by stop_order
        usort($routeSuppliers, function($a, $b) {
            return $a->stop_order - $b->stop_order;
        });
    
        $data = [
            'route_id' => $routeId,
            'routeSuppliers' => $routeSuppliers
        ];
    
        $this->view('vehicle_manager/routes/v_route_map', $data);
    }
    


    //  =====================================================
    // JSON/AJAX FETCH METHODS HERE
    // ===================================================== 


    public function getRouteSuppliers($routeId) {
        // Fetch bag details from the model using the collection ID
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        header('Content-Type: application/json');
        echo json_encode($routeSuppliers);
        exit();
    }

    public function getUnassignedSuppliers() {
        
    }


    //  =====================================================
    // CONTROLLER CRUDS
    // ===================================================== 


    public function createRoute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeName = trim($_POST['route_name']);
            $vehicleId = trim($_POST['vehicle_id']);


            if (empty($routeName) || empty($vehicleId)) {
                return;
            }

            if ($this->routeModel->createRoute($routeName,$vehicleId)) {
                setFlashMessage('Route created successfully!');
                header('Location: ' . URLROOT . '/route/'); 
                exit();
            } else {
            }
        }
    }

    public function deleteRoute()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $route_id = $_POST['route_id'];


            if ($this->routeModel->deleteRoute($route_id)) {
                setFlashMessage('Route deleted successfully!');
                redirect('route/');
            } else {
                setFlashMessage('Route deletion failed!');
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
    
            // Fjust a temp value, can use null also, used my index number here.
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