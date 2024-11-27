<?php

// Database configurations
define('DB_HOST', 'mysql-tfms.alwaysdata.net');
define('DB_USER', 'tfms');
define('DB_PASSWORD', 'tfmsgroupproject');
define('DB_NAME','tfms_tfms');

//APPROOT
define('APPROOT',dirname(dirname(__FILE__)));

//URLROOT (Dynamic links)
define('URLROOT', 'http://tfms.alwaysdata.net');  // Update this to match your domain

//Sitename
define('SITENAME', 'EverGreen');

// Upload paths
define('UPLOADROOT', dirname(dirname(__DIR__)) . '/public/uploads');
define('UPLOADURL', URLROOT . '/uploads');  // For web URLs
define('PUBLICPATH', dirname(APPROOT) . '/public');
define('UPLOADPATH', PUBLICPATH . '/uploads');  // For file operations

// Timezone setting
date_default_timezone_set('Asia/Colombo');
?>