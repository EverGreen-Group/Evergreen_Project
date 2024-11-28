<?php

//databasse configarations
// define('DB_HOST', '34.93.234.68');
// define('DB_HOST', 'localhost');
// define('DB_USER', 'root');
// define('DB_PASSWORD', '');
// define('DB_NAME','tfms');

// Database configurations
define('DB_HOST', 'mysql-tfms.alwaysdata.net');
define('DB_USER', 'tfms');
define('DB_PASSWORD', 'tfmsgroupproject');
define('DB_NAME','tfms_tfms');

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

// Add this with your other configurations
date_default_timezone_set('Asia/Colombo');

// Add Stripe configuration
define('STRIPE_PUBLIC_KEY', 'pk_test_51PtvxYB06Hyjgz1TVSQNz1EwGC9MKH9UypKICcwp7zhqMO1Bw0Rtn8MMWUvtVCQcGzvjhwa4oM8biZRinioTwbtI00hKWmT0Ai');
define('STRIPE_SECRET_KEY', 'sk_test_51PtvxYB06Hyjgz1TLkJ3b97f9jQ3iGjhmNqR1YJNR8GSCBFT2BNozxAtr1chzAUnlO7Mrxbp1z9epTulM1eM1twg00wqE6Nhdu');

?>