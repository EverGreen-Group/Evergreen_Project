<?php

//databasse configarations
// define('DB_HOST', '34.93.234.68');
// define('DB_HOST', 'mysql-tfms.alwaysdata.net');
// define('DB_USER', 'tfms');
// define('DB_PASSWORD', 'tfmsgroupproject');
// define('DB_NAME','tfms_tfms');
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME','tfms');
//APPROOT
// //APPROOT
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

// Add this with your other configurations
date_default_timezone_set('Asia/Colombo');
?>
