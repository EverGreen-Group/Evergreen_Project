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

        // Format suppliers for the map/dropdown
        $suppliersForMap = array_map(function ($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'name' => $supplier->full_name, // Changed from supplier_name to full_name
                'preferred_day' => $supplier->preferred_day, // Include preferred_day
                'location' => [
                    'lat' => (float) $supplier->latitude,
                    'lng' => (float) $supplier->longitude
                ],
                'average_collection' => $supplier->average_collection,
                'number_of_collections' => $supplier->number_of_collections

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





}





?>