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

if(isset($_POST['username']) && isset($_POST['device'])){
	//echo "hi";
	//return;
	include_once '../model/querymanager.php';
	//echo "hi1";
	//return;
	$mysqliConn = QueryManager::getSqlConnection();


	$username =mysqli_escape_string($mysqliConn, $_POST['username']);
	$device =mysqli_escape_string($mysqliConn, $_POST['device']);

	include_once '../model/admindao.php';
	$adao= new AdminDao();
	//echo "hi3";
	//return;
	if($adao->deleteDeviceId($device)){
		echo "Device deleted successfully";
	}else{
		echo "Error, contact admin";
	}
	
}else{
	echo "invalid access";
}



