<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$aldao=new AdminLoginDao();

$adminDetails=$aldao->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}

if(isset($_POST['device_rowid'])==false || isset($_POST['user_name'])==false || isset($_POST['mobile_nr'])==false
    || isset($_POST['device_id'])==false || isset($_POST['imei_nr'])==false
    || isset($_POST['timezone'])==false || isset($_POST['latitude'])==false || isset($_POST['longitude'])==false
    || isset($_POST['city'])==false || isset($_POST['place'])==false
    || isset($_POST['country'])==false){
        
        echo "Invalid Input";
        return;
}
include_once '../model/functions.php';
include_once '../model/admindao.php';
$adao=new AdminDao();
include_once '../model/datadao.php';
$ddao=new Datadao();

$device_id_got=$_POST['device_id_got'];
$deviceDetail=$ddao->getDeviceDetailByDeviceID($device_id_got);

if($deviceDetail==null){
    echo "Some error device detail not found ".$device_id_got;
}
include_once '../model/DeviceEntity.php';
$deviceDetailNew=new DeviceEntity();

$deviceDetailNew->device_id=$_POST['device_id'];
$deviceDetailNew->user=$_POST['user_name'];
$deviceDetailNew->mobile_no=$_POST['mobile_nr'];
$deviceDetailNew->imei_nr=$_POST['imei_nr'];
$deviceDetailNew->timezone_minute=$_POST['timezone'];

$deviceDetailNew->project_id="";
if(isset($_POST['project_id'])){
	$deviceDetailNew->project_id=$_POST['project_id'];
}

$deviceDetailNew->location_id="";
if(isset($_POST['location_id'])){
	$deviceDetailNew->location_id=$_POST['location_id'];
}

$deviceDetailNew->project_name = $_POST['project_name'];
if(isset($_POST['project_name'])){
	$deviceDetailNew->project_name = $_POST['project_name'];
}

$deviceDetailNew->latitude=$_POST['latitude'];
$deviceDetailNew->longitude=$_POST['longitude'];
$deviceDetailNew->city=$_POST['city'];
$deviceDetailNew->place=$_POST['place'];
$deviceDetailNew->country=$_POST['country'];

if(containsWhiteSpace($deviceDetail->user)){
    echo "User Name can't contain white space.";
    return;
}

if(containsWhiteSpace($deviceDetail->device_id)){
    echo "Device ID can't contain white space.";
    return;
}
if(isValidLatitude($deviceDetail->latitude)==false){
    $deviceDetail->latitude="0.0";
}

if(isValidLongitude($deviceDetail->longitude)==false){
    $deviceDetail->longitude="0.0";
}

$deviceDetailNew->date_time=$_POST['date_time'];
if( strtolower($device_id_got) != strtolower($deviceDetail->device_id)) {
    $deviceExist=$adao->getDeviceByDeviceID($deviceDetail->device_id);
    if($deviceExist!=null){
        
        echo $deviceExist->device_id. " Exists";
        return;
    }
}

$count=$_REQUEST['count']+1;

$ar_paramName=array();
$ar_paramType=array();
$ar_paramUnit=array();
$ar_paramPosition=array();

for($i=1;	$i<=$count;	$i++){
	if(strlen($_REQUEST['param_name'.$i])<=0 ||  strlen($_REQUEST['param_type'.$i])<=0
			||  strlen($_REQUEST['unit'.$i])<=0  || strlen($_REQUEST['position'.$i])<=0  ){
		echo "Invalid Modem Params.";
		return ;
	}
	$ar_paramName[]=$_REQUEST['param_name'.$i];
	$ar_paramType[]=$_REQUEST['param_type'.$i];
	$ar_paramUnit[]=$_REQUEST['unit'.$i];
	if(is_numeric($_REQUEST['position'.$i])){
		$ar_paramPosition[]=$_REQUEST['position'.$i];
	}else{
		echo "invalid position for param ='".$_REQUEST['param_name'.$i]."'";
		return;
	}
	
}

$result=$adao->updateDevice($deviceDetailNew,$device_id_got);
if($result) {
    echo "Device updated.";
    
    // Handle live data parameters (modem_params)
    $ar_paramName = $_POST['paramName'];
    $ar_paramType = $_POST['paramType'];
    $ar_paramUnit = $_POST['paramUnit'];
    $ar_paramPosition = $_POST['paramPosition'];
    
    // Delete existing modem parameters
    $adao->deleteModemParams($device_id_got);
    
    // Add new modem parameters
    for($i = 0; $i < count($ar_paramName); $i++) {
        if(!empty($ar_paramName[$i]) && !empty($ar_paramType[$i])) {
            $adao->addModemParams(
                $ar_paramName[$i],
                $ar_paramType[$i],
                $ar_paramUnit[$i],
                $ar_paramPosition[$i],
                $device_id_got
            );
        }
    }
    
    // Note: Database parameters (logparam) are handled separately in do_save_logparams.php
    // No need to handle them here to prevent interference
} else {
    echo "Error while updating device.";
}

