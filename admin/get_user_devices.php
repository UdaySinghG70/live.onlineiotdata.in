<?php
session_start();

if(!isset($_SESSION['admin_name'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if(!isset($_POST['username'])) {
    echo json_encode(['error' => 'Username not provided']);
    exit;
}

include_once '../model/datadao.php';
$ddao = new DataDao();

$devices = $ddao->getDeviceByUserName($_POST['username']);
echo json_encode($devices);
?> 