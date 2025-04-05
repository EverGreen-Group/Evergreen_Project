<?php

class Route extends Controller{
    private $collectionModel;
    private $routeModel;
    private $driverModel;
    private $vehicleModel;
    private $collectionSupplierRecordModel;
    private $collectionSchedulesModel;
    private $bagModel;


    public function __construct(){
        $this->collectionModel = $this->model('M_Collection');
        $this->routeModel = $this->model('M_Route');
        $this->driverModel = $this->model('M_Driver');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->collectionSchedulesModel = $this->model('M_CollectionSchedule');
        $this->bagModel = $this->model('M_Bag');
    }

    public function index(){
        $allRoutes = $this->routeModel->getAllUndeletedRoutes();
        $totalRoutes = $this->routeModel->getTotalRoutes();
        $unassignedRoutes = $this->routeModel->getUnassignedRoutesCount();
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();

        $availableVehicles = $this->vehicleModel->getAllAvailableVehicles();

        $data = [
            'allRoutes' => $allRoutes,
            'totalRoutes' => $totalRoutes,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'unassignedRoutes' => $unassignedRoutes,
            'availableVehicles' => $availableVehicles
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


    //  =====================================================
    // JSON/AJAX FETCH METHODS HERE
    // ===================================================== 


    public function getRouteSuppliers($routeId) {
        // Fetch bag details from the model using the collection ID
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        // Return the bag details as JSON
        header('Content-Type: application/json');
        echo json_encode($routeSuppliers);
        exit();
    }

    public function getUnassignedSuppliers() {
        
    }


    //  =====================================================
    // CONTROLLER CRUDS
    // ===================================================== 

    public function addSupplier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplierId = $_POST['supplier_id'];
            $routeId = $_POST['route_id']; // Ensure you pass the route ID as well

            // Get the last stop order for the route
            $lastStopOrder = $this->routeModel->getLastStopOrder($routeId);
            $newStopOrder = $lastStopOrder + 1; // Increment for the new supplier

            // Call the model method to add the supplier to the route with the new stop order
            if ($this->routeModel->addSupplierToRoute($routeId, $supplierId, $newStopOrder)) {
                // Update the remaining capacity after adding the supplier
                $this->routeModel->updateRemainingCapacity($routeId, 'add');

                // Redirect or handle success
                header('Location: ' . URLROOT . '/route/manageRoute/' . $routeId);
                exit();
            } else {
                // Handle error
                // You can set an error message to display to the user
            }
        }
    }

    public function removeSupplier() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $supplierId = $_POST['supplier_id'];
            $routeId = $_POST['route_id'];

            if ($this->routeModel->removeSupplierFromRoute($routeId, $supplierId)) {
                $this->routeModel->updateRemainingCapacity($routeId, 'remove');
                header('Location: ' . URLROOT . '/route/manageRoute/' . $routeId);
                exit();
            } else {

            }
        }
    }

    public function createRoute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeName = trim($_POST['route_name']);
            $vehicleId = trim($_POST['vehicle_id']);


            if (empty($routeName) || empty($vehicleId)) {
                return;
            }

            if ($this->routeModel->createRoute($routeName,$vehicleId)) {
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

            // Call model method to delete route
            if ($this->routeModel->deleteRoute($route_id)) {
                // Redirect with success message
                flash('route_message', 'Route deleted successfully');
                redirect('route/');
            } else {
                // Redirect with error message
                flash('route_message', 'Failed to delete route', 'error');
                redirect('route/');
            }
        }
    }

    public function getAvailableVehicles($day)
    {
        // Make sure nothing is output before this
        ob_clean(); // Clear any previous output

        try {
            $vehicles = $this->vehicleModel->getAvailableVehiclesByDay($day);

            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'data' => $vehicles,
                'message' => 'Vehicles retrieved successfully'
            ]);
            exit; // End the script after sending JSON
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
            exit;
        }
    }

    public function toggleLock() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $routeId = $_POST['route_id'];

            // Call the model method to toggle the lock state
            if ($this->routeModel->toggleLock($routeId)) {
                // Redirect back to the route management page with a success message
                flash('route_message', 'Route lock state toggled successfully');
                redirect('route/');
            } else {
                // Handle error
                flash('route_message', 'Failed to toggle route lock state', 'error');
                redirect('route/');
            }
        } else {
            // If not a POST request, redirect or handle accordingly
            redirect('route/');
        }
    }





}





?>