<?php
session_start();
if(!isset($_SESSION['admin_name'])){
    header('Location: login.php');
    exit;
}
include_once '../model/admindao.php';
$adao = new AdminDao();

$preset_name = isset($_POST['presetName']) ? trim($_POST['presetName']) : '';
$param_names = isset($_POST['paramName']) ? $_POST['paramName'] : array();
$param_types = isset($_POST['paramType']) ? $_POST['paramType'] : array();
$param_units = isset($_POST['paramUnit']) ? $_POST['paramUnit'] : array();

if ($preset_name === '' || count($param_names) === 0) {
    echo json_encode(['success' => false, 'message' => 'Preset name and at least one parameter are required.']);
    exit;
}

$success = true;
for ($i = 0; $i < count($param_names); $i++) {
    $name = trim($param_names[$i]);
    $type = trim($param_types[$i]);
    $unit = trim($param_units[$i]);
    if ($name === '' || $type === '') continue;
    if (!$adao->addPresetParam($preset_name, $name, $type, $unit)) {
        $success = false;
    }
}

if ($success) {
    echo json_encode(['success' => true, 'message' => 'Preset saved successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving some parameters.']);
} 