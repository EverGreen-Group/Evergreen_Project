<?php
// Start session
session_start();

//load configurations
require_once 'config/config.php';

//load helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/flash_helper.php';
require_once 'helpers/RoleHelper.php';
require_once 'helpers/PhotoHelper.php';
//require_once __DIR__ . '/../vendor/autoload.php';

//load libraries
require_once 'libraries/Core.php';
require_once 'libraries/Controller.php';
require_once 'libraries/Database.php';