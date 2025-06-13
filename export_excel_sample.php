<?php
session_start();
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

set_time_limit(0);
ini_set ( 'memory_limit', '2500M' );
include_once ("model/datadao.php");
include_once ("model/admindao.php");
include_once ("model/functions.php");
require_once 'Classes/PHPExcel.php';

function startsWith($haystack, $needle) {
    $length = strlen ( $needle );
    return (substr ( $haystack, 0, $length ) === $needle);
}

$modemid = $_GET ['modemid'];
$startdate = $_GET ['startdate'];
$enddate = $_GET ['enddate'];

//$modemid = "TESTING2";
//$startdate = "2013-10-1";
//$enddate = "2014-2-10";

$filename = $modemid . "_daterange_" . $startdate . "_" . $enddate;
$filename = str_replace ( "-", "_", $filename );

$adao = new AdminDAO ();
$dao = new DataDAO ();

$armp = $adao->getModemParams ( $modemid );
$startdata=0;
//$datalimit=3000;
$mainardata = $dao->getSubmitDataFull ( $modemid, $startdate, $enddate );

$submitmonth=date("F", strtotime(date($dao->getSubmitMonthForWorkSheet($modemid, $startdate, $enddate))));
//echo $maindatacount."</br>";
$worksheetcount=1;
//echo $worksheetcount;

$objPHPExcel = new PHPExcel ();
$objPHPExcel->getProperties ()->setCreator ( "Virtual Web" )->setTitle ( "Exported data" );
$timezone = new DateTimeZone('Asia/Calcutta');
$toskip = 3;
$sheetno=2;
for($sheetcount=0;$sheetcount<$worksheetcount;$sheetcount++)
{
    set_time_limit(0);//echo "data limit $datalimit startdata $startdata";
    if($sheetcount==0)
    {
        $objPHPExcel->getSheet(0)->setTitle(ucwords($submitmonth));
        $objPHPExcel->setActiveSheetIndex(0);
    }
    else {
        $sheettitle='sheet '.$sheetno;
        $objPHPExcel->createSheet($sheetcount);
        $objPHPExcel->getSheet($sheetcount)->setTitle(ucwords($submitmonth));
        $objPHPExcel->setActiveSheetIndex($sheetcount);
        $sheetno++;
        //$datalimit=$datalimit+3000;
    }
    $startdate_1 = new DateTime($startdate, $timezone);
    $startdateSTR=$startdate_1->format("d-M-Y ");
    
    $enddate_1 = new DateTime($enddate, $timezone);
    $enddateSTR=$enddate_1->format("d-M-Y ");
    
    $objPHPExcel->getActiveSheet()->mergeCells("A1:Z1");
    $objPHPExcel->getActiveSheet()->setCellValue("A1", "Report for date range $startdateSTR  -  $enddateSTR");
    $objPHPExcel->getActiveSheet()->setCellValue("A2", "Site ID:");
    $objPHPExcel->getActiveSheet()->setCellValue("B2", $modemid);
    for($i = 0; $i < count ( $armp ); $i ++) {
        $key=chr(65 + $i);
        if((65 + $i)>90){
            $key="A".chr(64+((65 + $i)-90));
        }
        $objPHPExcel->getActiveSheet(  )->setCellValue ( $key. "3", $armp [$i]->ar [2] );
        //echo $key. "3"."<br/>";
    }
    // print units
    
    for($i = 0; $i < count ( $armp ); $i ++) {
        
        $unit = $armp [$i]->ar [8];
        
        if($unit=="X")
            $unit = "";
            else
                $unit = "( $unit )";
                
                $key=chr(65 + $i);
                if((65 + $i)>90){
                    $key="A".chr(64+((65 + $i)-90));
                }
                $objPHPExcel->getActiveSheet (  )->setCellValue (  $key. "4", $unit );
                //$objPHPExcel->getActiveSheet (  )->setCellValue ( chr ( 65 + $i ) . "4", $unit );
    }$x=0;
    //for($x = 0; $x < count ( $mainardata ); $x ++) {
    for($startdata ; $startdata <  count ( $mainardata ); $startdata ++) {
        
        if(isset($mainardata[$startdata]))
        {
            $data = $mainardata[$startdata]->data;
            
            if($submitmonth!=date("F", strtotime(date($mainardata[$startdata]->submitdate))))
            {
                $submitmonth=date("F", strtotime(date($mainardata[$startdata]->submitdate)));
                $worksheetcount++;
                //$objPHPExcel->getSheet($sheetcount)->setTitle(ucwords($submitmonth));
                continue 2;
            }
            
            $arhum = array();
            $artopics = array();
            $ardata = array();
            $arhum_for_dew = array();
            $artemp_for_dew = array();
            
            $prev = 0;
            $dewpos = 0;
            $temp_hum_pos  = 0;
            $humfound = false;
            $dewfound = false;
            
            $humcount = -1;
            $dewcount = -1;
            
            $calculated_humidity = 0;		// for dew
            $required_temperature = 0;		// for dew
            $firsthumidity = true;
            $firsttemperature = true;
            
            // ardata stores value corresponding to a parameter type
            
            for($i = 0; $i < count ( $armp ); $i ++) {
                
                $artopics[$i] = $armp[$i]->ar[2];
                $len = intval($armp[$i]->ar[3]);
                $func = removefaltoocharacters(strtolower($armp[$i]->ar[4]));
                
                if($func=="date")			// date is predefined function of php (i created pdate function in modal/functions.php)
                    $func = "pdate";
                    
                    $ardata[$i] = $func($data, $armp, $i, $prev, $len);
                    
                    $prev = intval($prev) + intval($len);
            }
            
            /*********************new functions of report_datewise_data************************/
            
            $schema_insert = "";
            
            for($i=0;$i<count($armp);$i++){
                
                $last = count ( $armp [$i]->ar ) - 1;
                $reportinclusions = $armp [$i]->ar [$last];
                $fieldtype = $armp [$i]->ar [4];
                $decimals = $armp[$i]->ar[7];
                
                $ar_report_inclusions = explode ( ",", $reportinclusions );
                
                $rep_inc_size = count ( $ar_report_inclusions );
                
                $dt = isset($ardata[$i]) && strlen($ardata[$i])>0 ? $ardata[$i] : "?";
                
                if($dt=="?")
                    ;
                else{
                    if(strpos($dt,":")>-1)
                        ;
                    else if(is_numeric($dt))
                        $dt = round($dt,$decimals);
                }
                if($_SESSION['user']=="itram2m"  && strpos( $armp [$i]->ar [2], "Soil Moisture") !== false ){
                    if($dt=="200"){
                        $dt="-";
                    }
                }
                $key=chr(65 + $i);
                if((65 + $i)>90){
                    $key="A".chr(64+((65 + $i)-90));
                }			//$objPHPExcel->getActiveSheet(  )->setCellValue ( chr ( 65 + $i ) . ($x + 3 + $toskip), $dt );
                $objPHPExcel->getActiveSheet(  )->setCellValue ( $key . ($x + 3 + $toskip), $dt );
            }
            $x++;
        }
        else{
            $sheetcount=$worksheetcount;
        }
    }
    
}
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
// header("Content-Type: application/vnd.ms-excel");
header("Content-Type: application/force-download");
// header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");
header("Content-Disposition: attachment;filename=$filename.xls");
header("Content-Transfer-Encoding: binary ");
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');

$objWriter->save('php://output');
?>