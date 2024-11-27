<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// session test
//session_start(); I added one in bootloader.php

// Load the bootloader and core application files
require_once '../app/bootloader.php';

// Initialize the Core class
$init = new Core;
