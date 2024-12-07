<?php
// Update the include to use APPROOT instead of URLROOT
include_once APPROOT . '/services/GoogleMapsService.php';

class Drivingpartner extends controller {
    private $collectionScheduleModel;
    private $driverModel;
    private $teamModel;
    private $vehicleModel;
    private $routeModel;
    private $collectionModel;
    private $googleMapsService;
    private $partnerModel;

    public function __construct() {
        if (!RoleHelper::hasAnyRole([RoleHelper::ADMIN, RoleHelper::DRIVING_PARTNER])) {
            // Redirect unauthorized access
            flash('message', 'Unauthorized access', 'alert alert-danger');
            redirect('');
            exit();
        }

        $this->collectionScheduleModel = $this->model('M_CollectionSchedule');
        $this->collectionModel = $this->model('M_Collection');
        $this->driverModel = $this->model('M_Driver');
        $this->teamModel = $this->model('M_Team');
        $this->vehicleModel = $this->model('M_Vehicle');
        $this->routeModel = $this->model('M_Route');
        $this->googleMapsService = new GoogleMapsService();
        $this->partnerModel = $this->model('M_Partner');
    }

    public function index() {
        $partnerModel = $this->model('M_Partner');
        $scheduleModel = $this->model('M_CollectionSchedule');
        
        // Get driving partner's team ID
        $partnerDetails = $partnerModel->getPartnerDetails($_SESSION['user_id']);
        $teamId = $partnerDetails->team_id ?? null;

        if (!$teamId) {
            $data = [
                'upcomingShifts' => [],
                'message' => 'No team assigned'
            ];
        } else {
            // Get upcoming schedules for the team
            $upcomingShifts = $scheduleModel->getUpcomingSchedules($teamId);
            $data = [
                'upcomingShifts' => $upcomingShifts,
                'currentTeam' => $partnerDetails->current_team
            ];
        }

        $this->view('driving_partner/v_dashboard', $data);
    }

    public function profile() {
        $data = [];
        $this->view('pages/profile', $data);
    }

    public function team() {
        $data = [];
        $this->view('driving_partner/v_team', $data);
    }

    public function route() {
        $data = [];
        $this->view('driving_partner/v_route', $data);
    }

    public function shift() {
        $partnerModel = $this->model('M_Partner');
        $scheduleModel = $this->model('M_CollectionSchedule');
        
        // Get driver's team ID
        $partnerDetails = $partnerModel->getPartnerDetails($_SESSION['user_id']);
        $teamId = $partnerDetails->team_id ?? null;
    
        if (!$teamId) {
            $data = [
                'upcomingShifts' => [],
                'message' => 'No team assigned'
            ];
        } else {
            // Get upcoming schedules for the team
            $upcomingShifts = $scheduleModel->getUpcomingSchedules($teamId);
            $data = [
                'upcomingShifts' => $upcomingShifts,
                'currentTeam' => $partnerDetails->current_team
            ];
        }
    
        $this->view('shared/management/shift', $data);
    }
    
    public function scheduleDetails($id) {
        $schedule = $this->model('M_CollectionSchedule')->getScheduleById($id);
        if (!$schedule) {
            redirect('shared/management/shift');
        }

        $route = $this->model('M_Route')->getRouteById($schedule->route_id);
        $team = $this->model('M_Team')->getTeamById($schedule->team_id);
        $vehicle = $this->model('M_Vehicle')->getVehicleById($schedule->vehicle_id);

        $currentUserId = $_SESSION['user_id'];
        $userRole = RoleHelper::hasRole(RoleHelper::DRIVER) ? 'driver' : 
                   (RoleHelper::hasRole(RoleHelper::DRIVING_PARTNER) ? 'driving_partner' : null);

        $collectionId = $this->collectionModel->getCollectionIdByScheduleId($id);

        $collectionBags = $this->collectionModel->getCollectionBags($collectionId);
        if (!$collectionBags) {
            $collectionBags = [];
        }

        $data = [
            'schedule' => $schedule,
            'route' => $route,
            'team' => $team,
            'vehicle' => $vehicle,
            'userRole' => $userRole,
            'isReady' => $this->model('M_CollectionSchedule')->isUserReady($id, $currentUserId),
            'collectionBags' => $collectionBags
        ];

        // Add this to get route suppliers
        $routeSuppliers = $this->routeModel->getRouteSuppliers($data['route']->route_id);
        $data['routeSuppliers'] = $routeSuppliers;

        $data['collection'] = $this->collectionScheduleModel->getCollectionByScheduleId($id);

        $this->view('shared/collection/schedule_details', $data);
    }

