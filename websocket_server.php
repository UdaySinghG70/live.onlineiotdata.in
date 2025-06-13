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
    }

    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        // Broadcast message to all connected clients
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function onClose(\Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }

    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }

    public function broadcast($message) {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }
}

// Create event loop
$loop = Factory::create();

// Create WebSocket server with SSL
$webSocket = new Server('0.0.0.0:8080', $loop);

// Create secure WebSocket server
$secureWebSocket = new SecureServer($webSocket, $loop, [
    'local_cert' => '/etc/letsencrypt/live/live.onlineiotdata.in/fullchain.pem',
    'local_pk' => '/etc/letsencrypt/live/live.onlineiotdata.in/privkey.pem',
    'verify_peer' => false
]);

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

$server->run(); 