<?php
//$_REQUEST['username']="NHPC_SLHEP";
//$_REQUEST['password']="a1s2d3f4";
//$_REQUEST['device_id']="TAMEN";

//&& isset($_REQUEST['password'])
if(isset($_REQUEST['username'])  && isset($_REQUEST['device_id'])){
	$user_name = $_REQUEST['username'];
	//$password = $_REQUEST['password'];
	$device_id = $_REQUEST['device_id'];
	
	
	if(strlen($user_name)==0){
		echo "Inavlid User.";
		return ;
	}
// 	if(strlen($password)==0){
// 		echo "Invalid Password.";
// 		return;
// 	}
	
	include_once 'model/logindao.php';
	$ldao=new Logindao();
	
	include_once 'model/datadao.php';
	$ddao = new Datadao();
	
	include_once 'model/admindao.php';
	$adao = new AdminDao();
	
	
// 	$user=$ldao->checkLogin($user_name, $password);
// 	if($user==null){
// 		echo "no user found.";
// 		return ;
// 	}
	
	$deviceArr = $ddao->getDeviceByUserName($user_name);
	$found_device = false;
	foreach ($deviceArr as $value) {
	  if(strtoupper($value->device_id)==strtoupper($device_id)){
	  	$found_device = true;
	  	break;
	  }
	}
	if($found_device==false){
		echo "no device found";
	}
	
	$deviceInfo = $adao->getDeviceByDeviceID($device_id);
	$current_data = $ddao->getCurrentRechargedData($device_id );
	$data_arr[] = $current_data;
	$deviceParams = $adao->getDeviceParams($device_id);
	
	if($deviceParams!=null && $current_data!=null){
		$date_time ="0000-00-00T00:00:00";
		$water_level = 0;
		$battery_voltage = 0;
		$row=explode(",", $current_data->data) ;
		//print_r($row);
		$data_arr=array();
		for ($j=0; $j<count($row); $j++){
			$value=$row[$j];
			//echo " <br/> ".strtolower($deviceParams[$j]->param_name)."  ";
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
				//$value=  "20".substr($dt, 4, 2)."-".substr($dt,2,2)."-".substr($dt,0,2)."T".substr($dt,6,2).":".substr($dt,8,2).":00";//date("Y-m-d H:i:s",$dt);
				//$date_time=$value;
				
			}
			$data_arr[$deviceParams[$j]->param_name] = $value;
		}
// 		$arr = array("Project id"=> $deviceInfo->project_id ,
// 		     "Location id"=> $deviceInfo->location_id ,
// 		 	"date_time"=> $date_time,
// 		 	"Water Level"=> $water_level,
// 		 	"Battary Volt"=> $battery_voltage."V",
// 		 	"Network status"=> "");
		echo json_encode($data_arr);
	}
	
	// var test = {
	//     "id": "109",
	//     "No. of interfaces": "4"
	// }
	
	
}else{
	echo "invalid";
}