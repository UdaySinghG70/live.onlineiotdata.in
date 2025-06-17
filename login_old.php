<!DOCTYPE html>
<html lang="en">
<head>
	<title>Online Iot Data</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
<!-- 	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css"> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
<!-- 	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css"> -->
<!--===============================================================================================-->	
<!-- 	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css"> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	
<style type="text/css">
.input100{
	box-shadow: inset 1px 1px 9px 2px #d0cdcd;
    border-radius: 5px;
    padding-left: 20px;
}
</style>
</head>
<body>
	
	<div class="limiter" style="background-color: #e6e3e3;margin-top: -10px;height: 100%;">
	
		<h3 style="text-align: center;padding: 10px;background-color: #f0ecec;color: #000;margin: 0px 40px;display: none;">Sound Monitoring System</h3>
		<div class="container-login100" style="justify-content:center;height:100%;justify-content:center;" >
		
		<div style="clear: both; "></div>
			<div class="wrap-login100" style="box-shadow: -1px -1px 13px 5px #949090">
				<h3 style="text-align: center;padding: 0px;background-color: #f0ecec;color: #000;margin-bottom: 20px ;">Sound Monitoring System</h3>
		
				<form class="login100-form validate-form" method="post" action="do_login.php" autocomplete="off">
					<span class="login100-form-logo" style="background-color: #a7a4a4;">
<!-- 						<i class="zmdi zmdi-landscape"></i> -->
						<i class="zmdi zmdi-account "></i>
					</span>

					<span class="login100-form-title p-b-34 p-t-27" style="color: #A00;">
						Log in
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Enter username">
						<input class="input100" type="text" name="username" placeholder="Username" value="<?php if(isset($_REQUEST['user_name']))echo $_REQUEST['user_name'];?>" style="padding-left:30px;">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<input class="input100" type="password" name="pass" placeholder="Password" style="padding-left:30px;">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="contact100-form-checkbox" style="display: none;">
						<input class="input-checkbox100" id="ckb1" type="checkbox" name="remember-me">
						<label class="label-checkbox100" for="ckb1">
							Remember me
						</label>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" style="color: #A00;">
							Login
						</button>
					</div>

					<!-- <div class="text-center p-t-90">
						<a class="txt1" href="#">
							Forgot Password?
						</a>
					</div>-->
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
<!-- 	<script src="vendor/animsition/js/animsition.min.js"></script> -->
<!--===============================================================================================-->
<!-- 	<script src="vendor/bootstrap/js/popper.js"></script> -->
<!-- 	<script src="vendor/bootstrap/js/bootstrap.min.js"></script> -->
<!--===============================================================================================-->
<!-- 	<script src="vendor/select2/select2.min.js"></script> -->
<!--===============================================================================================-->
<!-- 	<script src="vendor/daterangepicker/moment.min.js"></script> -->
<!-- 	<script src="vendor/daterangepicker/daterangepicker.js"></script> -->
<!--===============================================================================================-->
<!-- 	<script src="vendor/countdowntime/countdowntime.js"></script> -->
<!--===============================================================================================-->
	<script src="js/main.js"></script>
	<script type="text/javascript">
	if(document.getElementsByTagName) {
		var inputElements = document.getElementsByTagName("input");
		for (i=0; inputElements[i]; i++) {
		if (inputElements[i].className && (inputElements[i].className.indexOf("disableAutoComplete") != -1)) {
		inputElements[i].setAttribute("autocomplete","off");
		}
		}
		}
	</script>

</body>
</html>