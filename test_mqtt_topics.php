<?php
require('vendor/autoload.php');

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// MQTT Configuration
const MQTT_HOST = '103.212.120.23';
const MQTT_PORT = 1883;
const MQTT_USERNAME = 'admin';
const MQTT_PASSWORD = 'BeagleBone99';

function logMessage($message) {
    $timestamp = date('Y-m-d H:i:s');
    echo "[$timestamp] $message\n";
}

try {
    $clientId = 'test_mqtt_client_' . rand(1, 10000);
    $mqtt = new MqttClient(MQTT_HOST, MQTT_PORT, $clientId);
    
    $connectionSettings = (new ConnectionSettings)
        ->setUsername(MQTT_USERNAME)
        ->setPassword(MQTT_PASSWORD)
        ->setKeepAliveInterval(60);
    
    $mqtt->connect($connectionSettings);
    logMessage("Connected to MQTT broker");
    
    // Subscribe to specific topics that ESP32 might be using
    $topics = [
        'MLIVE' => 'MLIVE',
        'MDATA' => 'MDATA',
        '+/live' => 'Wildcard live',
        '+/data' => 'Wildcard data',
        '#' => 'All topics'
    ];
    
    foreach ($topics as $topic => $description) {
        $mqtt->subscribe($topic, function ($topic, $message) use ($description) {
            logMessage("[$description] Topic: $topic, Message: $message");
        }, 0);
        logMessage("Subscribed to: $topic ($description)");
    }
    
    logMessage("Listening for messages... (Press Ctrl+C to stop)");
    
    // Listen for messages
    $mqtt->loop(true);
    
} catch (Exception $e) {
    logMessage("Error: " . $e->getMessage());
}
?> 