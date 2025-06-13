<?php
session_start();

include_once 'model/querymanager.php';
$connection = QueryManager::getSqlConnection();
if($connection==null){
	echo "Database Error";
	header('Location: login.php?msg=error');
	return ;
}
$username = mysqli_real_escape_string($connection, $_POST['username']);
$password = mysqli_real_escape_string($connection, $_POST['pass']);

//$username = $_POST['username'];
//$password = $_POST['pass'];

if(strlen($username)==0){
    header('Location: login.php?msg=error&user_name='.$username);
    return ;
}if(strlen($password)==0){
    header('Location: login.php?msg=error&user_name='.$username);
    return;
}
 
include_once 'model/logindao.php';
$ldao=new Logindao();
$user=$ldao->checkLogin($username, $password);

if($user==null){
    header('Location: login.php?msg=error&user_name='.$username);
    return ;
}else{
    $_SESSION['user_name']=$user->user_name;
    $_SESSION['email_id']=$user->email_id;
    header('Location: index.php');
    
}