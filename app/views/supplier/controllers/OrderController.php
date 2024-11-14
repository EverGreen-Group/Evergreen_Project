<?php

class OrderController {
    public function order() {
        // Load the orders view
        $title = 'Orders';
        $content = BASE_PATH . 'views/pages/OrderPage.php';
        include BASE_PATH . 'views/layouts/main.php';
    }

    public function create() {
        // Logic to create a new order
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product = $_POST['product'];
            $quantity = $_POST['quantity'];

            if ($product && $quantity) {
                $orderModel = new Order();
                $orderModel->create($product, $quantity);
                header('Location: /order/success');
            } else {
                echo "Please fill in all required fields.";
            }
        }
    }
}
