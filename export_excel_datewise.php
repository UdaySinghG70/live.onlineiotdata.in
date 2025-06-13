<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
if(isset($_SESSION['user_name']) ==false){
    echo "Invalid Access";
    return ;
}
include_once 'model/logindao.php';
$ldao=new LoginDao();

$user_name=$_SESSION['user_name'];

$userDetails=$ldao->getUserByUserName($user_name);
if($userDetails==null){
    echo "Invalid Access";
    return ;
}

set_time_limit(0);
ini_set('memory_limit', '2500M');
include_once("model/datadao.php");
include_once ("model/admindao.php");
//include_once ("model/admindao.php");
//include_once ("model/functions.php");

// Use PhpSpreadsheet instead of PHPExcel
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;

function startsWith($haystack, $needle) {
    $length = strlen ( $needle );
    return (substr ( $haystack, 0, $length ) === $needle);
}

$device_id = $_GET['device_id'];
$startdate = $_GET['start_date'];
$enddate = $_GET['end_date'];
$format = isset($_GET['format']) ? $_GET['format'] : 'xlsx';

// Format the filename according to the new pattern
$formatted_start = str_replace('-', '_', $startdate);
$formatted_end = str_replace('-', '_', $enddate);
$filename = $device_id . "_" . $formatted_start . "to" . $formatted_end;

$adao=new AdminDao();

$dao = new DataDAO();

// Get parameters for this device
$param_query = "SELECT param_name, position, unit FROM logparam 
                WHERE device_id = ? 
                ORDER BY position";
$param_params = array($device_id);
$parameters = $dao->getData($param_query, $param_params);

if(empty($parameters)) {
    echo "No parameters defined for this device";
    return;
}

// Get the data
$data_query = "SELECT date, time, data 
               FROM logdata 
               WHERE device_id = ? 
               AND date >= ? AND date <= ? 
               ORDER BY date DESC, time DESC";
$data_params = array($device_id, $startdate, $enddate);
$data = $dao->getData($data_query, $data_params);

if(empty($data)) {
    echo "No data found for the specified date range";
    return;
}

// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator("IoT Data Export")
    ->setLastModifiedBy("IoT System")
    ->setTitle("Data Export for " . $device_id)
    ->setSubject("Data Export")
    ->setDescription("Data export for device " . $device_id . " from " . $startdate . " to " . $enddate);

// Get active sheet
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Data Export');

// Add headers
$sheet->setCellValue('A1', 'S.No.');
$sheet->setCellValue('B1', 'Timestamp (DDMMYYHHMM)');

// Add parameter names as column headers
$col = 'C';
$first = true;
foreach($parameters as $param) {
    if ($first) {
        $first = false;
        continue; // Skip the first parameter as it's the timestamp
    }
    $sheet->setCellValue($col . '1', $param->param_name . ' (' . $param->unit . ')');
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

// Add data
$row = 2;
$serialNumber = 1;
foreach($data as $record) {
    // Add Serial Number
    $sheet->setCellValue('A' . $row, $serialNumber);
    
    // Split data into values
    $values = explode(',', $record->data);
    $timestamp = array_shift($values); // Get the timestamp
    
    // Format timestamp for display
    $formatted_timestamp = substr($timestamp, 0, 2) . "-" . 
                          substr($timestamp, 2, 2) . "-" . 
                          substr($timestamp, 4, 2) . " " . 
                          substr($timestamp, 6, 2) . ":" . 
                          substr($timestamp, 8, 2);
    
    $sheet->setCellValue('B' . $row, $formatted_timestamp);
    
    // Add values under corresponding parameters
    $col = 'C';
    foreach($values as $value) {
        $sheet->setCellValue($col . $row, $value);
        $col++;
    }
    
    $row++;
    $serialNumber++;
}

if ($format === 'xlsx') {
    // Style the header row for XLSX
    $headerStyle = [
        'font' => [
            'bold' => true,
            'color' => ['rgb' => 'FFFFFF'],
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => '0067AC'],
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
        ],
    ];

    $lastCol = $col;
    $sheet->getStyle('A1:' . chr(ord($lastCol) - 1) . '1')->applyFromArray($headerStyle);
    $sheet->getRowDimension(1)->setRowHeight(30);

    // Style the data
    $dataStyle = [
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ],
    ];
    $sheet->getStyle('A2:' . chr(ord($lastCol) - 1) . ($row - 1))->applyFromArray($dataStyle);

    // Style the Serial Number column
    $serialNumberStyle = [
        'font' => [
            'bold' => true,
        ],
        'fill' => [
            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
            'startColor' => ['rgb' => 'F8F9FA'],
        ],
    ];
    $sheet->getStyle('A2:A' . ($row - 1))->applyFromArray($serialNumberStyle);

    // Add borders
    $borderStyle = [
        'borders' => [
            'allBorders' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                'color' => ['rgb' => 'E2E8F0'],
            ],
            'outline' => [
                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                'color' => ['rgb' => '0067AC'],
            ],
        ],
    ];
    $sheet->getStyle('A1:' . chr(ord($lastCol) - 1) . ($row - 1))->applyFromArray($borderStyle);

    // Freeze the header row
    $sheet->freezePane('A2');

    // Set headers for XLSX download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
    
    // Create Excel file
    $writer = new Xlsx($spreadsheet);
} else {
    // Set headers for CSV download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename="' . $filename . '.csv"');
    
    // Create CSV file
    $writer = new Csv($spreadsheet);
    $writer->setDelimiter(',');
    $writer->setEnclosure('"');
    $writer->setLineEnding("\r\n");
    $writer->setSheetIndex(0);
}

// Save to PHP output
header('Cache-Control: max-age=0');
$writer->save('php://output');
?>