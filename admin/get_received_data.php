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

include_once '../model/querymanager.php';

$device_id = $_POST['device_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];
$start_time = isset($_POST['start_time']) && $_POST['start_time'] !== '' ? $_POST['start_time'] : null;
$end_time = isset($_POST['end_time']) && $_POST['end_time'] !== '' ? $_POST['end_time'] : null;
$page = isset($_POST['page']) && is_numeric($_POST['page']) && $_POST['page'] > 0 ? intval($_POST['page']) : 1;
$page_size = 50;
$offset = ($page - 1) * $page_size;

try {
    // Build the WHERE clause dynamically
    $where = "device_id = ? AND date >= ? AND date <= ?";
    $params = [$device_id, $start_date, $end_date];
    $types = 'sss';
    if ($start_time) {
        $where .= " AND time >= ?";
        $params[] = $start_time;
        $types .= 's';
    }
    if ($end_time) {
        $where .= " AND time <= ?";
        $params[] = $end_time;
        $types .= 's';
    }

    // Get total count
    $count_qry = "SELECT COUNT(*) as total FROM logdata WHERE $where";
    $count_stmt = QueryManager::prepareStatement($count_qry);
    if ($count_stmt) {
        $bind_params = array_merge([$types], $params);
        call_user_func_array([$count_stmt, 'bind_param'], refValues($bind_params));
        $count_stmt->execute();
        $count_result = $count_stmt->get_result();
        $total = 0;
        if ($row = $count_result->fetch_assoc()) {
            $total = intval($row['total']);
        }
        $count_stmt->close();
    } else {
        echo json_encode(['error' => 'Database error: could not prepare count statement']);
        exit;
    }

    // Query logdata table for the required data (paginated)
    $data_qry = "SELECT date, time, device_id, data FROM logdata WHERE $where ORDER BY date DESC, time DESC LIMIT ? OFFSET ?";
    $data_stmt = QueryManager::prepareStatement($data_qry);
    if ($data_stmt) {
        $data_params = array_merge($params, [$page_size, $offset]);
        $data_types = $types . 'ii';
        $bind_data_params = array_merge([$data_types], $data_params);
        call_user_func_array([$data_stmt, 'bind_param'], refValues($bind_data_params));
        $data_stmt->execute();
        $result = $data_stmt->get_result();
        $formatted_data = [];
        while ($row = $result->fetch_assoc()) {
            $formatted_data[] = [
                'date' => $row['date'],
                'time' => $row['time'],
                'device_id' => $row['device_id'],
                'data' => $row['data']
            ];
        }
        $data_stmt->close();
        echo json_encode([
            'total' => $total,
            'data' => $formatted_data
        ]);
    } else {
        echo json_encode(['error' => 'Database error: could not prepare statement']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Error fetching data: ' . $e->getMessage()]);
}

// Helper for call_user_func_array with references
function refValues($arr) {
    if (strnatcmp(phpversion(),'5.3') >= 0) {
        $refs = array();
        foreach($arr as $key => $value)
            $refs[$key] = &$arr[$key];
        return $refs;
    }
    return $arr;
}
?> 