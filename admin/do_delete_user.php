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

if(isset($_POST['username'])){
	include_once '../model/querymanager.php';
	$mysqliConn = QueryManager::getSqlConnection();
	$username = mysqli_escape_string($mysqliConn, $_POST['username']);
	
	include_once '../model/datadao.php';
	$ddao= new Datadao();
	
	$deviceArr= $ddao->getDeviceByUserName($username);
	if($deviceArr==null || count($deviceArr)<=0 ){
		include_once '../model/admindao.php';
		$adao= new AdminDao();
		if($adao->deleteUserId($username)){
				echo "User deleted successfully";
		}else{
			echo "Error, contact admin.";
		}
		
	}else{
		echo "Delete Devices under this user.";
	}
	
}else{
	echo "Invalid access";
}
