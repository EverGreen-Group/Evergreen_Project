<?php

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Simaak\EvergreenProject\WebSocket\ChatServer;

// Define the root path of your application
define('ROOTPATH', dirname(__DIR__));

// Include Composer's autoloader (in case other dependencies are needed)
require ROOTPATH . '/vendor/autoload.php';

// Include the configuration file to define DB constants
require ROOTPATH . '/app/config/config.php';

// Manually include M_Chat and Database files
require ROOTPATH . '/app/models/M_Chat.php';
require ROOTPATH . '/app/libraries/Database.php';

// Initialize the WebSocket server
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080\n";
$server->run();