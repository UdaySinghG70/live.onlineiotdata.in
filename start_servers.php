<?php
echo "Starting servers...\n";

// Create logs directory if it doesn't exist
if (!file_exists('logs')) {
    mkdir('logs', 0777, true);
    echo "Created logs directory\n";
}

// Start WebSocket server in background with nohup
$websocket_cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" websocket_server.php >> logs/websocket.log 2>&1 &';
exec($websocket_cmd);
echo "WebSocket server started (check logs/websocket.log for output)\n";

// Start MQTT watchdog in background with nohup
$watchdog_cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" mqtt_watchdog.php >> logs/watchdog.log 2>&1 &';
exec($watchdog_cmd);
echo "MQTT watchdog started (check logs/watchdog.log for output)\n";

// Start MQTT client in background with nohup
$mqtt_cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" mqtt_client.php >> logs/mqtt.log 2>&1 &';
exec($mqtt_cmd);
echo "MQTT client started (check logs/mqtt.log for output)\n";

echo "\nAll servers are running. Check the log files for details:\n";
echo "- logs/websocket.log for WebSocket server output\n";
echo "- logs/mqtt.log for MQTT client output\n";
echo "- logs/watchdog.log for MQTT watchdog output\n";
echo "\nPress Ctrl+C to stop all servers.\n";

// Keep the script running
while (true) {
    sleep(1);
} 