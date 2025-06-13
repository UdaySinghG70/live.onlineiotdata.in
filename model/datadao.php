<?php
class Datadao{
    
  function getDeviceDetailByDeviceID($device_id){
      include_once"querymanager.php";
      $qry="select id,device_id,imei_nr,user,latitude,longitude, place, city, country,address,date_time,timezone_minute  
            from devices where device_id='".$device_id."'";
      $row=QueryManager::getonerow($qry);
      if(isset($row)){
          include_once"DeviceEntity.php";
          $device=new DeviceEntity();
          
          $device->id=$row[0];
          $device->device_id=$row[1];
          $device->imei_nr=$row[2];
          $device->user=$row[3];
          $device->latitude=$row[4];
          $device->longitude=$row[5];
          $device->place=$row[6];
          $device->city=$row[7];
          $device->country=$row[8];
          $device->address=$row[9];
          $device->date_time=$row[10];
          $device->timezone_minute=$row[11];
          
          return $device;
          
      }else{
          return null;
      }
      
  }
  
  function getDeviceDataByDevicedIdAndUtcTime($device_id,$dt){
      include_once"querymanager.php";
      $qry="select id,device_id,data_type,date_time_utc,date_time,data_status, max_data, min_data,imei_nr   
            from data_received where device_id='".$device_id."' and date_time_utc=$dt";
      
      $row=QueryManager::getonerow($qry);
      if(isset($row)){
          include_once"DeviceData.php";
          $data=new DeviceData();
          
          $data->id=$row[0];
          $data->device_id=$row[1];
          $data->data_type=$row[2];
          $data->data_time_utc=$row[3];
          $data->date_time =$row[4];
          $data->data_status=$row[5];
          $data->max_data=$row[6];
          $data->min_data=$row[7];
          $data->imei_nr=$row[8];
          
          return $data;
          
      }else{
          return null;
      }
  }
  
  function getRainfallTotal($device_id ,$deviceParams, $dt){
  	
  	$data = $this->getSubmitDataFull($device_id, $dt, $dt);
  	$rainfall_index = -1;
  	for($i=0; $i<count($deviceParams); $i++){
  		if(strtolower($deviceParams[$i]->param_name)=="rain" || strtolower($deviceParams[$i]->param_name)=="rainfall"){
  			$rainfall_index=$i;
  		}
  	}
  	$rainfall_total = 0;
	if($data!=null){
		for($i=0;$i<count($data); $i++){
			$row=explode(",", $data[$i]->data);
			if(count($row)>$rainfall_index){
				$rainfall_total+=$row[$rainfall_index];
			}
		}
	}
  	return  $rainfall_total;
  }
  
  function getCurrentData($device_id ){
  	include_once"querymanager.php";
  	$qry="select id,device_id, data, date, time, recharge_status
  	from received where device_id='".$device_id."' order by id desc limit 0,1";
  	 
  	$row=QueryManager::getonerow($qry);
  	if(isset($row)){
  		include_once"ReceivedEntity.php";
  		$data=new ReceivedEntity();
  		$data->id=$row[0];
  		$data->device_id=$row[1];
  		$data->data=$row[2];
  		$data->date=$row[3];
  		$data->time=$row[4];
  		$data->recharge_status=$row[5];
  	
  		return $data;
  		 
  	}else{
  		return null;
  	}
  	 
  }
  
  function getCurrentRechargedData($device_id ){
  	include_once"querymanager.php";
  	$qry="select id,device_id, data, date, time, recharge_status
  	from received where device_id='".$device_id."' and recharge_status='y' order by id desc limit 0,1";
  
  	$row=QueryManager::getonerow($qry);
  	if(isset($row)){
  		include_once"ReceivedEntity.php";
  		$data=new ReceivedEntity();
  		$data->id=$row[0];
  		$data->device_id=$row[1];
  		$data->data=$row[2];
  		$data->date=$row[3];
  		$data->time=$row[4];
  		$data->recharge_status=$row[5];
  		 
  		return $data;
  			
  	}else{
  		return null;
  	}
  
  }
  
