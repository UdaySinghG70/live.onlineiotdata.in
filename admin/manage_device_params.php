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



include_once '../model/admindao.php';
$adao=new AdminDao();

$device_id=$_REQUEST['device_id'];

$device_params = $adao->getDeviceParams($device_id);


?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Edit User</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
<link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../vendor/animate/animate.css">
<link rel="stylesheet" type="text/css" href="../vendor/animsition/css/animsition.min.css">
<link rel="stylesheet" type="text/css" href="../css/util.css">
<link rel="stylesheet" type="text/css" href="../css/main.css">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="../css/thickbox.css">
<!--===============================================================================================-->
<style type="text/css">
	.nav_control ul{
		list-style: none;
	}
	.nav_control ul li a{
		padding: 10px 5px;
		
	}
	.nav_control ul li{
		
		margin: 10px 5px;
		float: left;
	}
	.data_container .row,.form_container .row{
	   padding: 10px;
	}
	.form_container textarea{
	   border: 1px solid #ddd;
		min-width: 400px;
	}
	.data_container select, .form_container select{
	   margin: 0px 10px; 
	}
	.data_container label, .form_container label{
	   min-width: 120px;
		
	}
	.input_txt{
		padding: 3px 3px;
		border: 1px solid #b3afaf;
		max-width: 320px;
		float: left;
		margin: 0px 10px;
	}
	 
	.btn{
		padding: 10px 25px;
		float: left;
		margin: 0px 10px;
	}
	.select_txt{
	   padding: 9px 5px;
    	float: left;
		margin: 0px 10px;
		min-width: 100px;
	}
	table {
	   border: 1px solid #ddd; 
	}
	 table tr th{
	   padding: 5px;
		border: 1px solid #ddd;
		text-align: center;
	}
    table tr td{
	   padding: 5px;
		border: 1px solid #ddd;
	} 
	form label{
	   float: left;
		padding: 6px 5px;
	}
	.nav_ctrl{
	    color: #A00;
		padding: 4px 10px;
		background-color:#ddd; 
		cursor: pointer;
	}
input[type=text]:focus, textarea:focus {
  box-shadow: 0 0 5px rgba(81, 203, 238, 1);
/*   padding: 3px 0px 3px 3px; */
/*   margin: 5px 1px 3px 0px; */
  border: 1px solid rgba(81, 203, 238, 1);
}
</style>
<!--===============================================================================================-->
	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
	
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/jquery.fixedtable.js"></script>
	<script src="../js/thickbox.js"></script>
	<script src="../js/verge.js"></script>
	<script src="js/devices.js"></script>
	
	<script type="text/javascript">

        deviceWidth = verge.viewportW();
        deviceHeight = verge.viewportH();
        
        deviceWidth = deviceWidth * 90 / 100;
        deviceHeight = deviceHeight * 80 / 100;
        
        if(deviceWidth>800){
        	deviceWidth=800;
        }
        //alert(deviceWidth+" h="+deviceheight );
    </script>
    
</head>
<body>
<div class="limiter">
	<div class="main_body" style="">
		<?php include_once 'admin_header.php';?>
		
		<div style="clear: both">&nbsp;</div>
	
		
		<div class="data_container" style="justify-content:center;width: 100%;padding-left: 8%;padding-right: 8%;">
				
			<h5 style="padding: 10px 0px;">Manage Device Params</h5>
			<form>
					<div style="float:left;">
						<label>User Name</label>
						<select name="user_name" class="select_txt">
    					<?php 
    					for($i=0; $i<count($userArr); $i++){
    					    echo "<option value='".$userArr[$i]->user_name."'>".$userArr[$i]->user_name."</option>";
    					}
    					?>
    					</select>
					</div>
					
					<input type="button" name="get_devices" value="Get" class="btn"/>
					
			</form>
			 
			<div style="clear: both;">&nbsp;</div>
			<div class="data">
				
			</div>
		</div>
	</div>
</div>


	<script type="text/javascript">
	
	$( function() {
// 		$("input[name='start_date']").datepicker({
// 			dateFormat: 'yy-mm-dd',
// 		});
	    //$( "#startdate" ).datepicker();
// 		$("input[name='end_date']").datepicker({
// 			dateFormat: 'yy-mm-dd',
// 		});

		//SelectElement("countryoptions", "<?php //echo $userDetails->country;?>");

	});
</script>

</body>
</html>
