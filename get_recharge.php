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

<style>
.tableDiv {
    margin-top: 18px;
    background: #fff;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    box-shadow: none;
    padding: 0;
    overflow-x: auto;
}
.FixedTables {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}
.FixedTables th, .FixedTables td {
    padding: 10px 8px;
    text-align: center;
    border-bottom: 1px solid #f0f0f0;
}
.FixedTables th {
    background: #f5faff;
    color: #333;
    font-weight: 500;
    border-bottom: 1.5px solid #e0e0e0;
}
.FixedTables tr:last-child td {
    border-bottom: none;
}
.FixedTables tr:hover td {
    background: #f0f7fa;
}
@media (max-width: 600px) {
    .FixedTables th, .FixedTables td {
        padding: 7px 4px;
        font-size: 13px;
    }
}
</style>

<div style="clear: both;"></div>
	<div id="tableDiv_Arrays" class="tableDiv" style="overflow-x:auto;">
		<table id="Open_Text_Arrays" class="FixedTables">
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
				    echo "<tr>";
				    echo "<td>".$start_date."</td>";
				    echo "<td>".$end_date."</td>";
				    $days=round($date_diff / (60 * 60 * 24));
				    echo "<td>".$days."</td>";
				    echo "</tr>";
				}
				?>
			</tbody>
		</table>
	</div>


