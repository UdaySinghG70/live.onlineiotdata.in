<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
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

if(isset($_POST['username'])==false){
    echo "user name ";
    header('Location: users.php');
    return;
}
$username=$_POST['username'];

include_once '../model/datadao.php';
$ddao=new Datadao();

$deviceArr=$ddao->getDeviceByUserName($username);
if($deviceArr==null){
    echo "No device found";
    
    return;
}
?>

<div >
<table style="text-align: center;width: 100%;" class="frm_table">
<tbody>
<?php 
    
        echo "<tr>";
        echo "<td>Device ID:</td>";
        echo "<td> <select name='device_id' style='padding:5px;'>";
        for($i=0; $i<count($deviceArr); $i++){
        	echo "<option value='".$deviceArr[$i]->device_id."'>".$deviceArr[$i]->device_id."</option>";
        }
        echo "</select></td>";
        
        echo "<td>Start Date</td>";
        echo "<td> <input type='text' name='start_date' class='input_txt donttype' placeholder='Start Date' autocomplete='off'/> </td>";
        echo "<td>End Date</td>";
        echo "<td> <input type='text' name='end_date' class='input_txt donttype' placeholder='End Date' autocomplete='off'/> </td>";
        echo "<td><input type='button' value='Search' class='btn' name='get_data'/> </td>";
        echo "</tr>";
    
?>
	
</tbody>
</table>
<div class="msg_task" style="float: right;">
&nbsp;
</div>
</div>


<div class="received_data" style="margin-top: 10px;">
&nbsp;
</div>
<script type="text/javascript">
$( function() {
	 
	//$("input[name='start_date']").datepicker({
		//dateFormat: 'yy-mm-dd',
	//});
	//$("input[name='end_date']").datepicker({
		//dateFormat: 'yy-mm-dd',
	//});
	$("input[name='start_date']").datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' , minDate: new Date('2018-01-01')});
	$("input[name='end_date']").datepicker({ changeMonth: true, changeYear: true, dateFormat: 'yy-mm-dd' , minDate: new Date('2018-01-01')});
	
});
</script>
