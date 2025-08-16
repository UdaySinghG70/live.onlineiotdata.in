<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    try {
        // Increase execution time and memory limits for large datasets
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '1G');
        
        // Check if required extensions are available
        $required_extensions = ['zip', 'xml', 'xmlreader', 'xmlwriter', 'zlib', 'gd', 'mbstring', 'iconv', 'ctype', 'fileinfo'];
        $missing_extensions = [];
        
        foreach ($required_extensions as $ext) {
            if (!extension_loaded($ext)) {
                $missing_extensions[] = $ext;
            }
        }
        
        if (!empty($missing_extensions)) {
            throw new Exception('Missing required PHP extensions: ' . implode(', ', $missing_extensions));
        }
        
        // Check if autoload file exists
        $autoload_path = '../vendor/autoload.php';
        if (!file_exists($autoload_path)) {
            throw new Exception('Composer autoload file not found at: ' . $autoload_path);
        }
        
        require_once $autoload_path;
        
        // Check if PhpSpreadsheet class exists
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            throw new Exception('PhpSpreadsheet class not found. Please ensure PhpSpreadsheet is properly installed.');
        }
        
        // Use memory-efficient approach for large datasets
        $dataCount = count($data);
        $chunkSize = 500; // Reduced chunk size for better performance
        
        if ($dataCount > 2000) {
            // For large datasets, use CSV with Excel formatting (most efficient)
            $excel_filename = preg_replace('/\\.csv$/i', '.xlsx', $filename);
            
            // Create a temporary CSV file first
            $temp_csv = tempnam(sys_get_temp_dir(), 'excel_export_');
            $csv_handle = fopen($temp_csv, 'w');
            
            // Write CSV data
            $header = ['Sr. No.', 'Date', 'Time'];
            foreach ($params as $p) {
                $h = $p['name'];
                if ($p['unit']) $h .= ' [' . $p['unit'] . ']';
                $header[] = $h;
            }
            fputcsv($csv_handle, $header);
            
            $sr = 1;
            foreach ($data as $row) {
                $csvValues = $row['data'] ? explode(',', $row['data']) : [];
                $line = [$sr++, $row['date'], $row['time']];
                foreach ($csvValues as $v) {
                    $line[] = trim($v);
                }
                fputcsv($csv_handle, $line);
            }
            fclose($csv_handle);
            
            // Create Excel file from CSV with optimized settings
            $csv_reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
            $csv_reader->setDelimiter(',');
            $csv_reader->setEnclosure('"');
            $csv_reader->setSheetIndex(0);
            $csv_reader->setReadDataOnly(true); // Skip formatting for speed
            
            $spreadsheet = $csv_reader->load($temp_csv);
            $sheet = $spreadsheet->getActiveSheet();
            
            // Basic column sizing (skip autosize for large datasets to save time)
            $colCount = count($header);
            for ($col = 1; $col <= $colCount; $col++) {
                $sheet->getColumnDimensionByColumn($col)->setWidth(15); // Fixed width instead of autosize
            }
            
            // Output with optimized writer settings
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $excel_filename . '"');
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false); // Disable formula calculation for speed
            $writer->save('php://output');
            
            // Clean up
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
            unlink($temp_csv);
            
        } else {
            // For smaller datasets, use the original approach with chunked processing
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
            
            // Process data in chunks to save memory and time
            $sr = 1;
            $rowNum = 2;
            $chunks = array_chunk($data, $chunkSize);
            
            foreach ($chunks as $chunkIndex => $chunk) {
                foreach ($chunk as $row) {
                    $csvValues = $row['data'] ? explode(',', $row['data']) : [];
                    $line = [$sr++, $row['date'], $row['time']];
                    foreach ($csvValues as $v) {
                        $line[] = trim($v);
                    }
                    $sheet->fromArray($line, NULL, 'A' . $rowNum);
                    $rowNum++;
                }
                
                // Force garbage collection after each chunk
                if (function_exists('gc_collect_cycles')) {
                    gc_collect_cycles();
                }
                
                // Reset execution time limit after each chunk
                set_time_limit(300);
            }
            
            // Autosize columns only for smaller datasets
            $colCount = count($header);
            for ($col = 1; $col <= $colCount; $col++) {
                $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            }
            
            // Output
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . preg_replace('/\\.csv$/i', '.xlsx', $filename) . '"');
            
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false); // Disable formula calculation for speed
            $writer->save('php://output');
            
            // Clean up
            $spreadsheet->disconnectWorksheets();
            unset($spreadsheet);
        }
        
        exit;
        
    } catch (Exception $e) {
        // Log the error for debugging
        error_log('Excel export error: ' . $e->getMessage());
        
        // Return a user-friendly error message
        header('HTTP/1.1 500 Internal Server Error');
        header('Content-Type: text/html; charset=utf-8');
        echo '<html><body>';
        echo '<h2>Excel Export Error</h2>';
        echo '<p>The Excel export failed due to: <strong>' . htmlspecialchars($e->getMessage()) . '</strong></p>';
        echo '<p>This usually happens with very large datasets. Please try:</p>';
        echo '<ul>';
        echo '<li>Reducing the date range (try smaller time periods)</li>';
        echo '<li>Downloading as CSV instead (faster and more reliable)</li>';
        echo '<li>Contacting your administrator to increase server limits</li>';
        echo '</ul>';
        echo '<p><a href="javascript:history.back()">Go Back</a></p>';
        echo '</body></html>';
        exit;
    }
}

exit('Invalid export type'); 