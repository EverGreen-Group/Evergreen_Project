<?php

// Assuming BASE_PATH is defined somewhere in your project
define('BASE_PATH', __DIR__ . '/../');

class HomeController {
    public function index() {
        // Load the model if needed
        // require_once 'models/YourModel.php';

        // Fetch data for the dashboard
        // $data = YourModel::getData();

        // Placeholder for data, replace with actual data fetching logic
        $data = [
            'totalOrders' => 150,
            'pendingOrders' => 30,
            'completedOrders' => 120,
            'notifications' => [
                'Driver sent you a collection late message',
                'Reminder of leaf collection today at 10:00pm',
            ],
        ];

        // Include the view file
        include BASE_PATH . 'views/pages/SupplyDashboard.php';
    }
}
