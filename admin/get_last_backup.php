<?php
session_start();

// Check if user is logged in
if(!isset($_SESSION['admin_name'])) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

include_once '../model/admindao.php';
$adao = new AdminDao();

// Get last backup details
$backup = $adao->getLastBackupDetails();

if ($backup) {
    // Format the date
    $date = new DateTime($backup['date']);
    $formattedDate = $date->format('d M Y');
    
    // Format the schedule (capitalize first letter)
    $schedule = ucfirst($backup['schedule']);
    
    // Format the tables
    $tables = $backup['tables'] === 'all' ? 'All Tables' : ucfirst($backup['tables']);
    
    $response = [
        'date' => $formattedDate,
        'schedule' => $schedule,
        'tables' => $tables,
        'success' => true
    ];
} else {
    $response = [
        'message' => 'No backups found',
        'success' => false
    ];
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response); 