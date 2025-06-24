<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Asia/Kolkata');

if($_SESSION['admin_name']==false){
    echo "Invalid Access";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$adao=new AdminLoginDao();

$adminDetails=$adao->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}

if(isset($_POST['user_name'])==false || isset($_POST['mobile_no'])==false 
    || isset($_POST['password'])==false || isset($_POST['email_id'])==false
    || isset($_POST['city'])==false || isset($_POST['pincode'])==false
    || isset($_POST['address'])==false|| isset($_POST['country'])==false){
    
        echo "Invalid Input";
    return;
}
include_once '../model/functions.php';

$user_name=$_POST['user_name'];
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
if(strlen($password)<1){
    echo "Minimum Password length must be 2 character";
}
$email_id=$_POST['email_id'];
$city=$_POST['city'];
$pincode=$_POST['pincode'];
$address=$_POST['address'];
$country=$_POST['country'];


include_once '../model/UserEntity.php';
$user=new UserEntity();

$user->user_name=$user_name;
$user->mobile=$mobile_no;
$user->email_id =$email_id;
$user->department_name ="";
if(isset($_POST['department_name'])){
	$user->department_name = $_POST['department_name'];
}

$user->password=$password;
$user->city=$city;
$user->pincode=$pincode;
$user->address=$address;
$user->country=$country;
$user->date_time=date('Y-m-d H:i:s');


if(isValidEmail($email_id)==false){
    echo "invalid Email ID";
    return;
}

if(isValidMobile($mobile_no)==false){
    echo "invalid Mobile Number";
    return;
}

include_once '../model/admindao.php';
$adao=new AdminDao();

include_once '../model/logindao.php';
$ldao=new LoginDao();

$userDetailsExist=$ldao->getUserByUserName($user_name);
if($userDetailsExist!=null){
    echo "User Allready Exist";
    return;
}

$result=$adao->createUser($user);
if($result){
    echo $user->user_name." User Created";
}else{
    echo "error";
}


//$user->user_name=$user_name;
