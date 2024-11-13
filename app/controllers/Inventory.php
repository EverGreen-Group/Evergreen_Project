<?php

require_once '../app/models/M_Products.php';
class Inventory extends controller
{
    private $productModel;

    public function __construct()
    {
        // Initialization code if needed
    }

    public function index()
    {
        $data = [];

        $this->view('inventory/v_dashboard', $data);
    }

    public function product()
    {
        $data = [];

        $this->view('inventory/v_product', $data);
    }

    public function createproduct()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                'product-name' => htmlspecialchars(trim($_POST['product-name'])),
                "location" => htmlspecialchars(trim($_POST['location'])),
                "details" => htmlspecialchars(trim($_POST['details'])),
                "price" => htmlspecialchars(trim($_POST['price'])),
                "profit" => htmlspecialchars(trim($_POST['profit'])),
                "margin" => htmlspecialchars(trim($_POST['margin'])),
                "quantity" => htmlspecialchars(trim($_POST['quantity'])),
                'product-name_err'=>'',
                "location_err" => '',
                "details_err" =>'',
                "price_err"=>'',
                "profit_err" =>'',
                "margin_err" =>'',
                "quantity_err" =>'',


            ];

            if(empty($data['product-name'])){
                $data['product-name_err'] = 'Please enter product name';
            }
            if(empty($data['location'])){
                $data['location_err'] = 'Please enter location';
            }
            if(empty($data['details'])){
                $data['details_err'] = 'Please enter product details';
            }
            if(empty($data['price'])){
                $data['price_err'] = 'Please enter price';
            }
            if(empty($data['profit'])){
                $data['profit_err'] = 'Please enter profit';
            }
            if(empty($data['margin'])){
                $data['margin_err'] = 'Please enter margin';
            }
            if(empty($data['quantity'])){
                $data['quantity_err'] = 'Please enter quantity';
            }
            
            if(empty($data['product-name_err']) && empty($data['location_err']) && 
               empty($data['details_err']) && empty($data['price_err']) && 
               empty($data['profit_err']) && empty($data['margin_err']) && 
               empty($data['quantity_err'])){

                
                $productModel = new M_Products();
                if($productModel->createProduct($data)){
                    redirect('inventory/product');
                }else{
                    die('Something went wrong');
                }
            }else{
                $this->view('inventory/v_createproduct', $data);
            }

        }else {
            $data=[
                'product-name'=>'',
                "location" => '',
                "details" =>'',
                "price"=>'',
                "profit" =>'',
                "margin" =>'',
                "quantity" =>'',


            ];
        }
        

        $this->view('inventory/v_create_product', $data);
    }

    public function fertilizerdashboard()
    {
        $data = [];

        $this->view('inventory/v_fertilizer_dashboard', $data);
    }

    public function fertilizer()
    {
        $data = [];

        $this->view('inventory/v_fertilizer_available', $data);
    }
    public function createfertilizer()
    {
        $data = [];

        $this->view('inventory/v_create_fertilizer', $data);
    }

    public function machine()
    {
        $data = [];

        $this->view('inventory/v_machineallocation', $data);
    }


    public function createa()
    {
        $data = [];

        $this->view('inventory/v_create_product', $data);
    }

    public function item(){
        $data=[];

        $this->view('inventory/v_item',$data);
    }

   
}
