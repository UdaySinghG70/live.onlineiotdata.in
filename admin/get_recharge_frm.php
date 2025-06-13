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

if(isset($_REQUEST['user_name'])==false){
    echo "no user name given";
    return;
}

include_once '../model/admindao.php';
$adao=new AdminDao();
include_once '../model/datadao.php';
$ddao=new Datadao();

$user_name=$_REQUEST['user_name'];

$deviceArr=$ddao->getDeviceByUserName($user_name);
if($deviceArr==null){
    echo "<div style='clear: both;'>&nbsp;</div>";
    echo "No device added for user: ".$user_name;
    return ;
}
//echo "Welcome Admin";
?>
	
<div style="clear: both;">&nbsp;</div>

<div style="display: block;">
	
	<div style="float:left;">
		<label>Start Date</label>
		<select name="device_id" class="select_txt">
			<?php 
			for($i=0; $i<count($deviceArr); $i++){
			    echo "<option value='".$deviceArr[$i]->device_id."'>".$deviceArr[$i]->device_id."</option>";
			}
			?>
		</select>
	</div>
	
	<div style="clear: both;">&nbsp;</div>
	<div style="float:left;">
		<label>Start Date</label>
		<input type="text" class="input_txt" name="start_date" placeholder="Start Date">
	</div>
	
	<div style="clear: both;">&nbsp;</div>
	<div style="float:left;">
		<label>End Date</label>
		<input type="text" class="input_txt" name="end_date" placeholder="End Date">
	</div>
	<div style="clear: both;">&nbsp;</div>
	
	<input type="button" name="recharge_device" value="Submit" class="btn"/>

</div>


