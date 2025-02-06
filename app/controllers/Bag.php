<?php

class Bag extends Controller{
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
        $this->view('vehicle_manager/v_collection_2', []);
    }

    public function getBagDetails($bagId) {
        // Check if the request is an AJAX request
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            $bagDetails = $this->bagModel->getBagById($bagId);
    
            if ($bagDetails) {
                echo json_encode(['success' => true, 'bag' => $bagDetails]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Bag not found.']);
            }
        } else {
            // Handle non-GET requests
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
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
    
            // Prepare the data for updating
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


    public function deleteBag($bagId) {
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
            // Call the model method to delete the bag
            $deleteResult = $this->bagModel->deleteBagById($bagId);
    
            if ($deleteResult) {
                echo json_encode(['success' => true, 'message' => 'Bag deleted successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete bag.']);
            }
        } else {
            // Handle non-DELETE requests
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function getSupplierBagDetails($collectionId,$supplierId) {
        // Fetch bag details from the model using the collection ID
        $bagDetails = $this->bagModel->getBagsByCollectionSupplier($collectionId, $supplierId);

        // Return the bag details as JSON
        header('Content-Type: application/json');
        echo json_encode($bagDetails);
        exit();
    }


    // WHEN THE SUPPLIER PRESSES CONFIRM ADDITION WHICH MEANS HE HAS CONFIRMED THE DRIVERS ADDITION AND THE DRIVER CAN NOW FINALIZE THIS SUPPLIERS COLLECTION
    public function confirmAddition() {
        // Check if the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get the supplier ID from the session
            $supplierId = $_SESSION['supplier_id'];
    
            // Get the JSON data from the request body
            $data = json_decode(file_get_contents("php://input"), true);
    
            // Validate the incoming data
            if (empty($data['collection_id'])) {
                echo json_encode(['success' => false, 'message' => 'Collection ID is required.']);
                return;
            }
    
    
            // Call the model method to update the supplier approval status
            $result = $this->bagModel->approveSupplierBags($supplierId, $data['collection_id']);
    
            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Supplier approval updated successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update supplier approval.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }


}





?>