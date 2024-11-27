<?php
class Core {
    // URL format --> /controller/method/params
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $url = $this->getURL();

        // Look in controllers for first value
        if (isset($url[0])) {
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')) {
                // If exists, set as controller
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            } else {
                // Controller doesn't exist - show 404
                $this->show404();
                return;
            }
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller class
        $this->currentController = new $this->currentController;

        // Check for second part of URL
        if (isset($url[1])) {
            // Check to see if method exists in controller
            if (method_exists($this->currentController, $url[1])) {
                $this->currentMethod = $url[1];
                unset($url[1]);
            } else {
                // Method doesn't exist - show 404
                $this->show404();
                return;
            }
        }

        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        try {
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        } catch (Exception $e) {
            // Any other errors - show 404
            $this->show404();
        }
    }

    protected function show404() {
        // Store the current URL before redirecting
        if (isset($_SERVER['HTTP_REFERER'])) {
            $_SESSION['previous_url'] = $_SERVER['HTTP_REFERER'];
        } else {
            $_SESSION['previous_url'] = URLROOT; // Default to home if no referrer
        }

        http_response_code(404);
        require_once '../app/views/pages/404.php';
        exit();
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