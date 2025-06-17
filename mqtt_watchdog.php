<?php

const CHECK_INTERVAL = 60; // Check every 60 seconds
const MQTT_LOG_FILE = 'logs/mqtt.log';
const MAX_LOG_SIZE = 10 * 1024 * 1024; // 10MB
const MAX_LOG_FILES = 5; // Keep last 5 rotated log files

// Set default timezone to IST
date_default_timezone_set('Asia/Kolkata');

function logWatchdog($message) {
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] Watchdog: $message\n";
    
    // Ensure logs directory exists and is writable
    if (!file_exists('logs')) {
        mkdir('logs', 0777, true);
    }
    
    // Try to write to log file
    $logFile = 'logs/watchdog.log';
    if (file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX) === false) {
        // If we can't write to the log file, try to write to system error log
        error_log("Failed to write to watchdog log file: $logMessage");
    }
}

function isMqttProcessRunning() {
    $cmd = "ps aux | grep '[p]hp.*mqtt_client.php'";
    exec($cmd, $output);
    $isRunning = !empty($output);
    logWatchdog("MQTT process check: " . ($isRunning ? "Running" : "Not running"));
    return $isRunning;
}

function startMqttClient() {
    $cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" mqtt_client.php >> logs/mqtt.log 2>&1 &';
    exec($cmd);
    logWatchdog("Started MQTT client process");
}

function rotateLogFile() {
    if (file_exists(MQTT_LOG_FILE) && filesize(MQTT_LOG_FILE) > MAX_LOG_SIZE) {
        // Create backup filename with timestamp
        $backupFile = MQTT_LOG_FILE . '.' . date('Y-m-d-H-i-s');
        
        // Rename current log file
        rename(MQTT_LOG_FILE, $backupFile);
        
        // Create new empty log file with proper permissions
        touch(MQTT_LOG_FILE);
        chmod(MQTT_LOG_FILE, 0666);
        
        logWatchdog("Rotated MQTT log file to $backupFile");
        
        // Clean up old log files
        $logFiles = glob(MQTT_LOG_FILE . '.*');
        if (count($logFiles) > MAX_LOG_FILES) {
            // Sort by modification time, oldest first
            usort($logFiles, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });
            
            // Remove oldest files
            $filesToRemove = array_slice($logFiles, 0, count($logFiles) - MAX_LOG_FILES);
            foreach ($filesToRemove as $file) {
                unlink($file);
                logWatchdog("Removed old log file: $file");
            }
        }
    }
}

// Ensure log directory exists and has proper permissions
if (!file_exists('logs')) {
    mkdir('logs', 0777, true);
} else {
    chmod('logs', 0777);
}

// Initialize log files if they don't exist
$logFiles = ['mqtt.log', 'watchdog.log'];
foreach ($logFiles as $logFile) {
    $logPath = "logs/$logFile";
    if (!file_exists($logPath)) {
        touch($logPath);
        chmod($logPath, 0666);
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
        } else {
            logWatchdog("MQTT client process is running normally");
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