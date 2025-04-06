<?php
require_once APPROOT . '/models/M_Products.php';
require_once APPROOT . '/models/M_Fertilizer.php';
require_once APPROOT . '/models/M_Dashbord.php';
require_once APPROOT . '/models/M_Machine.php';

require_once '../app/models/M_Products.php';
class Inventory extends controller
{
    private $productModel;
    private $fertilizerModel;

    private $stockvalidate;
    private $machineModel;





    public function __construct()
    {

        $this->productModel = new M_Products();
        $this->fertilizerModel = new M_Fertilizer();
        $this->stockvalidate = new M_stockvalidate();
        $this->machineModel = new M_Machine();

    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $report = ['report' => $_POST['report']];
        }

        $stockvalidate = $this->stockvalidate->getvalidateStocks();

        // Dummy values for the new statistics
        $awaitingInventory = 5; // Number of items awaiting inventory addition
        $kgApprovedToday = 150; // Number of kg approved today
        $fertilizerOrders = 3; // Number of fertilizer orders

        $data = [
            'stockvalidate' => $stockvalidate,
            'awaitingInventory' => $awaitingInventory,
            'kgApprovedToday' => $kgApprovedToday,
            'fertilizerOrders' => $fertilizerOrders,
            'bagUsageCounts' => ['active' => 5, 'inactive' => 10]
        ];

