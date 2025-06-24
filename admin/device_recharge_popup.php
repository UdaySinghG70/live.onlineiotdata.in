<?php
// device_recharge_popup.php
session_start();

if (!isset($_SESSION['admin_name'])) {
    echo "<div style='padding:2rem;color:#dc2626;'>Invalid Access</div>";
    exit;
}

if (!isset($_GET['device_id'])) {
    echo "<div style='padding:2rem;color:#dc2626;'>No device specified.</div>";
    exit;
}

$device_id = $_GET['device_id'];

include_once '../model/querymanager.php';
$mysqliConn = QueryManager::getSqlConnection();

$stmt = $mysqliConn->prepare("SELECT start_date, end_date FROM recharge WHERE device_id = ? ORDER BY id DESC LIMIT 1");
$stmt->bind_param('s', $device_id);
$stmt->execute();
$stmt->bind_result($start_date, $end_date);

$found = $stmt->fetch();
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Device Recharge Info</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: transparent;
            margin: 0;
            padding: 0;
        }
        .popup-content-minimal {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 180px;
            min-height: 60px;
            padding: 0.5rem 0.5rem 0.3rem 0.5rem;
            background: none;
            box-shadow: none;
            border-radius: 0;
        }
        .popup-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0067ac;
            margin-bottom: 1rem;
            letter-spacing: 0.01em;
        }
        .popup-row {
            margin-bottom: 0.5rem;
            font-size: 0.98rem;
            display: flex;
            gap: 0.5rem;
        }
        .label {
            color: #64748b;
            font-weight: 500;
        }
        .value {
            color: #1e293b;
            font-weight: 600;
        }
        .no-record {
            color: #dc2626;
            margin-bottom: 0.5rem;
        }
        @media (max-width: 500px) {
            .popup-content-minimal {
                min-width: 0;
                padding: 0.3rem 0.1rem 0.2rem 0.1rem;
            }
            .popup-title {
                font-size: 1rem;
            }
            .popup-row {
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <div class="popup-content-minimal">
        <div class="popup-title">Device Recharge Info</div>
        <div class="popup-row"><span class="label">Device ID:</span> <span class="value"><?php echo htmlspecialchars($device_id); ?></span></div>
        <?php if ($found): ?>
            <div class="popup-row"><span class="label">Start Date:</span> <span class="value"><?php echo htmlspecialchars($start_date); ?></span></div>
            <div class="popup-row"><span class="label">End Date:</span> <span class="value"><?php echo htmlspecialchars($end_date); ?></span></div>
        <?php else: ?>
            <div class="no-record">No recharge record found for this device.</div>
        <?php endif; ?>
    </div>
</body>
</html> 