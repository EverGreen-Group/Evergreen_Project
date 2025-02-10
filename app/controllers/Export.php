<?php

require_once APPROOT . '/models/M_Export.php';
class Export extends controller
{

    private $exportModel;
    public function __construct()
    {

        $this->exportModel = new M_Export();

    }

    public function release()
    {
        // Get JSON input
        $input = json_decode(file_get_contents("php://input"), true);

        // Debugging: Log received input
        error_log(print_r($input, true));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate JSON data
            if (!isset($input['StockType'], $input['company'], $input['notes'], $input['Manager'], $input['price'], $input['Quantity'], $input['RegNo'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Missing required fields']);
                return;
            }

            // Trim and sanitize input
            $data = [
                'stock_name' => trim($input['StockType']),
                'export_company' => trim($input['company']),
                'note' => trim($input['notes']),
                'manager_name' => trim($input['Manager']),
                'export_price' => trim($input['price']),
                'export_quantity' => trim($input['Quantity']),
                'reg_no' => trim($input['RegNo']),
            ];

            // Insert data into database
            if ($this->exportModel->add_export_data($data)) {
                http_response_code(201); // Created
                echo json_encode(['message' => 'Export record added successfully']);
                
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Database insertion failed']);
            }

            

        } else {
            // Handle GET request
            $export = $this->exportModel->get_export_data();
            $data = ['exports' => $export];
            $this->view('inventory/v_export', $data);
        }
        
    }


}