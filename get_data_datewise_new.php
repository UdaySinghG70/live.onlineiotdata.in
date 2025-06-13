//i think we can delete this


<?php
session_start();
date_default_timezone_set("UTC");
if(isset($_SESSION['user_name']) ==false){
	echo "Invalid access";
	header('Location: login.php?msg=error');
	return;
}
if(isset($_POST['start_date'])==false || isset($_POST['end_date'])==false){
	echo "Invalid Entry";
	return ;
}
if(isset($_POST['device_id'])==false){
	echo "no device id";
	return;
}
$device_id=$_POST['device_id'];
$start_date=$_POST['start_date'];
$end_date=$_POST['end_date'];
if(strtotime($start_date) > strtotime($end_date)){
	echo "Start date should be less than or equal to end date";
	return ;
}

include_once 'model/datadao.php';
$ddao=new Datadao();

include_once 'model/admindao.php';
$adao=new AdminDao();


$pg=1;
if(isset($_POST['pg'])){
	$pg=$_POST['pg'];
	if($pg<=0){
	    $pg=1;
	}
}

$recordsToDisplay = 50;
$deviceInfo = $adao->getDeviceByDeviceID($device_id);

$deviceParams = $adao->getDeviceParams($device_id);
if($deviceParams==null){
	echo "Device parameters not found.";
	return ;
}
$recordCount=$ddao->getDataCountbyDateTime($device_id, $start_date, $end_date, "y");
$linkCount = $recordCount % $recordsToDisplay == 0 ? ( int )( $recordCount / $recordsToDisplay ) : ( int ) ( $recordCount / $recordsToDisplay ) + 1;

if($pg>$linkCount && $linkCount>0){
    $pg=$linkCount;
}
$starttingRecord = ($pg-1) * $recordsToDisplay;

$dataArr=$ddao->getDataByDateTime($device_id, $start_date, $end_date, "y", $starttingRecord, $recordsToDisplay);



if($linkCount - $pg > 10){
	$linkStart=$pg;
}else{
	if($linkCount > 10){
		$linkStart = $linkCount - 10;
	}else {
		$linkStart=1;
	}
}


if($recordCount==0){
    //print_r($dataArr);
    echo "no data found";
    return;
}

echo "<a target='_blank' href='export_excel_datewise.php?device_id=".$device_id."&start_date=".$start_date."&end_date=".$end_date."'>"
        ."<img src='images/download-to-excel-icon-3.jpg' style='width:40px;'></a>";
if($pg!=1){
	echo "<a class='nav_ctrl prev_btn'>Prev</a>	";
}else{
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
if($pg<$linkCount){
	echo "<a class='nav_ctrl  next_btn'>Next</a>	";	
}
echo "<input type='hidden' value='".$pg."' name='current_page'>";
?>
	<div style="float: right;">
		<input type="number" value="<?php echo $pg;?>" name="go_to_page" class="nav_ctrl" style="max-width: 80px;float: left;" min="0"/>
		<label style="float: left;padding: 4px 10px;padding-left: 0px;">/<?php echo $linkCount;?>&nbsp;</label>
		<a class="nav_ctrl  next_btn btn_goto_page">Go</a>	
		<div style="clear: both;"></div>
	</div>
	<div style="clear: both;"></div>
	<div id="tableDiv_Arrays" class="tableDiv" style="overflow-x:auto;">
		<table id="Open_Text_Arrays" class="FixedTables" style="text-align: center;width: 100%;">
			<thead>
				<tr>
					<?php 
					for($i=0; $i<count($deviceParams); $i++){
						echo "<th>".$deviceParams[$i]->param_name."<br/>(".$deviceParams[$i]->unit.")</th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
				<?php 
				for($i=0;$i<count($dataArr);$i++){
					
					$row=explode(",", $dataArr[$i]->data) ;
					echo "<tr>";
					for ($j=0; $j<count($row); $j++){
						
						$value=$row[$j];
						if($deviceParams[$j]->param_type=="datetime"){
							if(strlen($deviceInfo->timezone_minute)>0 && is_numeric($deviceInfo->timezone_minute)){
								$minutes_to_add=$deviceInfo->timezone_minute;
							}else{
								$minutes_to_add=330;
							}
							//$value=$value+($minutes_to_add*60);
							$dt=$value;
							//$value=date("d-m-Y H:i:s",$value);
							$value=  substr($dt,0,2)."-".substr($dt,2,2)."-20".substr($dt, 4, 2)." ".substr($dt,6,2).":".substr($dt,8,2).":00";//date("Y-m-d H:i:s",$dt);
							//$submit_time=substr($dt,6,2).":".substr($dt,8,2).":00";
						}
						echo "<td> $value </td>";
					}
					echo "</tr>";
// 					for($j=0; $j<count($deviceParams); $j++ ){
						
// 					}
					
// 					echo "<tr><td>".$dataArr[$i]->date_time."</td>"
// 					       ."<td> ".$dataArr[$i]->instant_data."</td>"
//                             ."<td> ".$dataArr[$i]->max_data."</td>"
// 							."<td> ".$dataArr[$i]->min_data."</td>"
// 							."<td> ".$dataArr[$i]->data_type."</td>"
// 							."<td> ".$dataArr[$i]->data_status."</td>"
// 						."</tr>";
					
				}
				?>
				
			</tbody>
		</table>
	</div>



