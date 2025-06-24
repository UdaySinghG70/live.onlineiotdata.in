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
            background: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .popup-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
            max-width: 350px;
            margin: 2rem auto;
            padding: 2rem 1.5rem;
            text-align: center;
        }
        .popup-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0067ac;
            margin-bottom: 1.5rem;
        }
        .popup-row {
            margin-bottom: 1rem;
            font-size: 1rem;
        }
        .label {
            color: #64748b;
            font-weight: 500;
        }
        .value {
            color: #1e293b;
            font-weight: 600;
        }
        .close-btn {
            margin-top: 1.5rem;
            background: #0067ac;
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 0.5rem 1.5rem;
            font-size: 1rem;
            cursor: pointer;
        }
        .close-btn:hover {
            background: #005491;
        }
    </style>
</head>
<body>
    <div class="popup-card">
        <div class="popup-title">Device Recharge Info</div>
        <div class="popup-row"><span class="label">Device ID:</span> <span class="value"><?php echo htmlspecialchars($device_id); ?></span></div>
        <?php if ($found): ?>
            <div class="popup-row"><span class="label">Start Date:</span> <span class="value"><?php echo htmlspecialchars($start_date); ?></span></div>
            <div class="popup-row"><span class="label">End Date:</span> <span class="value"><?php echo htmlspecialchars($end_date); ?></span></div>
        <?php else: ?>
            <div class="popup-row" style="color:#dc2626;">No recharge record found for this device.</div>
        <?php endif; ?>
        <button class="close-btn" onclick="window.close()">Close</button>
    </div>
</body>
</html> 