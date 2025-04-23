<?php


class VehicleDriver extends controller {
    private $collectionScheduleModel;
    private $driverModel;
    private $vehicleModel;
    private $routeModel;
    private $collectionModel;
    private $scheduleModel;
    private $supplierModel;
    private $userModel;

    public function __construct() {
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::DRIVER])) {
            // Redirect unauthorized access
            redirect('');
            exit();
        }

        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
        $this->collectionModel = $this->model('M_Collection');
        $this->driverModel = $this->model('M_Driver');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->routeModel = $this->model('M_Route');
        $this->scheduleModel = $this->model('M_CollectionSchedule');
        $this->supplierModel = $this->model('M_Supplier');
        $this->userModel = $this->model('M_User');
    }


    public function index() {
        if (!isset($_SESSION['driver_id'])) {
            redirect('login');
            return;
        }

        $driverId = $_SESSION['driver_id'];

        $collectionId = $this->collectionModel->checkCollectionExists($driverId);
        if ($collectionId) {
            redirect('vehicledriver/collection/' . $collectionId);
            exit();
        }

        // Get today's schedule
        $schedule = $this->scheduleModel->getTodaysScheduleByDriverId($driverId);

        // Check if the schedule is empty
        if ($schedule) {
            $allSchedules = $this->scheduleModel->getAllAssignedSchedulesByDriverId($driverId);
            $data = [
                'schedule' => $schedule,
                'allSchedules' => $allSchedules
            ];

            // Check if the schedule has ended collections
            $count = $this->scheduleModel->checkEndedScheduleCollection($schedule->schedule_id);
            if ($count) {
                $data['collection_completed'] = 1;
            }
        } else {
            // Handle the case where there is no schedule for today
            $data = [
                'schedule' => null,
                'allSchedules' => $this->scheduleModel->getAllAssignedSchedulesByDriverId($driverId),
                'collection_completed' => 0 // or any other default value you want to set
            ];
        }

        $this->view('vehicle_driver/v_dashboard', $data);
    }
    

    // protected function isAjaxRequest() {
    //     return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    // }

    public function profile() {
        $data = [];
        $this->view('pages/profile', $data);
    }

    public function updateVehicle($vehicleId, $latitude, $longitude) { // TESTED
        // Ensure the parameters are valid
        if (is_numeric($vehicleId) && is_numeric($latitude) && is_numeric($longitude)) {
            $this->collectionModel->updateVehicleLocation($vehicleId, $latitude, $longitude);
        } else {

        }
    }

    


    // public function scheduleDetails($id) {
    //     // Get schedule details by ID
    //     $schedule = $this->model('M_CollectionSchedule')->getScheduleById($id);
    //     if (!$schedule) {
    //         redirect('vehicledriver/shift');
    //     }
    
    //     // Get related route and vehicle information
    //     $route = $this->model('M_Route')->getRouteById($schedule->route_id);
    //     $vehicle = $this->model('M_Vehicle')->getVehicleByRouteId($schedule->vehicle_id);
    
    //     // Get collection details for the schedule
    //     $collection = $this->collectionModel->getUpcomingCollectionDetailsByScheduleId($id);
        
    //     // Get route suppliers for the route
    //     $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($route->route_id);
    
    //     // Prepare default values for collection-related data
    //     $bagsAdded = $collection->bags_added ?? 0;
    //     $fertilizerDistributed = $collection->fertilizer_distributed ?? 0;
    //     $collectionCompleted = $collection && $collection->end_time !== null;
    
    //     // Prepare data to pass to the view
    //     $data = [
    //         'schedule' => $schedule,
    //         'route' => $route,
    //         'vehicle' => $vehicle,
    //         'collection' => $collection,
    //         'routeSuppliers' => $routeSuppliers,
    //         'bagsAdded' => $bagsAdded,
    //         'fertilizerDistributed' => $fertilizerDistributed,
    //         'collectionCompleted' => $collectionCompleted
    //     ];
    
    //     // Render the view with the data
    //     $this->view('vehicle_driver/v_schedule_details', $data);
    // }
    
    





    public function collection($collectionId) { // TESTED
        $collection = $this->collectionModel->getCollectionDetails($collectionId);
        $vehicleLocation = $this->vehicleModel->getVehicleLocation($collection->vehicle_id);
        $collectionSuppliers = $this->collectionScheduleModel->getCollectionSupplierRecords($collectionId);


        if($collection->collection_status !== 'Pending') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }

        
        $leafTypesResult = $this->collectionModel->getCollectionTeaLeafTypes();
        $leafTypes = $leafTypesResult['success'] ? $leafTypesResult['leafTypes'] : [];
    

        $formatSupplier = function($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'supplierName' => $supplier->supplier_name,
                'remarks' => 'Call upon arrival',
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ],
                'address' => $supplier->address ?? 'No address provided',
                'image' => $supplier->image_path,
                'estimatedCollection' => $supplier->average_collection,
                'status' => $supplier->status,
                'contact' => $supplier->contact_number,
                'arrival_time' => $supplier->arrival_time,
                'stop_order' => $supplier->stop_order ?? null
            ];
        };
    
        $formattedSuppliers = [];
        $currentSupplier = null;
        
        foreach ($collectionSuppliers as $supplier) {
            if (!$currentSupplier && !$supplier->arrival_time && $supplier->status != 'Collected' && $supplier->status != 'No Show') {
                $currentSupplier = $formatSupplier($supplier);
            }
            
            if ($supplier->status !== 'Collected') {
                $formattedSuppliers[] = $formatSupplier($supplier);
            }
        }
    
        $data = [
            'pageTitle' => 'Collection Route',
            'driverName' => $collection->first_name,
            'collections' => $formattedSuppliers,
            'collection' => $collection,
            'vehicleLocation' => $vehicleLocation,
            'currentSupplier' => $currentSupplier,
            'leafTypes' => $leafTypes
        ];
    
        $this->view('vehicle_driver/v_collection_route', $data);
    }


    public function getVehicleLocation($vehicleId) { 
        $vehicleLocation = $this->vehicleModel->getVehicleLocation($vehicleId);
        
        // Set proper JSON content type header
        header('Content-Type: application/json');
        
        if ($vehicleLocation) {
            // Return the JSON directly, don't use echo
            return json_encode($vehicleLocation);
        } else {
            // Add HTTP status code and return instead of echo
            http_response_code(404);
            return json_encode(['error' => 'Vehicle location not found']);
        }
        
        // Make sure nothing else is output after this
        exit;
    }



    // public function collectionStatus($collectionId) { // OLD PART I THINK, DIDNT COME ACROSS THIS
    //     $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
    //     if (!$collection) {
    //         redirect('vehicledriver/shift');
    //     }

    //     // Get all supplier records for this collection
    //     $collectionSupplierRecords = $this->collectionScheduleModel->getCollectionSuppliers($collectionId);
        
    //     // Find the current supplier (first one without arrival_time)
    //     $currentSupplier = null;
    //     $currentStopNumber = 0;
    //     foreach ($collectionSupplierRecords as $index => $record) {
    //         if (!$record->arrival_time and $record->status != 'Collected') {
    //             $currentSupplier = $record;
    //             $currentStopNumber = $index + 1;
    //             break;
    //         }
    //     }

    //     $data = [
    //         'collection' => $collection,
    //         'currentSupplier' => $currentSupplier,
    //         'currentStopNumber' => $currentStopNumber,
    //         'collectionSupplierRecords' => $collectionSupplierRecords
    //     ];

    //     $this->view('vehicle_driver/v_collection_status', $data);
    // }





    public function finalizeCollection($collectionId, $supplierId) { // WHEN WE ARE DONE WITH A SUPPLIER, TESTED

        $result = $this->collectionModel->finalizeSupplierCollection($collectionId, $supplierId);
        
        if ($result) {
            setFlashMessage('Collection finalized sucessfully!');
            redirect("vehicledriver/collection/$collectionId");
        } else {
            setFlashMessage('Failed to finalize the collection!', 'error');
            redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
        }
    }



    public function createCollection($scheduleId) { // TESTED
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
            $supplierCount = $this->routeModel->getSupplierCountByScheduleId($scheduleId);
            if ($supplierCount < 1) {
                redirect('vehicledriver/' . $scheduleId);
            }
    
            $collectionId = $this->collectionModel->createCollection($scheduleId);
    
            if ($collectionId) {
                $notificationModel = $this->model('M_Notification');
    
                $driverUserId = $_SESSION['user_id']; //THIS IS US in the driver pov
                $notificationModel->createNotification(
                    $driverUserId,
                    'Collection has started for' . $scheduleId,
                    'You have started the collection.',
                    ['link' => 'vehicledriver/']
                );
    
                // Notify the manager
                // $managerId = $this->userModel->getManagerIdByScheduleId($scheduleId);
                // $managerUserId = $this->userModel->getUserIdByManagerId($managerId);
                // if ($managerUserId) {
                //     $notificationModel->createNotification(
                //         $managerUserId,
                //         'Collection Started',
                //         'A collection has started for your schedule.',
                //         ['link' => 'collection/details/' . $collectionId]
                //     );
                // }
    
                // Notify each supplier
                $supplierIds = $this->userModel->getSupplierIdsByScheduleId($scheduleId);
                foreach ($supplierIds as $supplierId) {
                    $supplierUserId = $this->userModel->getUserIdBySupplierId($supplierId);
                    if ($supplierUserId) {
                        $notificationModel->createNotification(
                            $supplierUserId,
                            'Collection Started',
                            'The collection for your schedule has started.',
                            ['link' => 'supplier/']
                        );
                    }
                }
    
                redirect('vehicledriver/collection/' . $collectionId);
            } else {
                redirect('vehicledriver/');
            }
        } else {
            redirect('vehicledriver/' . $scheduleId);
        }
    }
    

    // public function endCollection() { // POSSIBLY A DUPLICATE FROM old version, we are using completeCollection
    //     // Get the JSON input
    //     $data = json_decode(file_get_contents("php://input"));
    
    //     if (isset($data->collection_id)) {
    //         $collectionId = $data->collection_id;
    //         $result = $this->collectionModel->finalizeCollection($collectionId);
    
    //         if ($result['success']) {
    //             // Notifications setup
    //             $notificationModel = $this->model('M_Notification');
    
    //             // Get the driver (current session user)
    //             $driverUserId = $_SESSION['user_id'];
    
    //             // Get the schedule_id from the collection
    //             $scheduleId = $this->collectionModel->getScheduleIdByCollectionId($collectionId);
    
    //             // Notify the driver
    //             $notificationModel->createNotification(
    //                 $driverUserId,
    //                 'Collection Ended',
    //                 'You have successfully ended the collection.',
    //                 ['link' => 'vehicledriver/']
    //             );
    

    //             // $managerId = $this->userModel->getManagerIdByScheduleId($scheduleId);
    //             // $managerUserId = $this->userModel->getUserIdByManagerId($managerId);
    //             // if ($managerUserId) {
    //             //     $notificationModel->createNotification(
    //             //         $managerUserId,
    //             //         'Collection Ended',
    //             //         'The collection for your schedule has been completed.',
    //             //         ['link' => 'collection/details/' . $collectionId]
    //             //     );
    //             // }
    
    //             // Notify all suppliers
    //             $supplierIds = $this->routeModel->getSupplierIdsByScheduleId($scheduleId);
    //             foreach ($supplierIds as $supplierId) {
    //                 $supplierUserId = $this->userModel->getUserIdBySupplierId($supplierId);
    //                 if ($supplierUserId) {
    //                     $notificationModel->createNotification(
    //                         $supplierUserId,
    //                         'Collection Completed',
    //                         'The collection for your schedule has ended.',
    //                         ['link' => 'supplier/collectionBags/' . $collectionId]
    //                     );
    //                 }
    //             }
    
    //             echo json_encode(['success' => true, 'message' => 'Collection ended successfully.']);
    //         } else {
    //             echo json_encode(['success' => false, 'message' => 'Failed to end collection.']);
    //         }
    //     } else {
    //         echo json_encode(['success' => false, 'message' => 'Invalid collection ID.']);
    //     }
    // }
    



    /**
     * Show collection bags for a specific supplier
     */
    public function collectionBags($collectionId, $supplierId) { // tested, the page we land right into after we created a collection
        // Get the collection details
        $collection = $this->collectionModel->getCollectionDetails($collectionId);

        if($collection->collection_status !== 'Pending') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }

        $collectionSupplierRecord = $this->collectionModel->getCollectionSupplierRecordDetails($collectionId,$supplierId);
        if($collectionSupplierRecord->status !== 'Added') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }
        
        // Get the supplier details
        $supplier = $this->supplierModel->getSupplierById($supplierId);
        
        // Get bags for this collection and supplier
        $bags = $this->collectionModel->getCollectionBags($collectionId, $supplierId);  // tested
        
        // Format supplier data for display
        $formattedSupplier = [
            'id' => $supplier->supplier_id,
            'supplierName' => $supplier->supplier_name,
            'image' => $supplier->image_path,
            'estimatedCollection' => $supplier->average_collection,
            'contact' => $supplier->contact_number
        ];
        
        $data = [
            'pageTitle' => 'Collection Bags',
            'collection' => $collection,
            'supplier' => $formattedSupplier,
            'bags' => $bags
        ];
        
        $this->view('vehicle_driver/v_collection_bags', $data);
    }

    /**
     * Show add bag form
     */
    public function addBag($collectionId, $supplierId) { // USING THIS OK, TESTED
        $collection = $this->collectionModel->getCollectionDetails($collectionId);
        $supplier = $this->supplierModel->getSupplierById($supplierId);


        if($collection->collection_status !== 'Pending') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }

        $collectionSupplierRecord = $this->collectionModel->getCollectionSupplierRecordDetails($collectionId,$supplierId);
        if($collectionSupplierRecord->status !== 'Added') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }
        
        $leafTypesResult = $this->collectionModel->getCollectionTeaLeafTypes();
        $leafTypes = $leafTypesResult['success'] ? $leafTypesResult['leafTypes'] : [];
        
        $formattedSupplier = [
            'id' => $supplier->supplier_id,
            'supplierName' => $supplier->supplier_name,
            'image' => $supplier->image_path,
            'estimatedCollection' => $supplier->average_collection,
            'contact' => $supplier->contact_number
        ];
        
        $data = [
            'pageTitle' => 'Add Collection Bag',
            'collection' => $collection,
            'supplier' => $formattedSupplier,
            'leafTypes' => $leafTypes
        ];
        // setFlashMessage("Added the bag successfully!");
        
        $this->view('vehicle_driver/v_collection_bag_add', $data);
    }

    /**
     * Save a new bag
     */
    public function saveBag() { // IS USED TESTED!
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('vehicledriver/');
        }

        $collectionId = filter_input(INPUT_POST, 'collection_id', FILTER_SANITIZE_NUMBER_INT);
        $supplierId = filter_input(INPUT_POST, 'supplier_id', FILTER_SANITIZE_NUMBER_INT);
        $bagId = filter_input(INPUT_POST, 'bag_id', FILTER_SANITIZE_STRING);
        $actualWeight = filter_input(INPUT_POST, 'actual_weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $leafType = filter_input(INPUT_POST, 'leaf_type', FILTER_SANITIZE_NUMBER_INT);
        $leafAge = filter_input(INPUT_POST, 'leaf_age', FILTER_SANITIZE_STRING);
        $moistureLevel = filter_input(INPUT_POST, 'moisture_level', FILTER_SANITIZE_STRING);
        $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
        
        // Validation for actual weight
        if ($actualWeight <= 0) {
            setFlashMessage('Weight must be a positive value.', 'error');
            redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
            return; 
        }

        if ($actualWeight > 40) {
            setFlashMessage('Weight cannot exceed capacity!', 'error');
            redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
            return; 
        }
        
        $bagData = [
            'collection_id' => $collectionId,
            'supplier_id' => $supplierId,
            'bag_id' => $bagId,
            'actual_weight' => $actualWeight,
            'leaf_type_id' => $leafType,
            'leaf_age' => $leafAge,
            'moisture_level' => $moistureLevel,
            'notes' => $notes
        ];
        
        $result = $this->collectionModel->saveBag($bagData);

        if (!$result['success']) {
            setFlashMessage($result['message'], 'error');
        } else {
            setFlashMessage("Assigned the bag to the supplier!");
        }
        redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
    }

    /**
     * Show update bag form
     */
    public function updateBag($collectionId, $supplierId, $bagId) { // TESTED


        // Get the bag details
        $bag = $this->collectionModel->getBagById($bagId, $collectionId);
        
        if (!$bag) {
            setFlashMessage('Bag not found, please retry!', 'error');
            redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
        }
        
        $collection = $this->collectionModel->getCollectionDetails($collectionId);

        if($collection->collection_status !== 'Pending') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }

        $collectionSupplierRecord = $this->collectionModel->getCollectionSupplierRecordDetails($collectionId,$supplierId);
        if($collectionSupplierRecord->status !== 'Added') {
            setFlashMessage("You have already completed this collection. You have no access here!", 'warning');
            redirect('vehicledriver/');
        }
        
        // Get the supplier details
        $supplier = $this->supplierModel->getSupplierById($supplierId);
        
        // Get leaf types for dropdown
        $leafTypesResult = $this->collectionModel->getCollectionTeaLeafTypes();
        $leafTypes = $leafTypesResult['success'] ? $leafTypesResult['leafTypes'] : [];
        
        
        $data = [
            'pageTitle' => 'Update Collection Bag',
            'collection' => $collection,
            'supplier_id' => $supplierId,
            'bag' => $bag,
            'leafTypes' => $leafTypes
        ];
        
        $this->view('vehicle_driver/v_collection_bag_update', $data);
    }

    /**
     * Update bag submission
     */
    public function updateBagSubmit() { //TESTED
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('vehicledriver/');
        }
        
        // Sanitize and validate POST data
        $historyId = filter_input(INPUT_POST, 'history_id', FILTER_SANITIZE_NUMBER_INT);
        $collectionId = filter_input(INPUT_POST, 'collection_id', FILTER_SANITIZE_NUMBER_INT);
        $supplierId = filter_input(INPUT_POST, 'supplier_id', FILTER_SANITIZE_NUMBER_INT);
        $bagId = filter_input(INPUT_POST, 'bag_id', FILTER_SANITIZE_STRING);
        $actualWeight = filter_input(INPUT_POST, 'actual_weight', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $leafType = filter_input(INPUT_POST, 'leaf_type', FILTER_SANITIZE_NUMBER_INT);
        $leafAge = filter_input(INPUT_POST, 'leaf_age', FILTER_SANITIZE_STRING);
        $moistureLevel = filter_input(INPUT_POST, 'moisture_level', FILTER_SANITIZE_STRING);
        $notes = filter_input(INPUT_POST, 'notes', FILTER_SANITIZE_STRING);
        
        // Create bag data array
        $bagData = [
            'history_id' => $historyId,
            'bag_id' => $bagId,
            'collection_id' => $collectionId,
            'supplier_id' => $supplierId,
            'actual_weight' => $actualWeight,
            'leaf_type' => $leafType,
            'leaf_age' => $leafAge,
            'moisture_level' => $moistureLevel,
            'notes' => $notes
        ];
        
        // Update the bag
        $result = $this->collectionModel->updateBag($bagData);
        
        if ($result['success']) {
            setFlashMessage($result['message']);
        } else {

            setFlashMessage($result['message'], 'error');
        }
        
        redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
    }


    public function removeBag($bagId, $collectionId, $supplierId) {  //TESTED
        // Delete the bag
        $this->collectionModel->deleteBag($bagId, $collectionId);
        setFlashMessage("Removed the bag sucessfully!");
        
        // Redirect back to bags list
        redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
    }

    public function getBagDetails($bagId = null) { // DIDNT COME ACROSS THIS YET IN NORMAL DRIVER PART
        // Settting to json tyipe
        header('Content-Type: application/json');
        

        if (!$bagId) {
            $bagId = isset($_POST['bag_id']) ? $_POST['bag_id'] : null;
        }
        

        if (!$bagId) {
            echo json_encode([
                'success' => false,
                'message' => 'No bag ID provided'
            ]);
            return;
        }
        

        $capacityResult = $this->collectionModel->getBagCapacity($bagId);
        

        echo json_encode([
            'success' => $capacityResult['success'],
            'capacity' => $capacityResult['capacity'],
            'bag_id' => $bagId
        ]);
    }

    public function completeCollection($collectionId) { // tESTED
        // Update the collection status to awaiting inventory addition
        $result = $this->collectionModel->completeCollection($collectionId);
        
        if ($result) {
            setFlashMessage('Collection completed sucessfully!');
        } else {
            setFlashMessage('Collection completion failed!');
        }
        
        redirect('vehicledriver/');
    }

    public function cancelSupplierCollection($collectionId, $supplierId) { // tESTED

        // We have to follow some steps. 
        // We intially need to check the bag_usage_history for that supplier_id and collection_id, 
        // if its empty we may proceed to the next step.



        
        // Check if there are any bags for this supplier in this collection
        $bags = $this->collectionModel->getBagsByCollectionAndSupplier($collectionId, $supplierId); // tested
        
        if (!empty($bags)) {
            // Cannot cancel if bags exist
            setFlashMessage('Cannot cancel collection for this supplier, there are bags already assigned. First remove them!', 'error');
            redirect("vehicledriver/collectionBags/$collectionId/$supplierId");
            return;
        }
        
        // Update the status to 'No Show'
        if ($this->collectionModel->updateSupplierCollectionStatus($collectionId, $supplierId, 'No Show')) {
            setFlashMessage('Marked the supplier as unavailable!');
        } else {
            setFlashMessage('Couldnt mark this supplier as unavailable!', 'error');
        }

        redirect("vehicledriver/collection/$collectionId");
    }

}

?>