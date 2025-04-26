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
        // $input = json_decode(file_get_contents("php://input"), true);

        // Debugging: Log received input
        // error_log(print_r($input, true));


        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_export'])) {
            // Validate JSON data
            if (!isset($_POST['stock-name'], $_POST['company-name'], $_POST['notes'], $_POST['manager'], $_POST['price'], $_POST['quantity'], $_POST['reg-no'])) {
                http_response_code(400);
                // echo json_encode(['error' => 'Missing required fields']);
                setFlashMessage('Missing required fields','error');
                redirect('export/release');
                
            }

            // Trim and sanitize input
            $data = [
                'stock_name' => trim($_POST['stock-name']),
                'export_company' => trim($_POST['company-name']),
                'note' => trim($_POST['notes']),
                'manager_name' => trim($_POST['manager']),
                'export_price' => trim($_POST['price']),
                'export_quantity' => trim($_POST['quantity']),
                'reg_no' => trim($_POST['reg-no']),
            ];

             // Price validation
            
            if (!is_numeric($data['price'])) {
                setFlashMessage('Price must be a number', 'error');
                redirect('export/release');
            }
            if ((float) $data['price'] <= 0) {
                setFlashMessage('Price must be greater than zero', 'error');
                redirect('export/release');
            }

            // Quantity validation
            
            if (!is_numeric($data['quantity'])) {
                setFlashMessage('Quantity must be a number', 'error');
                redirect('export/release');
            }
            if ((float) $data['quantity'] <= 0) {
                setFlashMessage('Quantity must be greater than zero', 'error');
                redirect('export/release');
            }

            // Insert data into database
            if ($this->exportModel->add_export_data($data)) {
                setFlashMessage('Export record added successfully','success');
                redirect('export/release');
                // http_response_code(201); // Created
                // echo json_encode(['message' => 'Export record added successfully']);

            } else {
                // http_response_code(500);
                // echo json_encode(['error' => 'Database insertion failed']);
                setFlashMessage('Database insertion failed','error');
                redirect('export/release');
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

            $l_m_e_count=count($lastmonth_exports);
            $allexportcount=count($exportall);


            $data = [
                'all_exports_count' => $allexportcount,
                'lastmonth_exports_count' => $l_m_e_count,
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