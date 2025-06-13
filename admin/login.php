<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin Login - Cloud Data Monitoring</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		body {
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(135deg, #1a1c20 0%, #2c3e50 100%);
			padding: 20px;
		}

		.login-container {
			width: 100%;
			max-width: 400px;
			background: white;
			border-radius: 12px;
			box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
			padding: 40px 30px;
			position: relative;
			overflow: hidden;
		}

		.login-container::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 4px;
			background: #0067ac;
		}

		.login-header {
			text-align: center;
			margin-bottom: 40px;
		}

		.login-header h1 {
			font-size: 24px;
			color: #2c3e50;
			font-weight: 600;
			margin-bottom: 8px;
		}

		.login-header p {
			color: #7f8c8d;
			font-size: 16px;
		}

		.admin-badge {
			display: inline-block;
			padding: 4px 12px;
			background: #0067ac;
			color: white;
			border-radius: 20px;
			font-size: 12px;
			font-weight: 500;
			margin-top: 8px;
		}

		.form-group {
			margin-bottom: 24px;
		}

		.form-group label {
			display: block;
			margin-bottom: 8px;
			color: #34495e;
			font-size: 14px;
			font-weight: 500;
		}

		.form-control {
			width: 100%;
			padding: 12px 16px;
			border: 2px solid #e2e8f0;
			border-radius: 8px;
			font-size: 15px;
			color: #2d3748;
			transition: all 0.3s ease;
			background-color: #fff;
		}

		.form-control:focus {
			outline: none;
			border-color: #0067ac;
			box-shadow: 0 0 0 3px rgba(0, 103, 172, 0.1);
		}

		.login-btn {
			width: 100%;
			padding: 12px;
			background: #0067ac;
			color: white;
			border: none;
			border-radius: 8px;
			font-size: 16px;
			font-weight: 500;
			cursor: pointer;
			transition: all 0.3s ease;
		}

		.login-btn:hover {
			background: #005491;
			transform: translateY(-1px);
		}

		.login-btn:active {
			transform: translateY(0);
		}

		@media (max-width: 480px) {
			.login-container {
				padding: 30px 20px;
			}

			.login-header h1 {
				font-size: 22px;
			}
		}
	</style>
</head>
<body>
	<div class="login-container">
		<div class="login-header">
			<h1>Cloud Data Monitoring</h1>
			<p>Administrator Access</p>
			<div class="admin-badge">Admin Portal</div>
		</div>

		<form method="post" action="do_login.php" autocomplete="off">
			<div class="form-group">
				<label for="username">Admin Username</label>
				<input 
					id="username"
					class="form-control" 
					type="text" 
					name="username" 
					placeholder="Enter admin username"
					value="<?php if(isset($_REQUEST['admin_name'])) echo htmlspecialchars($_REQUEST['admin_name']); ?>"
					required
				>
			</div>

			<div class="form-group">
				<label for="password">Password</label>
				<input 
					id="password"
					class="form-control" 
					type="password" 
					name="pass" 
					placeholder="Enter admin password"
					required
				>
			</div>

			<button type="submit" class="login-btn">Sign In to Admin</button>
		</form>
	</div>

	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
	<script>
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