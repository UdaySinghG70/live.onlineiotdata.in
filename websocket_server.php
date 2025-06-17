<?php
// Suppress deprecation notices
error_reporting(E_ALL & ~E_DEPRECATED);

require 'vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\SecureServer;
use React\Socket\Server;

// Custom logging function with timestamp
function logWithTimestamp($message) {
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message");
}

class LiveDataServer implements \Ratchet\MessageComponentInterface {
    protected $clients;
    
    public function __construct() {
        $this->clients = new \SplObjectStorage;
        logWithTimestamp("WebSocket server initialized");
    }

    public function onOpen(\Ratchet\ConnectionInterface $conn) {
        $this->clients->attach($conn);
        logWithTimestamp("New connection established: " . $conn->remoteAddress);
    }

    public function onMessage(\Ratchet\ConnectionInterface $from, $msg) {
        logWithTimestamp("Received message: " . $msg);
        // Broadcast message to all connected clients
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }

    public function onClose(\Ratchet\ConnectionInterface $conn) {
        $this->clients->detach($conn);
        logWithTimestamp("Connection closed: " . $conn->remoteAddress);
    }

    public function onError(\Ratchet\ConnectionInterface $conn, \Exception $e) {
        logWithTimestamp("Error: " . $e->getMessage());
        $conn->close();
    }

    public function broadcast($message) {
        foreach ($this->clients as $client) {
            $client->send($message);
        }
    }
}

try {
    logWithTimestamp("Starting WebSocket server...");
    logWithTimestamp("PHP version: " . PHP_VERSION);
    logWithTimestamp("Current working directory: " . getcwd());
    
    // Check SSL certificates
    $certPath = '/etc/letsencrypt/live/live.onlineiotdata.in/fullchain.pem';
    $keyPath = '/etc/letsencrypt/live/live.onlineiotdata.in/privkey.pem';
    
    logWithTimestamp("Checking SSL certificates...");
    logWithTimestamp("Certificate path: " . $certPath);
    logWithTimestamp("Key path: " . $keyPath);
    
    if (!file_exists($certPath)) {
        throw new Exception("SSL certificate not found at: " . $certPath);
    }
    if (!file_exists($keyPath)) {
        throw new Exception("SSL key not found at: " . $keyPath);
    }
    if (!is_readable($certPath)) {
        throw new Exception("SSL certificate not readable at: " . $certPath);
    }
    if (!is_readable($keyPath)) {
        throw new Exception("SSL key not readable at: " . $keyPath);
    }
    
    logWithTimestamp("SSL certificates verified");
    
    // Create event loop
    $loop = Factory::create();
    logWithTimestamp("Event loop created");

    // Create WebSocket server
    $webSocket = new Server('0.0.0.0:8081', $loop);
    logWithTimestamp("WebSocket server created on 0.0.0.0:8081");

    // Create secure WebSocket server
    $secureWebSocket = new SecureServer($webSocket, $loop, [
        'local_cert' => $certPath,
        'local_pk' => $keyPath,
        'verify_peer' => false,
        'allow_self_signed' => true
    ]);
    logWithTimestamp("Secure WebSocket server created");

    // Create the WebSocket application
    $app = new HttpServer(
        new WsServer(
            new LiveDataServer()
        )
    );

    // Create the server
    $server = new IoServer($app, $secureWebSocket, $loop);
    logWithTimestamp("Ratchet server created and running");

    $server->run();
} catch (\Exception $e) {
    logWithTimestamp("Fatal error: " . $e->getMessage());
    logWithTimestamp("Stack trace: " . $e->getTraceAsString());
    throw $e;
} 