<?php
date_default_timezone_set("UTC");
$device_id= $_REQUEST['DEV'];
$type= $_REQUEST['TYPE'];
 $dt=$_REQUEST['DT'];//+19800;
//$tm = date("Y-m-d H:i:s",$dt);
//$time = new DateTime($tm);
//$time=strtotime($tm);

$max= $_REQUEST['MAX'];
$min= $_REQUEST['MIN'];
$instant_data= $_REQUEST['INST'];
$status= $_REQUEST['STATUS'];
$imei=number_format($_REQUEST['IM'],0,'','') ;

include_once 'model/datadao.php';
$ddao=new Datadao();

$device=$ddao->getDeviceDetailByDeviceID($device_id);
if($device==null){
    echo "ACK";
    return;
}

$minutes_to_add=$device->timezone_minute;


 $dt=$dt+($minutes_to_add*60);

//$time=$dt;
//$time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
//$time=$time+($minutes_to_add*60);
$submit_time=date("Y-m-d H:i:s",$dt);

$device_data=$ddao->getDeviceDataByDevicedIdAndUtcTime($device_id,$dt);
if($device_data!=null){
    echo "ACK";
    return;
}
include_once 'model/DeviceData.php';
$data=new DeviceData();
$data->device_id=$device_id;
$data->data_type=$type;
$data->data_time_utc=$dt;
$data->date_time=$submit_time;
$data->max_data=$max;
$data->min_data=$min;
$data->data_status=$status;
$data->imei_nr=$imei;
$data->instant_data=$instant_data;

include_once 'model/admindao.php';
$adao=new AdminDao();

$rechargeHistory=$adao->getRechargeHistoryByDeviceId($device_id);
$rechargeFound=false;
$submit_time2=date("Y-m-d",$dt);


for($i=0;$i<count($rechargeHistory);$i++){
    if(strtotime($rechargeHistory[$i]->start_date)<=strtotime($submit_time2) && 
        strtotime($rechargeHistory[$i]->end_date)>=strtotime($submit_time2) ){
        $rechargeFound=true;
    }    
}

$result=$ddao->saveData($data,$rechargeFound);
//$result=$ddao->saveData($data);
if($result){
    echo "ACK";
}else{
    echo "ERROR";
}



