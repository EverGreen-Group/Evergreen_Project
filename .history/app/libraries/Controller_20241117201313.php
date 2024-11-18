<?php
class Controller {

    // Method to load and instantiate a model
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        
        // Instantiate the model and return it
        return new $model();
    }

    // Method to load and render a view
    public function view($view, $data = []) {
        if (file_exists('../app/views/' . $view . '.php')) {
            // Make data array elements available as variables in the view
            extract($data);

            // Include the view file
            require_once '../app/views/' . $view . '.php';
        } else {
            die('The view does not exist.');
        }
    }

    protected function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    protected function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . URLROOT . '/employeemanager');
            exit();
        }
    }

    protected function preventLoginAccess() {
        if ($this->isLoggedIn()) {
            header('Location: ' . URLROOT . '/');
            exit();
        }
    }
}
