<?php

require_once APPROOT . '/models/M_Products.php';
require_once APPROOT . '/models/M_Fertilizer.php';
require_once APPROOT . '/models/M_Dashbord.php';
require_once APPROOT . '/models/M_Machine.php';
require_once APPROOT . '/models/M_Inventory_Config.php';
require_once APPROOT . '/models/M_Fertilizer_Order.php';
require_once '../app/models/M_Products.php';
require_once APPROOT . '/services/EmailService.php';

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
    private $notificationModel;
    private $userModel;





    public function __construct()
    {


        $this->productModel = new M_Products();
        $this->fertilizerModel = new M_Fertilizer();
        $this->stockvalidate = new M_Dashbord();
        $this->machineModel = new M_Machine();
        $this->inventoryConfigModel = new M_Inventory_Config();
        $this->leafchartdata = new M_Dashbord();
        $this->fertilizerOrderModel = new M_Fertilizer_Order();
        $this->notificationModel = $this->model('M_Notification');
        $this->userModel = $this->model('M_User');

        $this->logModel = $this->model('M_Log');


    }

    public function index()
    {
        // Get the stock validation data

        $stockvalidate = $this->stockvalidate->getvalidateStocks();

        // Get leaf quantities for the last 7 days
        $leafQuantities = $this->stockvalidate->getleafoflast7days();

        $machine = $this->machineModel->getmachines();
        $fertilizer = $this->fertilizerOrderModel->getfertilizerorderforInventory();
        $awaitingstock = $this->stockvalidate->getvalidateStocks();
        $approvecollection = $this->stockvalidate->getcompletecollections();

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

        $awaitingInventory = count($awaitingstock);
        $kgApprovedToday = count($approvecollection);
        $fertilizerOrders = count($fertilizer);
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
        $Allproducts = $this->productModel->getproduct();
        $inactivecount = 0;

        foreach ($Allproducts as $recod) {
            if ($recod->is_deleted == 1) {
                $inactivecount += 1;

            }

        }

        $totalProducts = count($products);
        $data = [
            'totalInactive' => $inactivecount,
            'products' => $products,
            'totalProducts' => $totalProducts,
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
            // $_POST = filter_input_array(INPUT_POST);
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
                setFlashMessage($data['product-name_err'], 'error');
                redirect('inventory/createproduct');
            }
            if (empty($data['details'])) {
                $data['details_err'] = 'Please enter product details';
                setFlashMessage($data['details_err'], 'error');
                redirect('inventory/createproduct');
            }
            if (empty($data['grade'])) {
                $data['grade_err'] = "Please enter a grade.";
                setFlashMessage($data['grade_err'], 'error');
                redirect('inventory/createproduct');
            } elseif (substr($data['grade'], 0, 2) !== 'GT') {

                $data['grade_err'] = "Grade must start with 'GT'.";
                setFlashMessage($data['grade_err'], 'error');
                redirect('inventory/createproduct');
            } elseif (strlen($data['grade']) < 5) {
                $data['grade_err'] = "Code must be at least 7 characters long.";
                setFlashMessage($data['grade_err'], 'error');
                redirect('inventory/createproduct');
            }

            // Price validation
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/createproduct/');
            }
            if (!is_numeric($data['price'])) {
                $data['price_err'] = 'Price must be a number';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/createproduct/');
            }
            if ((float) $data['price'] <= 0) {
                $data['price_err'] = 'Price must be greater than zero';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/createproduct/');
            }

            // Quantity validation
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/createproduct/');
            }
            if (!is_numeric($data['quantity'])) {
                $data['quantity_err'] = 'Quantity must be a number';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/createproduct/');
            }
            if ((float) $data['quantity'] <= 0) {
                $data['quantity_err'] = 'Quantity must be greater than zero';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/createproduct/');
            }

            if (
                empty($data['product-name_err']) &&
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
                setFlashMessage('Added product successfully!', 'error');
                redirect('inventory/createproduct');
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
        $fertilizer2 = $this->fertilizerOrderModel->getfertilizerorderforInventory();
        $approvedCount = 0;
        $pendingCount = 0;

        foreach ($fertilizer2 as $recod) {

            if ($recod->status == 'Approved') {
                $approvedCount += 1;
            }
            if ($recod->status == 'Pending') {
                $pendingCount += 1;
            }


        }
        $data = [
            'fertilizer' => $fertilizer,
            'chart_data' => $bar_chart_data,
            'totalorder' => count($fertilizer),
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
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

            $fid = $_POST['fertilizer_id'];
            $fquantity = $_POST['order_quantity'];
            $supplierId = $_POST['supplier_id'];
            $supplierName = $_POST['full_name'];
            $supplierEmail = $_POST['supplier_email'];

            $fertilizer = $this->fertilizerModel->getFertilizerById($fid);
            if ($fertilizer) {
                $newfquantity = $fertilizer->quantity - $fquantity;

                if ($newfquantity < 0) {

                    setFlashMessage("Insufficient fertilizer quantity", "error");
                    redirect('inventory/fertilizer');
                } else {

                    $this->fertilizerModel->updatFertilizerwhenapprove($fid, $newfquantity);
                    setFlashMessage("Fertilizer Approved Successfully");
                }
            } else {
                // Handle error: Fertilizer not found
                setFlashMessage("Fertilizer not found");
            }

            $this->fertilizerOrderModel->updateFertilizerByStatus($us, 'Approved');

            // Send notification email
            $emailService = new EmailService();
            $emailService->sendFertilizerRequest($supplierEmail, $supplierName, 'Accept');

            $this->notificationModel->createNotification(
                $this->userModel->getUserIdBySupplierId($supplierId),
                'Fertilizer Request',
                'Your fertilizer request has been accepted.',
                ['link' => 'supplier/requestFertilizer/']
            );

            // redirect('Inventory/fertilizerdashboard');

        } elseif ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status_reject'])) {
            $us = $_GET['id'];

            $this->fertilizerOrderModel->updateFertilizerByStatus($us, 'Cancelled');



            // Send rejection email
            $supplierId = $_POST['supplier_id'];
            $supplierName = $_POST['full_name'];
            $supplierEmail = $_POST['supplier_email'];
            $emailService = new EmailService();
            $emailService->sendFertilizerRequest($supplierEmail, $supplierName, 'Reject');

            // redirect('Inventory/fertilizerdashboard');
        }


        $fertilizerRequest = $this->fertilizerOrderModel->getfertilizerorderforInventory();
        $fertilizer = $this->fertilizerModel->getfertilizer();

        $approvedCount = 0;
        $pendingCount = 0;

        foreach ($fertilizerRequest as $recod) {

            if ($recod->status == 'Approved') {
                $approvedCount += 1;
            }
            if ($recod->status == 'Pending') {
                $pendingCount += 1;
            }


        }
        $totalorders = count($fertilizerRequest);

        $data = [
            'fertilizerRequest' => $fertilizerRequest,
            'fertilizer' => $fertilizer,
            'totalorder' => $totalorders,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
        ];

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

                setFlashMessage("no file found", "error");
            }

            // Validation
            if (empty($data['fertilizer_name'])) {
                $data['fertilizer_name_err'] = "Please Enter Fertilizer name";
                setFlashMessage($data['fertilizer_name_err'], 'error');
                redirect('inventory/createfertilizer');
            }
            if (empty($data['company_name'])) {
                $data['company_name_err'] = "Please Enter Company name";
                setFlashMessage($data['company_name_err'], 'error');
                redirect('inventory/createfertilizer');
            }
            if (empty($data['details'])) {
                $data['details_err'] = "Please Enter Details";

                setFlashMessage($data['details_err'], 'error');
                redirect('inventory/createfertilizer');
            }
            if (empty($data['code'])) {
                $data['code_err'] = "Please enter a code.";
                setFlashMessage($data['code_err'], 'error');
                redirect('inventory/createfertilizer');
            } elseif (substr($data['code'], 0, 2) !== 'FT') {
                $data['code_err'] = "Code must start with 'FT'.";
                setFlashMessage($data['code_err'], 'error');
                redirect('inventory/createfertilizer');

            } elseif (strlen($data['code']) < 7) {
                $data['code_err'] = "Code must be at least 7 characters long.";
                setFlashMessage($data['code_err'], 'error');
                redirect('inventory/createfertilizer');
            }

            // Price validation
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/createfertilizer/');
            }
            if (!is_numeric($data['price'])) {
                $data['price_err'] = 'Price must be a number';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/createfertilizer/');
            }
            if ((float) $data['price'] <= 0) {
                $data['price_err'] = 'Price must be greater than zero';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/createfertilizer/');
            }

            // Quantity validation
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/createfertilizer/');
            }
            if (!is_numeric($data['quantity'])) {
                $data['quantity_err'] = 'Quantity must be a number';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/createfertilizer/');
            }
            if ((float) $data['quantity'] <= 0) {
                $data['quantity_err'] = 'Quantity must be greater than zero';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/createfertilizer/');
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
                    redirect('inventory/fertilizer');
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

    public function updatefertilizer($id = null)
    {
        if ($id === null && isset($_POST['id'])) {
            $id = $_POST['id'];
        }
        if ($id === null) {
            // Handle the case where no ID is provided
            die('No fertilizer ID provided');
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fertilizer_name'])) {


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

            // Fertilizer Name validation
            if (empty($data['fertilizer_name'])) {
                $data['fertilizer_name_err'] = 'Please enter fertilizer name';
                setFlashMessage($data['fertilizer_name_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }

            // Company Name validation
            if (empty($data['company_name'])) {
                $data['company_name_err'] = 'Please select company name';
                setFlashMessage($data['company_name_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }

            // Code validation
            if (empty($data['code'])) {
                $data['code_err'] = 'Please enter code';
                setFlashMessage($data['code_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            } elseif (substr($data['code'], 0, 2) !== 'FT') {
                $data['code_err'] = "Code must start with 'FT'.";
                setFlashMessage($data['code_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            } elseif (strlen($data['code']) < 7) {
                $data['code_err'] = "Code must be at least 7 characters long.";
                setFlashMessage($data['code_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }

            // Price validation
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }
            if (!is_numeric($data['price'])) {
                $data['price_err'] = 'Price must be a number';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }
            if ((float) $data['price'] <= 0) {
                $data['price_err'] = 'Price must be greater than zero';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }

            // Quantity validation
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }
            if (!is_numeric($data['quantity'])) {
                $data['quantity_err'] = 'Quantity must be a number';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }
            if ((float) $data['quantity'] <= 0) {
                $data['quantity_err'] = 'Quantity must be greater than zero';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
            }

            // Unit validation
            if (empty($data['unit'])) {
                $data['unit_err'] = 'Please select unit';
                setFlashMessage($data['unit_err'], 'error');
                redirect('inventory/updatefertilizer/' . $data['id']);
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
                    redirect('inventory/fertilizer');
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
            // var_dump($_POST);
            // $_POST = filter_input_array(INPUT_POST);
            // Collect form data

            $data = [
                'machine_name' => trim($_POST['machine_name']),
                'brand' => trim($_POST['brand']),
                'started_date' => trim($_POST['started_date']),
                'model_number' => trim($_POST['model_number']),
                'next_maintenance' => trim($_POST['next_maintenance']),
                'total_working_hours' => trim($_POST['total_working_hours']),
                'special_notes' => trim($_POST['specialnotes']),
            ];

            // Validate data
            // Validate data
            $errors = [];

            if (empty($data['started_date'])) {
                $errors['started_date'] = 'Started date is required.';
            } else {
                $startedDate = strtotime($data['started_date']);
                if ($startedDate >= time()) {
                    $errors['started_date'] = 'Started date must be in the past.';
                    setFlashMessage($errors['started_date'], 'error');
                    redirect('Inventory/machine');
                }
            }

            if (empty($data['model_number'])) {
                $errors['model_number'] = 'Model number is required.';
            }

            if (empty($data['next_maintenance'])) {
                $errors['next_maintenance'] = 'Next maintenance is required.';
            } else {
                $nextMaintenance = strtotime($data['next_maintenance']);
                if ($nextMaintenance <= time()) {
                    $errors['next_maintenance'] = 'Next maintenance date must be in the future.';
                    setFlashMessage($errors['next_maintenance'], 'error');
                    redirect('Inventory/machine');
                }
            }

            if (empty($data['total_working_hours'])) {
                $errors['total_working_hours'] = 'Total working hours are required.';
            }



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



            $machine1 = $this->machineModel->getmachineById($us);
            if ($machine1->status == 'Ready') {
                $this->machineModel->updateMachineByStatus($us, 'Allocated');
                setFlashMessage("Machine State Changed");
                redirect('Inventory/machine');
            } else {
                setFlashMessage("Machine Should be in Ready State", 'error');
                redirect('Inventory/machine');
            }

        } elseif (isset($_GET['id']) && isset($_POST['status_deallocate'])) {
            $us = $_GET['id'];

            $machineModel = $this->model('M_Machine');
            $machineModel->updateMachineByStatus($us, 'Ready');
            setFlashMessage("Machine State Changed");
            redirect('Inventory/machine');

        } elseif (isset($_GET['id']) && isset($_POST['status_repair'])) {
            $us = $_GET['id'];

            $machine1 = $this->machineModel->getmachineById($us);
            if ($machine1->status == 'Ready') {
                $this->machineModel->updateMachineByStatus($us, 'Repair');
                setFlashMessage("Machine State Changed");
                redirect('Inventory/machine');
            } else {
                setFlashMessage("Machine Should be in Ready State", 'error');
                redirect('Inventory/machine');
            }

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
            // $_POST = filter_input_array(INPUT_POST);

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
                setFlashMessage($data['product-name_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }
            if (empty($data['details'])) {
                $data['details_err'] = 'Please enter product details';
                setFlashMessage($data['details_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }
            if (empty($data['grade'])) {
                $data['grade_err'] = "Please enter a grade.";
                setFlashMessage($data['grade_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            } elseif (substr($data['grade'], 0, 2) !== 'GT') {

                $data['grade_err'] = "Grade must start with 'GT'.";
                setFlashMessage($data['grade_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            } elseif (strlen($data['grade']) < 5) {
                $data['grade_err'] = "Code must be at least 7 characters long.";
                setFlashMessage($data['grade_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }

            // Price validation
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter price';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }
            if (!is_numeric($data['price'])) {
                $data['price_err'] = 'Price must be a number';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }
            if ((float) $data['price'] <= 0) {
                $data['price_err'] = 'Price must be greater than zero';
                setFlashMessage($data['price_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }

            // Quantity validation
            if (empty($data['quantity'])) {
                $data['quantity_err'] = 'Please enter quantity';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }
            if (!is_numeric($data['quantity'])) {
                $data['quantity_err'] = 'Quantity must be a number';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }
            if ((float) $data['quantity'] <= 0) {
                $data['quantity_err'] = 'Quantity must be greater than zero';
                setFlashMessage($data['quantity_err'], 'error');
                redirect('inventory/updateproduct/' . $id);
            }

            if (
                empty($data['product-name_err']) &&
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



        // echo "Received data: " . $jsonData;  
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rate_config'])) {
            // Check if required fields are present
            // $_POST = filter_input_array(INPUT_POST);

            $data = [
                'normal_leaf_rate' => $_POST['normal_leaf_rate'],
                'super_leaf_rate' => $_POST['super_leaf_rate'],
                'fertilizer_stock_lower' => $_POST['fertilizer_stock_lower'],
                'fertilizer_stock_mid_low' => $_POST['fertilizer_stock_mid_low'],
                'fertilizer_stock_mid_high' => $_POST['fertilizer_stock_mid_high'],
                'Leaf_age_1' => $_POST['Leaf_age_1'],
                'Leaf_age_2' => $_POST['Leaf_age_2'],
                'Leaf_age_3' => $_POST['Leaf_age_3']
            ];

            // Log the data array
            // error_log(print_r($data, true));

            // print_r("qwe");
            $this->inventoryConfigModel->add_inventory_config($data);

        }

        $totalteaweight = $this->inventoryConfigModel->get_total_tea_weightBymonth();
        $export = $this->inventoryConfigModel->get_total_tea_weightBymonth_export();
        $netincome = $this->inventoryConfigModel->get_income_by_month();

        $data2 = [];

        $exportAmountsByMonth = [];
        foreach ($export as $exp) {
            $exportAmountsByMonth[$exp->month] = $exp->total_quantity;
        }

        $netincomeByMonth = [];
        foreach ($netincome as $net) {
            $netincomeByMonth[$net->month] = $net->total_income;
        }

        // Step 2: Merge data into a single array for the view
        $overviewData = [];
        foreach ($totalteaweight as $tea) {
            $month = $tea->month;
            $overviewData[] = [
                'month' => $month,
                'total_tea_weight' => $tea->total_quantity,
                'export_amount' => isset($exportAmountsByMonth[$month]) ? $exportAmountsByMonth[$month] : '0',
                'net_income' => isset($netincomeByMonth[$month]) ? $netincomeByMonth[$month] : '0' // Placeholder
            ];
        }




        $fertilizer = $this->fertilizerModel->getfertilizer();
        $data = [
            'overviewData' => $overviewData,
            'fertilizer' => $fertilizer,

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
        $inactiveBags1 = $this->stockvalidate->getBagsByStatus('inactive');
        $inactiveBags = $this->stockvalidate->getInactiveBags();

        // Calculate statistics
        $totalBags = count($activeBags) + count($inactiveBags1);
        $activeBagsCount = count($activeBags);
        $inactiveBagsCount = count($inactiveBags1);

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
        // var_dump($data);
    }


    public function markAsActive($id = null)
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

        if ($bag->status !== 'inactive') {
            setFlashMessage('Bag is already active!', 'error');
            redirect('inventory/collectionBags');
        }

        if ($this->stockvalidate->markAsActive($id)) {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Bag with ID {$id} has been marked as active.",
                $_SERVER['REQUEST_URI'],
                http_response_code()
            );
            setFlashMessage('Bag has been emptied successfully!');
        } else {
            $this->logModel->create(
                $_SESSION['user_id'],
                $_SESSION['email'],
                $_SERVER['REMOTE_ADDR'],
                "Failed to mark bag with ID {$id} as active.",
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
            // $_POST = filter_input_array(INPUT_POST);

            $data = [
                'capacity_kg' => trim($_POST['capacity_kg']),
                'status' => 'active'
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
        $lastid = $this->stockvalidate->getLastBagId();
        $newid = $lastid->bag_id + 1;
        $data = [
            'next_bag_id' => $newid,
        ];

        $this->view('inventory/v_create_bag', $data);
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
            // $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

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


    public function viewFertilizerRequests()
    {
        $fertilizerRequest = $this->fertilizerOrderModel->getfertilizerorderforInventory();
        $data = [
            'fertilizerRequest' => $fertilizerRequest
        ];
        // echo "<pre>";
        // print_r($fertilizerRequest);
        // echo "</pre>";

        $this->view('inventory/v_fertilizer_request', $data);
    }

    public function markRequestAsPaid($orderId)
    {
        if (!$orderId) {
            setFlashMessage('No order id exists', 'warning');
            redirect('inventory/viewFertilizerRequests');
        }

        $status = 'Paid';
        $this->fertilizerOrderModel->updatePaymentStatus($orderId, $status);
        redirect('inventory/viewFertilizerRequests');
    }

    public function markRequestAsFailed($orderId)
    {
        if (!$orderId) {
            setFlashMessage('No order id exists', 'warning');
            redirect('inventory/viewFertilizerRequests');
        }

        $status = 'Failed';
        $this->fertilizerOrderModel->updatePaymentStatus($orderId, $status);
        redirect('inventory/viewFertilizerRequests');
    }

    public function viewBagUsageHistory()
    {
        $bagHistory = $this->stockvalidate->getBagUsageHistory();
        $data = [
            'bagHistory' => $bagHistory
        ];

        $this->view('inventory/v_bag_usage_history', $data);
    }

}
