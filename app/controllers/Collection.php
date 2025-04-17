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
        $collectionDetails = $this->collectionModel->getCollectionById($collectionId);  // tested but also repeated version of the lesser one
        if (!$collectionDetails) {
            header("Location: /error_page.php?message=Collection not found");
            exit;
        }
        
        $routeDetails = $this->routeModel->getRouteDetailsByCollection($collectionId);  // tested but date is null
        $scheduleId = $collectionDetails->schedule_id;
        $scheduleDetails = $this->collectionSchedulesModel->getScheduleDetails($scheduleId);    // tested, only 1 result
        $vehicleDetails = $this->vehicleModel->getVehicleIdByScheduleId($scheduleId);       // tested
        $driverId = $scheduleDetails->driver_id;
        $driverDetails = $this->driverModel->getDriverDetails($driverId);   // tested
        $collectionSuppliersDetails = $this->collectionModel->getCollectionSuppliers($collectionId);    // only the distinct csr records nothing detailed tho

        $csrCount = $this->collectionModel->getCollectionSuppliersCount($collectionId); // tested, we get the total_suppliers, collected_count and remaining_count
        $totalSuppliers = $csrCount->total_suppliers;
        $remainingSuppliers = $csrCount->remaining_count;

        $vehicleRemainingCapacity = $vehicleDetails->capacity - $collectionDetails->total_quantity;

        // need to send the bags logs also
        $logModel = $this->model('M_Log');
        $bagLogs = $logModel->getBagLogsByCollection($collectionId);    // tested

        $leafModel =$this->model('M_Dashbord');
        $leafTypes =$leafModel->getLeafTypes();

        $vehicleLocation = $this->vehicleModel->getVehicleLocation($scheduleDetails->vehicle_id);   // working




        $data = [
            'routeDetails' => $routeDetails,
            'vehicleDetails' => $vehicleDetails,
            'driverDetails' => $driverDetails,
            'collectionSuppliersDetails' => $collectionSuppliersDetails,
            'totalSuppliers' => $totalSuppliers,
            'collectionDetails' => $collectionDetails,
            'remainingSuppliers' => $remainingSuppliers,
            'vehicleRemainingCapacity' => $vehicleRemainingCapacity,
            'bagLogs' => $bagLogs,
            'leafTypes' => $leafTypes,
            'vehicleLocation' => $vehicleLocation
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