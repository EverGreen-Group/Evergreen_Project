<?php
require_once APPROOT . '/models/M_Products.php';
require_once APPROOT . '/models/M_Fertilizer.php';
require_once APPROOT . '/models/M_Dashbord.php';
require_once APPROOT . '/models/M_Machine.php';
require_once APPROOT . '/models/M_Inventory_Config.php';
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once '../app/models/M_Products.php';
class Inventory extends controller
{
    private $productModel;
    private $fertilizerModel;

    private $stockvalidate;
    private $machineModel;

    private $inventoryConfigModel;
    private $leafchartdata;
    private $fertilizerOrderModel;





    public function __construct()
    {

        $this->productModel = new M_Products();
        $this->fertilizerModel = new M_Fertilizer();
        $this->stockvalidate = new M_Dashbord();
        $this->machineModel = new M_Machine();
        $this->inventoryConfigModel = new M_Inventory_Config();
        $this->leafchartdata = new M_Dashbord();
        $this->fertilizerOrderModel = new M_Fertilizer_Order();

    }

    public function index()
    {
        // Get the stock validation data
        
        $stockvalidate = $this->stockvalidate->getvalidateStocks();

        // Get leaf quantities for the last 7 days
        $leafQuantities = $this->stockvalidate->getleafoflast7days();

        $machine = $this->machineModel->getmachines();
        
        // Process the leaf quantities data for the chart
        $normalLeafData = [];
        $superLeafData = [];
        $dates = [];
        
        foreach ($leafQuantities as $record) {
            $date = $record->date;
            if (!in_array($date, $dates)) {
                $dates[] = $date;
            }
            
            if ($record->leaf_type_id == 1) {
                $normalLeafData[$date] = $record->total_quantity;
            } else if ($record->leaf_type_id == 2) {
                $superLeafData[$date] = $record->total_quantity;
            }
        }

        // Fill in missing dates with 0
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            if (!isset($normalLeafData[$date])) {
                $normalLeafData[$date] = 0;
            }
            if (!isset($superLeafData[$date])) {
                $superLeafData[$date] = 0;
            }
        }
        
        // Sort by date
        ksort($normalLeafData);
        ksort($superLeafData);

        $awaitingInventory = 5;
        $kgApprovedToday = 150;
        $fertilizerOrders = 3;
        $activeBags = $this->stockvalidate->getBagsByStatus('active');
        $inactiveBags = $this->stockvalidate->getBagsByStatus('inactive');

        $activeBagsCount = count($activeBags);
        $inactiveBagsCount = count($inactiveBags);

        $data = [
            'stockvalidate' => $stockvalidate,
            'awaitingInventory' => $awaitingInventory,
            'kgApprovedToday' => $kgApprovedToday,
            'fertilizerOrders' => $fertilizerOrders,
            'bagUsageCounts' => ['active' => $activeBagsCount, 'inactive' => $inactiveBagsCount],
            'normalLeafData' => array_values($normalLeafData),
            'superLeafData' => array_values($superLeafData),
            'chartDates' => array_keys($normalLeafData),
            'machines' => $machine,
        ];
       // var_dump($data);


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
                    setFlashMessage('Added product sucessfully!');
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

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_approve'])) {
            $us = $_GET['id'];
            // var_dump($us);

            $this->fertilizerOrderModel->updateFertilizerByStatus($us, 'Approved');
            // redirect('Inventory/fertilizerdashboard');

        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_reject'])) {
            $us = $_GET['id'];