        $this->view('inventory/v_dashboard', $data);
    }

    public function product()
    {
        $products = $this->productModel->getAllProducts();
        $data = [
            'products' => $products
        ];

        if (isset($_GET['search'])) {
            $search = $_GET['search'];
            $products = $this->productModel->searchProducts($search);
            $data = [
                'products' => $products
            ];
        }

        $this->view('inventory/v_product', $data);
    }

    public function order()
    {
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
                "grade" => trim($_POST['grade']),
                "price" => trim($_POST['price']),
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
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }
            if (
                empty($data['product-name_err']) && empty($data['location_err']) &&
                empty($data['details_err']) && empty($data['price_err']) &&
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
                "grade" => '',
                "price" => '',
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

    public function updatefertilizer($id)
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            ) {

                
            }

        } else {
            $fertilizer = $this->fertilizerModel->getFertilizerById($id);
            $data = [
                'id' => $id,
                'fertilizer' => $fertilizer
            ];
            // print_r($data);
            $this->view('inventory/v_update_fertilizer', $data);
        }
    }

    public function machine()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form'])) {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST);
          // Collect form data

            $data = [
                'machine_name' => trim($_POST['machine_name']),
                'brand' => trim($_POST['brand']),
                'started_date' => trim($_POST['started_date']),
                'last_maintenance' => trim($_POST['last_maintenance']),
                'next_maintenance' => trim($_POST['next_maintenance']),
                'total_working_hours' => trim($_POST['total_working_hours']),
                'special_notes' => trim($_POST['specialnotes']),
            ];

            // Validate data
            $errors = [];
            if (empty($data['machine_name'])) $errors['machine_name'] = 'Machine name is required.';
            if (empty($data['brand'])) $errors['brand'] = 'Brand is required.';
            if (empty($data['started_date'])) $errors['started_date'] = 'Started date is required.';
            if (empty($data['last_maintenance'])) $errors['last_maintenance'] = 'Last maintenance is required.';
            if (empty($data['next_maintenance'])) $errors['next_maintenance'] = 'Next maintenance is required.';
            if (empty($data['total_working_hours'])) $errors['total_working_hours'] = 'Total working hours are required.';
            if (empty($data['special_notes'])) $errors['special_notes'] = 'Special notes are required.';

            if (empty($errors)) {
                // Save the data to the database
                $machineModel = $this->model('M_Machine');

                if ($machineModel->insertMachineData($data)) {
                    // Redirect to success page or show success message
                    flash('machine_message', 'Machine data added successfully!');
                    redirect('Inventory/machine');
                } else {
                    // Handle database error
                    die('Something went wrong');
                }
            } else {
                // Load the form view with errors
                $this->view('inventory/v_machineallocation', $data);
            }
        }
        elseif (isset($_GET['id']) && isset($_POST['status_allocate'])) {
            $us=$_GET['id'];

            $machineModel = $this->model('M_Machine');
            $machineModel->updateMachineByStatus($us, 'Allocated');
            redirect('Inventory/machine');
            
        }
        elseif (isset($_GET['id']) && isset($_POST['status_deallocate'])) {
            $us=$_GET['id'];

            $machineModel = $this->model('M_Machine');
            $machineModel->updateMachineByStatus($us, 'Repair');
            redirect('Inventory/machine');
            
        }
        else {
            // GET request
            $machines= $this->machineModel->getmachines();
            $data = [
                'machines' => $machines
            ];
            // Load the form view for GET requests
            $this->view('inventory/v_machineallocation',$data);
            //var_dump($data);
        }

        
        $this->view('inventory/v_machineallocation', $data);
        //var_dump($data);
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
                "grade" => trim($_POST['grade']),
                'price' => trim($_POST['price']),
                'quantity' => trim($_POST['quantity']),
                'unit' => trim($_POST['unit']),
                'image_path' => '',
                // Error fields
                'product-name_err' => '',
                'location_err' => '',
                'details_err' => '',
                'price_err' => '',
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
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
            }
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
            }

            // Make sure no errors
            if (
                empty($data['product-name_err']) && empty($data['location_err']) &&
                empty($data['details_err']) && empty($data['price_err']) &&
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

    public function recodes()
    {
        $data = [];

        $this->view('inventory/v_recodes', $data);
    }

    public function deletefertilizer($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if ($this->fertilizerModel->deleteFertilizer($id)) {
                flash('fertilizer_message', 'Fertilizer Removed');
            } else {
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

    public function getStockValidations() {
        // Get status filter if provided
        $status = isset($_GET['status']) ? $_GET['status'] : 'All';
        
        // Get the data from model
        $stocks = $this->stockvalidate->getvalidateStocks($status);
        
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($stocks);
        exit();
    }

    public function viewAwaitingInventory($collectionId)
    {
        $collectionDetails = $this->stockvalidate->getvalidateStocks($collectionId);


        $bagsForCollection = $this->stockvalidate->getBagForCollection($collectionId);

        if (empty($bagsForCollection)) {
            redirect("inventory/"); 
        }

        $data = [
            'collectionDetails' => $collectionDetails,
            'collectionBags' => $bagsForCollection 
        ];


        $this->view('inventory/v_view_collection_bags', $data);
    }  


    // APPROVING BAG, MUST IMRPOVE IT FURTHER, LIKE DEDUCTIONS AND ALL
    public function approveBag($historyId, $collectionId)
    {
        
        // Call the model method to handle all logic and database operations
        $result = $this->stockvalidate->processApproval($historyId);
        
        if ($result['success']) {
            flash('inventory_message', $result['message'], 'alert alert-success');
        } else {
            flash('inventory_message', $result['message'], 'alert alert-danger');
        }
        
        redirect("inventory/viewAwaitingInventory/$collectionId");
    }


    public function updateBag($historyId)
    {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'history_id' => $historyId,
                'actual_weight_kg' => trim($_POST['actual_weight_kg']),
                'leaf_age' => trim($_POST['leaf_age']),
                'moisture_level' => trim($_POST['moisture_level']),
                'deduction_notes' => trim($_POST['deduction_notes']),
                'leaf_type_id' => trim($_POST['leaf_type_id']),
                'error' => ''
            ];
    
            // Validate weight
            if (empty($data['actual_weight_kg']) || !is_numeric($data['actual_weight_kg'])) {
                $data['error'] = 'Please enter a valid weight';
            }
    
            // If no errors, update the bag
            if (empty($data['error'])) {
                if ($this->stockvalidate->updateBag($data)) {
                    // Get collection ID for redirect
                    $collectionId = $this->stockvalidate->getBagCollectionId($historyId);
                    flash('inventory_message', 'Bag updated successfully', 'alert alert-success');
                    redirect("inventory/viewAwaitingInventory/$collectionId");
                } else {
                    $data['error'] = 'Something went wrong';
                }
            }
        } else {
            // Get existing bag data
            $bag = $this->stockvalidate->getBagByHistoryId($historyId);
            
            // If bag not found, redirect
            if (!$bag) {
                flash('inventory_message', 'Bag not found', 'alert alert-danger');
                redirect("inventory/");
            }
            
            // Get leaf types for dropdown
            $leafTypes = $this->stockvalidate->getLeafTypes();
            
            $data = [
                'bag' => $bag,
                'leaf_types' => $leafTypes,
                'error' => ''
            ];
        }
    
        $this->view('inventory/v_update_bag', $data);
    }



}
