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
        $totalActive = $this->routeModel->getTotalActiveRoutes();
        $totalInactive = $this->routeModel->getTotalInactiveRoutes();
        $unallocatedSuppliers = $this->routeModel->getUnallocatedSuppliers();


        $data = [
            'allRoutes' => $allRoutes,
            'totalRoutes' => $totalRoutes,
            'totalActive' => $totalActive,
            'totalInactive' => $totalInactive,
            'unassignedSuppliersList' => $unallocatedSuppliers
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
        
        // Prepare the data to be passed to the view
        $data = [
            'route_id' => $routeId,
            'route_name' => $routeDetails->route_name,
            'number_of_suppliers' => $routeDetails->number_of_suppliers,
            'vehicle_id' => $vehicleId,
            'day' => $routeDetails->day,
            'vehicleDetails' => $vehicleDetails
        ];

        // Load the view with the data
        $this->view('vehicle_manager/routes/v_manage_route', $data);

    }


    //  =====================================================
    // JSON/AJAX FETCH METHODS HERE
    // ===================================================== 

    public function getUnallocatedSuppliersByDay($day) {
        // Ensure the day parameter is valid
        if (!in_array($day, ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid day provided']);
            return;
        }
    
        // Fetch unallocated suppliers for the given day
        $suppliers = $this->routeModel->getUnallocatedSuppliersByDay($day);
        
        // Check if the suppliers query was successful
        if ($suppliers === false) {
            echo json_encode(['success' => false, 'message' => 'Error fetching suppliers']);
            return;
        }
    
        // Return the response
        echo json_encode(['success' => true, 'data' => $suppliers]);
    }

    public function getRouteSuppliers($routeId) {
        // Fetch bag details from the model using the collection ID
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($routeId);

        // Return the bag details as JSON
        header('Content-Type: application/json');
        echo json_encode($routeSuppliers);
        exit();
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
                $this->routeModel->updateRemainingCapacity($routeId);

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

            // Call the model method to remove the supplier from the route
            if ($this->routeModel->removeSupplierFromRoute($routeId, $supplierId)) {
                // Redirect or handle success
                header('Location: ' . URLROOT . '/route/manageRoute/' . $routeId);
                exit();
            } else {
                // Handle error
                // You can set an error message to display to the user
            }
        }
    }

    public function createRoute() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the form data
            $routeName = trim($_POST['route_name']);
            $routeDay = trim($_POST['route_day']);
            $vehicleId = trim($_POST['vehicle_id']);

            // Validate the input
            if (empty($routeName) || empty($routeDay) || empty($vehicleId)) {
                // Handle validation error (e.g., set an error message)
                // You can redirect back with an error message or set a session variable
                return;
            }

            // Call the model method to create the route
            if ($this->routeModel->createRoute($routeName, $routeDay, $vehicleId)) {
                // Redirect to the route management page or success page
                header('Location: ' . URLROOT . '/route/'); // Adjust the redirect as needed
                exit();
            } else {
                // Handle error (e.g., set an error message)
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





}





?>