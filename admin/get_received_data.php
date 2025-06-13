<?php
session_start();

if(!isset($_SESSION['admin_name'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Validate required parameters
if(!isset($_POST['device_id']) || !isset($_POST['start_date']) || !isset($_POST['end_date'])) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

include_once '../model/datadao.php';
$ddao = new DataDao();

$device_id = $_POST['device_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

try {
    // Get received data with filters
    $data = $ddao->getReceivedData($device_id, $start_date, $end_date);
    
    // Format the data for response
    $formatted_data = array_map(function($row) {
        return [
            'date' => $row->submit_date,
            'time' => $row->submit_time,
            'device_id' => $row->device_id,
            'data' => $row->data,
            'recharge_status' => $row->recharge_found
        ];
    }, $data);
    
    echo json_encode($formatted_data);
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}
?> 