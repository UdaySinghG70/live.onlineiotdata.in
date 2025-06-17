<?php
session_start();
require('model/querymanager.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Function to get log content
function getLogContent($logFile, $lines = 100) {
    $logPath = "logs/$logFile";
    if (!file_exists($logPath)) {
        return "Log file not found: $logFile";
    }
    
    $content = array();
    $handle = fopen($logPath, "r");
    if ($handle) {
        // Move to end of file
        fseek($handle, 0, SEEK_END);
        $position = ftell($handle);
        $chunk = 4096;
        $data = '';
        $count = 0;
        
        // Read file backwards
        while ($position > 0 && $count < $lines) {
            $size = min($chunk, $position);
            $position -= $size;
            fseek($handle, $position);
            $data = fread($handle, $size) . $data;
            $count += substr_count($data, "\n");
            $content = array_merge(explode("\n", $data), $content);
            $data = '';
        }
        fclose($handle);
    }
    
    // Get only the last $lines
    $content = array_slice($content, -$lines);
    return implode("\n", $content);
}

// Get log file from request
$logFile = isset($_GET['log']) ? $_GET['log'] : 'mqtt.log';
$validLogs = ['mqtt.log', 'watchdog.log', 'websocket.log', 'ftp_upload.log'];

if (!in_array($logFile, $validLogs)) {
    $logFile = 'mqtt.log';
}

// Get log content
$logContent = getLogContent($logFile);

// Handle AJAX requests
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    echo json_encode(['content' => $logContent]);
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Log Viewer - <?php echo htmlspecialchars($logFile); ?></title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .log-container {
            background-color: #1e1e1e;
            color: #fff;
            font-family: monospace;
            padding: 15px;
            height: 80vh;
            overflow-y: auto;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .log-line {
            margin: 0;
            padding: 2px 0;
        }
        .log-line:hover {
            background-color: #2d2d2d;
        }
        .auto-scroll {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-12">
                <h2>Log Viewer: <?php echo htmlspecialchars($logFile); ?></h2>
                <div class="btn-group mb-3">
                    <?php foreach ($validLogs as $log): ?>
                    <a href="?log=<?php echo urlencode($log); ?>" 
                       class="btn btn-<?php echo $log === $logFile ? 'primary' : 'outline-primary'; ?>">
                        <?php echo htmlspecialchars($log); ?>
                    </a>
                    <?php endforeach; ?>
                </div>
                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" id="autoScroll" checked>
                    <label class="form-check-label" for="autoScroll">Auto-scroll</label>
                </div>
                <div class="log-container" id="logContent">
                    <?php echo nl2br(htmlspecialchars($logContent)); ?>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            const logContent = $('#logContent');
            let autoScroll = true;
            
            // Handle auto-scroll toggle
            $('#autoScroll').change(function() {
                autoScroll = $(this).is(':checked');
                if (autoScroll) {
                    logContent.scrollTop(logContent[0].scrollHeight);
                }
            });
            
            // Update log content every 2 seconds
            setInterval(function() {
                $.get('view_logs.php', {
                    log: '<?php echo urlencode($logFile); ?>',
                    ajax: 1
                }, function(data) {
                    const newContent = data.content;
                    if (newContent !== logContent.text()) {
                        logContent.html(newContent.split('\n').map(line => 
                            `<div class="log-line">${line}</div>`
                        ).join(''));
                        
                        if (autoScroll) {
                            logContent.scrollTop(logContent[0].scrollHeight);
                        }
                    }
                });
            }, 2000);
        });
    </script>
</body>
</html> 