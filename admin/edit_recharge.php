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

if(isset($_REQUEST['recharge_id'])==false){
    echo "no recharge id";
    return;
}

$recharge_id=$_REQUEST['recharge_id'];

include_once '../model/admindao.php';
$adao = new AdminDao();

$recharge_detail=$adao->getRechargeHistoryByRechargeId($recharge_id);
if($recharge_detail==null){
    echo "No recharge detail found";
    return;
}
?>
<div style="clear: both;">&nbsp;</div>

<div style="display: block;">
<img src="../images/loader.gif" class="loading_file" />
	<form>
		<label>Edit Recharge</label>
		<div style="clear: both;">&nbsp;</div>
    	
		<input type="hidden" name="recharge_id" value="<?php echo $recharge_detail->id;?>">
    	<input type="hidden" name="reload_href" value="<?php echo $_REQUEST['reload_href'];?>">
    	
    	<div style="float:left;">
    		<label>Device ID</label>
    		<label><?php echo $recharge_detail->device_id;?></label> 
    	</div>
    	<div style="clear: both;">&nbsp;</div>
    	<div style="float:left;">
    		<label>Start Date</label>
    		<input type="text" class="input_txt" name="start_date" placeholder="Start Date" value="<?php echo $recharge_detail->start_date; ?>">
    	</div>
    	<div style="clear: both;">&nbsp;</div>
    	
    	<div style="float:left;">
    		<label>End Date</label>
    		<input type="text" class="input_txt" name="end_date" placeholder="End Date" value="<?php echo $recharge_detail->end_date; ?>">
    	</div>
    	<div style="clear: both;">&nbsp;</div>
    	<div class="msg_task_update"style="clear: both;">&nbsp;</div>
    	
    	<input type="button" name="update_recharge" value="Submit" class="btn"/>
    		
	</form>
	
</div>



