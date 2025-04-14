<?php

class Collection extends Controller{
    private $collectionModel;
    private $routeModel;
    private $driverModel;
    private $vehicleModel;
    private $collectionSupplierRecordModel;
    private $collectionSchedulesModel;


    public function __construct(){
        $this->collectionModel = $this->model('M_Collection');
        $this->routeModel = $this->model('M_Route');
        $this->driverModel = $this->model('M_Driver');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->collectionSchedulesModel = $this->model('M_CollectionSchedule');
    }

    public function index(){
        $this->view('vehicle_manager/v_collection_2', []);
    }

    public function details($collectionId) {
        $collectionDetails = $this->collectionModel->getCollectionById($collectionId);
        if (!$collectionDetails) {
            header("Location: /error_page.php?message=Collection not found");
            exit;
        }
        
        $routeDetails = $this->routeModel->getRouteDetailsByCollection($collectionId);
        $scheduleId = $collectionDetails->schedule_id;
        $scheduleDetails = $this->collectionSchedulesModel->getScheduleDetails($scheduleId);
        $vehicleDetails = $this->vehicleModel->getVehicleIdByScheduleId($scheduleId);
        $driverId = $scheduleDetails->driver_id;
        $driverDetails = $this->driverModel->getDriverDetails($driverId);
        $collectionSuppliersDetails = $this->collectionModel->getCollectionSuppliers($collectionId);

        $csrCount = $this->collectionModel->getCollectionSuppliersCount($collectionId);
        $totalSuppliers = $csrCount->total_suppliers;
        $remainingSuppliers = $csrCount->remaining_count;

        $vehicleRemainingCapacity = $vehicleDetails->capacity - $collectionDetails->total_quantity;




        $data = [
            'routeDetails' => $routeDetails,
            'vehicleDetails' => $vehicleDetails,
            'driverDetails' => $driverDetails,
            'collectionSuppliersDetails' => $collectionSuppliersDetails,
            'totalSuppliers' => $totalSuppliers,
            'collectionDetails' => $collectionDetails,
            'remainingSuppliers' => $remainingSuppliers,
            'vehicleRemainingCapacity' => $vehicleRemainingCapacity
        ];

        $this->view('vehicle_manager/v_collection_2', $data);
    }

    public function updateBag() {
        // Check if the request is an AJAX request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the raw POST data
            $data = json_decode(file_get_contents("php://input"), true);

            // Validate the incoming data
            if (empty($data['bag_id']) || empty($data['actual_weight_kg'])) {
                echo json_encode(['success' => false, 'message' => 'Bag ID and weight are required.']);
                return;
            }

            $bagId = $data['bag_id'];
            $actualWeight = $data['actual_weight_kg'];
            $leafTypeId = $data['leaf_type_id'];
            $leafAge = $data['leaf_age'];
            $moistureLevel = $data['moisture_level'];
            $notes = $data['notes'];
            $supplierId = $data['supplier_id'];
            $collectionId = $data['collection_id'];


            // Call the model method to update the bag
            $updateResult = $this->bagModel->updateBag($bagId, $actualWeight, $leafTypeId, $leafAge, $moistureLevel, $notes, $supplierId, $collectionId);

            if ($updateResult) {
                echo json_encode(['success' => true, 'message' => 'Bag updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update bag.']);
            }
        } else {
            // Handle non-POST requests
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }
}





?>