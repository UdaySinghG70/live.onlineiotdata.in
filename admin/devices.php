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
    <title>Manage Devices - Cloud Data Monitoring</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <link rel="stylesheet" type="text/css" href="../css/thickbox.css">
    <style>
        .devices-container {
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

        .filter-form {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-size: 0.875rem;
            color: #4b5563;
            min-width: 120px;
        }

        .form-control {
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.875rem;
            min-width: 200px;
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: #0067ac;
            box-shadow: 0 0 0 3px rgba(0, 103, 172, 0.1);
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
            text-decoration: none;
        }

        .btn:hover {
            background: #005291;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 103, 172, 0.1);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn .material-icons {
            font-size: 18px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            background: #f1f5f9;
            color: #0067ac;
            border: none;
            text-decoration: none;
        }

        .action-btn:hover {
            background: #e2e8f0;
        }

        .action-btn.delete {
            color: #dc2626;
        }

        .action-btn.delete:hover {
            background: #fee2e2;
        }

        .devices-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
        }

        .devices-table th {
            background: #f8fafc;
            padding: 0.75rem;
            font-weight: 500;
            color: #4b5563;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .devices-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .devices-table tr:hover td {
            background: #f8fafc;
        }

        .msg-container {
            margin: 1rem 0;
            padding: 1rem;
            border-radius: 6px;
            background: #f0fdf4;
            color: #059669;
        }

        .msg-container.error {
            background: #fef2f2;
            color: #dc2626;
        }

        /* Loading indicator */
        .loading_file {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(to right, #0067ac, #005291);
            animation: loading 1.5s infinite ease-in-out;
            z-index: 1000;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @media (max-width: 768px) {
            .devices-container {
                padding: 1rem;
            }

            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group {
                flex-direction: column;
                align-items: stretch;
            }

            .form-label {
                margin-bottom: 0.5rem;
            }

            .devices-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        #rechargeModal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        #rechargeModalContent {
            background: #fff;
            border-radius: 0;
            box-shadow: none;
            max-width: 500px;
            width: 98vw;
            margin: auto;
            padding: 0;
            position: relative;
            min-width: 220px;
            min-height: 60px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
        }
        #closeRechargeModal {
            position: absolute;
            top: 6px;
            right: 10px;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #888;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }
        #rechargeModalBody {
            padding: 0.8rem 0.8rem 0.7rem 0.8rem;
            font-size: 1rem;
            color: #222;
        }
        @media (max-width: 600px) {
            #rechargeModalContent {
                max-width: 99vw;
                min-width: 0;
            }
        }
    </style>
</head>
<body>
    <div class="loading_file"></div>
    <?php include_once 'admin_header.php';?>
    
    <main class="dashboard">
        <div class="devices-container">
            <?php 
            if($userArr==null){
                echo '<div class="msg-container error">
                    <span class="material-icons">error</span>
                    No users found. <a href="create_user.php" class="action-btn">Create User</a>
                </div>';
                return;
            }
            ?>

            <div class="page-header">
                <h1 class="page-title">
                    <span class="material-icons">devices</span>
                    Manage Devices
                </h1>
            </div>

            <form class="filter-form">
                <div class="form-group">
                    <label class="form-label" for="user_name">User Name</label>
                    <select id="user_name" name="user_name" class="form-control">
                        <?php 
                        foreach($userArr as $user){
                            echo "<option value='".htmlspecialchars($user->user_name)."'>".htmlspecialchars($user->user_name)."</option>";
                        }
                        ?>
                    </select>
                </div>
                
                <input type="button" name="get_devices" class="btn" value="Get Devices">
                
            </form>

            <div class="msg-container msgdata" style="display: none;">
                &nbsp;
            </div>

            <div class="data">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>
    </main>

    <!-- Modal for Recharge Info (only included once) -->
    <div id="rechargeModal">
        <div id="rechargeModalContent">
            <button id="closeRechargeModal">&times;</button>
            <div id="rechargeModalBody"></div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="../js/jquery.fixedtable.js"></script>
    <script src="../js/thickbox.js"></script>
    <script src="../js/verge.js"></script>
    <script src="js/devices.js"></script>
    <script>
        $(function() {
            // Calculate device width for thickbox
            let deviceWidth = verge.viewportW();
            let deviceHeight = verge.viewportH();
            
            deviceWidth = deviceWidth * 90 / 100;
            deviceHeight = deviceHeight * 80 / 100;
            
            if(deviceWidth > 800) {
                deviceWidth = 800;
            }

            // Show/hide message container
            function showMessage(message, isError = false) {
                $('.msgdata')
                    .html(message)
                    .removeClass('error')
                    .toggleClass('error', isError)
                    .show();
            }

            // Initialize any existing functionality from devices.js
            if (typeof initializeDevices === 'function') {
                initializeDevices();
            }
        });

        $(document).ready(function() {
            // Use event delegation for dynamically loaded .recharge-btn
            $(document).on('click', '.recharge-btn', function() {
                var deviceId = $(this).data('device');
                $('#rechargeModalBody').html('<div style="text-align:center;padding:2rem;">Loading...</div>');
                $('#rechargeModal').css({'display':'flex'}).hide().fadeIn(150);
                $.get('device_recharge_popup.php?device_id=' + encodeURIComponent(deviceId), function(data) {
                    $('#rechargeModalBody').html(data);
                });
            });
            $('#closeRechargeModal, #rechargeModal').on('click', function(e) {
                if (e.target === this) {
                    $('#rechargeModal').fadeOut(150);
                }
            });
        });
    </script>
</body>
</html>