            $this->fertilizerOrderModel->updateFertilizerByStatus($us, 'Rejected');
            // redirect('Inventory/fertilizerdashboard');

        }

        $fertilizer = $this->fertilizerOrderModel->getfertilizerorderforInventory();
    
        $data=[
            'fertilizers' => $fertilizer
        ];
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        $this->view('inventory/v_fertilizer_available',$data);
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
            } else {
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
                    setFlashMessage('Fertilizer added sucessfully!');

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
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['machine_name'])) {
            // Sanitize POST data
            var_dump($_POST);
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
            if (empty($data['machine_name']))
                $errors['machine_name'] = 'Machine name is required.';
            if (empty($data['brand']))
                $errors['brand'] = 'Brand is required.';
            if (empty($data['started_date']))
                $errors['started_date'] = 'Started date is required.';
            if (empty($data['last_maintenance']))
                $errors['last_maintenance'] = 'Last maintenance is required.';
            if (empty($data['next_maintenance']))
                $errors['next_maintenance'] = 'Next maintenance is required.';
            if (empty($data['total_working_hours']))
                $errors['total_working_hours'] = 'Total working hours are required.';
            if (empty($data['special_notes']))
                $errors['special_notes'] = 'Special notes are required.';


                var_dump($data);
            if (empty($errors)) {
                // Save the data to the database
                $machineModel = $this->model('M_Machine');

                if ($machineModel->insertMachineData($data)) {
                    // Redirect to success page or show success message
                    setFlashMessage('Machine added sucessfully!');
                    redirect('Inventory/machine');
                } else {
                    // Handle database error
                    die('Something went wrong');
                }
            } else {
                // Load the form view with errors
                var_dump($errors);
                
                $this->view('inventory/v_machineallocation', $data);
            }
        } elseif (isset($_GET['id']) && isset($_POST['status_allocate'])) {
            $us = $_GET['id'];

            $machineModel = $this->model('M_Machine');
            $machineModel->updateMachineByStatus($us, 'Allocated');
            redirect('Inventory/machine');

        } elseif (isset($_GET['id']) && isset($_POST['status_deallocate'])) {
            $us = $_GET['id'];

            $machineModel = $this->model('M_Machine');
            $machineModel->updateMachineByStatus($us, 'Repair');
            redirect('Inventory/machine');

        } else {
            // GET request
            $machines = $this->machineModel->getmachines();
            $data = [
                'machines' => $machines
            ];
            // Load the form view for GET requests
            $this->view('inventory/v_machineallocation', $data);
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
                    setFlashMessage('Product updated sucessfully');
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
                setFlashMessage('Product removed successful');
            } else {
                setFlashMessage('Product removal failed', 'error');
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
                setFlashMessage('Fertilizer removed successfully!');
            } else {
                setFlashMessage('Fertilizer removal failed!', 'error');
            }
        }
        redirect('inventory/fertilizerdashboard');
    }

    
    public function payments()
{
    $jsonData = file_get_contents("php://input");
    $input = json_decode($jsonData, true);

    // Log the incoming data
    error_log(print_r($input, true));

    // echo "Received data: " . $jsonData;  
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Check if required fields are present
        if (isset($input['normalLeafRate'], $input['superLeafRate'], $input['fertilizerStockLower'], $input['fertilizerStockMidLow'], $input['fertilizerStockMidHigh'], $input['leafAge1'], $input['leafAge2'], $input['leafAge3'])) {
            $data = [
                'normalLeafRate' => $input['normalLeafRate'],
                'superLeafRate' => $input['superLeafRate'],
                'fertilizerStockLower' => $input['fertilizerStockLower'],
                'fertilizerStockMidLow' => $input['fertilizerStockMidLow'],
                'fertilizerStockMidHigh' => $input['fertilizerStockMidHigh'],
                'leafAge1' => $input['leafAge1'],
                'leafAge2' => $input['leafAge2'],
                'leafAge3' => $input['leafAge3']
            ];

            // Log the data array
            error_log(print_r($data, true));

            $this->inventoryConfigModel->add_inventory_config($data);
        } else {
            // Handle missing fields
            echo "Error: Missing required fields.";
        }
    }


    $fertilizer = $this->fertilizerModel->getfertilizer();
    $data = [
        'fertilizer' => $fertilizer
    ];

   

    $this->view('inventory/v_payments',$data);
}


public function payments2() {

    $paymentModel = $this->model('M_Payment');
    $paymentSummary = $paymentModel->getPaymentSummary();



    $data = [
        'payment_summary' => $paymentSummary
    ];

    $this->view('inventory/v_payments_2', $data);
}

