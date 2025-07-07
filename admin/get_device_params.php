<?php
session_start();

if(!isset($_SESSION['admin_name'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

if(!isset($_POST['device_id'])) {
    echo json_encode(['error' => 'Missing device_id']);
    exit;
}

include_once '../model/querymanager.php';
$device_id = $_POST['device_id'];

try {
    $qry = "SELECT param_name, unit FROM logparam WHERE device_id = ? ORDER BY id ASC";
    $stmt = QueryManager::prepareStatement($qry);
    if ($stmt) {
        $stmt->bind_param('s', $device_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $params = [];
        while ($row = $result->fetch_assoc()) {
            $params[] = [
                'name' => $row['param_name'],
                'unit' => $row['unit']
            ];
        }
        $stmt->close();
        echo json_encode(['params' => $params]);
    } else {
        echo json_encode(['error' => 'Database error: could not prepare statement']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching params: ' . $e->getMessage()]);
}
?> 