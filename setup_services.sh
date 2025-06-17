#!/bin/bash

# Stop any existing processes
pkill -f "mqtt_client.php"
pkill -f "mqtt_watchdog.php"
pkill -f "websocket_server.php"

# Create logs directory if it doesn't exist
mkdir -p /var/www/live.onlineiotdata.in/logs
chmod 777 /var/www/live.onlineiotdata.in/logs

# Copy service files to systemd directory
cp mqtt-client.service /etc/systemd/system/
cp mqtt-watchdog.service /etc/systemd/system/
cp websocket-server.service /etc/systemd/system/

# Reload systemd to recognize new services
systemctl daemon-reload

# Enable services to start on boot
systemctl enable mqtt-client.service
systemctl enable mqtt-watchdog.service
systemctl enable websocket-server.service

# Start the services
systemctl start mqtt-client.service
systemctl start mqtt-watchdog.service
systemctl start websocket-server.service

# Check service status
echo "Checking service status..."
systemctl status mqtt-client.service
systemctl status mqtt-watchdog.service
systemctl status websocket-server.service

echo "Setup complete. Services should now be running and will start automatically on boot."
echo "To check logs, use:"
echo "  journalctl -u mqtt-client.service"
echo "  journalctl -u mqtt-watchdog.service"
echo "  journalctl -u websocket-server.service" 