<?php
echo "Starting servers...\n";

// Start WebSocket server in background with nohup
$websocket_cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" websocket_server.php >> websocket.log 2>&1 &';
exec($websocket_cmd);
echo "WebSocket server started (check websocket.log for output)\n";

// Start MQTT client in background with nohup
$mqtt_cmd = 'nohup php -d display_errors=1 -d error_reporting="E_ALL & ~E_DEPRECATED" mqtt_client.php >> mqtt.log 2>&1 &';
exec($mqtt_cmd);
echo "MQTT client started (check mqtt.log for output)\n";

echo "\nBoth servers are running. Check the log files for details:\n";
echo "- websocket.log for WebSocket server output\n";
echo "- mqtt.log for MQTT client output\n";
echo "\nPress Ctrl+C to stop both servers.\n";

// Keep the script running
while (true) {
    sleep(1);
} 