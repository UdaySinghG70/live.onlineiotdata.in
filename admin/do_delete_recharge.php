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

if(isset($_REQUEST['recharge_id'])==false){
    echo "no recharge id found";
    return;
}

$recharge_id=$_REQUEST['recharge_id'];

include_once '../model/admindao.php';
$adao=new AdminDao();

$recharge_detail=$adao->getRechargeHistoryByRechargeId($recharge_id);

$result=$adao->deleteRechargeByRechargeId($recharge_id);
if($result){
    echo "done";
    $result=$adao->rechargeDeviceData($recharge_detail->device_id,$recharge_detail->start_date,$recharge_detail->end_date,'n');
    
}else{
    echo "error";
}
