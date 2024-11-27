<?php

require_once '../app/helpers/auth_middleware.php';
class PaymentController extends Controller {
    private $stripeSecretKey;
    private $stripePublicKey;

    $

    public function __construct() {
        parent::__construct();
        $this->stripeSecretKey = STRIPE_SECRET_KEY;
        $this->stripePublicKey = STRIPE_PUBLIC_KEY;
        
        require_once APPROOT . '/vendor/autoload.php';
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);
    }

    public function index() {
        $this->checkout();
    }

    public function checkout() {
        $data = [
            'title' => 'Checkout',
            'stripe_public_key' => $this->stripePublicKey
        ];
        
        $this->view('payment/checkout', $data);
    }

    public function processPayment() {
        try {
            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str);

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $json_obj->amount * 100, // Convert to cents
                'currency' => 'usd',
                'payment_method_types' => ['card'],
                'metadata' => [
                    'order_id' => $json_obj->order_id
                ]
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function webhook() {
        $endpoint_secret = STRIPE_WEBHOOK_SECRET;
        
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
        } catch(\UnexpectedValueException $e) {
            http_response_code(400);
            exit();
        } catch(\Stripe\Exception\SignatureVerificationException $e) {
            http_response_code(400);
            exit();
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                // Handle successful payment
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                // Handle failed payment
                break;
        }

        http_response_code(200);
    }
} 