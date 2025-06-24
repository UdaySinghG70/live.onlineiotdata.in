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
<link rel="stylesheet" href="css/admin-style.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
        <h1 class="page-title">Edit User</h1>
        <div class="form-card">
            <?php 
            if($userDetails==null){
                echo "<div class='message extra_msg_task'>User not found.</div>";
                return ;
            }
            ?>
            <form id="data_frm">
                <div class="form-row">
                    <label class="form-label">User Name</label>
                    <input type="hidden" name="user_name_old" value="<?php echo $userDetails->user_name;?>">
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
                    <textarea rows="4" name="address" class="form-control" placeholder="Enter full address"><?php echo $userDetails->address;?></textarea>
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
    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="js/create_user.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <style>
        .dashboard {
            margin-top: 80px;
            padding: 2rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        .page-title {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .form-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .form-row {
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 1rem;
        }
        .form-label {
            flex: 0 0 140px;
            color: #4a5568;
            font-size: 0.875rem;
            font-weight: 500;
            padding-top: 0.5rem;
        }
        .form-control {
            flex: 1;
            min-width: 200px;
            max-width: 400px;
            padding: 0.5rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.875rem;
            color: #2d3748;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            outline: none;
            border-color: #0067ac;
            box-shadow: 0 0 0 3px rgba(0, 103, 172, 0.1);
        }
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        select.form-control {
            background-color: white;
            cursor: pointer;
        }
        .btn {
            background: #0067ac;
            color: white;
            border: none;
            padding: 0.625rem 1.5rem;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn:hover {
            background: #005491;
            transform: translateY(-1px);
        }
        .btn:active {
            transform: translateY(0);
        }
        .message {
            margin-left: 1rem;
            font-size: 0.875rem;
        }
        .msg_task {
            color: #059669;
        }
        .extra_msg_task {
            color: #dc2626;
        }
        @media (max-width: 768px) {
            .dashboard {
                padding: 1rem;
                margin-top: 120px;
            }
            .form-card {
                padding: 1.5rem;
            }
            .form-row {
                flex-direction: column;
                gap: 0.5rem;
            }
            .form-label {
                flex: none;
                padding-top: 0;
            }
            .form-control {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</body>
</html>