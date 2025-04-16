<?php
// Update the include to use APPROOT instead of URLROOT
include_once APPROOT . '/services/GoogleMapsService.php';

class VehicleDriver extends controller {
    private $collectionScheduleModel;
    private $driverModel;
    private $vehicleModel;
    private $routeModel;
    private $collectionModel;
    private $googleMapsService;
    private $scheduleModel;

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
        $this->googleMapsService = new GoogleMapsService();
        $this->scheduleModel = $this->model('M_CollectionSchedule');
    }

    public function index() { // TESTED, must study this well, bit hard
        if (!isset($_SESSION['driver_id'])) {
            redirect('login');
            return;
        }
    
        $driverId = $_SESSION['driver_id'];
    
        // If a collection is already active for this driver, redirect immediately.
        $collectionId = $this->collectionModel->checkCollectionExists($driverId);
        if ($collectionId) {
            redirect('vehicledriver/collection/' . $collectionId);
            exit();
        }
        
        try {
            // Get all upcoming schedules for this driver
            $allSchedules = $this->scheduleModel->getUpcomingSchedules($driverId);
            
            // Determine today's day name, e.g., "Monday"
            $currentDay = date('l');
    
            // Initialize arrays for schedules today and in the future.
            $todaySchedules = [];
            $upcomingSchedules = [];
            
            foreach ($allSchedules as $schedule) {
                // If the schedule's day field matches today's day, it's a today's schedule.
                if (strcasecmp($schedule->day, $currentDay) == 0) {
                    // Add full start datetime so the view can compare properly
                    $todayDate = date('Y-m-d');
                    $schedule->start_datetime = strtotime($todayDate . ' ' . $schedule->start_time);
                    $schedule->end_datetime = $schedule->start_datetime + 3600; // assume 1 hour
                    $todaySchedules[] = $schedule;
                }
                else {
                    $upcomingSchedules[] = $schedule;
                }
            }
            
            $data = [
                'todaySchedules'   => $todaySchedules,
                'upcomingSchedules'=> $upcomingSchedules,
                'currentWeek'      => date('W'),
                'currentDay'       => $currentDay,
                'lastUpdated'      => date('Y-m-d H:i:s')
            ];
            
            if (empty($todaySchedules) && empty($upcomingSchedules)) {
            }
            
        } catch (Exception $e) {
            error_log($e->getMessage());
            
            $data = [
                'todaySchedules'   => [],
                'upcomingSchedules'=> []
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
            // Get upcoming schedules for the team
            $upcomingShifts = $scheduleModel->getUpcomingSchedules($teamId);
            $data = [
                'upcomingShifts' => $upcomingShifts,
                'currentTeam' => $driverDetails->current_team
            ];
        }
    
        $this->view('shared/management/shift', $data);
    }
    
    public function scheduleDetails($id) {
        // Get schedule details by ID
        $schedule = $this->model('M_CollectionSchedule')->getScheduleById($id);
        if (!$schedule) {
            redirect('vehicledriver/shift');
        }
    
        // Get related route and vehicle information
        $route = $this->model('M_Route')->getRouteById($schedule->route_id);
        $vehicle = $this->model('M_Vehicle')->getVehicleByRouteId($schedule->vehicle_id);
    
        // Get collection details for the schedule
        $collectionId = $this->collectionModel->getUpcomingCollectionIdByScheduleId($id);
        $collections = $collectionId ? $this->collectionModel->getUpcomingCollectionDetailsByScheduleId($id) : [];
        // $collection = isset($collections[0]) ? $collections[0] : null;
    
        // Get route suppliers for the route
        $routeSuppliers = $this->routeModel->getRouteSuppliersByRouteId($route->route_id);
    
        // Prepare default values for collection-related data
        $collectionBags = $collections ? $this->collectionModel->getCollectionBagsByCollectionId($collectionId) : [];
        $bagsAdded = $collection->bags_added ?? 0;
        $fertilizerDistributed = $collection->fertilizer_distributed ?? 0;
        $collectionCompleted = (is_object($collections) && isset($collection->end_time) && $collections->end_time !== null) ? true : false;

    
        // Prepare data to pass to the view
        $data = [
            'schedule' => $schedule,
            'route' => $route,
            'vehicle' => $vehicle,
            'collectionBags' => $collectionBags,
            'collection' => (object) $collections,
            'routeSuppliers' => $routeSuppliers,
            'bagsAdded' => $bagsAdded,
            'fertilizerDistributed' => $fertilizerDistributed,
            'collectionCompleted' => $collectionCompleted
        ];
    
        // Render the view with the data
        $this->view('vehicle_driver/v_schedule_details', $data);
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
        $driverLocation = $this->getVehicleLocation($collection->vehicle_id);
        $vehicleLocation = $this->vehicleModel->getVehicleLocation($collection->vehicle_id);
    
        // Get all suppliers for this collection
        $collectionSuppliers = $this->collectionScheduleModel->getCollectionSupplierRecords($collectionId);

        // Filter out collected suppliers
        $filteredSuppliers = array_filter($collectionSuppliers, function($supplier) {
            return $supplier->status != 'Collected';
        });

        // Set the current supplier to the first uncollected supplier
        $currentSupplier = !empty($filteredSuppliers) ? $filteredSuppliers[0] : null;

        // Format suppliers for the view
        $formattedSuppliers = array_map(function($supplier) {
            return [
                'id' => $supplier->supplier_id,
                'supplierName' => $supplier->supplier_name,
                'remarks' => 'Call upon arrival',
                'location' => [
                    'lat' => (float)$supplier->latitude,
                    'lng' => (float)$supplier->longitude
                ],
                'address' => $supplier->address ?? 'No address provided',
                'image' => $supplier->profile_image ? 
                    URLROOT . '/public/uploads/supplier_photos/' . $supplier->profile_image : 
                    URLROOT . '/public/img/default-user.png',
                'estimatedCollection' => $supplier->average_collection,
                'status' => $supplier->status,
                'contact' => $supplier->contact_number,
                'arrival_time' => $supplier->arrival_time,
            ];
        }, $filteredSuppliers);
    
        $data = [
            'pageTitle' => 'Collection Route',
            'driverName' => $collection->first_name,
            'vehicleInfo' => 'TEST VEHICLE',
            'driverLocation' => $driverLocation,
            'collections' => $formattedSuppliers,
            'collection' => $collection,
            'vehicleLocation' => $vehicleLocation  
        ];
    
        $this->view('vehicle_driver/v_collection_route', $data);
    }


    public function getVehicleLocation($vehicleId) { // TESTED
        $vehicleLocation = $this->vehicleModel->getVehicleLocation($vehicleId);
        if ($vehicleLocation) {
            echo json_encode($vehicleLocation);
        } else {
            // Return an error response
            echo json_encode(['error' => 'Vehicle location not found']);
        }
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
            // Logic to create a collection
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
                setFlashMessage('Failed to create the collection!', 'error');
                redirect('vehicledriver/scheduleDetails/' . $scheduleId);
            }
        } else {
            // If not a POST request, show the form or redirect
            redirect('vehicledriver/scheduleDetails/' . $scheduleId);
        }
    }
    

    public function endCollection() { // POSSIBLY A DUPLICATE FROM old version, we are using completeCollection
        // Get the JSON input
        $data = json_decode(file_get_contents("php://input"));
    
        if (isset($data->collection_id)) {
            // Call the model method to finalize the collection
            $result = $this->collectionModel->finalizeCollection($data->collection_id);
    
            if ($result['success']) {
                echo json_encode(['success' => true, 'message' => 'Collection ended successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to end collection.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid collection ID.']);
        }
    }
    



    /**
     * Show collection bags for a specific supplier
     */
    public function collectionBags($collectionId, $supplierId) { // tested, the page we land right into after we created a collection
        // Get the collection details
        $collection = $this->collectionModel->getCollectionDetails($collectionId);
        
        // Get the supplier details
        $supplier = $this->supplierModel->getSupplierById($supplierId);
        
        // Get bags for this collection and supplier
        $bags = $this->collectionModel->getCollectionBags($collectionId, $supplierId);
        
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
        
        // Get the collection details
        $collection = $this->collectionModel->getCollectionDetails($collectionId);
        
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
        $bags = $this->collectionModel->getBagsByCollectionAndSupplier($collectionId, $supplierId);
        
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