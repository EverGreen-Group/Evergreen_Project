<?php
require dirname(__DIR__) . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\WebSocket\CollectionServer;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new CollectionServer()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080\n";
$server->run(); 