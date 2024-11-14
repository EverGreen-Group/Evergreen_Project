<?php
require_once APPROOT . '/models/M_Products.php';

require_once '../app/models/M_Products.php';
class Inventory extends controller
{
    private $productModel;

    public function __construct()
    {

        $this->productModel = new M_Products();
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'product-name' => trim($_POST['product-name']),
                "location" => trim($_POST['location']),
                "details" => trim($_POST['details']),
                "code" => trim($_POST['code']),
                "price" => trim($_POST['price']),
                "profit" => trim($_POST['profit']),
                "margin" => trim($_POST['margin']),
                "quantity" => trim($_POST['quantity']),
                "unit" => trim($_POST['unit']),
                'product-name_err' => '',
                "location_err" => '',
                "details_err" => '',
                "price_err" => '',
                "profit_err" => '',
                "margin_err" => '',
                "quantity_err" => '',


            ];

            if (empty($data['product-name'])) {
                $data['product-name_err'] = 'Please enter product name';
            }
            if (empty($data['location'])) {
                $data['location_err'] = 'Please enter location';
            }
            if (empty($data['details'])) {
                $data['details_err'] = 'Please enter product details';
            }
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['profit'])) {
                $data['profit_err'] = 'Please enter profit';
            }
            if (empty($data['margin'])) {
                $data['margin_err'] = 'Please enter margin';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            if (
                empty($data['product-name_err']) && empty($data['location_err']) &&
                empty($data['details_err']) && empty($data['price_err']) &&
                empty($data['profit_err']) && empty($data['margin_err']) &&
                empty($data['quantity_err'])
            ) {

                if ($this->productModel->createProduct($data)) {

                    redirect('inventory/product');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('inventory/v_createproduct', $data);
            }

        } else {
            $data = [
                'product-name' => '',
                "location" => '',
                "details" => '',
                "price" => '',
                "profit" => '',
                "margin" => '',
                "quantity" => '',


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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'fertilizer-name' => trim($_POST['fertilizer-name']),
                'company-name' => trim($_POST['company-name']),
                'details' => trim($_POST['details']),
                'code' => trim($_POST['code']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quaantity']),
                'unit' => trim($_POST['unit']),
                'ferilizer-name_err' => '',
                'company-name_err' => '',
                'details_err' => '',
                'code_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'unit_err' => '',

            ];
            //validation
            if (empty($data['fertilizer-name'])) {
                $data['fertilizer-name_err'] = "Please Enter Fertilizer name";

            }
            if (empty($data['company-name'])) {
                $data['company-name_err'] = "Please Enter Company name";
            }
            if (empty($data['details'])) {
                $data['details_err'] = "Please Enter Details";
            }
            if (empty($data['code'])) {
                $data['code_err'] = "Please Enter Code";
            }
            if (empty($data['price'])) {
                $data['price_err'] = "Please Enter Price";
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = "Please Enter Quantity";
            }
            if (empty($data['unit'])) {
                $data['unit_err'] = "Please Enter Unit";
            }

            if(empty($data['fertilizer-name']) && empty($data['company-name']) && empty($data['details']) 
            && empty($data['code']) && empty($data['price']) && empty($data['quantity']) && empty($data['unit']) ){
        
            }


        } else {
            $data = [
                'ferilizer-name' => '',
                'company-name' => '',
                'details' => '',
                'code' => '',
                'price' => '',
                'quantity' => '',
                'unit' => '',

            ];

            $this->view('inventory/v_create_fertilizer', $data);
        }

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

    public function item()
    {
        $data = [];

        $this->view('inventory/v_item', $data);
    }


}
