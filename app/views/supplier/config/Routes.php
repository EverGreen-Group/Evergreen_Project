<?php
// Include necessary files
require_once 'config/db.php'; // Include your database connection
require_once 'controllers/HomeController.php';
require_once 'controllers/ComplaintController.php';
require_once 'controllers/FertilizerController.php';
require_once 'controllers/OrderController.php';
require_once 'controllers/PaymentsController.php';
require_once 'controllers/ProfileController.php';
require_once 'controllers/NotificationsController.php';

function handleRequest() {
    // Get the current URL path
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $url = rtrim($url, '/'); // Remove trailing slash
    $url = filter_var($url, FILTER_SANITIZE_URL); // Sanitize the URL

    // Define routes
    $routes = [
        '/' => [HomeController::class, 'index'],
        '/complaint' => [ComplaintController::class, 'showForm'],
        '/submit-complaint' => [ComplaintController::class, 'submit'],
        '/fertilizer' => [FertilizerController::class, 'index'],
        '/order' => [OrderController::class, 'index'],
        '/payments' => [PaymentsController::class, 'index'],
        '/profile' => [ProfileController::class, 'index'],
        '/notifications' => [NotificationsController::class, 'index'],
    ];

    // Check if the route exists
    if (array_key_exists($url, $routes)) {
        [$controller, $method] = $routes[$url];
        $controllerInstance = new $controller();
        $controllerInstance->$method();
    } else {
        // Handle 404 - Not Found
        http_response_code(404);
        echo "404 - Page Not Found";
    }
}

// Call the request handler
handleRequest();
