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
}
