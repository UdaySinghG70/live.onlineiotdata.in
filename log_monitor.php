<?php
require('vendor/autoload.php');

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

// MQTT Configuration
const MQTT_HOST = '103.212.120.23';
const MQTT_PORT = 1883;
const MQTT_USERNAME = 'admin';
const MQTT_PASSWORD = 'BeagleBone99';
const LOG_TOPIC_PREFIX = 'server/logs/';

// Generate a random client ID
$clientId = 'log_monitor_' . rand(1, 10000);

// Files to monitor
$logFiles = [
    'mqtt.log' => 'mqtt',
    'watchdog.log' => 'watchdog',
    'websocket.log' => 'websocket'
];

// Store last positions
$lastPositions = [];

function logDebug($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] DEBUG: $message\n";
    error_log($logMessage, 3, 'logs/log_monitor.log');
}

function initializeLastPositions() {
    global $logFiles, $lastPositions;
    foreach ($logFiles as $file => $topic) {
        $path = "logs/$file";
        if (file_exists($path)) {
            $lastPositions[$file] = filesize($path);
            logDebug("Initialized $file position to " . $lastPositions[$file]);
        } else {
            $lastPositions[$file] = 0;
            logDebug("File $file does not exist, position set to 0");
        }
    }
}

function publishLogUpdate($mqtt, $topic, $content) {
    try {
        $mqtt->publish(LOG_TOPIC_PREFIX . $topic, $content, 0);
        logDebug("Published update to $topic: " . substr($content, 0, 50) . "...");
    } catch (Exception $e) {
        logDebug("Failed to publish log update: " . $e->getMessage());
    }
}

try {
    logDebug("Starting log monitor with client ID: $clientId");
    
    // Create MQTT client instance
    $mqtt = new MqttClient(MQTT_HOST, MQTT_PORT, $clientId);
    
    // Set connection settings
    $connectionSettings = (new ConnectionSettings)
        ->setUsername(MQTT_USERNAME)
        ->setPassword(MQTT_PASSWORD)
        ->setKeepAliveInterval(60)
        ->setLastWillTopic(LOG_TOPIC_PREFIX . 'status')
        ->setLastWillMessage('Log monitor disconnected')
        ->setLastWillQualityOfService(1);
    
    // Connect to the broker
    logDebug("Connecting to MQTT broker...");
    $mqtt->connect($connectionSettings);
    logDebug("Connected to MQTT broker");
    
    // Initialize last positions
    initializeLastPositions();
    
    // Publish initial status
    $mqtt->publish(LOG_TOPIC_PREFIX . 'status', 'Log monitor connected', 1);
    logDebug("Published initial status message");
    
    // Send a test message to each topic
    foreach ($logFiles as $file => $topic) {
        $testMessage = "Test message from log monitor for $topic at " . date('Y-m-d H:i:s');
        publishLogUpdate($mqtt, $topic, $testMessage);
    }
    
    echo "Log monitor started. Publishing to topics:\n";
    foreach ($logFiles as $file => $topic) {
        echo "- " . LOG_TOPIC_PREFIX . $topic . "\n";
    }
    
    // Main monitoring loop
    $loopCount = 0;
    while (true) {
        $loopCount++;
        if ($loopCount % 100 == 0) { // Log every 100 iterations
            logDebug("Monitoring loop iteration $loopCount");
        }
        
        foreach ($logFiles as $file => $topic) {
            $path = "logs/$file";
            if (file_exists($path)) {
                $currentSize = filesize($path);
                
                // Check if file has new content
                if ($currentSize > $lastPositions[$file]) {
                    logDebug("New content detected in $file");
                    
                    // Open file and seek to last position
                    $handle = fopen($path, 'r');
                    fseek($handle, $lastPositions[$file]);
                    
                    // Read new content
                    $newContent = fread($handle, $currentSize - $lastPositions[$file]);
                    fclose($handle);
                    
                    // Update last position
                    $lastPositions[$file] = $currentSize;
                    
                    // Publish new content
                    if (!empty(trim($newContent))) {
                        publishLogUpdate($mqtt, $topic, $newContent);
                    }
                }
            }
        }
        
        // Sleep briefly to prevent high CPU usage
        usleep(100000); // 100ms
    }
    
} catch (Exception $e) {
    logDebug("Fatal error in log monitor: " . $e->getMessage());
    throw $e; // Re-throw to ensure the process exits
} 