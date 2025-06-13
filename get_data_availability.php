<?php
// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

header('Content-Type: application/json');

session_start();
require_once 'model/querymanager.php';

try {
    // Check if user is logged in
    if (!isset($_SESSION['user_name'])) {
        throw new Exception('Unauthorized');
    }

    // Check if device_id and year are provided
    if (!isset($_GET['device_id'])) {
        throw new Exception('Device ID is required');
    }

    if (!isset($_GET['year'])) {
        throw new Exception('Year is required');
    }

    $device_id = $_GET['device_id'];
    $year = intval($_GET['year']);
    $user = $_SESSION['user_name'];

    // Validate year
    $currentYear = intval(date('Y'));
    if ($year < $currentYear - 5 || $year > $currentYear) {
        throw new Exception('Invalid year selected');
    }

    // Verify that the device belongs to the user
    $device_check_query = "SELECT device_id FROM devices WHERE device_id = '$device_id' AND user = '$user'";
    $device_check_row = QueryManager::getonerow($device_check_query);

    if (!$device_check_row) {
        throw new Exception('Invalid device ID');
    }

    // Set the date range for the selected year
    $start_date = "$year-01-01";
    $end_date = "$year-12-31";
    
    // If it's the current year, limit end date to today
    if ($year === $currentYear) {
        $end_date = date('Y-m-d');
    }

    // First, verify if the logdata table exists
    $table_check_query = "SHOW TABLES LIKE 'logdata'";
    $table_exists = QueryManager::getonerow($table_check_query);

    if (!$table_exists) {
        throw new Exception('Data table not found');
    }

    // Get column information
    $columns_query = "SHOW COLUMNS FROM logdata";
    $columns_result = QueryManager::getMultipleRow($columns_query);
    $columns = [];
    
    while ($column = mysqli_fetch_assoc($columns_result)) {
        $columns[] = $column['Field'];
    }

    // Construct the query based on available columns
    if (in_array('timestamp', $columns)) {
        // If there's a timestamp column
        $query = "SELECT DATE(timestamp) as date, COUNT(*) as count 
                 FROM logdata 
                 WHERE device_id = '$device_id' 
                 AND timestamp BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59' 
                 GROUP BY DATE(timestamp)";
    } else if (in_array('date', $columns)) {
        // If there are separate date and time columns
        $query = "SELECT date, COUNT(*) as count 
                 FROM logdata 
                 WHERE device_id = '$device_id' 
                 AND date BETWEEN '$start_date' AND '$end_date' 
                 GROUP BY date";
    } else {
        throw new Exception('Required date column not found in data table');
    }

    $result = QueryManager::getMultipleRow($query);

    if ($result === false) {
        $mysqli = QueryManager::getSqlConnection();
        throw new Exception('Database query failed: ' . mysqli_error($mysqli));
    }

    // Format the data for the Data graph
    $data = array();
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['date']] = (int)$row['count'];
        }
    }

    echo json_encode($data);

} catch (Exception $e) {
    error_log("Data Availability Error: " . $e->getMessage());
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
} catch (Error $e) {
    error_log("Data Availability Critical Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Internal server error: ' . $e->getMessage()]);
} 