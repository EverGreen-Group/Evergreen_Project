<?php
class Routes extends Controller {
    private $routeModel;
    private $supplierModel;

    public function __construct() {
        $this->routeModel = $this->model('M_Route');
        $this->supplierModel = $this->model('M_Supplier');
    }

    public function index() {
        $activeRoutes = $this->routeModel->getActiveRoutes();
        
        $data = [
            'title' => 'Route Planning',
            'routes' => $activeRoutes
        ];

        $this->view('vehicle_manager/v_route', $data);
    }

    public function calculate() {
        $data = [
            'title' => 'Calculate New Route'
        ];
        $this->view('vehicle_manager/v_route_calculate', $data);
    }

    public function getSupplierPoints() {
        if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
            die('Direct access not permitted');
        }

        $suppliers = $this->supplierModel->getActiveSuppliers();
        $points = [];

        foreach ($suppliers as $supplier) {
            $points[] = [
                'id' => $supplier->id,
                'x' => floatval($supplier->longitude),
                'y' => floatval($supplier->latitude)
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($points);
    }

    public function saveRoute() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeData = json_decode(file_get_contents("php://input"), true);
            
            $result = $this->routeModel->saveRoute($routeData);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        }
    }

    public function getRouteDetails($routeId = null) {
        if ($routeId === null) {
            redirect('routes');
        }

        $routeDetails = $this->routeModel->getRouteDetails($routeId);
        
        if ($routeDetails === null) {
            redirect('routes');
        }

        $data = [
            'title' => 'Route Details',
            'route' => $routeDetails['route'],
            'suppliers' => $routeDetails['suppliers']
        ];

        $this->view('vehicle_manager/v_route_details', $data);
    }

    public function updateStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $routeId = $_POST['route_id'] ?? null;
            $status = $_POST['status'] ?? null;

            if ($routeId && $status) {
                $result = $this->routeModel->updateRouteStatus($routeId, $status);
                
                header('Content-Type: application/json');
                echo json_encode(['success' => $result]);
            }
        }
    }

    public function delete($routeId = null) {
        if ($routeId === null) {
            redirect('routes');
        }

        $result = $this->routeModel->deleteRoute($routeId);
        
        if ($result) {
            flash('route_message', 'Route deleted successfully');
        } else {
            flash('route_message', 'Error deleting route', 'alert alert-danger');
        }
        
        redirect('routes');
    }

    public function getRoutesByDay($day) {
        // Fetch routes based on the selected day
        $routes = $this->routeModel->getRoutesByDay($day);
        echo json_encode(['routes' => $routes]);
    }


}