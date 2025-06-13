<?php
session_start();
require_once 'model/querymanager.php';

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user = $_SESSION['user_name'];

// Get all devices for the logged-in user
$devices_query = "SELECT device_id FROM devices WHERE user = '$user'";
$devices_result = QueryManager::getMultipleRow($devices_query);

$statuses = array();

if ($devices_result && mysqli_num_rows($devices_result) > 0) {
    while ($device = mysqli_fetch_assoc($devices_result)) {
        $device_id = $device['device_id'];
        
        // Get parameters for this device
        $params_query = "SELECT param_name FROM modem_params WHERE device_id = '$device_id' ORDER BY position";
        $params_result = QueryManager::getMultipleRow($params_query);

        if ($params_result && mysqli_num_rows($params_result) > 0) {
            while ($param = mysqli_fetch_assoc($params_result)) {
                $param_name = $param['param_name'];
                
                // Get last received data timestamp for this parameter
                $last_data_query = "SELECT MAX(timestamp) as last_timestamp FROM device_data 
                                  WHERE device_id = '$device_id' AND param_name = '$param_name'";
                $last_data_result = QueryManager::getSingleRow($last_data_query);
                
                $last_timestamp = $last_data_result ? $last_data_result['last_timestamp'] : null;
                $current_time = time();
                $is_active = $last_timestamp && (strtotime($last_timestamp) > ($current_time - 300)); // 5 minutes threshold

                $statuses[] = array(
                    'device_id' => $device_id,
                    'param_name' => $param_name,
                    'is_active' => $is_active,
                    'last_timestamp' => $last_timestamp ? date('Y-m-d H:i:s', strtotime($last_timestamp)) : null
                );
            }
        }
    }
}

header('Content-Type: application/json');
echo json_encode($statuses); 