<?php
// Define the base path for includes
define('BASE_PATH', __DIR__ . DIRECTORY_SEPARATOR);

// Include the controller files
require_once BASE_PATH . 'models/Database.php';
require_once BASE_PATH . 'controllers/HomeController.php';
require_once BASE_PATH . 'controllers/LeaveController.php';
require_once BASE_PATH . 'controllers/TaskController.php';
require_once BASE_PATH . 'controllers/EvaluationController.php';
require_once BASE_PATH . 'controllers/SalaryController.php';
require_once BASE_PATH . 'controllers/AttendanceController.php';
require_once BASE_PATH . 'controllers/ProfileController.php';

// Load routes
$routes = require BASE_PATH . 'config/routes.php';

// Simple router
$request = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Remove query string from request
$request = strtok($request, '?');

// Route the request
$route = $routes[$request] ?? null;

if ($route) {
    $controllerName = $route[0];
    $methodName = $route[1];
    $allowedMethod = $route[2] ?? 'GET';

    if ($method !== $allowedMethod) {
        header("HTTP/1.0 405 Method Not Allowed");
        echo "Method Not Allowed";
        exit;
    }

    $controller = new $controllerName();
    $controller->$methodName();
} else {
    // Fallback to the old routing system
    $page = $_GET['page'] ?? 'dashboard';

    // Route the request to the appropriate controller
    switch ($page) {
        case 'dashboard':
            $controller = new HomeController();
            $controller->index();
            break;
        case 'apply-leave':
            $controller = new LeaveController();
            $controller->apply();
            break;
        case 'view-tasks':
            $controller = new TaskController();
            $controller->view();
            break;
        case 'evaluation':
            $controller = new EvaluationController();
            $controller->complete();
            break;
        case 'salary-slip':
            $controller = new SalaryController();
            $controller->salary();
            break;
        case 'view-attendance':
            $controller = new AttendanceController();
            $controller->viewAttendance();
            break;
        case 'personal-detail':
            $controller = new ProfileController();
            $controller->viewDetails();
            break;
        case 'logout':
            // Handle logout logic here
            session_destroy();
            header('Location: login.php');
            exit;
        default:
            // Handle 404 Not Found
            header("HTTP/1.0 404 Not Found");
            echo "Page not found";
            break;
    }
}