<?php
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class LiveDataServer implements \Ratchet\MessageComponentInterface {
    protected $clients;
    
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        error_log("WebSocket server initialized");
    }

    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
        error_log("New connection established: " . $conn->remoteAddress);
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        error_log("Received message: " . $msg);
        // Broadcast message to all connected clients
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function onClose(\Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
        error_log("Connection closed: " . $conn->remoteAddress);
    }

    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
        error_log("Error occurred: " . $e->getMessage());
        $conn->close();
    }

    public function broadcast($message) {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }
}

try {
    error_log("Starting WebSocket server...");
    
    // Create the WebSocket application
    $app = new HttpServer(
        new WsServer(
            new LiveDataServer()
        )
    );

    // Create the server
    $server = IoServer::factory($app, 8081);
    error_log("WebSocket server created and running on port 8081");

    $server->run();
} catch (\Exception $e) {
    error_log("Fatal error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    throw $e;
} 