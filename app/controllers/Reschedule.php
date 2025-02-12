<?php

class Reschedule extends Controller{
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
        $this->view('vehicle_manager/v_reschedule', []);
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


}





?>