<?php
require('vendor/autoload.php');
require('model/querymanager.php');

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use WebSocket\Client;

// Custom logging function with timestamp
function logWithTimestamp($message) {
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] $message");
}

// MQTT Configuration
const MQTT_HOST = '103.212.120.23';
const MQTT_PORT = 1883;
const MQTT_USERNAME = 'admin';
const MQTT_PASSWORD = 'BeagleBone99';

// Generate a random client ID
$clientId = 'php_mqtt_client_' . rand(1, 10000);

// Add at the top of the file after the require statements
$processedMessages = [];

try {
    logWithTimestamp("Starting MQTT client...");
    logWithTimestamp("PHP version: " . PHP_VERSION);
    logWithTimestamp("Current working directory: " . getcwd());
    logWithTimestamp("MQTT Configuration - Host: " . MQTT_HOST . ", Port: " . MQTT_PORT);
    
    // Create MQTT client instance
    $mqtt = new MqttClient(MQTT_HOST, MQTT_PORT, $clientId);
    logWithTimestamp("MQTT client instance created with client ID: " . $clientId);
    
    // Set connection settings
    $connectionSettings = (new ConnectionSettings)
        ->setUsername(MQTT_USERNAME)
        ->setPassword(MQTT_PASSWORD)
        ->setKeepAliveInterval(60)
        ->setLastWillTopic('client/disconnect')
        ->setLastWillMessage('Client disconnected')
        ->setLastWillQualityOfService(1);
    
    logWithTimestamp("Connection settings configured");
    
    // Connect to the broker
    logWithTimestamp("Connecting to MQTT broker at " . MQTT_HOST . ":" . MQTT_PORT);
    $mqtt->connect($connectionSettings);
    logWithTimestamp("Connected to MQTT broker successfully");

    // Message processing callback
    $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) {
        global $lastPing;
        if ($elapsedTime - $lastPing >= 30) {
            $mqtt->ping();
            $lastPing = $elapsedTime;
            logWithTimestamp("MQTT ping sent");
        }
    });

    // Subscribe to topics
    logWithTimestamp("Subscribing to MQTT topics");
    $mqtt->subscribe('+/live', function ($topic, $message) {
        global $processedMessages;
        $messageKey = md5($message);
        
        // Check if message was already processed
        if (isset($processedMessages[$messageKey])) {
            logWithTimestamp("Skipping duplicate live data message");
            return;
        }
        
        logWithTimestamp("Received live data on topic: " . $topic);
        processLiveData($topic, $message);
        
        // Store message hash with timestamp
        $processedMessages[$messageKey] = time();
        
        // Clean up old message hashes (older than 5 minutes)
        $cutoff = time() - 300;
        foreach ($processedMessages as $key => $timestamp) {
            if ($timestamp < $cutoff) {
                unset($processedMessages[$key]);
            }
        }
    }, 0);

    $mqtt->subscribe('+/data', function ($topic, $message) {
        global $processedMessages;
        $messageKey = md5($message);
        
        // Check if message was already processed
        if (isset($processedMessages[$messageKey])) {
            logWithTimestamp("Skipping duplicate logged data message");
            return;
        }
        
        logWithTimestamp("Received logged data on topic: " . $topic);
        processLoggedData($topic, $message);
        
        // Store message hash with timestamp
        $processedMessages[$messageKey] = time();
        
        // Clean up old message hashes (older than 5 minutes)
        $cutoff = time() - 300;
        foreach ($processedMessages as $key => $timestamp) {
            if ($timestamp < $cutoff) {
                unset($processedMessages[$key]);
            }
        }
    }, 0);

    logWithTimestamp("Starting MQTT event loop");
    // Start the event loop
    $mqtt->loop(true);

} catch (Exception $e) {
    logWithTimestamp("MQTT Error: " . $e->getMessage());
    logWithTimestamp("Stack trace: " . $e->getTraceAsString());
    throw $e;
}

/**
 * Process live data messages
 * Format: value1,value2,value3,...,valuen,device_id
 */
function processLiveData($topic, $message) {
    try {
        logWithTimestamp("Processing live data message: " . $message);
        $values = explode(',', $message);
        $device_id = array_pop($values); // Get and remove device_id from the end

        // Create WebSocket client
        $client = new Client("wss://live.onlineiotdata.in:8081");
        logWithTimestamp("WebSocket client created");
        
        // Send data to WebSocket server
        $data = [
            'device_id' => $device_id,
            'values' => $values
        ];
        
        $client->send(json_encode($data));
        logWithTimestamp("Data sent to WebSocket server");
        $client->close();
        
        // Query modem_params table
        $query = "SELECT param_name, position, unit FROM modem_params WHERE device_id = '$device_id' ORDER BY position";
        $result = QueryManager::getMultipleRow($query);
        logWithTimestamp("Modem params queried for device: " . $device_id);
    } catch (Exception $e) {
        logWithTimestamp("Error processing live data: " . $e->getMessage());
        logWithTimestamp("Stack trace: " . $e->getTraceAsString());
    }
}

/**
 * Process logged data messages
 * Format: DDMMYYHHMM,value1,value2,...,valuen,device_id
 */
function processLoggedData($topic, $message) {
    try {
        logWithTimestamp("Processing logged data message: " . $message);
        $values = explode(',', $message);
        $device_id = array_pop($values); // Get and remove device_id from the end
        $timestamp = array_shift($values); // Get and remove timestamp from the start
        
        // Verify device_id exists in devices table
        $verifyQuery = "SELECT COUNT(*) as count FROM devices WHERE device_id = '$device_id'";
        $result = QueryManager::getOneRow($verifyQuery);
        
        // If device not found, ignore the message
        if (!$result || $result[0] == 0) {
            logWithTimestamp("Ignored MQTT message: Device ID '$device_id' not found in devices table");
            return;
        }
        
        // Parse timestamp for database date/time columns
        $date = DateTime::createFromFormat('dmyHi', $timestamp);
        if (!$date) {
            logWithTimestamp("Invalid timestamp format in MQTT message for device '$device_id'");
            return;
        }
        
        $formattedDate = $date->format('Y-m-d');
        $formattedTime = $date->format('H:i:s');

        // Check if the date falls within a recharge period
        $rechargeQuery = "SELECT COUNT(*) as count FROM recharge 
                          WHERE device_id = '$device_id' 
                          AND '$formattedDate' >= start_date 
                          AND '$formattedDate' <= end_date";
        $rechargeResult = QueryManager::getOneRow($rechargeQuery);

        // If no valid recharge period found, ignore the message
        if (!$rechargeResult || $rechargeResult[0] == 0) {
            logWithTimestamp("Ignored MQTT message: No valid recharge period found for device '$device_id' on date '$formattedDate'");
            return;
        }
        
        // Store the raw data string (original message without device_id)
        $dataString = $timestamp . ',' . implode(',', $values);
        
        // Store in logdata table
        $query = "INSERT INTO logdata (date, time, device_id, data) 
                 VALUES ('$formattedDate', '$formattedTime', '$device_id', '$dataString')";
        
        QueryManager::executeQuerySqli($query);
        logWithTimestamp("Logged data stored for device: " . $device_id);
    } catch (Exception $e) {
        logWithTimestamp("Error processing logged data: " . $e->getMessage());
        logWithTimestamp("Stack trace: " . $e->getTraceAsString());
    }
}