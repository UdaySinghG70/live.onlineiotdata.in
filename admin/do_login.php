<?php
session_start();

include_once '../model/querymanager.php';
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
    header('Location: login.php?msg=error&admin_name='.$username);
    return ;
}if(strlen($password)==0){
    header('Location: login.php?msg=error&admin_name='.$username);
    return;
}

include_once '../model/adminlogin_dao.php';
$ldao=new AdminLoginDao();
$user=$ldao->checkLogin($username, $password);

if($user==null){
    header('Location: login.php?msg=error&user_name='.$username);
    return ;
}else{
    $_SESSION['admin_name']=$user->admin_name;
    $_SESSION['admin_email_id']=$user->email_id;
    header('Location: index.php');
    
}