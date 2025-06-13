<?php
session_start();
if(isset($_SESSION['user_name'])==false){
	return "invalid_login";
}

include_once 'model/logindao.php';
$ldao=new LoginDao();

$user=$ldao->getUserByUserName($_SESSION['user_name']);
if($user==null){
	//header('Location: login.php');
	return "invalid_login";
}

class LiveStatus {
	public $id = "";
	public $data  = "";
	//public $birthdate = "";
}
include_once 'model/datadao.php';
$ddao=new DataDao();
include_once 'model/admindao.php';
$adao=new AdminDao();

$deviceArr=$ddao->getDeviceByUserName($user->user_name);

$data_arr=array();
$response_arr=array();
$dt_yesterday = date('Y-m-d', strtotime(date('Y-m-d'). '  -1 days'));
//$dt_yesterday = date('Y-m-d', strtotime("2022-03-11"));
//print_r($deviceArr);
for($i=0; $i<count($deviceArr); $i++){
	 
	$deviceInfo = $adao->getDeviceByDeviceID($deviceArr[$i]->device_id);
	$current_data = $ddao->getCurrentData($deviceArr[$i]->device_id );
	$data_arr[] = $current_data;
	$deviceParams = $adao->getDeviceParams($deviceArr[$i]->device_id);
	
	$response_row=new LiveStatus();
	$response_row->id = $deviceArr[$i]->device_id."_row";
	$respnse_str = "<div class='row_data' id='".$deviceArr[$i]->device_id."_row'>";
	$respnse_str ="";
	//echo "<div class='row_data' id='".$deviceArr[$i]->device_id."_row'>";
	if($deviceParams!=null && $current_data!=null){
		//echo "wah";
		 $respnse_str = "<div class='row_label'>";
		$respnse_str .= "<span class='' style='padding-left:0px;font-weight:bold;padding-right:10px;'>Device Id</span>";
		$respnse_str .= "<span class='param_value'>".strtoupper($deviceArr[$i]->device_id) ."</span>";
		$respnse_str .= "</div>";
		$row=explode(",", $current_data->data) ;
		//echo "<tr>";
		$rainfall_total = "";
			
		for ($j=0; $j<count($row); $j++){
				
			$value=$row[$j];
			$unit_1=$deviceParams[$j]->unit;
// 			if(strtolower( $deviceParams[$j]->param_name)=="rain" || strtolower($deviceParams[$j]->param_name)=="rainfall"){
// 				$rainfall_total = $ddao->getRainfallTotal($deviceArr[$i]->device_id, $deviceParams, $dt_yesterday);
// 			}else
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
				$unit_1="";
			}
			$respnse_str .= "<div style='float:left;' class='data_block'>";
			$respnse_str .= "<div class='param_lbl'>".$deviceParams[$j]->param_name."</div>";
			$respnse_str .= "<span class='param_value'>".$value." ".$unit_1."</span>";

			 $respnse_str .= "</div>";
			//echo "<td> $value </td>";
		}
		$respnse_str .= "<div style='line-height:1px;clear:both;'></div>";

	}else{
		//echo"its null";
	}
	//$respnse_str += "</div>";
	//echo $respnse_str;
	$response_row->data = $respnse_str;
	$response_arr[] = $response_row;
// 	if(strlen($rainfall_total)>0 ){
// 		echo "<div style='clear:both;line-height:1px;'>&nbsp;</div>";
// 		echo "<div style='float:left;'>";
// 		echo "<span class='param_lbl'>Total Rainfall </span>";
// 		echo "<span class='param_value'>".$rainfall_total." mm (".date('d-m-Y', strtotime($dt_yesterday)).")</span>";

// 		echo "</div>";
// 	}
}

echo json_encode($response_arr);
?>