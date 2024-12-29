<?php

require_once APPROOT . '/models/M_Export.php';
class Export extends controller{

    private $exportModel;
    public function __construct(){
        
        $this->exportModel = new M_Export();
        
    }

    public function release(){
        if ($_SERVER['REQUEST_METHOD']=='POST'){

            $_POST = filter_input_array(INPUT_POST);
            $data = [
                'stock_name' => trim($_POST['stock-name']),
                'export_company' => trim($_POST['company-name']),
                'export_date' => trim($_POST['confirm-date']),
                'manager_name' => trim($_POST['manager-name']),
                'export_price' => trim($_POST['price']),
                'export_quantity' => trim($_POST['quantity']),
                'reg_no' => trim($_POST['reg-no']),
                
            ];

            var_dump($data);
                    var_dump($_POST);

            //if(!empty($data['stock_name']) && !empty($data['export_company']) && !empty($data['export_date']) && !empty($data['manager_name']) && !empty($data['export_price']) && !empty($data['export_quantity']) && !empty($data['reg_no'])){
                if($this->exportModel->add_export_data($data)){
                    redirect('export/release');
                }else{
                    die('Something went wrong');
                    
                }
            //}

        }else{
            
            $export = $this->exportModel->get_export_data();
            $data = [
                'exports' => $export
            ];
            
            $this->view('inventory/v_export', $data);
             //var_dump($data['exports']);
        }
        
    }

}