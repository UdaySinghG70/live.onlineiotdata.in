<?php
class AdminDao{
    
    function createUser($user){
        //$user= new UserEntity();
        include_once"../model/querymanager.php";
        $qry="insert into user (user_name,password,department_name,email_id,city,pincode,address,mobile,date_time,country)"
            ."values('".$user->user_name."', '".$user->password."', '".$user->department_name."', '".$user->email_id."', '".$user->city."', "
            ."'".$user->pincode."', '".$user->address."', '".$user->mobile."','".$user->date_time."','".$user->country."' )";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    function getRechargeHistoryByDeviceId($device_id){
        include_once"querymanager.php";
        
        $qry="select id,device_id,start_date,end_date,no_of_days from recharge  where device_id='".$device_id."' order by start_date asc";
        
        $rows=QueryManager::getMultipleRow($qry);
        
        if(isset($rows) && mysqli_num_rows($rows)>0){
            
            $ardata=array();
            include_once 'RechargeEntity.php';
            while($row=mysqli_fetch_row($rows)){
                //print_r($row);
                $data=new RechargeEntity();
                $data->id=$row[0];
                $data->device_id=$row[1];
                $data->start_date=$row[2];
                $data->end_date=$row[3];
                $data->no_of_days=$row[4];
                
                $ardata[]=$data;
            }
            return $ardata;
            
        }else{
            return null;
        }
    }
	
    function getAllUsers(){
        include_once"querymanager.php";
        
        $qry="select id,user_name,password, department_name,email_id,city,pincode,address,mobile,date_time,country from user order by user_name asc";
            
            $rows=QueryManager::getMultipleRow($qry);
            
            if(isset($rows) && mysqli_num_rows($rows)>0){
                
                $ardata=array();
                include_once 'UserEntity.php';
                while($row=mysqli_fetch_row($rows)){
                    //print_r($row);
                    $data=new UserEntity();
                    $data->id=$row[0];
                    $data->user_name=$row[1];
                    $data->password=$row[2];
                    $data->department_name=$row[3];
                    $data->email_id=$row[4];
                    $data->city=$row[5];
                    $data->pincode=$row[6];
                    $data->address=$row[7];
                    $data->mobile=$row[8];
                    $data->date_time=$row[9];
                    $data->country=$row[10];
                    
                    $ardata[]=$data;
                }
                return $ardata;
                
            }else{
                return null;
            }
    }
	
	function getUsersWithLimit($starttingRecord, $recordsToDisplay){
    	
    	include_once"querymanager.php";
    	
    	$qry="select id,user_name,password, department_name,email_id,city,pincode,address,mobile,date_time,country from user order by user_name asc limit ".$starttingRecord.",".$recordsToDisplay."";
    	
    	$rows=QueryManager::getMultipleRow($qry);
    	
    	if(isset($rows) && mysqli_num_rows($rows)>0){
    	
    		$ardata=array();
    		include_once 'UserEntity.php';
    		while($row=mysqli_fetch_row($rows)){
    			//print_r($row);
    			$data=new UserEntity();
    			$data->id=$row[0];
    			$data->user_name=$row[1];
    			$data->password=$row[2];
    			$data->department_name=$row[3];
    			$data->email_id=$row[4];
    			$data->city=$row[5];
    			$data->pincode=$row[6];
    			$data->address=$row[7];
    			$data->mobile=$row[8];
    			$data->date_time=$row[9];
    			$data->country=$row[10];
    	
    			$ardata[]=$data;
    		}
    		return $ardata;
    	
    	}else{
    		return null;
    	}
    }
    
    function getUserCount(){
    	include_once 'querymanager.php';
    	$str = "";
    	$qry="select count(*) from user ";
    	$row=QueryManager::getonerow($qry);
    	if(isset($row)){
    		return $row[0];
    	}else{
    		return 0;
    	}
    }
    
    function getDeviceByDeviceID($device_id){
        include_once"querymanager.php";
        $qry = "select id,device_id,imei_nr,user,latitude,longitude,place,city,country,address,"
                ."date_time,timezone_minute,mobile_no,project_id,project_name,location_id from devices where device_id='".$device_id."'";
        $row=QueryManager::getonerow($qry);
        if(isset($row) ){
            $ardata=array();
            include_once 'DeviceEntity.php';
            
            $obj=new DeviceEntity();
                
            $obj->id=$row[0];
            $obj->device_id=$row[1];
            $obj->imei_nr=$row[2];
            $obj->user=$row[3];
            $obj->latitude=$row[4];
            $obj->longitude=$row[5];
            $obj->place=$row[6];
            $obj->city=$row[7];
            $obj->country=$row[8];
            $obj->address=$row[9];
            $obj->date_time=$row[10];
            $obj->timezone_minute=$row[11];
            $obj->mobile_no=$row[12];
            
            $obj->project_id=$row[13];
            $obj->project_name=$row[14];
            $obj->location_id=$row[15];
            
            return $obj;
            
        }else{
            return null;
        }
    }
    
	
	function saveDeviceId($device_detail){
        include_once"querymanager.php";
        //$device_detail=new DeviceEntity();
        $qry = "insert into devices (device_id,imei_nr,user,latitude,longitude,place,city,country,address,"
            ."date_time,timezone_minute,mobile_no, project_id, project_name, location_id ) values('".$device_detail->device_id."',".
            " '".$device_detail->imei_nr."', '".$device_detail->user."', '".$device_detail->latitude."', 
            '".$device_detail->longitude."', '".$device_detail->place."', '".$device_detail->city."', 
            '".$device_detail->country."', '','".$device_detail->date_time."', '".$device_detail->timezone_minute."'
            , '".$device_detail->mobile_no."', '".$device_detail->project_id."', '".$device_detail->project_name."', '".$device_detail->location_id."'   ) ";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    function updateDevice($deviceDetailNew, $device_id_got) {
        include_once "querymanager.php";
        
        // Get database connection for escaping
        $mysqli = QueryManager::getSqlConnection();
        
        // Escape strings to prevent SQL injection
        $new_device_id = mysqli_real_escape_string($mysqli, $deviceDetailNew->device_id);
        $old_device_id = mysqli_real_escape_string($mysqli, $device_id_got);
        
        // Start transaction
        $mysqli->begin_transaction();
        
        try {
            // Update devices table
            $qry = "UPDATE devices SET 
                device_id='$new_device_id', 
                imei_nr='".mysqli_real_escape_string($mysqli, $deviceDetailNew->imei_nr)."', 
                user='".mysqli_real_escape_string($mysqli, $deviceDetailNew->user)."', 
                latitude='".mysqli_real_escape_string($mysqli, $deviceDetailNew->latitude)."', 
                longitude='".mysqli_real_escape_string($mysqli, $deviceDetailNew->longitude)."', 
                place='".mysqli_real_escape_string($mysqli, $deviceDetailNew->place)."', 
                city='".mysqli_real_escape_string($mysqli, $deviceDetailNew->city)."', 
                country='".mysqli_real_escape_string($mysqli, $deviceDetailNew->country)."', 
                address='".mysqli_real_escape_string($mysqli, $deviceDetailNew->address)."', 
                date_time='".mysqli_real_escape_string($mysqli, $deviceDetailNew->date_time)."', 
                timezone_minute='".mysqli_real_escape_string($mysqli, $deviceDetailNew->timezone_minute)."', 
                mobile_no='".mysqli_real_escape_string($mysqli, $deviceDetailNew->mobile_no)."', 
                project_id='".mysqli_real_escape_string($mysqli, $deviceDetailNew->project_id)."', 
                project_name='".mysqli_real_escape_string($mysqli, $deviceDetailNew->project_name)."', 
                location_id='".mysqli_real_escape_string($mysqli, $deviceDetailNew->location_id)."' 
                WHERE device_id='$old_device_id'";
                
            if (!$mysqli->query($qry)) {
                throw new Exception("Error updating devices table: " . $mysqli->error);
            }
            
            // If device ID has changed, update all related tables
            if ($new_device_id !== $old_device_id) {
                // Update modem_params table
                $qry = "UPDATE modem_params SET device_id='$new_device_id' WHERE device_id='$old_device_id'";
                if (!$mysqli->query($qry)) {
                    throw new Exception("Error updating modem_params table: " . $mysqli->error);
                }
                
                // Update logparam table
                $qry = "UPDATE logparam SET device_id='$new_device_id' WHERE device_id='$old_device_id'";
                if (!$mysqli->query($qry)) {
                    throw new Exception("Error updating logparam table: " . $mysqli->error);
                }
                
                // Update received table
                $qry = "UPDATE received SET device_id='$new_device_id' WHERE device_id='$old_device_id'";
                if (!$mysqli->query($qry)) {
                    throw new Exception("Error updating received table: " . $mysqli->error);
                }
                
                // Update recharge table
                $qry = "UPDATE recharge SET device_id='$new_device_id' WHERE device_id='$old_device_id'";
                if (!$mysqli->query($qry)) {
                    throw new Exception("Error updating recharge table: " . $mysqli->error);
                }
                
                // Update logdata table
                $qry = "UPDATE logdata SET device_id='$new_device_id' WHERE device_id='$old_device_id'";
                if (!$mysqli->query($qry)) {
                    throw new Exception("Error updating logdata table: " . $mysqli->error);
                }
            }
            
            // Commit transaction
            $mysqli->commit();
                return true;
            
        } catch (Exception $e) {
            // Rollback transaction on error
            $mysqli->rollback();
            error_log("Error in updateDevice: " . $e->getMessage());
                return false;
            }
    }
    
