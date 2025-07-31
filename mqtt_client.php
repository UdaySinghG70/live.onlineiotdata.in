<?php
require('vendor/autoload.php');
require('model/querymanager.php');

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use WebSocket\Client;

// PID file locking mechanism
$pidFile = __DIR__ . '/mqtt_client.pid';

// Check if another instance is already running
if (file_exists($pidFile)) {
    $pid = file_get_contents($pidFile);
    if (posix_kill($pid, 0)) {
        die("Another instance of MQTT client is already running with PID: $pid\n");
    }
    // If we get here, the PID file exists but the process is not running
    unlink($pidFile);
}

// Create PID file
file_put_contents($pidFile, getmypid());

// Register shutdown function to remove PID file
register_shutdown_function(function() use ($pidFile) {
    if (file_exists($pidFile)) {
        unlink($pidFile);
    }
});

// MQTT Configuration
const MQTT_HOST = '103.212.120.23';
const MQTT_PORT = 1883;
const MQTT_USERNAME = 'admin';
const MQTT_PASSWORD = 'BeagleBone99';
const MAX_RECONNECT_ATTEMPTS = 10;
const RECONNECT_DELAY = 5; // seconds

// Generate a random client ID
$clientId = 'php_mqtt_client_' . rand(1, 10000);

// Initialize variables for reconnection logic
$reconnectAttempts = 0;
$lastReconnectTime = 0;
$isConnected = false;
$lastPing = 0; // Add this line to track last ping time

function logError($message, $error = null) {
    // Set timezone to IST
    date_default_timezone_set('Asia/Kolkata');
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message";
    if ($error) {
        $logMessage .= " Error: " . $error->getMessage();
    }
    $logMessage .= "\n";
    
    // Ensure logs directory exists and is writable
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }
    
    // Try to write to log file
    $logFile = 'logs/mqtt.log';
    if (file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX) === false) {
        // If we can't write to the log file, try to write to system error log
        error_log("Failed to write to MQTT log file: $logMessage");
    }
}

// Add initial connection log
logError("MQTT Client starting up...");

function connectToMqtt() {
    global $mqtt, $reconnectAttempts, $lastReconnectTime, $isConnected;
    
    // Define a unique client ID for the MQTT connection
    $clientId = "php-mqtt-client-" . uniqid();
    
    try {
        // Always create a new client instance on reconnect
        $mqtt = new MqttClient(MQTT_HOST, MQTT_PORT, $clientId);
        logError("MQTT Client instance created");
        
        // Set connection settings with clean session = false
        $connectionSettings = (new ConnectionSettings)
            ->setUsername(MQTT_USERNAME)
            ->setPassword(MQTT_PASSWORD)
            ->setKeepAliveInterval(60)
            ->setLastWillTopic('client/disconnect')
            ->setLastWillMessage('Client disconnected')
            ->setLastWillQualityOfService(1);
        
        // Connect to the broker
        $mqtt->connect($connectionSettings, false); // Set clean session to false here
        $isConnected = true;
        $reconnectAttempts = 0;
        logError("Successfully connected to MQTT broker");
        
        // Set up message handlers (subscriptions)
        setupMessageHandlers();
        
        return true;
    } catch (Exception $e) {
        $isConnected = false;
        logError("Failed to connect to MQTT broker", $e);
        return false;
    }
}

function setupMessageHandlers() {
    global $mqtt;
    
    // Message processing callback
    $mqtt->registerLoopEventHandler(function (MqttClient $mqtt, float $elapsedTime) {
        global $lastPing;
        if ($elapsedTime - $lastPing >= 30) {
            try {
                $mqtt->ping();
                $lastPing = $elapsedTime;
            } catch (Exception $e) {
                logError("Failed to send ping", $e);
                handleDisconnection();
            }
        }
    });

    // Subscribe to topics
    $mqtt->subscribe('+/live', function ($topic, $message) {
        try {
            logError("Received message on topic: $topic, message: $message");
            processLiveData($topic, $message);
        } catch (Exception $e) {
            logError("Error processing live data for topic: $topic", $e);
        }
    }, 0);
    logError("Subscribed to topic pattern: +/live");

    $mqtt->subscribe('+/data', function ($topic, $message) {
        try {
            logError("Received message on topic: $topic, message: $message");
            processLoggedData($topic, $message);
        } catch (Exception $e) {
            logError("Error processing logged data for topic: $topic", $e);
        }
    }, 0);
    logError("Subscribed to topic pattern: +/data");
    
    // Add wildcard subscription to catch any unexpected topics
    $mqtt->subscribe('#', function ($topic, $message) {
        logError("Received message on unexpected topic: $topic, message: $message");
    }, 0);
    logError("Subscribed to wildcard topic pattern: #");
}

