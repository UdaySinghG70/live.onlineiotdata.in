<?php
session_start();

if(!isset($_SESSION['admin_name'])) {
    header('HTTP/1.1 401 Unauthorized');
    exit('Unauthorized');
}

// Get and validate parameters
$device_id = isset($_GET['device_id']) ? $_GET['device_id'] : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$start_time = isset($_GET['start_time']) && $_GET['start_time'] !== '' ? $_GET['start_time'] : null;
$end_time = isset($_GET['end_time']) && $_GET['end_time'] !== '' ? $_GET['end_time'] : null;
$type = isset($_GET['type']) ? $_GET['type'] : 'csv';

if (!$device_id || !$start_date || !$end_date) {
    exit('Missing required parameters');
}

include_once '../model/querymanager.php';

// Get param names and units
$params = [];
$q = "SELECT param_name, unit FROM logparam WHERE device_id = ? ORDER BY id ASC";
$stmt = QueryManager::prepareStatement($q);
if ($stmt) {
    $stmt->bind_param('s', $device_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $params[] = [
            'name' => $row['param_name'],
            'unit' => $row['unit']
        ];
    }
    $stmt->close();
}

// Build WHERE clause for logdata
$where = "device_id = ? AND date >= ? AND date <= ?";
$qparams = [$device_id, $start_date, $end_date];
$qtypes = 'sss';
if ($start_time) {
    $where .= " AND time >= ?";
    $qparams[] = $start_time;
    $qtypes .= 's';
}
if ($end_time) {
    $where .= " AND time <= ?";
    $qparams[] = $end_time;
    $qtypes .= 's';
}

$data = [];
$q = "SELECT date, time, data FROM logdata WHERE $where ORDER BY date DESC, time DESC";
$stmt = QueryManager::prepareStatement($q);
if ($stmt) {
    // Use call_user_func_array for binding
    $bind_params = array_merge([$qtypes], $qparams);
    if (!function_exists('refValues')) {
        function refValues($arr) {
            if (strnatcmp(phpversion(),'5.3') >= 0) {
                $refs = array();
                foreach($arr as $key => $value)
                    $refs[$key] = &$arr[$key];
                return $refs;
            }
            return $arr;
        }
    }
    call_user_func_array([$stmt, 'bind_param'], refValues($bind_params));
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    $stmt->close();
}

// Prepare filename as user_device_startdate_starttime_enddate_endtime.csv
$user = isset($_GET['user_name']) && $_GET['user_name'] ? $_GET['user_name'] : 'user';
$start_time_safe = $start_time ? str_replace(':', '', $start_time) : '0000';
$end_time_safe = $end_time ? str_replace(':', '', $end_time) : '2359';
$filename = sprintf('%s_%s_%s_%s_%s_%s.csv', $user, $device_id, $start_date, $start_time_safe, $end_date, $end_time_safe);

if ($type === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $out = fopen('php://output', 'w');
    // Header row: Sr. No., Date, Time, Param1 [unit], Param2 [unit], ...
    $header = ['Sr. No.', 'Date', 'Time'];
    foreach ($params as $p) {
        $h = $p['name'];
        if ($p['unit']) $h .= ' [' . $p['unit'] . ']';
        $header[] = $h;
    }
    fputcsv($out, $header);
    // Data rows
    $sr = 1;
    foreach ($data as $row) {
        $csvValues = $row['data'] ? explode(',', $row['data']) : [];
        $line = [$sr++, $row['date'], $row['time']];
        foreach ($csvValues as $v) {
            $v = trim($v);
            // If value is numeric and starts with 0, prepend tab to preserve leading zeros in Excel
            if (preg_match('/^0[0-9]+$/', $v)) {
                $v = "\t" . $v;
            }
            $line[] = $v;
        }
        fputcsv($out, $line);
    }
    fclose($out);
    exit;
}

if ($type === 'excel') {
    require_once '../vendor/autoload.php';
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    // Header row
    $header = ['Sr. No.', 'Date', 'Time'];
    foreach ($params as $p) {
        $h = $p['name'];
        if ($p['unit']) $h .= ' [' . $p['unit'] . ']';
        $header[] = $h;
    }
    $sheet->fromArray($header, NULL, 'A1');
    // Data rows
    $sr = 1;
    $rowNum = 2;
    foreach ($data as $row) {
        $csvValues = $row['data'] ? explode(',', $row['data']) : [];
        $line = [$sr++, $row['date'], $row['time']];
        foreach ($csvValues as $v) {
            $line[] = trim($v);
        }
        $sheet->fromArray($line, NULL, 'A' . $rowNum);
        $rowNum++;
    }
    // Autosize columns
    $colCount = count($header);
    for ($col = 1; $col <= $colCount; $col++) {
        $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
    }
    // Output
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . preg_replace('/\\.csv$/i', '.xlsx', $filename) . '"');
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

exit('Invalid export type'); 