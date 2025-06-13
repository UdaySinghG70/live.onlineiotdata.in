module.exports = {
  apps: [{
    name: "websocket-server",
    script: "php",
    args: "websocket_server.php",
    watch: false,
    log_date_format: "YYYY-MM-DD HH:mm:ss",
    error_file: "logs/websocket-error.log",
    out_file: "logs/websocket-out.log",
    merge_logs: true,
    autorestart: true,
    max_restarts: 10,
    min_uptime: "5s"
  },
  {
    name: "mqtt-client",
    script: "php",
    args: "mqtt_client.php",
    watch: false,
    log_date_format: "YYYY-MM-DD HH:mm:ss",
    error_file: "logs/mqtt-error.log",
    out_file: "logs/mqtt-out.log",
    merge_logs: true,
    autorestart: true,
    max_restarts: 10,
    min_uptime: "5s"
  }]
} 