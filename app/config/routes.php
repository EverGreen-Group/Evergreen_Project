<?php

// Payment routes
$router->get('/payment/checkout', 'PaymentController@checkout');
$router->post('/payment/process', 'PaymentController@processPayment');
$router->post('/payment/webhook', 'PaymentController@webhook'); 