    public function setReady($scheduleId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $currentUserId = $_SESSION['user_id'];
            
            try {
                // First check if collection exists, if not create it
                $collection = $this->collectionScheduleModel->getCollectionByScheduleId($scheduleId);
                if (!$collection) {
                    // Create initial collection without start time
                    $this->collectionScheduleModel->createInitialCollection($scheduleId);
                    $collection = $this->collectionScheduleModel->getCollectionByScheduleId($scheduleId);
                }
                
                // Set partner as ready (but don't start collection yet)
                if ($this->collectionScheduleModel->setPartnerReady($scheduleId, $currentUserId)) {
                    flash('schedule_message', 'You are marked as ready. Please assign bags for collection.');
                } else {
                    flash('schedule_message', 'Something went wrong', 'alert alert-danger');
                }
                
                redirect('drivingpartner/scheduleDetails/' . $scheduleId);
                return;
            } catch (Exception $e) {
                flash('schedule_message', 'Error: ' . $e->getMessage(), 'alert alert-danger');
                redirect('drivingpartner/scheduleDetails/' . $scheduleId);
                return;
            }
        }
        redirect('drivingpartner/shift');
    }

    public function assignBags($scheduleId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $bags = isset($_POST['bags']) ? $_POST['bags'] : [];
            
            if (empty($bags)) {
                redirect('drivingpartner/scheduleDetails/' . $scheduleId);
                return;
            }

            // First create collection, then assign bags
            $this->collectionModel->createCollectionWithBags($scheduleId, $bags);
            redirect('drivingpartner/scheduleDetails/' . $scheduleId);
        }
    }

    public function supplier_collection() {
        $data = [];
        $this->view('driving_partner/supplier_collection', $data);
    }

    public function startCollection($collectionId) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
                
                // Verify all conditions are met
                if (!$collection->partner_approved) {
                    throw new Exception('Partner approval required');
                }
                if (!$collection->vehicle_manager_approved) {
                    throw new Exception('Vehicle manager approval required');
                }
                if (!$collection->initial_weight_bridge) {
                    throw new Exception('Initial weight bridge reading required');
                }
                
                // Start the collection using the model
                if ($this->collectionModel->startCollectionWithSuppliers($collectionId)) {
                    flash('collection_message', 'Collection started successfully');
                    redirect('drivingpartner/collectionRoute/' . $collectionId);
                } else {
                    flash('collection_message', 'Failed to start collection', 'alert alert-danger');
                    redirect('drivingpartner/scheduleDetails/' . $collection->schedule_id);
                }
                
            } catch (Exception $e) {
                flash('collection_message', 'Error: ' . $e->getMessage(), 'alert alert-danger');
                redirect('drivingpartner/scheduleDetails/' . $collection->schedule_id);
            }
        }
        redirect('drivingpartner/index');
    }

    public function staff() {
        $data = [];
        $this->view('driving_partner/v_staff', $data);
    }

    public function settings() {
        $data = [];
        $this->view('driving_partner/v_settings', $data);
    }

    public function personal_details() {
        $data = [];
        $this->view('driving_partner/v_personal_details', $data);
    }

    public function logout() {
        // Handle logout functionality
    }

    public function collection($collectionId) {
        // Get collection details
        $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
        if (!$collection) {
            redirect('vehicledriver/');
        }

        // Get schedule, team, and vehicle details
        $schedule = $this->collectionScheduleModel->getScheduleById($collection->schedule_id);
        $team = $this->teamModel->getTeamById($schedule->team_id);
        $vehicle = $this->vehicleModel->getVehicleById($schedule->vehicle_id);

        // Replace hardcoded location with actual driver location
        $driverLocation = $this->getCurrentDriverLocation();

        // Get all suppliers for this collection
        $collectionSuppliers = $this->collectionScheduleModel->getCollectionSupplierRecords($collectionId);

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
                'estimatedCollection' => 500,
                'status' => $supplier->status,
                'contact' => $supplier->contact_number,
                'arrival_time' => $supplier->arrival_time,
                'collection_time' => $supplier->collection_time,
                'quantity' => $supplier->quantity ?? 0
            ];
        }, $collectionSuppliers);

        $data = [
            'pageTitle' => 'Collection Route',
            'driverName' => $team->driver_name,
            'teamName' => $team->team_name,
            'vehicleInfo' => $vehicle->vehicle_type . ' (' . $vehicle->license_plate . ')',
            'driverLocation' => $driverLocation,
            'collections' => $formattedSuppliers,
            'schedule' => $schedule,
            'collection' => $collection
        ];

        $this->view('driving_partner/supplier_collection', $data);
    }

    public function markArrival() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get JSON data from request body
            $jsonData = json_decode(file_get_contents('php://input'), true);
            
            $collection_id = $jsonData['collection_id'];
            $supplier_id = $jsonData['supplier_id'];
            
            $data = [
                'collection_id' => $collection_id,
                'supplier_id' => $supplier_id,
                'arrival_time' => date('Y-m-d H:i:s')
            ];
            
            if ($this->collectionModel->updateArrivalTime($data)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update arrival time']);
            }
        }
    }

    public function optimizeRoute($routeId, $driverLat, $driverLng) {
        $mapsService = new GoogleMapsService();
        $routeModel = $this->routeModel;

        // Get suppliers
        $suppliers = $routeModel->getRouteSuppliers($routeId);
        
        // Prepare coordinates
        $origin = "{$driverLat},{$driverLng}";
        $destinations = array_map(function($supplier) {
            return "{$supplier->latitude},{$supplier->longitude}";
        }, $suppliers);

        // Get distance matrix
        $response = $mapsService->getDistanceMatrix($origin, $destinations);

        if ($response->status === 'OK') {
            // Calculate optimal order
            $supplierDistances = [];
            foreach ($suppliers as $index => $supplier) {
                $supplierDistances[] = [
                    'supplier_id' => $supplier->supplier_id,
                    'duration' => $response->rows[0]->elements[$index]->duration->value
                ];
            }

            // Sort by travel time
            usort($supplierDistances, function($a, $b) {
                return $a['duration'] - $b['duration'];
            });

            // Update orders in database
            $this->db->beginTransaction();
            try {
                foreach ($supplierDistances as $index => $supplier) {
                    $routeModel->updateSupplierOrder($routeId, $supplier['supplier_id'], $index + 1);
                }
                
                $this->db->commit();
                return true;
            } catch (Exception $e) {
                $this->db->rollBack();
                return false;
            }
        }
        return false;
    }

    // Add this new method to get current driver location
    private function getCurrentDriverLocation() {
        // If you have a stored location in session or database, retrieve it
        // Otherwise, get it from browser geolocation
        return [
            'lat' => $_SESSION['driver_lat'] ?? null,
            'lng' => $_SESSION['driver_lng'] ?? null
        ];
    }

    public function markSupplierArrival($collectionId, $supplierId, $arrivalTime, $latitude = null, $longitude = null) {
        $sql = "UPDATE collection_supplier_records 
                SET arrival_time = :arrival_time,
                    latitude = :latitude,
                    longitude = :longitude
                WHERE collection_id = :collection_id 
                AND supplier_id = :supplier_id";
                
        $params = [
            ':arrival_time' => $arrivalTime,
            ':latitude' => $latitude,
            ':longitude' => $longitude,
            ':collection_id' => $collectionId,
            ':supplier_id' => $supplierId
        ];
        
        return $this->db->query($sql, $params);
    }

    public function leave() {
        $userId = $_SESSION['user_id'];
        $leaveModel = $this->model('M_Leave');
        
        // Get leave balances first
        $leaveBalances = $leaveModel->getLeaveBalance($userId);
        
        $data = [
            'title' => 'Leave Management',
            'leaveBalance' => (object)[
                'annual' => $this->findLeaveBalanceByName($leaveBalances, 'Annual Leave'),
                'sick' => $this->findLeaveBalanceByName($leaveBalances, 'Sick Leave')
            ],
            'pendingLeaveCount' => $leaveModel->getPendingLeaveCount($userId),
            'leaveHistory' => $leaveModel->getLeaveHistory($userId),
            'leaveTypes' => $leaveModel->getLeaveTypes(),
            'availableSwapUsers' => $leaveModel->getAvailableSwapUsers($userId, $_SESSION['role_id']),
            'swapRequests' => $leaveModel->getSwapRequests($userId)
        ];
        
        $this->view('driving_partner/v_leave', $data);
    }

    public function handle_swap_request() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('vehicledriver/leave');
        }

        $jsonData = json_decode(file_get_contents('php://input'), true);
        $leaveModel = $this->model('M_Leave');
        
        $success = $leaveModel->updateSwapRequest(
            $jsonData['requestId'], 
            $jsonData['action'], 
            $_SESSION['user_id']
        );

        echo json_encode(['success' => $success]);
    }

    // Add this helper method
    private function findLeaveBalanceByName($balances, $leaveName) {
        if (!is_array($balances) && !is_object($balances)) {
            return 0;
        }
        foreach ($balances as $balance) {
            if ($balance->name === $leaveName) {
                return $balance->remaining_days;
            }
        }
        return 0;
    }

    public function supplierCollection($collectionId) {
        // Get collection details
        $collection = $this->collectionModel->getCollectionById($collectionId);
        if (!$collection) {
            flash('collection_message', 'Collection not found', 'alert alert-danger');
            redirect('drivingpartner/shift');
        }

        // Get schedule details
        $schedule = $this->collectionScheduleModel->getScheduleById($collection->schedule_id);
        
        // Get suppliers with their collection status
        $suppliers = $this->collectionModel->getCollectionSuppliers($collectionId);
        
        $data = [
            'collection' => $collection,
            'schedule' => $schedule,
            'suppliers' => $suppliers,
            'title' => 'Supplier Collection Management'
        ];

        $this->view('driving_partner/supplier_collection', $data);
    }

    public function updateSupplierStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supplierId = $_POST['supplier_id'] ?? null;
            $collectionId = $_POST['collection_id'] ?? null;
            $status = $_POST['status'] ?? null;
            
            if (!$supplierId || !$collectionId || !$status) {
                $response = [
                    'success' => false,
                    'message' => 'Missing required parameters'
                ];
                echo json_encode($response);
                return;
            }

            try {
                // Update supplier collection status
                $result = $this->collectionModel->updateSupplierCollectionStatus(
                    $collectionId,
                    $supplierId,
                    $status
                );

                if ($result) {
                    $response = [
                        'success' => true,
                        'message' => 'Status updated successfully'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Failed to update status'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ];
            }

            echo json_encode($response);
            return;
        }

        redirect('drivingpartner/shift');
    }

    public function recordCollection() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $supplierId = $_POST['supplier_id'] ?? null;
            $collectionId = $_POST['collection_id'] ?? null;
            $weight = $_POST['weight'] ?? null;
            $quality = $_POST['quality'] ?? null;
            $notes = $_POST['notes'] ?? '';

            if (!$supplierId || !$collectionId || !$weight || !$quality) {
                $response = [
                    'success' => false,
                    'message' => 'Missing required parameters'
                ];
                echo json_encode($response);
                return;
            }

            try {
                // Record the collection details
                $result = $this->collectionModel->recordSupplierCollection(
                    $collectionId,
                    $supplierId,
                    $weight,
                    $quality,
                    $notes
                );

                if ($result) {
                    $response = [
                        'success' => true,
                        'message' => 'Collection recorded successfully'
                    ];
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'Failed to record collection'
                    ];
                }
            } catch (Exception $e) {
                $response = [
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ];
            }

            echo json_encode($response);
            return;
        }

        redirect('drivingpartner/shift');
    }

    public function collectionRoute($collectionId) {
        // Get collection details
        $collection = $this->collectionScheduleModel->getCollectionById($collectionId);
        if (!$collection) {
            redirect('vehicledriver/');
        }

        // Get schedule, team, and vehicle details
        $schedule = $this->collectionScheduleModel->getScheduleById($collection->schedule_id);
        $team = $this->teamModel->getTeamById($schedule->team_id);
        $vehicle = $this->vehicleModel->getVehicleById($schedule->vehicle_id);

        // Replace hardcoded location with actual driver location
        $driverLocation = $this->getCurrentDriverLocation();

        // Get all suppliers for this collection
        $collectionSuppliers = $this->collectionScheduleModel->getCollectionSupplierRecords($collectionId);

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
                'estimatedCollection' => 500,
                'status' => $supplier->status,
                'contact' => $supplier->contact_number,
                'arrival_time' => $supplier->arrival_time,
                'collection_time' => $supplier->collection_time,
                'quantity' => $supplier->quantity ?? 0
            ];
        }, $collectionSuppliers);

        $data = [
            'pageTitle' => 'Collection Route',
            'driverName' => $team->driver_name,
            'teamName' => $team->team_name,
            'vehicleInfo' => $vehicle->vehicle_type . ' (' . $vehicle->license_plate . ')',
            'driverLocation' => $driverLocation,
            'collections' => $formattedSuppliers,
            'schedule' => $schedule,
            'collection' => $collection
        ];

        $this->view('driving_partner/supplier_collection', $data);
    }

    public function record_collection($supplierId, $collectionId) {
        // Verify that this supplier is part of the current collection
        $supplierRecord = $this->collectionScheduleModel->getSupplierCollectionRecord($collectionId, $supplierId);

        if (!$supplierRecord) {
            flash('collection_error', 'Invalid supplier or collection record');
            redirect('drivingpartner/a/' . $collectionId);
        }

        // Get collection details
        $collection = $this->collectionScheduleModel->getCollectionById($collectionId);

        $bags = $this->collectionScheduleModel->getCollectionBags($collectionId);
        
        if (!$collection) {
            flash('collection_error', 'Collection not found');
            redirect('drivingpartner');
        }

        $data = [
            'pageTitle' => 'Record Collection',
            'supplier' => $supplierRecord,
            'collection' => $collection,
            'bags' => $bags
        ];

        $this->view('driving_partner/record_collection', $data);
    }

    public function assign_bag() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die(json_encode(['success' => false, 'message' => 'Method not allowed']));
        }

        // Get POST data
        $data = json_decode(file_get_contents('php://input'));

        // Calculate actual weight server-side
        $actualWeight = $data->totalWeight - $data->bagWeight;

        // Prepare bag data
        $bagData = [
            'bag_weight_kg' => $data->bagWeight,
            'total_weight_kg' => $data->totalWeight,
            'actual_weight_kg' => $actualWeight,
            'moisture_level' => $data->moistureLevel,
            'leaf_type' => $data->leafType,
            'leaf_age' => $data->leafAge
        ];

        try {
            // Assign bag using model
            $result = $this->collectionScheduleModel->assignBagWithStatus(
                $data->bagId,
                $data->supplierId,
                $data->collectionId,
                $bagData
            );

            if ($result) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Database update failed');
            }
        } catch (Exception $e) {
            error_log("Error in assign_bag: " . $e->getMessage());
            echo json_encode([
                'success' => false, 
                'message' => 'Failed to assign bag: ' . $e->getMessage()
            ]);
        }
    }

    public function confirm_collection() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            die(json_encode(['success' => false, 'message' => 'Method not allowed']));
        }

        try {
            $data = json_decode(file_get_contents('php://input'));
            
            // Verify supplier PIN here
            if (!$this->verifySupplierPin($data->supplierId, $data->pin)) {
                throw new Exception('Invalid PIN');
            }

            $result = $this->collectionScheduleModel->confirmCollection(
                $data->collectionId,
                $data->supplierId
            );

            if ($result['success']) {
                echo json_encode([
                    'success' => true,
                    'isCompleted' => $result['isCompleted'],
                    'redirectUrl' => $result['isCompleted'] ? URLROOT . '/drivingpartner' : null
                ]);
            } else {
                throw new Exception('Failed to confirm collection');
            }

        } catch (Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    private function verifySupplierPin($supplierId, $pin) {
        // Implement PIN verification logic here
        // Return true if PIN is valid, false otherwise
        return true; // Temporary
    }
}

?>
