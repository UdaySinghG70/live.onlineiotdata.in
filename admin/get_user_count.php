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

// Get user count
$count = $adao->getTotalUsers();

// Return JSON response
header('Content-Type: application/json');
echo json_encode(['count' => $count]); 