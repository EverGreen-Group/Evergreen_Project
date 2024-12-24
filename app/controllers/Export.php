<?php

class Export extends controller{

    public function __construct(){
        
    }

    public function release(){
        if ($_SERVER['REQUEST_METHOD']=='POST'){

            $_POST = filter_input_array(INPUT_POST);
            $data = [
                'stock-name' => trim($_POST['stock-name']),
                'company-name' => trim($_POST['company-name']),
                'confirm-date' => trim($_POST['confirm-date']),
                'manager-name' => trim($_POST['manager-name']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'reg-no' => trim($_POST['reg-no']),
                
            ];
            var_dump($data);

        }else{
            $data = [];
            $this->view('inventory/v_export', $data);
        }
        
    }

}