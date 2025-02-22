<?php
namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class CollectionServer implements MessageComponentInterface {
    protected $clients;
    protected $supplierConnections = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        print_r([
            'event' => 'connection_opened',
            'connection_id' => $conn->resourceId,
            'total_connections' => count($this->clients)
        ]);
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "\n=== New Message ===\n";
        echo "From connection: {$from->resourceId}\n";
        echo "Message content: $msg\n";
        
        $data = json_decode($msg);
        
        if ($data->type === 'register') {
            $this->supplierConnections[$data->supplierId] = $from;
            echo "Supplier {$data->supplierId} registered (Connection ID: {$from->resourceId})\n";
            echo "Current supplier connections: \n";
            print_r($this->supplierConnections);
        }
        else if ($data->type === 'refreshTrigger') {
            echo "Received refresh trigger for supplier {$data->supplierId}\n";
            
            if (isset($this->supplierConnections[$data->supplierId])) {
                $targetConnection = $this->supplierConnections[$data->supplierId];
                echo "Found supplier connection: {$targetConnection->resourceId}\n";
                
                try {
                    $messageToSend = json_encode([
                        'type' => 'refreshTrigger'
                    ]);
                    echo "Sending message: $messageToSend\n";
                    $targetConnection->send($messageToSend);
                    echo "Refresh trigger sent successfully\n";
                } catch (\Exception $e) {
                    echo "Error sending message: " . $e->getMessage() . "\n";
                }
            } else {
                echo "No connection found for supplier {$data->supplierId}\n";
                echo "Available supplier connections: \n";
                print_r(array_keys($this->supplierConnections));
            }
        }
        echo "=== End Message ===\n";
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        $disconnectedSupplierId = null;
        
        foreach ($this->supplierConnections as $supplierId => $connection) {
            if ($connection === $conn) {
                $disconnectedSupplierId = $supplierId;
                unset($this->supplierConnections[$supplierId]);
                echo "Supplier $supplierId disconnected (Connection ID: {$conn->resourceId})\n";
                break;
            }
        }
        
        echo "Connection {$conn->resourceId} has disconnected\n";
        echo "Remaining connections: \n";
        print_r([
            'total_clients' => count($this->clients),
            'supplier_connections' => array_keys($this->supplierConnections)
        ]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error occurred on connection {$conn->resourceId}: {$e->getMessage()}\n";
        $conn->close();
    }
} 