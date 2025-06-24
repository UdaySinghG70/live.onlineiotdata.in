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
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: transparent;
        margin: 0;
        padding: 0;
    }
    .popup-card-better {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.13);
        max-width: 370px;
        min-width: 220px;
        width: 96vw;
        margin: 0 auto;
        padding: 1.5rem 1.2rem 1.2rem 1.2rem;
        display: flex;
        flex-direction: column;
        align-items: stretch;
        position: relative;
    }
    .popup-title-row {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.7rem;
    }
    .popup-title-icon {
        color: #0067ac;
        font-size: 1.5rem;
    }
    .popup-title {
        font-size: 1.18rem;
        font-weight: 700;
        color: #1e293b;
        letter-spacing: 0.01em;
    }
    .popup-divider {
        height: 1px;
        background: #e5e7eb;
        margin: 0.5rem 0 1.1rem 0;
        border: none;
    }
    .popup-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 0.5rem;
    }
    .popup-table td {
        padding: 0.35rem 0.2rem;
        font-size: 1rem;
    }
    .popup-label {
        color: #64748b;
        font-weight: 500;
        text-align: right;
        width: 40%;
        padding-right: 0.5rem;
        vertical-align: top;
    }
    .popup-value {
        color: #1e293b;
        font-weight: 600;
        text-align: left;
        width: 60%;
        word-break: break-all;
    }
    .no-record {
        color: #dc2626;
        margin: 0.5rem 0 0.2rem 0;
        text-align: center;
        font-weight: 500;
    }
    @media (max-width: 500px) {
        .popup-card-better {
            min-width: 0;
            padding: 1rem 0.3rem 0.7rem 0.3rem;
        }
        .popup-title {
            font-size: 1.05rem;
        }
        .popup-table td {
            font-size: 0.97rem;
        }
    }
</style>
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<div class="popup-card-better">
    <div class="popup-title-row">
        <span class="material-icons popup-title-icon">bolt</span>
        <span class="popup-title">Device Recharge Info</span>
    </div>
    <hr class="popup-divider"/>
    <table class="popup-table">
        <tr>
            <td class="popup-label">Device ID:</td>
            <td class="popup-value"><?php echo htmlspecialchars($device_id); ?></td>
        </tr>
        <?php if ($found): ?>
            <tr>
                <td class="popup-label">Start Date:</td>
                <td class="popup-value"><?php echo htmlspecialchars($start_date); ?></td>
            </tr>
            <tr>
                <td class="popup-label">End Date:</td>
                <td class="popup-value"><?php echo htmlspecialchars($end_date); ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="2" class="no-record">No recharge record found for this device.</td>
            </tr>
        <?php endif; ?>
    </table>
</div> 