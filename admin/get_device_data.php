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

if(isset($_REQUEST['startdate'])==false || isset($_REQUEST['enddate'])==false || isset($_REQUEST['device_id'])==false ){
	echo "invalid data";
	return;
}

$device_id=$_REQUEST['device_id'];
$startdate=$_REQUEST['startdate'];
$enddate=$_REQUEST['enddate'];

if(strtotime($startdate) > strtotime($enddate)){
	echo "Start date should be less than or equal to end date";
	return ;
}

include_once '../model/admindao.php';
$adao=new AdminDao();

$pg=1;
if(isset($_POST['pg'])){
	$pg=$_POST['pg'];
	if($pg<=0){
	    $pg=1;
	}
}

$recordsToDisplay = 50;

$recordCount=$adao->getRawDataCountbyDate($device_id,$startdate, $enddate,"all");
$linkCount = $recordCount % $recordsToDisplay == 0 ? ( int )( $recordCount / $recordsToDisplay ) : ( int ) ( $recordCount / $recordsToDisplay ) + 1;

$starttingRecord = ($pg-1) * $recordsToDisplay;

$dataArr=$adao->getRawDataByDate($device_id, $startdate, $enddate,"all",$starttingRecord,$recordsToDisplay);

if($pg>$linkCount){
	$pg=$linkCount;
}


if($linkCount - $pg > 10){
	$linkStart=$pg;
}else{
	if($linkCount > 10){
		$linkStart = $linkCount - 10;
	}else {
		$linkStart=1;
	}
}


if($recordCount==0){
	//print_r($dataArr);
	echo "no data found";
	return;
}

//print_r($dataArr);
?>
<div style="padding-top: 10px;">
<?php 
if($pg!=1){
	echo "<a class='nav_ctrl prev_btn'>Prev</a>	";
}else{
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
}
if($pg<$linkCount){
	echo "<a class='nav_ctrl  next_btn'>Next</a>	";	
}
echo "<input type='hidden' value='".$pg."' name='current_page'>";
?>
	<div style="float: right;">
		<input type="number" value="<?php echo $pg;?>" name="go_to_page" class="nav_ctrl" style="max-width: 80px;float: left;" min="0"/>
		<label style="float: left;padding: 4px 10px;padding-left: 0px;">/<?php echo $linkCount;?>&nbsp;</label>
		<a class="nav_ctrl  next_btn btn_goto_page">Go</a>	
		<div style="clear: both;"></div>
	</div>
	</div>
	<div style="clear: both;"></div>
	<div id="tableDiv_Arrays" class="tableDiv" style="overflow-x:auto;">
		<table id="Open_Text_Arrays" class="FixedTables" style="text-align: center;width: 100%;">
			<thead>
				<tr>
					
					<th>Data</th>
					<th>Date</th>
					<th>Time</th>
					<th>Recharge</th>
					
				</tr>
			</thead>
			<tbody>
				<?php 
				for($i=0;$i<count($dataArr);$i++){
					
					echo "<tr>
					
							<td>".$dataArr[$i]->data."</td>"
					       ."<td> ".$dataArr[$i]->date."</td>"
                            ."<td> ".$dataArr[$i]->time."</td>"
							."<td> ".$dataArr[$i]->recharge_status."</td>"
						."</tr>";
					
				}
				?>
				
			</tbody>
		</table>
	</div>
	
	
	<?php 

?>