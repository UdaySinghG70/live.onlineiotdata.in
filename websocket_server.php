<?php
require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;

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
    
    // Create event loop
    $loop = Factory::create();
    error_log("Event loop created");

    // Create WebSocket server with SSL
    $webSocket = new Server('0.0.0.0:8081', $loop);
    error_log("WebSocket server created on 0.0.0.0:8081");

    // Create secure WebSocket server
    $secureWebSocket = new SecureServer($webSocket, $loop, [
        'local_cert' => '/etc/letsencrypt/live/live.onlineiotdata.in/fullchain.pem',
        'local_pk' => '/etc/letsencrypt/live/live.onlineiotdata.in/privkey.pem',
        'verify_peer' => false
    ]);
    error_log("Secure WebSocket server created");

    // Create Ratchet server
    $server = IoServer::factory(
        new HttpServer(
            new WsServer(
                new LiveDataServer()
            )
        ),
        $secureWebSocket,
        $loop
    );
    error_log("Ratchet server created");

    $server->run();
} catch (\Exception $e) {
    error_log("Fatal error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
} 