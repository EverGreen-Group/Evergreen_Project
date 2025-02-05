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

        


        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $_POST = filter_input_array(INPUT_POST);
            $data = [
                'stock_name' => trim($_POST['StockType']),
                'export_company' => trim($_POST['company']),
                'note' => trim($_POST['notes']),
                'manager_name' => trim($_POST['Manager']),
                'export_price' => trim($_POST['price']),
                'export_quantity' => trim($_POST['Quantity']),
                'reg_no' => trim($_POST['RegNo']),

            ];

            
            if(!empty($data['stock_name']) && !empty($data['export_company']) && !empty($data['note']) && !empty($data['manager_name']) && !empty($data['export_price']) && !empty($data['export_quantity']) && !empty($data['reg_no'])){
                if($this->exportModel->add_export_data($data)){
                    redirect('export/release');

                }else{
                    die('Something went wrong');

                }
            }

            return(json_encode($data));

            
        } else {

            $export = $this->exportModel->get_export_data();
            $data = [
                'exports' => $export
            ];
// var_dump($data);
            $this->view('inventory/v_export', $data);
        }

    }

}