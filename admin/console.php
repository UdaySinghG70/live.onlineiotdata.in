<?php
// admin/console.php
session_start();
if (!isset($_SESSION['admin_name'])) {
    header('Location: login.php');
    exit;
}

$logDir = realpath(__DIR__ . '/../logs');
$logFiles = [];
if ($logDir && is_dir($logDir)) {
    foreach (scandir($logDir) as $file) {
        if ($file === '.' || $file === '..') continue;
        if (is_file($logDir . DIRECTORY_SEPARATOR . $file)) {
            $logFiles[] = $file;
        }
    }
}

// Serve log content via AJAX
if (isset($_GET['logfile'])) {
    $file = basename($_GET['logfile']);
    $path = $logDir . DIRECTORY_SEPARATOR . $file;
    if (in_array($file, $logFiles) && is_readable($path)) {
        header('Content-Type: text/plain');
        // Show only last 500 lines for performance
        $lines = @file($path);
        if ($lines !== false) {
            $lines = array_slice($lines, -500);
            echo htmlspecialchars(implode('', $lines));
        } else {
            echo "Could not read log file.";
        }
    } else {
        echo "Invalid log file.";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Console - Admin Logs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .console-container {
            max-width: 1000px;
            margin: 40px auto;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            padding: 2rem;
        }
        .console-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .console-select {
            font-size: 1rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }
        .log-viewer {
            background: #111827;
            color: #e5e7eb;
            font-family: monospace;
            font-size: 0.95rem;
            border-radius: 8px;
            padding: 1rem;
            min-height: 400px;
            max-height: 60vh;
            overflow-y: auto;
            white-space: pre-wrap;
        }
        .refresh-indicator {
            font-size: 0.9rem;
            color: #64748b;
            margin-left: 1rem;
        }
    </style>
</head>
<body>
<?php include_once 'admin_header.php'; ?>
<main class="dashboard">
    <div class="welcome-message" style="margin-bottom: 1.5rem;">
        <h2>Console Logs</h2>
        <p>View and monitor all server logs in real time. Select a log file to view its latest entries.</p>
    </div>
    <div class="dashboard-grid" style="grid-template-columns: 1fr;">
        <div class="stats-card" style="padding: 0;">
            <div class="stats-header" style="padding: 1.5rem 1.5rem 0.5rem 1.5rem;">
                <div class="stats-icon">
                    <span class="material-icons">terminal</span>
                </div>
                <div class="stats-title" style="font-size: 1.1rem;">Console Logs</div>
                <span class="refresh-indicator" id="refreshStatus" style="margin-left:auto; font-size:0.95em; color:#64748b;">Auto-refreshing...</span>
            </div>
            <div style="padding: 0 1.5rem 1.5rem 1.5rem;">
                <?php if (count($logFiles) > 1): ?>
                    <select id="logSelect" class="console-select" style="margin-bottom:1rem;">
                        <?php foreach ($logFiles as $file): ?>
                            <option value="<?= htmlspecialchars($file) ?>"><?= htmlspecialchars($file) ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php elseif (count($logFiles) === 1): ?>
                    <span style="font-size:1.1em; font-weight:500; color:#0067ac;">Viewing: <?= htmlspecialchars($logFiles[0]) ?></span>
                <?php else: ?>
                    <span>No log files found.</span>
                <?php endif; ?>
                <div class="log-viewer" id="logViewer" style="margin-top:1rem; background:#111827; color:#e5e7eb; font-family:monospace; font-size:0.95rem; border-radius:8px; min-height:400px; max-height:60vh; overflow-y:auto; white-space:pre-wrap;">
                    <?php if (count($logFiles) === 0): ?>
                        <em>No logs to display.</em>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
const logFiles = <?php echo json_encode($logFiles); ?>;
let currentLog = logFiles.length ? logFiles[0] : null;
const logViewer = document.getElementById('logViewer');
const refreshStatus = document.getElementById('refreshStatus');

function fetchLog() {
    if (!currentLog) return;
    fetch('?logfile=' + encodeURIComponent(currentLog) + '&_=' + Date.now())
        .then(r => r.text())
        .then(txt => {
            logViewer.textContent = txt;
            logViewer.scrollTop = logViewer.scrollHeight;
            refreshStatus.textContent = 'Auto-refreshing...';
        })
        .catch(() => {
            logViewer.textContent = 'Error loading log.';
            refreshStatus.textContent = 'Error.';
        });
}

if (logFiles.length) {
    fetchLog();
    setInterval(fetchLog, 2000);
}

const logSelect = document.getElementById('logSelect');
if (logSelect) {
    logSelect.addEventListener('change', function() {
        currentLog = this.value;
        fetchLog();
    });
}
</script>
</body>
</html> 