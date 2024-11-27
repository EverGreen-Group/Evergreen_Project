<?php

require_once '../app/models/M_Order.php';
require_once '../app/helpers/auth_middleware.php';

class OrderController {
    public function checkout($orderId) {
        $orderModel = $this->model('M_Order');
        $order = $orderModel->getOrderDetails($orderId);
        
        if (!$order) {
            redirect('orders');
        }

        $data = [
            'order' => $order,
            'stripe_public_key' => STRIPE_PUBLIC_KEY
        ];

        $this->view('orders/checkout', $data);
    }

    public function processPayment() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('orders');
        }

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $orderModel = $this->model('M_Order');
        $paymentIntent = $orderModel->createPaymentIntent(
            $data->order_id,
            $data->amount
        );

        if ($paymentIntent) {
            echo json_encode([
                'clientSecret' => $paymentIntent['clientSecret']
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Payment processing failed']);
        }
    }

    public function webhook() {
        $payload = @file_get_contents('php://input');
        $sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'];

        $orderModel = $this->model('M_Order');
        if ($orderModel->handleStripeWebhook($payload, $sigHeader)) {
            http_response_code(200);
        } else {
            http_response_code(400);
        }
    }
} 