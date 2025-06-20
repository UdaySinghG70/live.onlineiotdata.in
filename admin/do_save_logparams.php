<?php
session_start();
if(!isset($_SESSION['admin_name'])){
    header("Location: ../index.php");
    exit;
}
include_once '../model/admindao.php';
$adao = new AdminDao();

// Log incoming POST data for debugging
error_log("Incoming POST data: " . print_r($_POST, true));

$device_id = $_POST['device_id'];
$count = $_POST['count'];

// Log processing parameters
error_log("Processing parameters for device_id: $device_id, count: $count");

// Delete existing log parameters for this device
$deleteResult = $adao->deleteLogParams($device_id);
if (!$deleteResult) {
    error_log("Failed to delete existing log parameters for device_id: $device_id");
    echo "Error deleting existing parameters.";
    exit;
}

// Process each parameter
$success = true;
$processed = 0;

for ($i = 0; $i < $count; $i++) {
    $paramName = isset($_POST['paramName_db'][$i]) ? $_POST['paramName_db'][$i] : '';
    $paramType = isset($_POST['paramType_db'][$i]) ? $_POST['paramType_db'][$i] : '';
    $paramUnit = isset($_POST['paramUnit_db'][$i]) ? $_POST['paramUnit_db'][$i] : '';
    $paramPosition = isset($_POST['paramPosition_db'][$i]) ? $_POST['paramPosition_db'][$i] : '';
    
    // Log each parameter being processed
    error_log("Processing parameter $i: Name=$paramName, Type=$paramType, Unit=$paramUnit, Position=$paramPosition");
    
    // Skip if required fields are empty
    if (empty($paramName) || empty($paramType)) {
        error_log("Skipping parameter $i due to missing required fields");
        continue;
    }
    
    // Add parameter to database with correct column order:
    // param_name, param_type, unit, position, device_id
    if ($adao->addLogParam($paramName, $paramType, $paramUnit, $paramPosition, $device_id)) {
        $processed++;
    } else {
        error_log("Failed to add parameter $i");
        $success = false;
    }
}

if ($success && $processed > 0) {
    echo "Database parameters saved successfully.";
} else {
    echo "Error saving database parameters.";
}
?> 