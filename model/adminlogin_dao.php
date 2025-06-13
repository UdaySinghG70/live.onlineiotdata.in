<?php
class AdminLoginDao{
    function checkLogin($username, $password){
        include_once"querymanager.php";
        
        $qry="select id,admin_name,password,email_id  from admin where (admin_name='".$username."' OR email_id='".$username."' ) and password ='".$password."'";
        $rows = QueryManager::getonerow($qry);
        //print_r($rows);
        if(isset($rows) ){
            
            include_once 'AdminEntity.php';
            $user=new AdminEntity();
            $user->id=$rows[0];
            $user->admin_name=$rows[1];
            $user->password=$rows[2];
            $user->email_id=$rows[3];
            
            return $user;
            
        }else
            return null;
    }
    function getAdminByUserName($admin_name){
         
            include_once("querymanager.php");
            $qry="select id,admin_name,password,email_id from admin where admin_name='".$admin_name."'";
            $rows = QueryManager::getonerow($qry);
            // 		print_r($rows);
            if(isset($rows) ){
                
                include_once 'AdminEntity.php';
                $user=new AdminEntity();
                $user->id=$rows[0];
                $user->admin_name=$rows[1];
                $user->password=$rows[2];
                $user->email_id=$rows[3];
                
                
                return $user;
                
            }else
                return null;
        
    }
    
}