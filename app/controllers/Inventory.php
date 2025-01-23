<?php
require_once APPROOT . '/models/M_Products.php';
require_once APPROOT . '/models/M_Fertilizer.php';
require_once APPROOT . '/models/M_Stock.php';
require_once APPROOT . '/models/M_CollectionApproval.php';

require_once '../app/models/M_Products.php';
class Inventory extends controller
{
    private $productModel;
    private $fertilizerModel;
    private $stockModel;
    private $collectionApprovalModel;

    public function __construct()
    {

        $this->productModel = new M_Products();
        $this->fertilizerModel = new M_Fertilizer();
        $this->stockModel = new M_Stock();
        $this->collectionApprovalModel = new M_CollectionApproval();
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

    public function products(){


        $this->view('inventory/v_products');
    }

    public function stock()
    {
        // $products = $this->productModel->getAllProducts();
        $data = [
            // 'products' => $products
        ];

        $this->view('inventory/v_stocks', $data);
    }

    public function export()
    {
        // $products = $this->productModel->getAllProducts();
        $data = [
            // 'products' => $products
        ];

        $this->view('inventory/v_exports_2', $data);
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
             $_POST = filter_input_array(INPUT_POST);

            $data = [
                'fertilizer_name' => $_POST['fertilizer_name'],
                'company_name' => $_POST['company_name'],
                'details' => $_POST['details'],
                'code' => $_POST['code'],
                'price' => $_POST['price'],
                'quantity' => $_POST['quantity'],
                'unit' => $_POST['unit'],
                'image_path' => '',
                'fertilizer_name_err' => '',
                'company_name_err' => '',
                'details_err' => '',
                'code_err' => '',
                'price_err' => '',
                'quantity_err' => '',
                'unit_err' => '',

            ];

            // Handle image upload
            if (isset($_FILES['fertilizer_image']) && $_FILES['fertilizer_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/fertilizers/';

                // Create upload directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                   // mkdir($uploadDir, 0777, true);
                }

                // Generate unique filename
                $fileExtension = pathinfo($_FILES['fertilizer_image']['name'], PATHINFO_EXTENSION);
                $uniqueFilename = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $uniqueFilename;

                // Move uploaded file
                if (move_uploaded_file($_FILES['fertilizer_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uniqueFilename;
                }
            }
            else {
                print_r("no file found");
            }

           
            

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
                && !empty($data['code']) && !empty($data['price']) && !empty($data['quantity']) && !empty($data['unit'] && !empty($data['image_path']))
            ) {

                if ($this->fertilizerModel->createFertilizer($data)) {
                    flash('fertilizer_message', 'Fertilizer Added');
                    
                    redirect('inventory/fertilizerdashboard');

                } else {
                    echo "<pre>";
                    print_r($data);
                    echo "</pre>";
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

    public function getTeaStock() {
        // Fetch tea stock data from the model
        $teaStockData = $this->stockModel->getTotalStockByTeaLeafType();

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($teaStockData);
    }

    public function getAvailableTeaStock() {
        // Fetch available tea stock data from the model
        $availableTeaStock = $this->stockModel->getTotalStockByTeaLeafType(); // Assuming you have this method in M_Stock

        // Prepare data for the chart
        $data = [];
        foreach ($availableTeaStock as $stock) {
            $data[] = [
                'leaf_type' => $stock->leaf_type,
                'total_stock' => $stock->total_stock,
            ];
        }

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function addStock() {
        // Get the data from the AJAX request
        $data = json_decode(file_get_contents("php://input"), true);

        // Validate and sanitize the data as needed
        $teaTypeId = $data['tea_type']; // This should be the ID
        $gradingId = $data['grading'];   // This should be the ID
        $quantity = $data['quantity'];
        $notes = $data['notes'];

        // Call the model method to add the stock
        $result = $this->stockModel->addStock($teaTypeId, $gradingId, $quantity, $notes);

        // Return a JSON response
        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
    }

    public function getTeaTypes() {
        // Fetch tea types from the model
        $teaTypes = $this->stockModel->getTeaTypes();

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($teaTypes);
    }

    public function getGradings($teaTypeId) {
        // Fetch gradings from the model based on the tea type ID
        $gradings = $this->stockModel->getGradingsByTeaType($teaTypeId); 

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($gradings);
    }

    public function getStockDetails($teaTypeId) {
        // Fetch stock details from the model
        $stockDetails = $this->stockModel->getStockDetailsByTeaType($teaTypeId);

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($stockDetails);
    }

    public function getAwaitingInventoryCollections() {
        // Fetch collections from the model
        $collections = $this->collectionApprovalModel->getAwaitingInventoryCollections();
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($collections);
    }

    public function getCollectionDetails($collectionId) {
        // Fetch collection details from the model
        $collectionDetails = $this->collectionApprovalModel->getCollectionDetails($collectionId);
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($collectionDetails);
    }

    public function getBagsBySupplier() {
        // Get the raw POST data
        $data = json_decode(file_get_contents("php://input"));
    
        // Extract supplierId and collectionId
        $supplierId = $data->supplierId;
        $collectionId = $data->collectionId;
    
        // Fetch bag details from the model
        $bags = $this->collectionApprovalModel->getBagsBySupplier($supplierId, $collectionId);
    
        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($bags);
    }

    public function getBagDetails($bagId) {
        // Fetch bag details from the model
        $bagDetails = $this->collectionApprovalModel->getBagDetails($bagId);

        // Check if bag details were found
        if (!$bagDetails) {
            // Optionally handle the case where no bag details are found
            http_response_code(404); // Set a 404 response code
            echo json_encode(['error' => 'Bag not found']);
            return;
        }

        // Return the data as JSON
        header('Content-Type: application/json');
        echo json_encode($bagDetails);
    }

    public function approveBag() {
        // Get the JSON input
        $data = json_decode(file_get_contents("php://input"), true);

        // Extract data
        $bagId = $data['bag_id'];
        $supplierId = $data['supplier_id'];
        $collectionId = $data['collection_id'];

        // Update the action in bag_usage_history to 'approved'
        $this->collectionApprovalModel->updateBagUsageHistory($bagId, 'approved');

        // Check if there are any more bags for the supplier in the collection
        $hasMoreBags = $this->collectionApprovalModel->checkSupplierBagsInCollection($supplierId, $collectionId);

        // If no more bags, update the collection_supplier_records
        if (!$hasMoreBags) {
            $this->collectionApprovalModel->updateCollectionSupplierApprovalStatus($supplierId, 'APPROVED');
        }

        // Check if all suppliers for the collection are approved
        $allSuppliersApproved = $this->collectionApprovalModel->checkAllSuppliersApproved($collectionId);

        // If all suppliers are approved, update the collection status
        if ($allSuppliersApproved) {
            $this->collectionApprovalModel->updateCollectionStatus($collectionId, 'Completed');
        }

        echo json_encode(['status' => 'success']);
    }
}