    function updateUser($user_detail_row,$user_name_old){
        include_once"querymanager.php";
        //$user_detail_row=new UserEntity();
        $qry= "update user set "
        ."user_name='".$user_detail_row->user_name."', "
            ."password='".$user_detail_row->password."', "
        ."department_name='".$user_detail_row->department_name."', "
        ."email_id='".$user_detail_row->email_id."', "
        ."city='".$user_detail_row->city."', "
        ."pincode='".$user_detail_row->pincode."', "
        ."address='".$user_detail_row->address."', "
        ."mobile='".$user_detail_row->mobile."', "
        ."date_time='".$user_detail_row->date_time."', "
        ."country='".$user_detail_row->country."' where user_name='".$user_name_old."'";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    function updateUserForDevices($user_name_new,$user_name_old){
        include_once "querymanager.php";
        $qry = "update devices set user='".$user_name_new."'  where user='$user_name_old' ";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
	function deleteRechargeByRechargeId($id){
    	include_once "querymanager.php";
    	$qry = "delete from recharge where id=".$id;
    	$result=QueryManager::executeQuerySqli($qry);
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }
	
    function rechargeDevice($device_id,$start_date,$end_date){
        include_once "querymanager.php";
        $qry = "insert into recharge (device_id,start_date,end_date,no_of_days)" 
                ."values('".$device_id."','".$start_date."','".$end_date."',0)";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    function rechargeDeviceData($device_id, $start_date, $end_date,$recharrge_status){
        include_once "querymanager.php";
         $qry = "update received set recharge_status='".$recharrge_status."' "
                ."where device_id='".$device_id."' and  date>='".$start_date."' and date<='".$end_date."' and recharge_status!='".$recharrge_status."'";
         //         $qry = "update data_received set recharge_status='".$recharrge_status."' "
//         ."where device_id='".$device_id."' and date_time>='".$start_date." 00:00:00' and date_time<='".$end_date." 23:59:59' and recharge_status!='".$recharrge_status."'";
        
            $result=QueryManager::executeQuerySqli($qry);
            if($result){
                return true;
            }else{
                return false;
            }
    }
    function getRechargeHistoryByRechargeId($recharge_id){
        include_once"querymanager.php";
        $qry = "select id,device_id,start_date,end_date,no_of_days from recharge where id='".$recharge_id."'";
            $row=QueryManager::getonerow($qry);
            if(isset($row) ){
                $ardata=array();
                include_once 'RechargeEntity.php';
                
                $obj=new RechargeEntity();
                
                $obj->id=$row[0];
                $obj->device_id=$row[1];
                $obj->start_date=$row[2];
                $obj->end_date=$row[3];
                $obj->no_of_days=$row[4];
                
                return $obj;
                
            }else{
                return null;
            }
    }
    
    function updateRecharge($recharge_id,$start_date,$end_date){
        include_once"querymanager.php";
        $qry = "update recharge set start_date='".$start_date."',end_date='".$end_date."' where id='".$recharge_id."'";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
	function getDeviceParamsForAdmin($device_id){
		include_once 'querymanager.php';
    	$qry="select id,param_name,param_type, unit, graph, position, device_id from modem_params where device_id='$device_id' order by position ASC";
    	$rows=QueryManager::getMultipleRow($qry);
    	
    	if(isset($rows) && mysqli_num_rows($rows)>0){
    	
    		$ardata=array();
    		include_once '../model/DeviceParamsEntity.php';
    		$ct=-1;
    		//include_once dirname(__FILE__).'\DeviceParamsEntity.php';
    		while($row=mysqli_fetch_row($rows)){
    			//print_r($row);
    			$data=new DeviceParamsEntity();
    			$data->id=$row[0];
    			$data->param_name=$row[1];
    			$data->param_type=$row[2];
    			$data->unit=$row[3];
    			$data->graph=$row[4];
    			$data->position=$row[5];
    			$data->device_id=$row[6];
    			
    			$ardata[++$ct]=$data;
    		}
    		return $ardata;
    	
    	}else{
    		return null;
    	}
	}
    
    function getDeviceParams($device_id){
    	include_once 'querymanager.php';
    	$qry="select id,param_name,param_type, unit, graph, position, device_id from modem_params where device_id='$device_id' order by position ASC";
    	$rows=QueryManager::getMultipleRow($qry);
    	
    	if(isset($rows) && mysqli_num_rows($rows)>0){
    	
    		$ardata=array();
    		include_once 'model/DeviceParamsEntity.php';
    		$ct=-1;
    		//include_once dirname(__FILE__).'\DeviceParamsEntity.php';
    		while($row=mysqli_fetch_row($rows)){
    			//print_r($row);
    			$data=new DeviceParamsEntity();
    			$data->id=$row[0];
    			$data->param_name=$row[1];
    			$data->param_type=$row[2];
    			$data->unit=$row[3];
    			$data->graph=$row[4];
    			$data->position=$row[5];
    			$data->device_id=$row[6];
    			
    			$ardata[++$ct]=$data;
    		}
    		return $ardata;
    	
    	}else{
    		return null;
    	}
    }
    
    function addModemParams($paramName,$paramType,$paramUnit,$paramPosition,$device_id){
    	include_once 'querymanager.php';
    	
    	// Validate and sanitize the position parameter
    	$position = intval($paramPosition); // Convert to integer
    	if($position <= 0) {
    	    $position = 1; // Default to 1 if invalid
    	}
    	
    	// Escape strings to prevent SQL injection
    	$mysqli = QueryManager::getSqlConnection();
    	$paramName = mysqli_real_escape_string($mysqli, $paramName);
    	$paramType = mysqli_real_escape_string($mysqli, $paramType);
    	$paramUnit = mysqli_real_escape_string($mysqli, $paramUnit);
    	$device_id = mysqli_real_escape_string($mysqli, $device_id);
    	
    	$qry = "INSERT INTO modem_params(param_name, param_type, unit, position, device_id) "
    			."VALUES('$paramName', '$paramType', '$paramUnit', $position, '$device_id')";

    	$result = QueryManager::executeQuerySqli($qry);
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }
    function deleteModemParams($device_id){
    	include_once 'querymanager.php';
    	$qry = "delete from modem_params where device_id='".$device_id."'";
    	
    	$result=QueryManager::executeQuerySqli($qry);
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }
	
	function getRawDataCountbyDate($device_id,$startdate, $enddate, $recharge_status){
    	include_once 'querymanager.php';
    	$str = "";
    	if($recharge_status!="all"){
    		$str = " and recharge_status='".$recharge_status."'";
    	}
    	$qry="select count(*) from received where device_id='".$device_id."' and date>='".$startdate."' and date<='".$enddate."'  ".$str;
    	$row=QueryManager::getonerow($qry);
    	if(isset($row)){
    		return $row[0];
    	}else{
    		return 0;
    	}
    }
    function getRawDataByDate($device_id, $startdate, $enddate, $recharge_status,$starttingRecord,$recordsToDisplay){
	    include_once 'querymanager.php';
	  	$str = "";
	    if($recharge_status!="all"){
	    	$str = " and recharge_status='".$recharge_status."'";
	    }
	  	$qry = "select id, device_id, data, imei_nr, date, time, recharge_status from received "
	  		." where device_id='$device_id' and date>='".$startdate."' and date<='".$enddate."' " .$str."  "
	  		." order by date,time asc limit ".$starttingRecord.",".$recordsToDisplay."";
	  	$rows=QueryManager::getMultipleRow($qry);
	  	if(isset($rows) && mysqli_num_rows($rows)>0){
	  		$ardata=array();
	  		include_once 'ReceivedEntity.php';
	  	
	  		while($row=mysqli_fetch_row($rows)){
	  			$obj=new ReceivedEntity();
	  				
	  			$obj->id=$row[0];
	  			$obj->device_id=$row[1];
	  			$obj->data=$row[2];
	  			$obj->imei_nr=$row[3];
	  			$obj->date=$row[4];
	  			$obj->time=$row[5];
	  			$obj->recharge_status=$row[6];
	  				
	  			$ardata[]=$obj;
	  		}
	  		return $ardata;
	  	}else{
	  		return null;
	  	}
    }
	
	
    function getLastBackup($tablels){
    	include_once 'querymanager.php';
    	$qry = "select id, file_name, start_date, end_date, backup_date, schedule, tables from backup where tables='$tablels' order by backup_date DESC limit 0,1 ";
    	$rows=QueryManager::getMultipleRow($qry);
    	if(isset($rows) && mysqli_num_rows($rows)>0){
    		$ardata=array();
    		include_once 'BackupEntity.php';
    	
    		while($row=mysqli_fetch_row($rows)){
    			$obj=new BackupEntity();
    				
    			$obj->id=$row[0];
    			$obj->file_name=$row[1];
    			$obj->start_date=$row[2];
    			$obj->end_date=$row[3];
    			$obj->backup_date=$row[4];
    			
    			$obj->schedule=$row[5];
    			$obj->tables=$row[6];
    			return $obj;	
    			//$ardata[]=$obj;
    		}
    		return null;
    	}else{
    		return null;
    	}
    }
    function getBackups($start, $limit){
    	include_once 'querymanager.php';
    	$qry = "select id, file_name, start_date, end_date, backup_date, schedule, tables from backup order by backup_date DESC Limit $start, $limit";
    	$rows=QueryManager::getMultipleRow($qry);
    	if(isset($rows) && mysqli_num_rows($rows)>0){
    		$ardata=array();
    		include_once 'BackupEntity.php';
    		 
    		while($row=mysqli_fetch_row($rows)){
    			$obj=new BackupEntity();
    
    			$obj->id=$row[0];
    			$obj->file_name=$row[1];
    			$obj->start_date=$row[2];
    			$obj->end_date=$row[3];
    			$obj->backup_date=$row[4];
    			 
    			$obj->schedule=$row[5];
    			$obj->tables=$row[6];
    			//return $obj;
    			$ardata[]=$obj;
    		}
    		return $ardata;
    	}else{
    		return null;
    	}
    }
    
    function getBackupsCount(){
    	include_once 'querymanager.php';
    	$qry = "select count(*) from backup";
    	$row=QueryManager::getonerow($qry);
    	
    	if(isset($row)){
    		return $row[0];
    	}else{
    		return 0;
    	}
    }
    
    function saveBackupFile($filename, $startdate, $enddate, $table, $schedule){
    	include_once"querymanager.php";
        $qry="insert into backup (file_name, start_date, end_date, backup_date, schedule, tables)"
            ."values('".$filename."', '".$startdate."', '".$enddate."', '".date('Y-m-d')."', '".$schedule."', '".$table."' )";
        $result=QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
	function deleteBackUp($fileName){
    	include_once"querymanager.php";
    	$qry="delete from backup where file_name='".$fileName."'";
    	$result=QueryManager::executeQuerySqli($qry);
    	if($result){
    		return true;
    	}else{
    		return false;
    	}
    }

    function deleteBackupsByPattern($pattern) {
        include_once "querymanager.php";
        $qry = "DELETE FROM backup WHERE file_name LIKE '%" . $pattern . "%'";
        $result = QueryManager::executeQuerySqli($qry);
        if($result){
            return true;
        }else{
            return false;
        }
    }
	
	function deleteUserId($username){
		include_once 'querymanager.php';
		$qry="delete from user where user_name='".$username."'";
		$result=QueryManager::executeQuerySqli($qry);
		if($result){
			return true;
		}else{
			return false;
		}
    	
    }
	
	function deleteDeviceId($device){
		include_once 'querymanager.php';    	
		
		try {
			$mysqli = QueryManager::getSqlConnection();
			if($mysqli==null){
				return false;
			}
			//echo "hip";
			
		    // Begin transaction
		    //$mysqli->begin_transaction();
			//return false;
		
			$query2 = "DELETE FROM recharge WHERE device_id='".$device."'";
		    if (!$mysqli->query($query2)) {
		    	throw new Exception("Error in second query: " . $mysqli->error);
		    }
		
			// Second delete query
		    $query2 = "DELETE FROM received WHERE device_id='".$device."'";
		    if (!$mysqli->query($query2)) {
		        throw new Exception("Error in second query: " . $mysqli->error);
		    }
		    
		    // First delete query
		    $query1 = "DELETE FROM devices WHERE device_id='".$device."'";
		    if (!$mysqli->query($query1)) {
		        throw new Exception("Error in first query: " . $mysqli->error);
		    }
		
		    
		    
		    // Commit transaction
		    //$mysqli->commit();
		    //echo "Both queries executed successfully!";
		} catch (Exception $e) {
		    // Rollback transaction
		    //$mysqli->rollback();
		    echo "Transaction failed: " . $e->getMessage();
		    $mysqli->close();
		    return false;
		}
		
		// Close connection
		$mysqli->close();
		return true;
    }
    
    function addLogParam($param_name, $param_type, $unit, $position, $device_id) {
        include_once 'querymanager.php';
        
        // Get database connection for escaping
        $mysqli = QueryManager::getSqlConnection();
        
        // Escape strings to prevent SQL injection
        $param_name = mysqli_real_escape_string($mysqli, $param_name);
        $param_type = mysqli_real_escape_string($mysqli, $param_type);
        $unit = mysqli_real_escape_string($mysqli, $unit);
        $position = intval($position); // Convert to integer
        $device_id = mysqli_real_escape_string($mysqli, $device_id);
        
        $qry = "INSERT INTO logparam(param_name, param_type, unit, position, device_id) "
               ."VALUES('$param_name', '$param_type', '$unit', $position, '$device_id')";
        
        return QueryManager::executeQuerySqli($qry);
    }

    function deleteLogParams($device_id) {
        include_once 'querymanager.php';
        
        // Get database connection for escaping
        $mysqli = QueryManager::getSqlConnection();
        $device_id = mysqli_real_escape_string($mysqli, $device_id);
        
        $qry = "DELETE FROM logparam WHERE device_id='$device_id'";
        return QueryManager::executeQuerySqli($qry);
    }

    function getLogParamsForAdmin($device_id) {
        include_once 'querymanager.php';
        
        // Get database connection for escaping
        $mysqli = QueryManager::getSqlConnection();
        $device_id = mysqli_real_escape_string($mysqli, $device_id);
        
        $qry = "SELECT id, param_name, param_type, unit, position FROM logparam WHERE device_id='$device_id' ORDER BY position ASC";
        $rows = QueryManager::getMultipleRow($qry);
        
        if(isset($rows) && mysqli_num_rows($rows) > 0) {
            $ardata = array();
            $ct = -1;
            while($row = mysqli_fetch_row($rows)) {
                $data = new stdClass();
                $data->id = $row[0];
                $data->param_name = $row[1];
                $data->param_type = $row[2];
                $data->unit = $row[3];
                $data->position = $row[4];
                $ardata[++$ct] = $data;
            }
            return $ardata;
        }
        return null;
    }

    function getTotalDevices() {
        include_once "querymanager.php";
        $qry = "SELECT COUNT(*) as total FROM devices";
        $row = QueryManager::getonerow($qry);
        if($row) {
            return $row[0];
        }
        return 0;
    }

    function getTotalUsers() {
        include_once "querymanager.php";
        $qry = "SELECT COUNT(*) as total FROM user";
        $row = QueryManager::getonerow($qry);
        if($row) {
            return $row[0];
        }
        return 0;
    }

    function getLastBackupDetails() {
        include_once "querymanager.php";
        $qry = "SELECT backup_date, schedule, tables FROM backup ORDER BY backup_date DESC, id DESC LIMIT 1";
        $row = QueryManager::getonerow($qry);
        if($row) {
            return [
                'date' => $row[0],
                'schedule' => $row[1],
                'tables' => $row[2]
            ];
        }
        return null;
    }
}