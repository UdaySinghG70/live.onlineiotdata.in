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

if(isset($_REQUEST['device_id'])==false 
    || isset($_REQUEST['start_date'])==false 
    || isset($_REQUEST['end_date'])==false ){
    
        echo "Invalid Input";
        return;
}

$device_id=$_REQUEST['device_id'];
$start_date=$_REQUEST['start_date'];
$end_date=$_REQUEST['end_date'];

include_once '../model/admindao.php';
$adao = new AdminDao();

$device_detail=$adao->getDeviceByDeviceID($device_id);
if($device_detail==null){
    echo "No Device detail found.";
    return;
}

if(strtotime($start_date)> strtotime($end_date)){
    echo "End date should be greater than start date";
    return ;
}

include_once '../model/admindao.php';
$adao=new AdminDao();

$rechargeHistory=$adao->getRechargeHistoryByDeviceId($device_id);
if($rechargeHistory!=null){
    for($i=0; $i<count($rechargeHistory); $i++){
        if( ( strtotime($rechargeHistory[$i]->start_date) <= strtotime($start_date) &&
            strtotime($rechargeHistory[$i]->end_date) >= strtotime($start_date))
            || (strtotime($rechargeHistory[$i]->start_date) <= strtotime($end_date) &&
                strtotime($rechargeHistory[$i]->end_date) >= strtotime($end_date)) ){
                    echo "Recharge for the selected time span or partial time span has been done.";
                    return ;
        }
    }
    
}
$result = $adao->rechargeDevice($device_id,$start_date,$end_date);

if($result){
    echo "Recharge Successfull";
    $adao->rechargeDeviceData($device_id, $start_date, $end_date,"y");
    
}else{
    echo "Error";
}



