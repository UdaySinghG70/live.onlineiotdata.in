<?php
require('vendor/autoload.php');

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// MQTT Configuration
const MQTT_HOST = '103.212.120.23';
const MQTT_PORT = 1883;
const MQTT_USERNAME = 'admin';
const MQTT_PASSWORD = 'BeagleBone99';

try {
    // Create MQTT client instance
    $mqtt = new MqttClient(MQTT_HOST, MQTT_PORT, 'test_client_' . rand(1, 10000));
    
    // Set connection settings
    $connectionSettings = (new ConnectionSettings)
        ->setUsername(MQTT_USERNAME)
        ->setPassword(MQTT_PASSWORD);
    
    // Connect to the broker
    echo "Connecting to MQTT broker...\n";
    $mqtt->connect($connectionSettings);
    echo "Connected successfully\n";
    
    // Publish test message
    $topic = 'server/logs/test';
    $message = 'Test message at ' . date('Y-m-d H:i:s');
    echo "Publishing to $topic: $message\n";
    $mqtt->publish($topic, $message, 0);
    
    // Disconnect
    $mqtt->disconnect();
    echo "Test completed successfully\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 