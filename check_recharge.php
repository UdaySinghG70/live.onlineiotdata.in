<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'model/querymanager.php';

try {
    if (!isset($_POST['device_id'])) {
        throw new Exception('Device ID not provided');
    }

    $device_id = $_POST['device_id'];
    
    // Sanitize the input
    $device_id = trim($device_id);
    if (empty($device_id)) {
        throw new Exception('Invalid device ID');
    }
    
    // Get database connection
    $con = QueryManager::getSqlConnection();
    if (!$con) {
        throw new Exception('Database connection failed');
    }
    
    // Escape the device_id to prevent SQL injection
    $device_id = mysqli_real_escape_string($con, $device_id);
    
    // First check if table exists
    $check_table = "SHOW TABLES LIKE 'recharge'";
    $table_exists = QueryManager::getonerow($check_table);
    
    if (!$table_exists) {
        throw new Exception('Recharge table does not exist');
    }
    
    // Check if device exists in recharge table
    $query = "SELECT device_id FROM recharge WHERE device_id = '$device_id'";
    $result = QueryManager::getonerow($query);
    
    if ($result) {
        echo json_encode(array(
            'status' => 'active',
            'message' => 'Device is active'
        ));
    } else {
        echo json_encode(array(
            'status' => 'expired',
            'message' => 'Your recharge has expired. Please renew to access your data.'
        ));
    }
} catch (Exception $e) {
    error_log("Recharge check error for device $device_id: " . $e->getMessage());
    error_log("Query that failed: " . (isset($query) ? $query : 'No query executed'));
    http_response_code(400);
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Error checking device status: ' . $e->getMessage(),
        'debug_info' => array(
            'error' => $e->getMessage(),
            'device_id' => $device_id,
            'query' => isset($query) ? $query : null
        )
    ));
}
?> 