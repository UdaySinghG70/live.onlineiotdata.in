<?php
session_start();
if(isset($_SESSION['user_name'])==false){
    header('Location: login.php');
    return;
}

include_once 'model/logindao.php';
$ldao=new LoginDao();
include_once 'model/datadao.php';
$ddao=new DataDao();

$user=$ldao->getUserByUserName($_SESSION['user_name']);

if($user==null){
    header('Location: login.php');
    return;
}

if(isset($_REQUEST['old_pass'])==false || isset($_REQUEST['new_pass'])==false 
    || isset($_REQUEST['c_new_pass'])==false){
    
        echo "Invalid Input";
        return ;
}
$old_pass=$_REQUEST['old_pass'];
$new_pass=$_REQUEST['new_pass'];
$c_new_pass=$_REQUEST['c_new_pass'];

if(strtolower($user->password) != strtolower($old_pass)){
    echo "Old password is incorrect.";
    return;
}

if(strlen($new_pass)<=0){
    echo "Invalid Input";
    return;
}

if($new_pass!=$c_new_pass){
    echo "Password dosen't match.";
    return;
}

$result = $ldao->updateUserPassword($user->user_name,$new_pass);
if($result){
    echo "done";
}else{
    echo "error";
}







