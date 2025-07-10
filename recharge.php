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

$user=$ldao->getUserByUserName($_SESSION['user_name']);

if($user==null){
    header('Location: login.php');
    return;
}
$department_name=$user->department_name;
$deviceArr=$ddao->getDeviceByUserName($user->user_name);
//echo "welcome user ".$user->user_name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Recharge History - Cloud Data Monitoring</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/dropdown.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #e3f2fd; /* light blue */
            min-height: 100vh;
        }

        .main-container {
            max-width: 480px;
            margin: 40px auto;
            padding: 0 10px;
        }

        .recharge-card {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: none;
            padding: 24px 18px 18px 18px;
            margin-top: 0;
        }

        .card-title {
            color: #222;
            font-size: 20px;
            margin-bottom: 18px;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #444;
            font-weight: 400;
            font-size: 13px;
        }

        .form-control {
            width: 100%;
            max-width: 100%;
            padding: 8px 10px;
            border: 1px solid #cfd8dc;
            border-radius: 4px;
            font-size: 14px;
            color: #222;
            background-color: #f9f9f9;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            outline: none;
            border: 1.5px solid #1976d2 !important;
            color: #222 !important;
            background: #fff;
            box-shadow: 0 0 0 2px #e3f2fd;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234a5568' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px;
        }

        .btn-submit {
            background: #1976d2;
            color: #fff;
            border: none;
            padding: 9px 0;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            font-size: 15px;
            width: 100%;
            margin-top: 8px;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: #1565c0;
        }

        .msgtask {
            display: block;
            margin-top: 8px;
            color: #888;
            font-size: 13px;
        }

        .data {
            margin-top: 30px;
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            box-shadow: none;
            padding: 18px 12px;
            min-height: 80px;
        }

        /* Table Styles */
        .recharge-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 13px;
        }

        .recharge-table th {
            background-color: #f5faff;
            color: #333;
            font-weight: 500;
            padding: 8px;
            text-align: left;
            border-bottom: 1.5px solid #e0e0e0;
        }

        .recharge-table td {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
            color: #222;
        }

        .recharge-table tr:hover {
            background-color: #f0f7fa;
        }

        @media (max-width: 480px) {
            .main-container {
                max-width: 98vw;
                padding: 0 2vw;
            }
            .recharge-card, .data {
                padding: 12px 4vw 10px 4vw;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'header1.php';?>

    <div class="main-container">
        <div class="recharge-card">
            <h2 class="card-title">Recharge History</h2>
            <form id="data_frm" autocomplete="off">
                <div class="form-group">
                    <label for="device_id">Device ID</label>
                    <select name="device_id" id="device_id" class="form-control">
                        <?php 
                        for($i=0; $i<count($deviceArr); $i++){
                            echo "<option value='".$deviceArr[$i]->device_id."'>".$deviceArr[$i]->device_id."</option>";
                        }
                        ?>
                    </select>
                    <label class="msgtask">&nbsp;</label>
                </div>

                <div class="form-group">
                    <input type="button" name="get_recharge" value="View History" class="btn-submit"/>
                    <label class="msgtask">&nbsp;</label>
                </div>
            </form>
        </div>

        <div class="data">
            <!-- Recharge history will be loaded here -->
        </div>
    </div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/recharge.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.fixedtable.js"></script>
    
    <script>
    $(function() {
        // Highlight the Account section in navigation
        $("#account_page").addClass("active");
    });
    </script>
</body>
</html>