public function createPaymentReport() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $year = $_POST['year'];
        $month = $_POST['month'];
        $normalLeafRate = $_POST['normal_leaf_rate'];
        $superLeafRate = $_POST['super_leaf_rate'];

        // Add validation
        if (empty($year) || empty($month) || empty($normalLeafRate) || empty($superLeafRate)) {
            setFlashMessage('Please enter the year, month, normal leaf rate, and super leaf rate to generate the report', 'error');
            redirect('inventory/payments2');
            return;
        }

        // Validate for negative values
        if ($normalLeafRate < 0 || $superLeafRate < 0) {
            setFlashMessage('Normal leaf rate and super leaf rate must be non-negative values.', 'error');
            redirect('inventory/payments2');
            return;
        }

        $paymentModel = $this->model('M_Payment');
        
        try {
            $result = $paymentModel->generateMonthlyPayment($year, $month, $normalLeafRate, $superLeafRate);
            
            if ($result) {
                setFlashMessage('Payment report created successfully!');
            } else {
                setFlashMessage('Payment report generation failed!', 'error');
            }
        } catch (Exception $e) {
            setFlashMessage('Error when generating the report, Error: ' . $e);
        }
        
        redirect('inventory/payments2');
    } else {
        redirect('inventory/payments2');
    }
}



public function deletePaymentReport($payment_id) {
    // Load payment model
    $paymentModel = $this->model('M_Payment');
    
    try {

        
        $result = $paymentModel->deletePayment($payment_id);
        
        
        if ($result) {
            setFlashMessage('Payment report deleted successfully!');
        } else {
            setFlashMessage('Payment report deletion failed!', 'error');
        }
    } catch (Exception $e) {

        setFlashMessage('Error when deleting the report: ' . $e->getMessage(), 'error');
    }
    
    redirect('inventory/payments2');
}


