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

//echo "Welcome Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create User - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.ico"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f8f9fa;
            color: #2c3e50;
        }

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
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <main class="dashboard">
        <h1 class="page-title">Create User</h1>
			
        <div class="form-card">
				<form id="data_frm">
                <div class="form-row">
                    <label class="form-label">User Name</label>
                    <input type="text" name="user_name" class="form-control" required/>
				</div>

                <div class="form-row">
                    <label class="form-label">Password</label>
                    <input type="text" name="password" class="form-control" placeholder="Enter password" required/>
				</div>

                <div class="form-row">
                    <label class="form-label">Department Name</label>
                    <input type="text" name="department_name" class="form-control" placeholder="Enter department name" required/>
				</div>

                <div class="form-row">
                    <label class="form-label">Email ID</label>
                    <input type="email" name="email_id" class="form-control" placeholder="Enter email address" required/>
				</div>
				
                <div class="form-row">
                    <label class="form-label">Mobile No</label>
                    <input type="text" name="mobile_no" class="form-control" placeholder="Enter mobile number" required/>
				</div>
				
                <div class="form-row">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" placeholder="Enter city" required/>
				</div>
				
                <div class="form-row">
                    <label class="form-label">Pincode</label>
                    <input type="text" name="pincode" class="form-control" placeholder="Enter pincode" required/>
				</div>
				
                <div class="form-row">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="4" placeholder="Enter full address"></textarea>
				</div>
				
                <div class="form-row">
                    <label class="form-label">Country</label>
					<?php include_once '../countries.php';?>
				</div>

                <div class="form-row">
                    <div class="form-label"></div>
                    <div>
                        <button type="button" name="create_user" class="btn">Create User</button>
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
</body>
</html>