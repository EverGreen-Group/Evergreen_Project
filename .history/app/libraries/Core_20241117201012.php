<?php
class Core {
    // URL format --> /controller/method/params
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getURL();

        // Construct the path to the controller file
        $controllerFile = '../app/controllers/' . ucwords($url[0]) . '.php';

        if (file_exists($controllerFile)) {
            $this->currentController = ucwords($url[0]);

            // Unset the controller in the URL
            unset($url[0]);

            // Include the controller file
            require_once $controllerFile;

            // Instantiate the controller
            $this->currentController = new $this->currentController;

            // Check if the method exists in the controller
            if (isset($url[1])) {
                if (method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            }

            // Set parameters
            $this->params = $url ? array_values($url) : [];

            // Call the method and pass parameters
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } else {
            die('Controller file does not exist: ' . $controllerFile);
        }
    }

    public function getURL() {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
?>