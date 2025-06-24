<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

include_once '../model/admindao.php';
$adao=new AdminDao();
include_once '../model/logindao.php';
$ldao=new LoginDao();

if(isset($_POST['user_name'])==false || isset($_POST['mobile_no'])==false
    || isset($_POST['password'])==false || isset($_POST['email_id'])==false
    || isset($_POST['city'])==false || isset($_POST['pincode'])==false
    || isset($_POST['address'])==false|| isset($_POST['country'])==false){
        
        echo "Invalid Input";
        return;
}
include_once '../model/functions.php';

$user_name_old=$_POST['user_name_old'];
$userDetail=$ldao->getUserByUserName($user_name_old);
if($userDetail==null){
    echo "No user detail found for user= ".$user_name_old;
    return;
}


$user_name=$_POST['user_name'];

if(strtolower($user_name) != strtolower($user_name_old)){
    $userexist=$ldao->getUserByUserName($user_name);
    if($userexist!=null){
        echo "New User Name allready exist= ".$user_name;
        return;
    }
}

if(containsWhiteSpace($user_name)){
    echo "UserName can't contain white space";
    return;
}

if(strlen($user_name)<1){
    echo "Minimum User name length must be 2 character";
    return ;
}
$mobile_no=$_POST['mobile_no'];

$password=$_POST['password'];
if(strlen($password)<=1){
    echo "Minimum Password length must be 2 character";
}
$email_id=$_POST['email_id'];
$city=$_POST['city'];
$pincode=$_POST['pincode'];
$address=$_POST['address'];
$country=$_POST['country'];

include_once '../model/UserEntity.php';
$user_detail_row=new UserEntity();

$user_detail_row->user_name=$user_name;
$user_detail_row->mobile=$mobile_no;
$user_detail_row->password=$password;
$user_detail_row->department_name="";
if(isset($_POST['department_name'])){
	$user_detail_row->department_name=$_POST['department_name'];
}

$user_detail_row->email_id=$email_id;
$user_detail_row->city=$city;
$user_detail_row->pincode=$pincode;
$user_detail_row->address=$address;
$user_detail_row->country=$country;
$user_detail_row->date_time = date('Y-m-d H:i:s');

$result=$adao->updateUser($user_detail_row,$user_name_old);
if($result){
    echo "User updated.";
    if(strtolower($user_name) != strtolower($user_name_old)){
        $result=$adao->updateUserForDevices($user_name,$user_name_old);
    }
}else{
    echo "Error while updating user.";
}
