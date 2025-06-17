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
$userArr=$adao->getAllUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recharge Management - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
<link rel="stylesheet" type="text/css" href="../css/thickbox.css">
    <style>
        .recharge-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.5rem;
            color: #1e293b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .recharge-form {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-label {
            font-size: 0.875rem;
            color: #4b5563;
            min-width: 120px;
            font-weight: 500;
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.875rem;
            min-width: 200px;
            transition: all 0.2s;
            background: white;
        }

        .form-control:focus {
            outline: none;
            border-color: #0067ac;
            box-shadow: 0 0 0 3px rgba(0, 103, 172, 0.1);
        }

        .recharge-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
        }

        .recharge-table th {
            background: #f8fafc;
            padding: 0.75rem;
            font-weight: 500;
            color: #4b5563;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .recharge-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .recharge-table tr:hover td {
            background: #f8fafc;
        }

        .msg-container {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 6px;
            background: #f0fdf4;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .msg-container.error {
            background: #fef2f2;
            color: #dc2626;
        }

        .action-link {
            color: #0067ac;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .action-link:hover {
            color: #005291;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #0067ac;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
		cursor: pointer;
            transition: all 0.2s;
        }

        .btn:hover {
            background: #005291;
        }

        .btn.secondary {
            background: #f1f5f9;
            color: #0067ac;
        }

        .btn.secondary:hover {
            background: #e2e8f0;
        }

        .btn.danger {
            background: #dc2626;
            color: white;
        }

        .btn.danger:hover {
            background: #b91c1c;
        }

        @media (max-width: 768px) {
            .recharge-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .form-group {
                flex-direction: column;
                align-items: flex-start;
            }

            .form-label {
                margin-bottom: 0.5rem;
            }

            .form-control {
                width: 100%;
            }

            .recharge-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <main class="dashboard">
        <div class="recharge-container">
		<?php 
		if($userArr==null){
                echo '<div class="msg-container error">
                    <span class="material-icons">error</span>
                    <span>No users found. <a href="create_user.php" class="action-link">Create User <span class="material-icons">add</span></a></span>
                </div>';
                return;
            }
            ?>

            <div class="page-header">
                <h1 class="page-title">
                    <span class="material-icons">account_balance_wallet</span>
                    Recharge Management
                </h1>
            </div>

            <form id="recahrge_frm" class="recharge-form">
                <div class="form-group">
                    <label class="form-label" for="user_name">User Name</label>
                    <select id="user_name" name="user_name" class="form-control">
						<option value="-">Select User</option>
    					<?php 
                        foreach($userArr as $user){
                            echo "<option value='".htmlspecialchars($user->user_name)."'>".htmlspecialchars($user->user_name)."</option>";
    					}
    					?>
    					</select>
					</div>
					<div class="data">
                    <!-- Dynamic content will be loaded here -->
					</div>
			</form>
			 
            <div class="msg_task"></div>
		</div>
    </main>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/jquery.fixedtable.js"></script>
    <script src="../js/thickbox.js"></script>
    <script src="../js/verge.js"></script>
    <script src="js/recharge.js"></script>
    <script>
        $(function() {
            // Calculate device width for thickbox
            let deviceWidth = verge.viewportW();
            let deviceHeight = verge.viewportH();
            
            // Set reasonable max dimensions
            deviceWidth = Math.min(deviceWidth * 0.9, 800);
            deviceHeight = Math.min(deviceHeight * 0.8, 600);
            
            // Override thickbox defaults
            window.tb_position = function() {
                var tbWindow = $('#TB_window');
                var width = $(window).width();
                var height = $(window).height();
                var outerHeight = tbWindow.outerHeight();
                
                tbWindow.css({
                    'position': 'fixed',
                    'margin-left': '-' + parseInt(deviceWidth / 2, 10) + 'px',
                    'width': deviceWidth + 'px',
                    'left': '50%',
                    'top': Math.max(20, (height - outerHeight) / 2) + 'px'
                });
            };

            // Initialize message handling
            function showMessage(message, isError = false) {
                $('.msg_task')
                    .html(`<div class="msg-container ${isError ? 'error' : ''}">
                        <span class="material-icons">${isError ? 'error' : 'check_circle'}</span>
                        <span>${message}</span>
                    </div>`)
                    .show();
            }

            // Initialize any existing functionality from recharge.js
            if (typeof initializeRecharge === 'function') {
                initializeRecharge();
            }
        });
    </script>
</body>
</html>