  function getDeviceDataByDevicedIdAndData($device_id,$data){
  	include_once"querymanager.php";
  	$qry="select id,device_id, data, date, time, recharge_status
  	from received where device_id='".$device_id."' and data='".$data."'";
  	
  	$row=QueryManager::getonerow($qry);
  	if(isset($row)){
  		include_once"ReceivedEntity.php";
  		$data=new ReceivedEntity();
  		$data->id=$row[0];
  		$data->device_id=$row[1];
  		$data->data=$row[2];
  		$data->imei_nr=$row[3];
  		$data->date=$row[4];
  		$data->time=$row[5];
  		$data->recharge_status=$row[6];
  		
  		return $data;
  	
  	}else{
  		return null;
  	}
  }
  
  function saveData($device_id,$data,$submit_date,$submit_time,$recharge_found){
      include_once"querymanager.php";
      //$data=new DeviceData();
      if($recharge_found){
          $recharge="y";
      }else{
          $recharge="n";
      }
     $qry="INSERT INTO received ( device_id, data, date, time, recharge_status) "
        ."VALUES ('".$device_id."', '".$data."', '".$submit_date."', '".$submit_time."', '".$recharge."')";
      $result=QueryManager::executeQuerySqli($qry);
      if($result){
          return true;
      }else{
          return false;
      }
  }
  function saveData_Old($data){
      include_once"querymanager.php";
      //$data=new DeviceData();
     $qry="INSERT INTO data_received ( device_id, data_type, date_time_utc, date_time, data_status," 
        ."max_data, min_data, imei_nr,instant_data,recharge_status) "
        ."VALUES ('".$data->device_id."', '".$data->data_type."', ".$data->data_time_utc.", 
        '".$data->date_time."', ".$data->data_status.", ".$data->max_data.", ".$data->min_data.","
            ."'".$data->imei_nr."' , ".$data->instant_data.",'y')";
      $result=QueryManager::executeQuerySqli($qry);
      if($result){
          return true;
      }else{
          return false;
      }
  }
  
  function getSubmitDataFull ( $device_id, $startdate, $enddate ){
      include_once"querymanager.php";
      
     $qry="select id, device_id, data, imei_nr, date, time, recharge_status from received "
          ."where device_id='$device_id' and date>='".$startdate."' and date<='".$enddate."' and recharge_status='y' order by date,time asc";
          
      $rows=QueryManager::getMultipleRow($qry);
      if(isset($rows) && mysqli_num_rows($rows)>0){
              
              $ardata=array();
              include_once 'ReceivedEntity.php';
              while($row=mysqli_fetch_row($rows)){
                  $data=new ReceivedEntity();
                  $data->id=$row[0];
                  $data->device_id=$row[1];
                  $data->data=$row[2];
                  $data->imei_nr=$row[3];
                  $data->date=$row[4];
                  $data->time=$row[5];
                  $data->recharge_status=$row[6];
                  
                  $ardata[]=$data;
              }
              return $ardata;
              
      }else{
            return null;
      }
  }
  
  function getDataByDate($device_id, $start_date, $end_date,$starttingRecord,$recordsToDisplay){
  	include_once"querymanager.php";
  	 $qry="select id,device_id, data_type, date_time_utc, date_time, data_status, max_data, min_data, imei_nr,instant_data from data_received "
  			." where date_time>='".$start_date." 00:00:00' and date_time<='".$end_date." 23:59:59' and device_id='".$device_id."' and recharge_status='y' order by date_time asc limit ".$starttingRecord.",".$recordsToDisplay;
  	$rows=QueryManager::getMultipleRow($qry);
  	if(isset($rows) && mysqli_num_rows($rows)>0){
  		
  		$ardata=array();
  		include_once 'DeviceData.php';
  		while($row=mysqli_fetch_row($rows)){
  			$data=new DeviceData();
  			$data->id=$row[0];
  			$data->device_id=$row[1];
  			$data->data_type=$row[2];
  			$data->data_time_utc=$row[3];
  			$data->date_time=$row[4];
  			$data->data_status=$row[5];
  			$data->max_data=$row[6];
  			$data->min_data=$row[7];
  			$data->imei_nr=$row[8];
  			$data->instant_data=$row[9];
  			
  			$ardata[]=$data;
  		}
  		return $ardata; 
  		
  	}else{
  		return null;
  	}
  	
  }
  
