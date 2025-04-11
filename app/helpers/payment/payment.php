// app/helpers/payment/PaymentGatewayInterface.php
interface PaymentGatewayInterface {
    public function processPayment(float $amount, array $orderData);
    public function verifyPayment(string $transactionId);
}