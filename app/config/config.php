<?php

//databasse configarations
// define('DB_HOST', '34.93.234.68');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME','tfms');
//APPROOT
define('APPROOT',dirname(dirname(__FILE__)));

//URLROOT
define('URLROOT', 'http://localhost/Evergreen_Project');

//website
define('SITENAME', 'EverGreen');

// Add this with your other constants
define('UPLOADROOT', dirname(dirname(__DIR__)) . '/public/uploads');

// URL for web access
define('UPLOADURL', URLROOT . '/uploads');  // For web URLs

// Server filesystem paths
define('PUBLICPATH', dirname(APPROOT) . '/public');
define('UPLOADPATH', PUBLICPATH . '/uploads');  // For file operations
?>