function handleDisconnection() {
    global $reconnectAttempts, $lastReconnectTime, $isConnected, $mqtt;
    
    $isConnected = false;
    $currentTime = time();
    
    // Disconnect and cleanup the old client if it exists
    if (isset($mqtt) && $mqtt instanceof MqttClient) {
        try {
            $mqtt->disconnect();
        } catch (Exception $e) {
            logError("Error during MQTT disconnect", $e);
        }
        unset($mqtt);
    }
    
    // Check if we should attempt reconnection
    if ($currentTime - $lastReconnectTime >= RECONNECT_DELAY) {
        if ($reconnectAttempts < MAX_RECONNECT_ATTEMPTS) {
            $reconnectAttempts++;
            $lastReconnectTime = $currentTime;
            logError("Attempting reconnection (Attempt $reconnectAttempts of " . MAX_RECONNECT_ATTEMPTS . ")");
            
            if (connectToMqtt()) {
                logError("Successfully reconnected to MQTT broker");
                return true;
            }
        } else {
            logError("Maximum reconnection attempts reached. Please check the MQTT broker status.");
        }
    }
    
    return false;
}

// Main execution
try {
    if (connectToMqtt()) {
        // Start the event loop
        while (true) {
            try {
                $mqtt->loop(true);
            } catch (Exception $e) {
                logError("Error in MQTT event loop", $e);
                if (!handleDisconnection()) {
                    break;
                }
            }
        }
    }
} catch (Exception $e) {
    logError("Fatal error in MQTT client", $e);
}

/**
 * Process live data messages
 * Format: value1,value2,value3,...,valuen,device_id
 */
function processLiveData($topic, $message) {
    logError("[LiveData] Received message on topic: $topic, message: $message");
    $values = explode(',', $message);
    $device_id = array_pop($values); // Get and remove device_id from the end

    try {
        // Create WebSocket client with proper configuration
        $wsUrl = "wss://live.onlineiotdata.in:8081";
        logError("Attempting to connect to WebSocket server at: " . $wsUrl);
        
        $client = new Client($wsUrl, [
            'timeout' => 5,
            'headers' => [
                'Origin' => 'https://live.onlineiotdata.in',
                'User-Agent' => 'MQTT-Client'
            ]
        ]);
        
        // Send data to WebSocket server
        $data = [
            'device_id' => $device_id,
            'values' => $values
        ];
        
        $jsonData = json_encode($data);
        logError("Sending data to WebSocket: " . $jsonData);
        
        $client->send($jsonData);
        $client->close();
        logError("Successfully sent live data to WebSocket for device: $device_id");
        
    } catch (Exception $e) {
        logError("Error processing live data for topic: $topic Error: " . $e->getMessage());
        // Log additional connection details
        logError("Connection details - Device ID: $device_id, Values count: " . count($values));
    }
    
    // Query modem_params table
    $query = "SELECT param_name, position, unit FROM modem_params WHERE device_id = '$device_id' ORDER BY position";
    $result = QueryManager::getMultipleRow($query);
}

/**
 * Process logged data messages
 * Format: DDMMYYHHMM,value1,value2,...,valuen,device_id
 */
function processLoggedData($topic, $message) {
    logError("[LoggedData] Received message on topic: $topic, message: $message");
    $values = explode(',', $message);
    $device_id = array_pop($values); // Get and remove device_id from the end
    $timestamp = array_shift($values); // Get and remove timestamp from the start
    
    try {
        // Verify device_id exists in devices table
        $verifyQuery = "SELECT COUNT(*) as count FROM devices WHERE device_id = '$device_id'";
        $result = QueryManager::getonerow($verifyQuery);
        
        // If device not found, ignore the message
        if (!$result || $result[0] == 0) {
            logError("Ignored MQTT message: Device ID '$device_id' not found in devices table");
            return;
        }
        
        // Parse timestamp for database date/time columns
        $date = DateTime::createFromFormat('dmyHi', $timestamp);
        if (!$date) {
            logError("Invalid timestamp format in MQTT message for device '$device_id'. Timestamp: '$timestamp'");
            return;
        }
        
        $formattedDate = $date->format('Y-m-d');
        $formattedTime = $date->format('H:i:s');

        // Store the raw data string (original message without device_id)
        $dataString = $timestamp . ',' . implode(',', $values);
        
        // Store in logdata table
        $query = "INSERT INTO logdata (date, time, device_id, data) 
                 VALUES ('$formattedDate', '$formattedTime', '$device_id', '$dataString')";
        
        QueryManager::executeQuerySqli($query);
        logError("Successfully stored MDATA for device '$device_id' with timestamp '$timestamp'");
        
    } catch (Exception $e) {
        logError("Failed to store MQTT message for device '$device_id': " . $e->getMessage());
        logError("Message content: $message");
        logError("Parsed values - Device ID: $device_id, Timestamp: $timestamp, Values count: " . count($values));
    }
}