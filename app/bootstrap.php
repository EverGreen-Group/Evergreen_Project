<?php
// Load Config
require_once 'config/config.php';

// Load Helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';
require_once 'helpers/flash_helper.php';
require_once 'helpers/RoleHelper.php';
require_once __DIR__ . '/../vendor/autoload.php';

// Autoload Core Libraries
spl_autoload_register(function($className) {
    require_once 'libraries/' . $className . '.php';
}); 