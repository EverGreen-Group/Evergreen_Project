<?php
require_once APPROOT . '/models/M_Products.php';
require_once APPROOT . '/models/M_Fertilizer.php';

require_once '../app/models/M_Products.php';
class Inventory extends controller
{
    private $productModel;
    private $fertilizerModel;

    public function __construct()
    {

        $this->productModel = new M_Products();
        $this->fertilizerModel = new M_Fertilizer();
    }

    public function index()
    {
        $products = $this->productModel->getAllProducts();

        $data = [
            'products' => $products
        ];

        $this->view('inventory/v_dashboard', $data);
    }

    public function product()
    {
        $products = $this->productModel->getAllProducts();
        $data = [
            'products' => $products
        ];

        $this->view('inventory/v_product', $data);
    }

    public function getfertilizer(){
        
    }
    public function order(){
        $data = [];

        $this->view('inventory/v_order', $data);
    }

    public function createproduct()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST);
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
                'image_path' => '',
                'product-name_err' => '',
                "location_err" => '',
                "details_err" => '',
                "code_err" => '',
                "price_err" => '',
                "profit_err" => '',
                "margin_err" => '',
                "quantity_err" => '',


            ];

            // Handle image upload
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/products/';

                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Generate unique filename
                $fileExtension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $uniqueFilename = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $uniqueFilename;

                // Move uploaded file
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uniqueFilename;
                }
            }


            //validate 

            if (empty($data['product-name'])) {
                $data['product-name_err'] = 'Please enter product name';
            }
            if (empty($data['location'])) {
                $data['location_err'] = 'Please enter location';
            }
            if (empty($data['details'])) {
                $data['details_err'] = 'Please enter product details';
            }
            if (empty($data['code'])) {
                $data['code_err'] = 'Please enter code';
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
                    flash('product_message', 'Product Added');
                    redirect('inventory/product');
                } else {
                    echo "<pre>";
                    print_r($data);
                    echo "</pre>";
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
                "code" => '',
                "price" => '',
                "profit" => '',
                "margin" => '',
                "quantity" => '',
                "unit" => '',
                'image_path' => '',
            ];
        }


        $this->view('inventory/v_create_product', $data);
    }



    public function fertilizerdashboard()
    {

        $fertilizer = $this->fertilizerModel->getfertilizer();
        $data = [
            'fertilizer' => $fertilizer
        ];

        

        $this->view('inventory/v_fertilizer_dashboard', $data);

        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }

    public function fertilizer()
    {
        $data = [];

        $this->view('inventory/v_fertilizer_available', $data);
    }
    public function createfertilizer()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // $_POST = filter_input_array(INPUT_POST);

            $data = [
                'fertilizer_name' => $_POST['fertilizer_name'],
                'company_name' => $_POST['company_name'],
                'details' => $_POST['details'],
                'code' => $_POST['code'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'unit' => $_POST['unit'],
                'fertilizer_name_err' => '',
                'company_name_err' => '',
                'details_err' => '',
                'code_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'unit_err' => '',

            ];
            

            //validation
            if (empty($data['fertilizer_name'])) {
                $data['fertilizer_name_err'] = "Please Enter Fertilizer name";

            }
            if (empty($data['company_name'])) {
                $data['company_name_err'] = "Please Enter Company name";
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

            if (
                !empty($data['fertilizer_name']) && !empty($data['company_name']) && !empty($data['details'])
                && !empty($data['code']) && !empty($data['price']) && !empty($data['quantity']) && !empty($data['unit'])
            ) {

                if ($this->fertilizerModel->createFertilizer($data)) {
                    flash('fertilizer_message', 'Fertilizer Added');
                    
                    redirect('inventory/fertilizerdashboard');

                } else {
                    die('Something went wrong');
                }


            }


        } else {
            $data = [
                'fertilizer_name' => '',
                'company_name' => '',
                'details' => '',
                'code' => '',
                'price' => '',
                'quantity' => '',
                'unit' => '',

            ];
            

            $this->view('inventory/v_create_fertilizer', $data);
        }

    }

    public function updatefertilizer($id){

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $data = [
                 'id' => $id,
                'fertilizer_name' => $_POST['fertilizer_name'],
                'company_name' => $_POST['company_name'],
                'details' => $_POST['details'],
                'code' => $_POST['code'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'unit' => $_POST['unit'],
                'fertilizer_name_err' => '',
                'company_name_err' => '',
                'details_err' => '',
                'code_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'unit_err' => '',

            ];

            if (
                !empty($data['fertilizer_name']) && !empty($data['company_name']) && !empty($data['details'])
                && !empty($data['code']) && !empty($data['price']) && !empty($data['quantity']) && !empty($data['unit'])
            ){

            }

        }else{
            $fertilizer = $this->fertilizerModel->getFertilizerById($id);
            $data = [
                'id' => $id,
                'fertilizer' => $fertilizer
            ];  
            $this->view('inventory/v_update_fertilizer', $data);
        }
    }

    public function machine()
    {
        $data = [];

        $this->view('inventory/v_machineallocation', $data);
    }


    public function export()
    {
        if ($_SERVER['REQUEST_METHOD']=='POST'){

            $_POST = filter_input_array(INPUT_POST);

        }
        $data = [
            'stock-name' => '',
            'company-name' => '',
            co
        ];

        $this->view('inventory/v_export', $data);
    }

    public function item()
    {
        $data = [];

        $this->view('inventory/v_item', $data);
    }

    public function updateproduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Initialize data array with POST data
            $data = [
                'id' => $id,
                'product-name' => trim($_POST['product-name']),
                'location' => trim($_POST['location']),
                'details' => trim($_POST['details']),
                "code" => trim($_POST['code']),
                'price' => trim($_POST['price']),
                'profit' => trim($_POST['profit']),
                'margin' => trim($_POST['margin']),
                'quantity' => trim($_POST['quantity']),
                'unit' => trim($_POST['unit']),
                'image_path' => '',
                // Error fields
                'product-name_err' => '',
                'location_err' => '',
                'details_err' => '',
                'price_err' => '',
                'profit_err' => '',
                'margin_err' => '',
                'quantity_err' => ''
            ];

            // Handle image upload
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/products/';

                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Generate unique filename
                $fileExtension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $uniqueFilename = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $uniqueFilename;

                // Move uploaded file
                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uniqueFilename;
                }
            }

            // Validate data
            if (empty($data['product-name'])) {
                $data['product-name_err'] = 'Please enter product name';
            }
            if (empty($data['location'])) {
                $data['location_err'] = 'Please enter location';
            }
            if (empty($data['details'])) {
                $data['details_err'] = 'Please enter product details';
            }
            if (empty($data['code'])) {
                $data['code_err'] = 'Please enter code';
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

            // Make sure no errors
            if (
                empty($data['product-name_err']) && empty($data['location_err']) &&
                empty($data['details_err']) && empty($data['price_err']) && empty($data['code_err']) &&
                empty($data['profit_err']) && empty($data['margin_err']) &&
                empty($data['quantity_err'])
            ) {

                // Validated
                if ($this->productModel->updateProduct($data)) {
                    flash('product_message', 'Product Updated Successfully');
                    redirect('inventory/product');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('inventory/v_update_product', $data);
            }

        } else {
            // GET request - show form to edit product
            $product = $this->productModel->getProductById($id);

            if (!$product) {
                redirect('inventory/product');
            }

            $data = [
                'id' => $id,
                'product' => $product
            ];

            $this->view('inventory/v_update_product', $data);
        }

    }

    public function deleteproduct($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($this->productModel->deleteProduct($id)) {
                flash('product_message', 'Product Removed');
            } else {
                flash('product_message', 'Something went wrong', 'alert alert-danger');
            }
        }
        redirect('inventory/product');
    }

    public function recodes(){
        $data = [];

        $this->view('inventory/v_recodes', $data);
    }

    public function deletefertilizer($id){
        if($_SERVER['REQUEST_METHOD']=='GET'){
            if($this->fertilizerModel->deleteFertilizer($id)){
                flash('fertilizer_message', 'Fertilizer Removed');
            }else{
                flash('fertilizer_message', 'Something went wrong', 'alert alert-danger');
            }
        }
        redirect('inventory/fertilizerdashboard');
    }

    public function payments()
    {
        $data = [];
        $this->view('inventory/v_payments', $data);
    }

 

}
