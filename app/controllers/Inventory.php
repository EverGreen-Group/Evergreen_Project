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
    private $logModel;





    public function __construct()
    {


        $this->productModel = new M_Products();
        $this->fertilizerModel = new M_Fertilizer();
        $this->stockvalidate = new M_Dashbord();
        $this->machineModel = new M_Machine();
        $this->inventoryConfigModel = new M_Inventory_Config();
        $this->leafchartdata = new M_Dashbord();
        $this->fertilizerOrderModel = new M_Fertilizer_Order();

        $this->logModel = $this->model('M_Log');


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
                'product-name' => trim($_POST['product_name']),
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

            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/products/';

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $uniqueFilename = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $uniqueFilename;

                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uniqueFilename;
                }
            }

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
                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Product '{$data['product-name']}' added successfully.",
                        $_SERVER['REQUEST_URI'],
                        http_response_code()
                    );
                    setFlashMessage('Added product successfully!');
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
        $fer_chat_data = $this->fertilizerModel->get_last_6month_quatity();

        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = 0; // default to 0
        }

        // Step 2: Fill in actual data
        foreach ($fer_chat_data as $row) {
            $month = $row->month;
            $quantity = $row->total_quantity;
            $months[$month] = (int) $quantity; // overwrite default 0 if found
        }

        $bar_chart_data = [];
        foreach ($months as $month => $quantity) {
            $bar_chart_data[] = [
                'month' => $month,
                'total_quantity' => $quantity,
            ];
        }
        $data = [
            'fertilizer' => $fertilizer,
            'chart_data' => $bar_chart_data
        ];
        // var_dump($fer_chat_data);

        $this->view('inventory/v_fertilizer_dashboard', $data);

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
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

        $data = [
            'fertilizers' => $fertilizer
        ];
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
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

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = pathinfo($_FILES['fertilizer_image']['name'], PATHINFO_EXTENSION);
                $uniqueFilename = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $uniqueFilename;

                if (move_uploaded_file($_FILES['fertilizer_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uniqueFilename;
                }
            } else {
                print_r("no file found");
            }

            // Validation
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
                && !empty($data['code']) && !empty($data['price']) && !empty($data['quantity']) && !empty($data['unit']) && !empty($data['image_path'])
            ) {
                if ($this->fertilizerModel->createFertilizer($data)) {
                    // Log successful fertilizer addition
                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Fertilizer '{$data['fertilizer_name']}' added successfully.",
                        $_SERVER['REQUEST_URI'],
                        http_response_code()
                    );
                    setFlashMessage('Fertilizer added successfully!');
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

    public function updatefertilizer($id =null)
{
    if ($id === null && isset($_POST['id'])) {
        $id = $_POST['id'];
    }
    if ($id === null) {
        // Handle the case where no ID is provided
        die('No fertilizer ID provided');
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fertilizer_name'])) {
        
        print_r($_POST);

        $data = [
            'id' => $id,
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
        
        // Basic validation
        if (empty($data['fertilizer_name'])) {
            $data['fertilizer_name_err'] = 'Please enter fertilizer name';
        }
        if (empty($data['company_name'])) {
            $data['company_name_err'] = 'Please select company name';
        }
        if (empty($data['code'])) {
            $data['code_err'] = 'Please enter code';
        }
        if (empty($data['price'])) {
            $data['price_err'] = 'Please enter price';
        }
        if (empty($data['quantity'])) {
            $data['quantity_err'] = 'Please enter quantity';
        }
        if (empty($data['unit'])) {
            $data['unit_err'] = 'Please select unit';
        }
        
        // Handle image upload
        if (isset($_FILES['fertilizer_image']) && $_FILES['fertilizer_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/fertilizers/';

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileExtension = pathinfo($_FILES['fertilizer_image']['name'], PATHINFO_EXTENSION);
            $uniqueFilename = uniqid() . '.' . $fileExtension;
            $uploadPath = $uploadDir . $uniqueFilename;

            if (move_uploaded_file($_FILES['fertilizer_image']['tmp_name'], $uploadPath)) {
                $data['image_path'] = $uniqueFilename;
            }
        }
        
        // Check for validation errors
        if (
            empty($data['fertilizer_name_err']) && 
            empty($data['company_name_err']) && 
            empty($data['code_err']) && 
            empty($data['price_err']) && 
            empty($data['quantity_err']) && 
            empty($data['unit_err'])
        ) {
            // print_r($data);
            if ($this->fertilizerModel->updateFertilizer($id, $data)) {
                print_r("ghfh");
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Fertilizer with ID {$id} updated successfully.",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
                setFlashMessage('Fertilizer updated successfully!');
                redirect('inventory/fertilizerdashboard');
            } else {
                die('Something went wrong with the update');
            }
        } else {
            // If there are errors, load the view with error messages
            $fertilizer = $this->fertilizerModel->getFertilizerById($id);
            $data['fertilizer'] = $fertilizer;
            $this->view('inventory/v_update_fertilizer', $data);
        }
    } else {
        // Initial load of the form
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


            // var_dump($data);
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
            $machinechart = $this->machineModel->machinetimeduration();
            $data = [
                'machines' => $machines
            ];
            // var_dump($machinechart);
            // Load the form view for GET requests
            $this->view('inventory/v_machineallocation', $data);
            
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
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

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
                'product-name_err' => '',
                'location_err' => '',
                'details_err' => '',
                'price_err' => '',
                'quantity_err' => ''
            ];

            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/products/';

                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);
                $uniqueFilename = uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $uniqueFilename;

                if (move_uploaded_file($_FILES['product_image']['tmp_name'], $uploadPath)) {
                    $data['image_path'] = $uniqueFilename;
                }
            }

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
                if ($this->productModel->updateProduct($data)) {
                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Product with ID {$id} updated successfully.",
                        $_SERVER['REQUEST_URI'],
                        http_response_code()
                    );
                    setFlashMessage('Product updated successfully');
                    redirect('inventory/product');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('inventory/v_update_product', $data);
            }

        } else {
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
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Product with ID {$id} removed successfully.",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
                setFlashMessage('Product removed successfully');
            } else {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Failed to remove product with ID {$id}.",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
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
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Fertilizer with ID {$id} removed successfully.",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
                setFlashMessage('Fertilizer removed successfully!');
            } else {
                $this->logModel->create(
                    $_SESSION['user_id'],
                    $_SESSION['email'],
                    $_SERVER['REMOTE_ADDR'],
                    "Failed to remove fertilizer with ID {$id}.",
                    $_SERVER['REQUEST_URI'],
                    http_response_code()
                );
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



        $this->view('inventory/v_payments', $data);
    }





    public function viewAwaitingInventory($collectionId)
    {
        $collectionDetails = $this->stockvalidate->getvalidateStocks();


        $bagsForCollection = $this->stockvalidate->getBagForCollection($collectionId);
        $getTotalQuantityInACollection = $this->stockvalidate->getTotalQuantityInACollection($collectionId);
        $totalQuantity = $getTotalQuantityInACollection->sum;
        $totalBags = $getTotalQuantityInACollection->count;
        $getBagCountsInCollection = $this->stockvalidate->getBagCountsInCollection($collectionId);
        $bagsApproved = $getBagCountsInCollection->finalized_count;
        $bagsNotApproved = $getBagCountsInCollection->not_finalized_count;

        // if (empty($bagsForCollection)) {
        //     redirect("inventory/");
        // }

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


    // APPROVING BAG, MUST IMRPOVE IT FURTHER, 
    public function approveBag($historyId, $collectionId)
    {
        $result = $this->stockvalidate->processApproval($historyId);

        if ($result['success']) {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Bag with history ID {$historyId} approved successfully.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Bag approval successful!');
        } else {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Failed to approve bag with history ID {$historyId}.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Failed to approve this bag', 'error');
        }

        redirect("inventory/viewAwaitingInventory/$collectionId");
    }


    public function updateBag($historyId)
    {
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

            if (empty($data['actual_weight_kg']) || !is_numeric($data['actual_weight_kg'])) {
                $data['error'] = 'Please enter a valid weight';
            }

            if (empty($data['error'])) {
                if ($this->stockvalidate->updateBag($data)) {
                    $collectionId = $this->stockvalidate->getBagCollectionId($historyId);
                    $this->logModel->create(
                        $_SESSION['user_id'],
                        $_SESSION['email'],
                        $_SERVER['REMOTE_ADDR'],
                        "Bag with history ID {$historyId} updated successfully.",
                        $_SERVER['REQUEST_URI'],
                        http_response_code()
                    );
                    setFlashMessage('Bag properties updated successfully!');
                    redirect("inventory/viewAwaitingInventory/$collectionId");
                } else {
                    $data['error'] = 'Something went wrong';
                }
            }
        } else {
            $bag = $this->stockvalidate->getBagByHistoryId($historyId);

            if (!$bag) {
                setFlashMessage('Bag not found, please try again later!', 'error');
                redirect("inventory/");
            }

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

        if ($this->stockvalidate->markAsInactive($id)) {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Bag with ID {$id} has been marked as inactive.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Bag has been emptied successfully!');
        } else {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Failed to mark bag with ID {$id} as inactive.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Failed to empty the bag!', 'error');
        }

        redirect('inventory/collectionBags');
    }

    public function deleteBag($bagId)
    {
        $result = $this->stockvalidate->deleteBag($bagId);

        if ($result) {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Bag with ID {$bagId} deleted successfully.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Bag deleted successfully!');
        } else {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Failed to delete bag with ID {$bagId}.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Bag deletion failed!', 'error');
        }

        redirect("inventory/collectionBags");
    }



    public function createBag()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'capacity_kg' => trim($_POST['capacity_kg']),
                'status' => 'inactive'
            ];

            if (empty($data['capacity_kg']) || !is_numeric($data['capacity_kg']) || $data['capacity_kg'] <= 0) {
                setFlashMessage('Please enter a capacity greater than 0!', 'error');
                redirect('inventory/createBag');
                exit;
            }

            $this->stockvalidate->addBag($data);
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "New bag with capacity {$data['capacity_kg']} kg created.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
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
