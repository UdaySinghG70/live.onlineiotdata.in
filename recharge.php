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
            background-color: #e3f2fd;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .recharge-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
        }

        .card-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            min-width: auto;
            float: none;
        }

        .form-control {
            width: 100%;
            max-width: 400px;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 14px;
            color: #2d3748;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234a5568' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            padding-right: 30px;
        }

        .btn-submit {
            background: #0067ac;
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
            min-width: 120px;
        }

        .btn-submit:hover {
            background: #005491;
            transform: translateY(-1px);
        }

        .msgtask {
            display: block;
            margin-top: 10px;
            color: #4a5568;
            font-size: 14px;
        }

        .data {
            margin-top: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            min-height: 100px;
        }

        /* Table Styles */
        .recharge-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .recharge-table th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .recharge-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .recharge-table tr:hover {
            background-color: #f8fafc;
        }

        @media (max-width: 768px) {
            .recharge-card {
                padding: 20px;
            }

            .form-control {
                max-width: 100%;
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