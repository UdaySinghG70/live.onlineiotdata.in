<?php
session_start();

if (!isset($_SESSION['admin_name'])) {
    echo "Invalid Access";
    exit;
}

if (!isset($_GET['device_id']) && !isset($_POST['device_id'])) {
    echo "No device ID provided.";
    exit;
}

$device_id = isset($_GET['device_id']) ? $_GET['device_id'] : $_POST['device_id'];

include_once '../model/querymanager.php';
$mysqliConn = QueryManager::getSqlConnection();

$device_id_safe = mysqli_real_escape_string($mysqliConn, $device_id);

$query = "SELECT start_date, end_date FROM recharge WHERE device_id = '$device_id_safe' ORDER BY id DESC LIMIT 1";
$result = $mysqliConn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    echo "<div style='padding:1rem;'>";
    echo "<h3>Recharge Info for Device: <span style='color:#0067ac;'>" . htmlspecialchars($device_id) . "</span></h3>";
    echo "<p><strong>Start Date:</strong> " . htmlspecialchars($row['start_date']) . "</p>";
    echo "<p><strong>End Date:</strong> " . htmlspecialchars($row['end_date']) . "</p>";
    echo "</div>";
} else {
    echo "<div style='padding:1rem; color:#dc2626;'>No recharge info found for this device.</div>";
} 