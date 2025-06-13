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

include_once 'model/admindao.php';
$adao=new AdminDao();

$user=$ldao->getUserByUserName($_SESSION['user_name']);

if($user==null){
    header('Location: login.php');
    return;
}

if(isset($_REQUEST['device_id'])==false){
    echo "no device id";
    return ;
}
$device_id=$_REQUEST['device_id'];
$rechargeArr=$adao->getRechargeHistoryByDeviceId($device_id);
if($rechargeArr == null){
    echo "no recharge history found";
    return ;
}

if(count($rechargeArr) <= 0){
    echo "no recharge history found";
    return ;
}
echo "Device ID: ".$device_id;
?>

<div style="clear: both;"></div>
	<div id="tableDiv_Arrays" class="tableDiv" style="overflow-x:auto;">
		<table id="Open_Text_Arrays" class="FixedTables" style="text-align: center;width: 100%;">
			<thead>
				<tr>
					<th>Recharge Date</th>
					<th>End Date</th>
					<th>No of days</th>
				</tr>
			</thead>
			<tbody>
				<?php 
				for($i=0; $i<count($rechargeArr); $i++){
				    $start_date=strtotime($rechargeArr[$i]->start_date);
				    $end_date=strtotime($rechargeArr[$i]->end_date);
				    $date_diff=$end_date-$start_date;
				    
				    $start_date=date('Y-M-d',$start_date);
				    $end_date=date('Y-M-d',$end_date);
				    
				    echo "<td>".$start_date."</td>";
				    echo "<td>".$end_date."</td>";
				    $days=round($date_diff / (60 * 60 * 24));
				    echo "<td>".$days."</td>";
				}
				?>
			
			</tbody>
		</table>
	</div>


