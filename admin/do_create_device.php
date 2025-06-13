<?php
session_start();

date_default_timezone_set('Asia/Kolkata');

if($_SESSION['admin_name']==false){
    echo "Invalid Access";
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

if(isset($_POST['user_name'])==false || isset($_POST['mobile_nr'])==false
    || isset($_POST['device_id'])==false || isset($_POST['imei_nr'])==false
    || isset($_POST['timezone'])==false || isset($_POST['latitude'])==false || isset($_POST['longitude'])==false
    || isset($_POST['city'])==false || isset($_POST['place'])==false
    || isset($_POST['country'])==false){
        
        echo "Invalid Input";
        return;
}
include '../model/functions.php';

$username=$_POST['user_name'];
$mobile_nr=$_POST['mobile_nr'];
echo $device_id=$_POST['device_id'];
$imei_nr=$_POST['imei_nr'];
$timezone=$_POST['timezone'];

$latitude=$_POST['latitude'];
$longitude=$_POST['longitude'];

$city=$_POST['city'];
$place=$_POST['place'];
$country=$_POST['country'];

include_once '../model/DeviceEntity.php';
$device_detail=new DeviceEntity();

include_once '../model/admindao.php';
$adao=new AdminDao();

// Include QueryManager for database operations
include_once '../model/querymanager.php';

// Get the database connection using the provided static method
$mysqli_conn = QueryManager::getSqlConnection();

if(containsWhiteSpace($username)){
    echo "User Name can't contain white space.";
    return;
}

if( containsWhiteSpace($device_id) ){
    echo "Device ID can't contain white space.";
    return;
}
if(isValidLatitude($latitude)==false){
    $latitude="0.0";
}

if(isValidLongitude($longitude)==false){
    $longitude="0.0";
}

$deviceExist=$adao->getDeviceByDeviceID($device_id);
if($deviceExist!=null){
    echo "Device Id allready exist.";
    return;
}
    
$device_detail->user=$username;
$device_detail->mobile_no=$mobile_nr;

$device_detail->device_id=$device_id;
$device_detail->imei_nr=$imei_nr;
$device_detail->timezone_minute=$timezone;

$device_detail->project_id="";
if(isset($_POST['project_id'])){
	$device_detail->project_id=$_POST['project_id'];
}

$device_detail->location_id="";
if(isset($_POST['location_id'])){
	$device_detail->location_id=$_POST['location_id'];
}

$device_detail->project_name = $_POST['project_name'];
if(isset($_POST['project_name'])){
	$device_detail->project_name = $_POST['project_name'];
}

$device_detail->latitude=$latitude;
$device_detail->longitude=$longitude;

$device_detail->city=$city;
$device_detail->place=$place;
$device_detail->country=$country;

if(strlen($_POST['date_time'])>4){
    $device_detail->date_time=$_POST['date_time'];
}else{
    $device_detail->date_time=date("Y-m-d H:i:s");
}


//collect modem params values
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
	$ar_paramPosition[]=$_REQUEST['position'.$i];

}




$result=$adao->saveDeviceId($device_detail);



if($result){
    echo " Device ID Created.";
    for($i=0;	$i<$count;	$i++){
    	$adao->addModemParams($ar_paramName[$i],$ar_paramType[$i],$ar_paramUnit[$i],$ar_paramPosition[$i],$device_id);
    }

    // Handle the formatted database parameters
    if(isset($_POST['database_params'])) {
        $dbParamsString = urldecode($_POST['database_params']);
        $current_date = date("Y-m-d");
        $current_time = date("H:i:s");

        // Get the database connection for escaping
        $mysqli_conn = QueryManager::getSqlConnection();

        if ($mysqli_conn) {
            // Escape the parameter string for SQL insertion
            $escapedDbParamsString = mysqli_real_escape_string($mysqli_conn, $dbParamsString);

            // Insert the new record
            $logdata_insert_qry = "INSERT INTO logdata (device_id, date, time, data) VALUES ('$device_id', '$current_date', '$current_time', '$escapedDbParamsString')";
            
            $insert_result = QueryManager::executeQuerySqli($logdata_insert_qry);
            if(!$insert_result) {
                error_log("Failed to insert into logdata table: " . mysqli_error($mysqli_conn));
            }
        } else {
            error_log("Failed to obtain mysqli connection for escaping parameters in do_create_device.php");
        }
    }

}else{
    echo "error";
}
