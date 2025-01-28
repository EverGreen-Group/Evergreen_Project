<?php

require_once APPROOT . '/models/M_Batch.php';
class Batches extends controller{

    private $batchModel;
    public function __construct(){
        // echo 'This is the pages controller';
        $this->batchModel = $this->model('M_Batch'); // Assuming you have a BatchModel
     }
 
     public function index(){
 
     }

    public function add() {
        // Check if the request method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get the raw POST data
            $data = json_decode(file_get_contents("php://input"), true);

            // Validate the input data
            $startTime = isset($data['start_time']) ? $data['start_time'] : null;

            // You can add more validation as needed

            // Prepare the batch data for insertion
            $batchData = [
                'start_time' => $startTime,
                'end_time' => null, // Set end_time to NULL since it's not known
                'total_output' => 0, // Default value
                'total_wastage' => 0, // Default value
            ];

            // Call the model method to insert the batch
            if ($this->batchModel->addBatch($batchData)) {
                // Return a success response
                echo json_encode(['success' => true, 'message' => 'Batch added successfully.']);
            } else {
                // Return an error response
                echo json_encode(['success' => false, 'message' => 'Failed to add batch.']);
            }
        } else {
            // Return an error response for invalid request method
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function getBatchesWithoutEndTime() {
        // Check if the request method is GET
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Call the model method to fetch batches without end_time
            $batches = $this->batchModel->getBatchesWithoutEndTime();

            // Return the batches as a JSON response
            echo json_encode($batches);
        } else {
            // Return an error response for invalid request method
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function addIngredient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validate and prepare data
            $ingredientData = [
                'batch_id' => $data['batch_id'],
                'leaf_type_id' => $data['leaf_type_id'],
                'quantity_used_kg' => $data['quantity_used_kg'],
                'added_at' => date('Y-m-d H:i:s') // Current timestamp
            ];

            if ($this->batchModel->addIngredient($ingredientData)) {
                echo json_encode(['success' => true, 'message' => 'Ingredient added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add ingredient.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function addOutput() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validate and prepare data
            $outputData = [
                'batch_id' => $data['batch_id'],
                'leaf_type_id' => $data['leaf_type_id'],
                'grading_id' => $data['grading_id'],
                'output_kg' => $data['output_kg'],
                'processed_at' => date('Y-m-d H:i:s') // Current timestamp
            ];

            if ($this->batchModel->addOutput($outputData)) {
                echo json_encode(['success' => true, 'message' => 'Output added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add output.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function addMachineUsage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validate and prepare data
            $machineUsageData = [
                'batch_id' => $data['batch_id'],
                'machine_id' => $data['machine_id'],
                'operator_id' => $data['operator_id'],
                'start_time' => date('Y-m-d H:i:s'), // Current timestamp
                'end_time' => null, // Set to null initially
                'notes' => $data['notes']
            ];

            if ($this->batchModel->addMachineUsage($machineUsageData)) {
                echo json_encode(['success' => true, 'message' => 'Machine usage added successfully.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add machine usage.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }

    public function getBatchDetails($batchId) {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            // Fetch batch details from the model
            $batchDetails = $this->batchModel->getBatchDetails($batchId);
            
            // Return the batch details as a JSON response
            echo json_encode($batchDetails);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
        }
    }
}