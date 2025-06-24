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

if(isset($_REQUEST['user_name'])==false){
    header('Location: users.php');
    return;
}
$user_name=$_REQUEST['user_name'];

include_once '../model/logindao.php';
$ldao=new LoginDao();
$userDetails=$ldao->getUserByUserName($user_name);

//echo "Welcome Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Edit User</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
<link rel="stylesheet" type="text/css" href="../login/vendor/animate/animate.css">
<link rel="stylesheet" type="text/css" href="../css/util.css">
<link rel="stylesheet" type="text/css" href="../css/main.css">
<link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
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
	.form_container .row{
	   padding: 10px;
	}
	.form_container textarea{
	   border: 1px solid #ddd;
		min-width: 400px;
	}
	.form_container select{
	   margin: 0px 10px; 
	}
	.form_container label{
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
	.data table {
	   border: 1px solid #ddd; 
	}
	.data table tr th{
	   padding: 5px;
		border: 1px solid #ddd;
		text-align: center;
	}
    .data table tr td{
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
</head>
<body>
    <?php include_once 'admin_header.php';?>
    <main class="dashboard">
        <div class="form-card" style="max-width: 800px; margin: 40px auto 0 auto;">
            <h1 class="page-title">Edit User</h1>
            <?php 
            if($userDetails==null){
                echo "<div style='color:#dc2626;font-weight:bold;'>User not found.</div>";
                return ;
            }
            ?>
            <form id="data_frm">
                <div class="form-row">
                    <input type="hidden" name="user_name_old" value="<?php echo $userDetails->user_name;?>">
                    <label class="form-label">User Name</label>
                    <input type="text" value="<?php echo $userDetails->user_name;?>" class="form-control" name="user_name" />
                </div>
                <div class="form-row">
                    <label class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" placeholder="Password" value="<?php echo $userDetails->password;?>"/>
                </div>
                <div class="form-row">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="department_name" class="form-control" placeholder="Department Name" value="<?php echo $userDetails->department_name;?>"/>
                </div>
                <div class="form-row">
                    <label class="form-label">Email ID</label>
                    <input type="text" name="email_id" class="form-control" placeholder="Email ID" value="<?php echo $userDetails->email_id;?>"/>
                </div>
                <div class="form-row">
                    <label class="form-label">Mobile No</label>
                    <input type="text" name="mobile_no" class="form-control" placeholder="Mobile No" value="<?php echo $userDetails->mobile;?>"/>
                </div>
                <div class="form-row">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" placeholder="City" value="<?php echo $userDetails->city;?>"/>
                </div>
                <div class="form-row">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" placeholder="Pincode" value="<?php echo $userDetails->pincode;?>" />
                </div>
                <div class="form-row">
                    <label class="form-label">Address</label>
                    <textarea rows="4" name="address" class="form-control"><?php echo $userDetails->address;?></textarea>
                </div>
                <div class="form-row">
                    <label class="form-label">Country</label>
                    <?php include_once '../countries.php';?>
                </div>
                <div class="form-row">
                    <div class="form-label"></div>
                    <div>
                        <input type="button" name="update_user" value="Update" class="btn"/>
                        <span class="message msg_task"></span>
                        <span class="message extra_msg_task"></span>
                    </div>
                </div>
            </form>
        </div>
    </main>

<!--===============================================================================================-->
	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="js/create_user.js"></script>
	<script src="../js/jquery-ui.js"></script>
	<script src="../js/jquery.fixedtable.js"></script>
	
	<script type="text/javascript">
	function SelectElement(id, valueToSelect)
	{    
	    var element = document.getElementById(id);
	    element.value = valueToSelect;
	}
	
	$( function() {
		$("input[name='start_date']").datepicker({
			dateFormat: 'yy-mm-dd',
		});
	    //$( "#startdate" ).datepicker();
		$("input[name='end_date']").datepicker({
			dateFormat: 'yy-mm-dd',
		});

		SelectElement("countryoptions", "<?php echo $userDetails->country;?>");


	});
</script>
</body>
</html>