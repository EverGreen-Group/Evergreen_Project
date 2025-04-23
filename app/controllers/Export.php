<?php

require_once APPROOT . '/models/M_Export.php';
require_once APPROOT .'/models/M_User.php';
class Export extends controller
{

    private $exportModel;
    private $profileModel;
    public function __construct()
    {

        $this->exportModel = new M_Export();
        $this->profileModel = new M_User();

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
            $exportall = $this->exportModel->get_export_data();
            $lastmonth_exports = $this->exportModel->get_lastmonth_exportdata();
            $tea_exports = $this->exportModel->get_tea_export_data_last12months();
            $managerdetails = $this->profileModel->getProfile($_SESSION['user_id']);
           

            $managerName = $managerdetails['profile']->first_name . ' ' . $managerdetails['profile']->last_name;

            $revenue = 0;
            $total_quantity = 0;
            foreach ($lastmonth_exports as $export) {
                $total_quantity += $export->export_quantity;
                $revenue += $export->export_price * $export->export_quantity;
            }

            // Step 1: Create array of last 12 months (YYYY-MM)
            $months = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = new DateTime(); // fresh object every time
                $date->modify("-$i month");
                $months[] = $date->format("Y-m");
            }
            $labels = $months;

            // Step 2: Initialize empty dataset arrays
            $black_tea_data = array_fill(0, 12, 0);
            $green_tea_data = array_fill(0, 12, 0);
            $herbal_tea_data = array_fill(0, 12, 0);

            // Step 3: Populate data based on exports
            foreach ($tea_exports as $entry) {
                $monthIndex = array_search($entry->month, $labels);
                if ($monthIndex !== false) {
                    $quantity = (int) $entry->total_quantity;
                    switch ($entry->stock_name) {
                        case "Black Tea":
                            $black_tea_data[$monthIndex] += $quantity;
                            break;
                        case "Green Tea":
                            $green_tea_data[$monthIndex] += $quantity;
                            break;
                        case "Herbal Tea":
                            $herbal_tea_data[$monthIndex] += $quantity;
                            break;
                    }
                }
            }


            

            $data = [
                'exports' => $exportall,
                'lastmonth_export' => $lastmonth_exports,
                'revenue' => $revenue,
                'total_quantity' => $total_quantity,
                'green_tea' => $green_tea_data,
                'black_tea' => $black_tea_data,
                'herbal_tea' => $herbal_tea_data,
                'managerName' => $managerName,

            ];

            //  var_dump($data);
            // print_r($managerName);
            $this->view('inventory/v_export', $data);
        }

    }



}