public function viewPaymentReport($payment_id) {
    $paymentModel = $this->model('M_Payment');

    $paymentDetails = $paymentModel->getPaymentDetailsByPaymentId($payment_id); 

    $data = [
        'payment_details' => $paymentDetails 
    ];

    $this->view('inventory/v_view_payment_report', $data);
}



    public function getStockValidations()
    {
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
        $getTotalQuantityInACollection = $this->stockvalidate->getTotalQuantityInACollection($collectionId);
        $totalQuantity = $getTotalQuantityInACollection->sum;
        $totalBags = $getTotalQuantityInACollection->count;
        $getBagCountsInCollection = $this->stockvalidate->getBagCountsInCollection($collectionId);
        $bagsApproved = $getBagCountsInCollection->finalized_count;
        $bagsNotApproved = $getBagCountsInCollection->not_finalized_count;

        if (empty($bagsForCollection)) {
            redirect("inventory/"); 
        }

        $data = [
            'collectionDetails' => $collectionDetails,
            'collectionBags' => $bagsForCollection,
            'collection_id' => $collectionId,
            'total_quantity' => $totalQuantity,
            'total_bags' => $totalBags,
            'bags_approved' => $bagsApproved,
            'bags_not_approved' => $bagsNotApproved
        ];


        $this->view('inventory/v_view_collection_bags', $data);
    }  


    // APPROVING BAG, MUST IMRPOVE IT FURTHER, LIKE DEDUCTIONS AND ALL
    public function approveBag($historyId, $collectionId)
    {
        
        // Call the model method to handle all logic and database operations
        $result = $this->stockvalidate->processApproval($historyId);
        
        if ($result['success']) {
            setFlashMessage('Bag approval successful!');
        } else {
            setFlashMessage('Failed tho approve this bag', 'error');
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
                    setFlashMessage('Bag properties updated sucessfully!');
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
                setFlashMessage('Bag not found, please try again later!', 'error');
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


    public function collectionBags()
    {
        // Get active bags (status = 'active')
        $activeBags = $this->stockvalidate->getBagsByStatus('active');
        
        // Get inactive bags (status = 'inactive')
        $inactiveBags = $this->stockvalidate->getBagsByStatus('inactive');
        
        // Calculate statistics
        $totalBags = count($activeBags) + count($inactiveBags);
        $activeBagsCount = count($activeBags);
        $inactiveBagsCount = count($inactiveBags);
        
        // Calculate total capacity
        $totalCapacity = 0;
        foreach ($activeBags as $bag) {
            $totalCapacity += $bag->capacity_kg;
        }
        foreach ($inactiveBags as $bag) {
            $totalCapacity += $bag->capacity_kg;
        }
        
        $data = [
            'activeBags' => $activeBags,
            'inactiveBags' => $inactiveBags,
            'totalBags' => $totalBags,
            'activeBagsCount' => $activeBagsCount,
            'inactiveBagsCount' => $inactiveBagsCount,
            'totalCapacity' => $totalCapacity
        ];
        
        $this->view('inventory/v_collection_bags', $data);
        var_dump($data);
    }


    public function markAsInactive($id = null)
    {

        if (!$id) {
            setFlashMessage('Invalid bag id, bag may not be used!', 'error');
            redirect('inventory/collectionBags');
        }
        
        // Make sure the ID is numeric
        if (!is_numeric($id)) {
            setFlashMessage('Bag id is not in numeric format', 'error');
            redirect('inventory/collectionBags');
        }
        

        $bag = $this->stockvalidate->getBagById($id);
        
        if (!$bag) {
            setFlashMessage('Bag is not found!', 'error');
            redirect('inventory/collectionBags');
        }
        
        if ($bag->status !== 'active') {
            setFlashMessage('Bag is already inactive!', 'error');
            redirect('inventory/collectionBags');
        }
        
        // Update bag status to inactive and reset weight
        if ($this->stockvalidate->markAsInactive($id)) {
            setFlashMessage('Bag has been emptied sucessfully!');
        } else {
            setFlashMessage('Failed to empty the bag!', 'error');
        }
        
        redirect('inventory/collectionBags');
    }

    public function deleteBag($bagId)
    {
        // Call the model method to delete the bag
        $result = $this->stockvalidate->deleteBag($bagId);
        
        if ($result) {
            setFlashMessage('Bag deleted successfuly!');
        } else {
            setFlashMessage('Bag deletion failed!', 'error');
        }
        
        redirect("inventory/collectionBags");
    }



    public function createBag() {
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Init data
            $data = [
                'capacity_kg' => trim($_POST['capacity_kg']),
                'status' => 'inactive'
            ];
            
            // Validate capacity
            if (empty($data['capacity_kg']) || !is_numeric($data['capacity_kg']) || $data['capacity_kg'] <= 0) {
                setFlashMessage('Please enter a capacity greater than 0!', 'error');
                redirect('inventory/createBag');
                exit;
            }
            
            $this->stockvalidate->addBag($data);
            redirect('inventory/collectionBags');

        }
            
        $this->view('inventory/v_create_bag');
    }

    public function rawLeafHistory()
    {
        // Get leaf quantities data
        $leafQuantities = $this->stockvalidate->getleafoflast7days();
        
        $data = [
            'leafQuantities' => $leafQuantities
        ];
        
        $this->view('inventory/v_raw_leaf_history', $data);
    }

    public function manageLeafRate()
    {

            
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Sanitize POST data
                $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
                // Initialize data
                $data = [
                    'leaf_type_id' => trim($_POST['leaf_type_id']),
                    'rate' => trim($_POST['rate']),
                    'error' => ''
                ];
        
                // Validate inputs
                if (empty($data['leaf_type_id']) || empty($data['rate'])) {
                    $data['error'] = 'Please fill in all fields';
                    $this->view('inventory/v_manage_rate_form', $data);
                    return;
                }
        
                // Validate leaf_type_id is either 1 or 2
                if (!in_array($data['leaf_type_id'], ['1', '2'])) {
                    $data['error'] = 'Invalid leaf type selected';
                    $this->view('inventory/v_manage_rate_form', $data);
                    return;
                }
        
                // Validate rate is a positive number
                if (!is_numeric($data['rate']) || $data['rate'] <= 0) {
                    $data['error'] = 'Rate must be a positive number';
                    $this->view('inventory/v_manage_rate_form', $data);
                    return;
                }
        
                // Add leaf rate using model
                if ($this->stockvalidate->addLeafRate($data)) {
                    setFlashMessage('Tea leaf rate added successfully!');
                    redirect('inventory/');
                } else {
                    $data['error'] = 'Something went wrong';
                    $this->view('inventory/v_manage_rate_form', $data);
                }
            } else {
                // Initialize empty data
                $data = [
                    'leaf_type_id' => '',
                    'rate' => '',
                    'error' => ''
                ];
            
            $this->view('inventory/v_manage_rate_form', $data);
        }
    }

}
