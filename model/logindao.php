<?php
class LoginDao{
    function getUserByUserID($user_id){
        include_once("querymanager.php");
        $qry="select id,user_name,password,department_name,email_id,city,pincode,address,mobile,date_time,country from user where id='".$user_id."'";
        $rows = QueryManager::getonerow($qry);
        // 		print_r($rows);
        if(isset($rows) ){
            
            include_once 'UserEntity.php';
            $user=new UserEntity();
            $user->id=$rows[0];
            $user->user_name=$rows[1];
            $user->password=$rows[2];
            $user->department_name=$rows[3];
            $user->email_id=$rows[4];
            $user->city=$rows[5];
            $user->pincode=$rows[6];
            $user->address=$rows[7];
            $user->mobile=$rows[8];
            
            $user->date_time=$rows[9];
            $user->country=$rows[10];
            
            return $user;
            
        }else
            return null;
    }
    
    function getUserByUserName($username){
        include_once("querymanager.php");
        $qry="select id,user_name,password,department_name,email_id,city,pincode,address,mobile,date_time,country from user where user_name='".$username."'";
        $rows = QueryManager::getonerow($qry);
        // 		print_r($rows);
        if(isset($rows) ){
            
            include_once 'UserEntity.php';
            $user=new UserEntity();
            $user->id=$rows[0];
            $user->user_name=$rows[1];
            $user->password=$rows[2];
            $user->department_name=$rows[3];
            $user->email_id=$rows[4];
            $user->city=$rows[5];
            $user->pincode=$rows[6];
            $user->address=$rows[7];
            $user->mobile=$rows[8];
            
            $user->date_time=$rows[9];
            $user->country=$rows[10];
            return $user;
            
        }else
            return null;
    }
    
    function checkLogin($username,$password){
        include_once("querymanager.php");
       $qry="select id,user_name,password,department_name, email_id,city,pincode,address,mobile,date_time,country from user where (user_name='".$username."' OR email_id='".$username."' ) and password ='".$password."'";
        $rows = QueryManager::getonerow($qry);
         //print_r($rows);
        if(isset($rows) ){
            
            include_once 'UserEntity.php';
            $user=new UserEntity();
            $user->id=$rows[0];
            $user->user_name=$rows[1];
            $user->password=$rows[2];
            $user->department_name=$rows[3];
            $user->email_id=$rows[4];
            $user->city=$rows[5];
            $user->pincode=$rows[6];
            $user->mobile=$rows[7];
            $user->latitude=$rows[8];
            $user->longitude=$rows[9];
            $user->country=$rows[10];
            
            return $user;
            
        }else
            return null;
    }
	    function updateUserPassword($user, $password){
    		include_once"querymanager.php";
    		//$user_detail_row=new UserEntity();
    		$qry= "update user set password='".$password."' where user_name='".$user."'";
    		$result=QueryManager::executeQuerySqli($qry);
    		if($result){
    			return true;
    		}else{
    			return false;
    		}
    	
    }

    
}