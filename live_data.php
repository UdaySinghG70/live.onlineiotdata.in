<?php
session_start();
require_once 'model/querymanager.php';

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user_name'];

// Get all devices for the logged-in user
$devices_query = "SELECT device_id FROM devices WHERE user = '$user'";
$devices_result = QueryManager::getMultipleRow($devices_query);

// Get user department name for header
include_once 'model/logindao.php';
$ldao = new LoginDao();
$userObj = $ldao->getUserByUserName($user);
$department_name = $userObj ? $userObj->department_name : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Data</title>
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/dropdown.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            padding: 0;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .device-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .device-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .last-update {
            color: #6c757d;
            font-size: 14px;
            margin-left: 15px;
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .device-id {
            font-size: 20px;
            color: #2c3e50;
            font-weight: 600;
        }

        .params-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 15px;
            width: 100%;
        }

        .param-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e9ecef;
            transition: transform 0.2s;
            height: 120px;
            display: flex;
            flex-direction: column;
        }

        .param-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .param-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .param-details {
            color: #6c757d;
            font-size: 12px;
            margin-top: auto;
        }

        .unit {
            display: inline-block;
            background-color: #e9ecef;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
        }


        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 16px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .param-card {
                min-width: 100%;
            }
        }

        .param-value {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin: 8px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .last-update {
            font-size: 12px;
            color: #95a5a6;
            margin-top: 5px;
        }

        .param-card.updated {
            animation: highlight 1s ease-out;
        }

        @keyframes highlight {
            0% { background-color: #dff0d8; }
            100% { background-color: #f8f9fa; }
        }
    </style>
</head>
<body>
    <?php include_once 'header1.php'; ?>

    <div class="main-container">
        <?php
        if ($devices_result && mysqli_num_rows($devices_result) > 0) {
            while ($device = mysqli_fetch_assoc($devices_result)) {
                $device_id = $device['device_id'];
                echo "<div class='device-section' id='device-" . htmlspecialchars($device_id) . "'>";
                echo "<div class='device-header'>";
                echo "<div class='device-id'>Device ID: " . htmlspecialchars($device_id) . "</div>";
                echo "<div class='last-update' id='last-update-" . htmlspecialchars($device_id) . "'></div>";
                echo "</div>";

                // Get modem parameters for this device
                $params_query = "SELECT param_name, unit, position FROM modem_params WHERE device_id = '$device_id' ORDER BY position";
                $params_result = QueryManager::getMultipleRow($params_query);

                if ($params_result && mysqli_num_rows($params_result) > 0) {
                    echo "<div class='params-container'>";
                    while ($param = mysqli_fetch_assoc($params_result)) {
                        echo "<div class='param-card' id='" . htmlspecialchars($device_id . "-" . $param['position']) . "'>";
                        echo "<div class='param-name'>" . htmlspecialchars($param['param_name']) . "</div>";
                        echo "<div class='param-value'>--</div>";
                        echo "<div class='param-details'>";
                        if (!empty($param['unit'])) {
                            echo "<span class='unit'>" . htmlspecialchars($param['unit']) . "</span>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                    echo "</div>";
                } else {
                    echo "<p class='no-data'>No parameters found for this device.</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='no-data'>No devices found for this user.</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $("#live_page").addClass("active");

            // Cache management functions
            const CACHE_KEY = 'deviceDataCache';
            
            function saveToCache(deviceId, values, timestamp) {
                try {
                    let cache = JSON.parse(localStorage.getItem(CACHE_KEY) || '{}');
                    cache[deviceId] = {
                        values: values,
                        timestamp: timestamp
                    };
                    localStorage.setItem(CACHE_KEY, JSON.stringify(cache));
                } catch (e) {
                    console.error('Error saving to cache:', e);
                }
            }

            function loadFromCache() {
                try {
                    const cache = JSON.parse(localStorage.getItem(CACHE_KEY) || '{}');
                    Object.keys(cache).forEach(deviceId => {
                        const data = cache[deviceId];
                        updateDeviceData({
                            device_id: deviceId,
                            values: data.values
                        }, data.timestamp, false); // false means don't show animation
                    });
                } catch (e) {
                    console.error('Error loading from cache:', e);
                }
            }

            // Load cached values immediately
            loadFromCache();

            // Connect to WebSocket server
            var ws = new WebSocket('ws://localhost:8080');

            ws.onopen = function() {
                console.log('Connected to WebSocket server');
            };

            ws.onmessage = function(event) {
                var data = JSON.parse(event.data);
                var timestamp = new Date().toLocaleTimeString();
                updateDeviceData(data, timestamp, true); // true means show animation
                saveToCache(data.device_id, data.values, timestamp);
            };

            ws.onerror = function(error) {
                console.error('WebSocket error:', error);
            };

            ws.onclose = function() {
                console.log('Disconnected from WebSocket server');
                // Try to reconnect after 5 seconds
                setTimeout(function() {
                    location.reload();
                }, 5000);
            };

            function updateDeviceData(data, timestamp, showAnimation = true) {
                var deviceId = data.device_id;
                var values = data.values;
                
                values.forEach(function(value, index) {
                    var paramCard = $('#' + deviceId + '-' + (index + 1));
                    if (paramCard.length) {
                        paramCard.find('.param-value').text(value);
                        
                        if (showAnimation) {
                            paramCard.addClass('updated');
                            setTimeout(function() {
                                paramCard.removeClass('updated');
                            }, 1000);
                        }
                    }
                });
                
                // Update the last update time in the device header
                $('#last-update-' + deviceId).text('Last update: ' + (timestamp || 'Cached'));
            }

            // Clear old cache entries (older than 24 hours)
            function cleanOldCache() {
                try {
                    const cache = JSON.parse(localStorage.getItem(CACHE_KEY) || '{}');
                    const now = new Date();
                    let hasChanges = false;

                    Object.keys(cache).forEach(deviceId => {
                        const timestamp = new Date(cache[deviceId].timestamp);
                        if ((now - timestamp) > (24 * 60 * 60 * 1000)) { // 24 hours
                            delete cache[deviceId];
                            hasChanges = true;
                        }
                    });

                    if (hasChanges) {
                        localStorage.setItem(CACHE_KEY, JSON.stringify(cache));
                    }
                } catch (e) {
                    console.error('Error cleaning cache:', e);
                }
            }

            // Clean old cache entries on page load
            cleanOldCache();
        });
    </script>
</body>
</html>