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

        // MAP SECTION

        $suppliers = $this->routeModel->getSuppliersInCollection($collectionId);
        
        // Format suppliers for the map
        $suppliersForMap = array_map(function($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'name' => $supplier->full_name, // Changed from supplier_name to full_name
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ],
                'average_collection' => $supplier->average_collection,
                'approval_status' => $supplier->approval_status

            ];
        }, $suppliers);


        $data = [
            'routeDetails' => $routeDetails,
            'vehicleDetails' => $vehicleDetails,
            'driverDetails' => $driverDetails,
            'collectionSuppliersDetails' => $collectionSuppliersDetails,
            'totalSuppliers' => $totalSuppliers,
            'remainingSuppliers' => $remainingSuppliers,
            'vehicleRemainingCapacity' => $vehicleRemainingCapacity,
            'suppliersForMap' => $suppliersForMap
        ];

        $this->view('vehicle_manager/v_collection_2', $data);
    }
}





?>