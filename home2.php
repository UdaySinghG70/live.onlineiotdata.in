<?php 
date_default_timezone_set("UTC");
$device_id= $_REQUEST['id'];
$data= $_REQUEST['data'];
//echo "as";
include_once 'model/admindao.php';
include_once 'model/datadao.php';

$adao=new AdminDao();
$ddao=new Datadao();
$modemInfo=$ddao->getDeviceDetailByDeviceID($device_id);

if($modemInfo==null){
	echo "OK";
	return;
}

$deviceParams=$adao->getDeviceParams($device_id);
$submit_date="0000-00-00";
$submit_time="00:00:00";

for($i=0; $i<count($deviceParams); $i++){
	
	$dateTimeFound=$deviceParams[$i]->param_type;
	
	if($dateTimeFound=="datetime"){
		
		$minutes_to_add=330;
		if(strlen($modemInfo->timezone_minute)>0 && is_numeric($modemInfo->timezone_minute)){
			$minutes_to_add=$modemInfo->timezone_minute;
		}
		
		$dataArr=explode(",", $data);
		$dt=$dataArr[$i];
		
		//$dt=$dt+($minutes_to_add*60);
		$input= substr($dt, 0, 2)."/".substr($dt, 2, 2)."/20".substr($dt, 4, 2) ." ".substr($dt, 6, 2).":".substr($dt, 8, 2).":00";
		//echo $input;
		//$dt = strtotime($input);
		$submit_date=  "20".substr($dt, 4, 2)."-".substr($dt,2,2)."-".substr($dt,0,2)." ".substr($dt,6,2).":".substr($dt,8,2).":00";//date("Y-m-d H:i:s",$dt);
		$submit_time=substr($dt,6,2).":".substr($dt,8,2).":00";
	}
}

$arrRecharge=$adao->getRechargeHistoryByDeviceId($device_id);
$rechargeFound=false;
for($i=0;$i<count($arrRecharge);$i++){
	if(strtotime($arrRecharge[$i]->start_date) <= strtotime($submit_date) &&
			strtotime($arrRecharge[$i]->end_date) >= strtotime($submit_date) ){
		$rechargeFound=true;
	}
}
if($ddao->getDeviceDataByDevicedIdAndData($device_id, $data)==null){
	$result=$ddao->saveData($device_id, $data, $submit_date, $submit_time, $rechargeFound);
	//$result=$ddao->saveData($data);
	if($result){
		echo "ACK";
	}else{
		echo "ERROR";
	}
}else{
		echo "ACK";
}


// $dt=$_REQUEST['DT'];//+19800;
// //$tm = date("Y-m-d H:i:s",$dt);
// //$time = new DateTime($tm);
// //$time=strtotime($tm);

// $max= $_REQUEST['MAX'];
// $min= $_REQUEST['MIN'];
// $instant_data= $_REQUEST['INST'];
// $status= $_REQUEST['STATUS'];
// $imei=number_format($_REQUEST['IM'],0,'','') ;



?>