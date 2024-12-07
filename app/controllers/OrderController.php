<?php

require_once '../app/models/M_Order.php';
require_once '../app/helpers/auth_middleware.php';

class OrderController extends Controller {
    private $orderModel;

    public function __construct() {
        $this->orderModel = $this->model('M_Order');
    }

    public function index() {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        $orders = $this->orderModel->getUserOrders($_SESSION['user_id']);
        
        $data = [
            'title' => 'My Orders',
            'orders' => $orders
        ];

        $this->view('orders/index', $data);
    }

    public function checkout($orderId = null) {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        if ($orderId === null) {
            redirect('orders');
        }

        $order = $this->orderModel->getOrderDetails($orderId);
        
        if (!$order || $order->user_id !== $_SESSION['user_id']) {
            redirect('orders');
        }

        $data = [
            'title' => 'Checkout',
            'order' => $order,
            'stripe_public_key' => STRIPE_PUBLIC_KEY
        ];

        $this->view('orders/checkout', $data);
    }

    public function processPayment() {
        if (!isLoggedIn()) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if (!$data || !isset($data->order_id) || !isset($data->amount)) {
                throw new Exception('Invalid request data');
            }

            $order = $this->orderModel->getOrderDetails($data->order_id);
            if (!$order || $order->user_id !== $_SESSION['user_id']) {
                throw new Exception('Invalid order');
            }

            $paymentIntent = $this->orderModel->createPaymentIntent(
                $data->order_id,
                $data->amount
            );

            if ($paymentIntent) {
                echo json_encode([
                    'clientSecret' => $paymentIntent['clientSecret']
                ]);
            } else {
                throw new Exception('Payment processing failed');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function webhook() {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        if ($this->orderModel->handleStripeWebhook($payload, $sigHeader)) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }

    public function confirmation($orderId) {
        if (!isLoggedIn()) {
            redirect('auth/login');
        }

        $order = $this->orderModel->getOrderDetails($orderId);
        
        if (!$order || $order->user_id !== $_SESSION['user_id']) {
            redirect('orders');
        }

        $data = [
            'title' => 'Order Confirmation',
            'order' => $order
        ];

        $this->view('orders/confirmation', $data);
    }
} 