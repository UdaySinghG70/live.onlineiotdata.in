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
        // Check data size and provide appropriate solution
        $dataCount = count($data);
        
        if ($dataCount > 10000) {
            // For very large datasets, redirect to CSV with explanation
            header('HTTP/1.1 200 OK');
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Large Dataset - Cloud Data Monitoring</title>
    <link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .header {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Ccircle cx=\'30\' cy=\'30\' r=\'4\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.3;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        .icon {
            font-size: 48px;
            margin-bottom: 20px;
            display: block;
            position: relative;
            z-index: 1;
        }

        .content {
            padding: 40px 30px;
        }

        .stats-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 30px;
            border-left: 4px solid #ff6b6b;
        }

        .stats-number {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .stats-label {
            color: #6c757d;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .solutions {
            margin-bottom: 30px;
        }

        .solutions h3 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .solution-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 16px;
            background: #f8f9fa;
            border-radius: 10px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .solution-item:hover {
            background: #e9ecef;
            transform: translateX(5px);
        }

        .solution-icon {
            background: #3498db;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
        }

        .solution-content h4 {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 4px;
        }

        .solution-content p {
            font-size: 14px;
            color: #6c757d;
            line-height: 1.5;
        }

        .actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            flex: 1;
            min-width: 140px;
            justify-content: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.4);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e9ecef;
        }

        .btn-secondary:hover {
            background: #e9ecef;
            color: #495057;
            transform: translateY(-2px);
        }

        .info-box {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 1px solid #90caf9;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-box h4 {
            color: #1565c0;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box p {
            color: #1976d2;
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }
            
            .header {
                padding: 25px 20px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .actions {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <span class="material-icons icon">data_usage</span>
            <h1>Large Dataset Detected</h1>
            <p>Your export request contains a significant amount of data</p>
        </div>
        
        <div class="content">
            <div class="stats-card">
                <div class="stats-number">' . number_format($dataCount) . '</div>
                <div class="stats-label">Total Records</div>
            </div>
            
            <div class="info-box">
                <h4><span class="material-icons" style="font-size: 20px;">info</span>Why Excel Export is Limited</h4>
                <p>Excel exports with more than 10,000 records can cause server timeouts, memory issues, and slow performance. For optimal reliability and speed, we recommend using CSV format for large datasets.</p>
            </div>
            
            <div class="solutions">
                <h3><span class="material-icons">lightbulb</span>Recommended Solutions</h3>
                
                <div class="solution-item">
                    <div class="solution-icon">
                        <span class="material-icons">download</span>
                    </div>
                    <div class="solution-content">
                        <h4>Download as CSV</h4>
                        <p>Faster, more reliable, and handles unlimited records. Opens perfectly in Excel, Google Sheets, and other applications.</p>
                    </div>
                </div>
                
                <div class="solution-item">
                    <div class="solution-icon" style="background: #27ae60;">
                        <span class="material-icons">schedule</span>
                    </div>
                    <div class="solution-content">
                        <h4>Reduce Date Range</h4>
                        <p>Try smaller time periods like 1-2 days or weekly chunks to stay under the 10,000 record limit.</p>
                    </div>
                </div>
                
                <div class="solution-item">
                    <div class="solution-icon" style="background: #f39c12;">
                        <span class="material-icons">view_list</span>
                    </div>
                    <div class="solution-content">
                        <h4>Use Pagination</h4>
                        <p>Download data in smaller chunks using the pagination controls (50 records per page).</p>
                    </div>
                </div>
            </div>
            
            <div class="actions">
                <a href="' . $_SERVER['REQUEST_URI'] . '&type=csv" class="btn btn-primary">
                    <span class="material-icons">file_download</span>
                    Download as CSV
                </a>
                <a href="javascript:history.back()" class="btn btn-secondary">
                    <span class="material-icons">arrow_back</span>
                    Go Back
                </a>
            </div>
        </div>
    </div>
</body>
</html>';
            exit;
        }
        
        // Increase execution time and memory limits for large datasets
        set_time_limit(600); // 10 minutes
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '2G');
        
        // Disable output buffering to prevent memory issues
        if (ob_get_level()) {
            ob_end_clean();
        }
        
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
        
        // Use streaming approach for all Excel exports
        $excel_filename = preg_replace('/\\.csv$/i', '.xlsx', $filename);
        
        // Create a temporary CSV file first (most efficient approach)
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
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->setPreCalculateFormulas(false); // Disable formula calculation for speed
        $writer->save('php://output');
        
        // Clean up
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
        unlink($temp_csv);
        
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
        echo '<p>This usually happens with very large datasets or server limitations. Please try:</p>';
        echo '<ul>';
        echo '<li><strong>Download as CSV</strong> - More reliable for large datasets</li>';
        echo '<li><strong>Reduce the date range</strong> - Try smaller time periods (1-2 days)</li>';
        echo '<li><strong>Use pagination</strong> - Download data in smaller chunks</li>';
        echo '<li><strong>Contact administrator</strong> - Server may need configuration updates</li>';
        echo '</ul>';
        echo '<p><a href="javascript:history.back()">Go Back</a> | <a href="' . $_SERVER['REQUEST_URI'] . '&type=csv">Download as CSV</a></p>';
        echo '</body></html>';
        exit;
    }
}

exit('Invalid export type'); 