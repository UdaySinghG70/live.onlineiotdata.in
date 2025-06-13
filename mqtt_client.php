<?php
require('vendor/autoload.php');
require('model/querymanager.php');

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use WebSocket\Client;

// MQTT Configuration
const MQTT_HOST = '103.212.120.23';
const MQTT_PORT = 1883;
const MQTT_USERNAME = 'admin';
const MQTT_PASSWORD = 'BeagleBone99';

// Generate a random client ID
$clientId = 'php_mqtt_client_' . rand(1, 10000);

try {
    error_log("Starting MQTT client...");
    
    // Create MQTT client instance
    $mqtt = new MqttClient(MQTT_HOST, MQTT_PORT, $clientId);
    error_log("MQTT client instance created");
    
    // Set connection settings
    $connectionSettings = (new ConnectionSettings)
        ->setUsername(MQTT_USERNAME)
        ->setPassword(MQTT_PASSWORD)
        ->setKeepAliveInterval(60)
        ->setLastWillTopic('client/disconnect')
        ->setLastWillMessage('Client disconnected')
        ->setLastWillQualityOfService(1);
    
    // Connect to the broker
    error_log("Connecting to MQTT broker at " . MQTT_HOST . ":" . MQTT_PORT);
    $mqtt->connect($connectionSettings);
    error_log("Connected to MQTT broker successfully");

    // Message processing callback
    $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) {
        global $lastPing;
        if ($elapsedTime - $lastPing >= 30) {
            $mqtt->ping();
            $lastPing = $elapsedTime;
            error_log("MQTT ping sent");
        }
    });

    // Subscribe to topics
    error_log("Subscribing to MQTT topics");
    $mqtt->subscribe('+/live', function ($topic, $message) {
        error_log("Received live data on topic: " . $topic);
        processLiveData($topic, $message);
    }, 0);

    $mqtt->subscribe('+/data', function ($topic, $message) {
        error_log("Received logged data on topic: " . $topic);
        processLoggedData($topic, $message);
    }, 0);

    error_log("Starting MQTT event loop");
    // Start the event loop
    $mqtt->loop(true);

} catch (Exception $e) {
    error_log("MQTT Error: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());
    throw $e;
}

/**
 * Process live data messages
 * Format: value1,value2,value3,...,valuen,device_id
 */
function processLiveData($topic, $message) {
    try {
        error_log("Processing live data message: " . $message);
        $values = explode(',', $message);
        $device_id = array_pop($values); // Get and remove device_id from the end

        // Create WebSocket client
        $client = new Client("wss://live.onlineiotdata.in:8081");
        error_log("WebSocket client created");
        
        // Send data to WebSocket server
        $data = [
            'device_id' => $device_id,
            'values' => $values
        ];
        
        $client->send(json_encode($data));
        error_log("Data sent to WebSocket server");
        $client->close();
        
        // Query modem_params table
        $query = "SELECT param_name, position, unit FROM modem_params WHERE device_id = '$device_id' ORDER BY position";
        $result = QueryManager::getMultipleRow($query);
        error_log("Modem params queried for device: " . $device_id);
    } catch (Exception $e) {
        error_log("Error processing live data: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
    }
}

/**
 * Process logged data messages
 * Format: DDMMYYHHMM,value1,value2,...,valuen,device_id
 */
function processLoggedData($topic, $message) {
    try {
        error_log("Processing logged data message: " . $message);
        $values = explode(',', $message);
        $device_id = array_pop($values); // Get and remove device_id from the end
        $timestamp = array_shift($values); // Get and remove timestamp from the start
        
        // Verify device_id exists in devices table
        $verifyQuery = "SELECT COUNT(*) as count FROM devices WHERE device_id = '$device_id'";
        $result = QueryManager::getOneRow($verifyQuery);
        
        // If device not found, ignore the message
        if (!$result || $result[0] == 0) {
            error_log("Ignored MQTT message: Device ID '$device_id' not found in devices table");
            return;
        }
        
        // Parse timestamp for database date/time columns
        $date = DateTime::createFromFormat('dmyHi', $timestamp);
        if (!$date) {
            error_log("Invalid timestamp format in MQTT message for device '$device_id'");
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
            error_log("Ignored MQTT message: No valid recharge period found for device '$device_id' on date '$formattedDate'");
            return;
        }
        
        // Store the raw data string (original message without device_id)
        $dataString = $timestamp . ',' . implode(',', $values);
        
        // Store in logdata table
        $query = "INSERT INTO logdata (date, time, device_id, data) 
                 VALUES ('$formattedDate', '$formattedTime', '$device_id', '$dataString')";
        
        QueryManager::executeQuerySqli($query);
        error_log("Logged data stored for device: " . $device_id);
    } catch (Exception $e) {
        error_log("Error processing logged data: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
    }
}