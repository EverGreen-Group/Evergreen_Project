<?php

class PaymentsController {
    public function payment() {
        // Load the payments view
        $title = 'Payments';
        $content = BASE_PATH . 'views/pages/Payments.php';
        include BASE_PATH . 'views/layouts/main.php';
    }
}
