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
    echo "no recharge id";
    return;
}

if(isset($_REQUEST['start_date'])==false){
    echo "Start ID?";
    return;
}

if(isset($_REQUEST['end_date'])==false){
    echo "End date?";
    return;
}

$recharge_id=$_REQUEST['recharge_id'];
$start_date=$_REQUEST['start_date'];
$end_date=$_REQUEST['end_date'];

if(strtotime($start_date)> strtotime($end_date)){
    echo "End date should be greater than start date";
    return ;
}

include_once '../model/admindao.php';
$adao=new AdminDao();
$rechargeDetail=$adao->getRechargeHistoryByRechargeId($recharge_id);

$rechargeHistory=$adao->getRechargeHistoryByDeviceId($rechargeDetail->device_id);
for($i=0; $i<count($rechargeHistory); $i++){
    if($rechargeHistory[$i]->id!=$recharge_id){
        if( ( strtotime($rechargeHistory[$i]->start_date) <= strtotime($start_date) &&
            strtotime($rechargeHistory[$i]->end_date) >= strtotime($start_date))
            || (strtotime($rechargeHistory[$i]->start_date) <= strtotime($end_date) &&
                strtotime($rechargeHistory[$i]->end_date) >= strtotime($end_date)) ){
                    echo "Recharge for the selected time span or partial time span has been done.";
                    return ;
        }
    }
    
}
$adao->rechargeDeviceData($rechargeDetail->device_id, $rechargeDetail->start_date, $rechargeDetail->end_date,"n");

$result=$adao->updateRecharge($recharge_id,$start_date,$end_date);
if($result){
    //header('Location: .php');
    echo "done";
    $adao->rechargeDeviceData($rechargeDetail->device_id, $start_date, $end_date,"y");
}else{
    echo "some error";
}