  function getDataCountbyDate($device_id,$start_date,$end_date){
  	include_once 'querymanager.php';
  	$qry="select count(*) from data_received where device_id='".$device_id."' and date_time>='".$start_date." 00:00:00' and date_time<='".$end_date." 23:59:59' and recharge_status='y'";
  	$row=QueryManager::getonerow($qry);
  	if(isset($row)){
  		return $row[0];
  	}else{
  		return 0;
  	}
  }
  function getDeviceByUserName($user_name){
  	include_once 'querymanager.php';
  	$qry="select id,device_id,imei_nr,user,latitude,longitude,place,city,country,address,date_time,timezone_minute,mobile_no,project_id,location_id,project_name from devices where user='$user_name'";
  	$rows=QueryManager::getMultipleRow($qry);
  	if(isset($rows) && mysqli_num_rows($rows)>0){
  		$ardata=array();
  		include_once 'DeviceEntity.php';
  		
  		while($row=mysqli_fetch_row($rows)){
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
  			$obj->location_id=$row[14];
  			$obj->project_name=$row[15];
  			
  			$ardata[]=$obj;
  		}
  		return $ardata;
  	}else{
  		return null;
  	}
  	
  }
  
  function getDataByDateTime($device_id, $start_date, $end_date,$recharge_status, $starttingRecord, $recordsToDisplay){
  	include_once 'querymanager.php';
  	$qry = "select id, device_id, data, imei_nr, date, time, recharge_status from received "
  		." where device_id='$device_id' and date>='".$start_date."' and date<='".$end_date."' and recharge_status='".$recharge_status."'  "
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
  function getDataCountbyDateTime($device_id,$start_date,$end_date, $recharge_status){
  	include_once 'querymanager.php';
  	$qry="select count(*) from received where device_id='".$device_id."' and date>='".$start_date."' and date<='".$end_date."' and recharge_status='y'";
  	$row=QueryManager::getonerow($qry);
  	if(isset($row)){
  		return $row[0];
  	}else{
  		return 0;
  	}
  }
  
  function getCount($query, $params) {
      include_once "querymanager.php";
      try {
          $stmt = QueryManager::prepareStatement($query);
          if ($stmt) {
              // For numeric parameters in LIMIT clause
              $types = '';
              foreach ($params as $param) {
                  if (is_int($param)) {
                      $types .= 'i';
                  } else {
                      $types .= 's';
                  }
              }
              
              $stmt->bind_param($types, ...$params);
              $stmt->execute();
              $result = $stmt->get_result();
              if ($row = $result->fetch_assoc()) {
                  $stmt->close();
                  return $row['count'];
              }
              $stmt->close();
          }
      } catch (Exception $e) {
          error_log("Database error in getCount: " . $e->getMessage());
      }
      return 0;
  }

  function getData($query, $params) {
      include_once "querymanager.php";
      try {
          $stmt = QueryManager::prepareStatement($query);
          if ($stmt) {
              // For numeric parameters in LIMIT clause
              $types = '';
              foreach ($params as $param) {
                  if (is_int($param)) {
                      $types .= 'i';
                  } else {
                      $types .= 's';
                  }
              }
              
              $stmt->bind_param($types, ...$params);
              $stmt->execute();
              $result = $stmt->get_result();
              
              $data = array();
              while ($row = $result->fetch_object()) {
                  $data[] = $row;
              }
              $stmt->close();
              return $data;
          }
      } catch (Exception $e) {
          error_log("Database error in getData: " . $e->getMessage());
      }
      return array();
  }
  
  function getReceivedData($device_id, $start_date, $end_date) {
      include_once "querymanager.php";
      include_once "ReceivedEntity.php";
      
      $params = [
          'device_id' => $device_id,
          'start_date' => $start_date,
          'end_date' => $end_date
      ];
      
      $query = "SELECT id, device_id, data, date as submit_date, time as submit_time, recharge_status as recharge_found 
                FROM received 
                WHERE device_id = :device_id 
                AND date >= :start_date 
                AND date <= :end_date
                ORDER BY date DESC, time DESC";
      
      return $this->getData($query, $params);
  }
  
}
?>
