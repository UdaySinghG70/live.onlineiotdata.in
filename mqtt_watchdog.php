<?php

const CHECK_INTERVAL = 60; // Check every 60 seconds
const MQTT_LOG_FILE = 'logs/mqtt.log';
const MAX_LOG_SIZE = 10 * 1024 * 1024; // 10MB

function logWatchdog($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] Watchdog: $message\n";
    error_log($logMessage, 3, 'logs/watchdog.log');
}

function isMqttProcessRunning() {
    $cmd = "ps aux | grep '[p]hp.*mqtt_client.php'";
    exec($cmd, $output);
    return !empty($output);
}

function startMqttClient() {
    $cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" mqtt_client.php >> mqtt.log 2>&1 &';
    exec($cmd);
    logWatchdog("Started MQTT client process");
}

function rotateLogFile() {
    if (file_exists(MQTT_LOG_FILE) && filesize(MQTT_LOG_FILE) > MAX_LOG_SIZE) {
        $backupFile = MQTT_LOG_FILE . '.' . date('Y-m-d-H-i-s');
        rename(MQTT_LOG_FILE, $backupFile);
        logWatchdog("Rotated MQTT log file to $backupFile");
    }
}

// Main watchdog loop
logWatchdog("Starting MQTT watchdog");

while (true) {
    try {
        // Check if MQTT process is running
        if (!isMqttProcessRunning()) {
            logWatchdog("MQTT client process not found. Restarting...");
            startMqttClient();
        }

        // Rotate log file if needed
        rotateLogFile();

        // Sleep for the check interval
        sleep(CHECK_INTERVAL);
    } catch (Exception $e) {
        logWatchdog("Error in watchdog: " . $e->getMessage());
        sleep(CHECK_INTERVAL);
    }
} 