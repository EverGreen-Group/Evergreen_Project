<?php

require_once '../app/models/M_Product.php';
require_once '../app/models/M_Order.php';
require_once '../app/models/M_Tracking.php';
require_once '../app/models/M_Cart.php';
require_once '../app/models/M_Category.php';
require_once '../app/models/M_Review.php';
require_once '../app/helpers/auth_middleware.php';
require_once '../app/helpers/utility_helper.php';

class ShopDelivery extends Controller {
    private $orderModel;
    private $trackingModel;

    public function __construct() {
        $this->orderModel = new M_Order();
        $this->trackingModel = new M_Tracking();
    }

    // public function index() {
    //     $this->load->model('delivery_model');
    //     $data['deliverylist'] = $this->delivery_model->getall_deliveries();
    //     $this->template->template_render('shopdelivery/tracking', $data);
    // }

    public function index() {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        // Enable error reporting for debugging
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        try {
            // Get all deliveries for the logged-in user
            $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
            
            // Get tracking information for each order
            $deliveries = [];
            foreach ($orders as $order) {
                $tracking = $this->trackingModel->getTrackingByOrderId($order->id);
                if ($tracking) {
                    $deliveries[] = [
                        'order' => $order,
                        'tracking' => $tracking
                    ];
                }
            }

            $data = [
                'title' => 'Delivery Tracking',
                'deliveries' => $deliveries
            ];

            //Debug output
            // echo '<pre>';
            // print_r($data);
            // echo '</pre>';

            $this->view('shopdelivery/tracking', $data);
        } catch (Exception $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }

    public function track($trackingCode = null) {
        if (!$trackingCode) {
            redirect('shopdelivery');
        }

        $tracking = $this->trackingModel->getTrackingByCode($trackingCode);
        $order = $this->orderModel->getOrderDetails($tracking->order_id);

        $data = [
            'tracking' => $tracking,
            'order' => $order
        ];

        $this->view('shopdelivery/index', $data);
    }

    public function updateTracking() {
        if (!isLoggedIn() || !isAdmin()) {
            jsonResponse(['success' => false, 'message' => 'Unauthorized']);
        }

        $data = json_decode(file_get_contents('php://input'));
        
        if ($this->trackingModel->updateTracking($data)) {
            jsonResponse(['success' => true]);
        } else {
            jsonResponse(['success' => false, 'message' => 'Update failed']);
        }
    }
} 