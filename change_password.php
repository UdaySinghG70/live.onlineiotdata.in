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
    <title>Change Password - Cloud Data Monitoring</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" type="text/css" href="css/dropdown.css">
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
            max-width: 400px;
            margin: 40px auto;
            padding: 0 10px;
        }

        .password-card {
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

        .error-message {
            color: #e53935;
            font-size: 13px;
            margin-top: 5px;
        }

        .success-message {
            color: #43a047;
            font-size: 13px;
            margin-top: 5px;
        }

        @media (max-width: 480px) {
            .main-container {
                max-width: 98vw;
                padding: 0 2vw;
            }
            .password-card {
                padding: 12px 4vw 10px 4vw;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'header1.php';?>

    <div class="main-container">
        <div class="password-card">
            <h2 class="card-title">Change Password</h2>
            <form id="data_frm" autocomplete="off">
                <div class="form-group">
                    <label for="old_pass">Current Password</label>
                    <input type="password" 
                           id="old_pass"
                           name="old_pass" 
                           class="form-control" 
                           placeholder="Enter your current password"
                           onblur="if (this.hasAttribute('readonly')==false) {this.setAttribute('readonly','readonly');}" 
                           readonly 
                           onclick="if (this.hasAttribute('readonly')) {
                               this.removeAttribute('readonly');
                               this.blur();
                               this.select();
                           }" />
                </div>

                <div class="form-group">
                    <label for="new_pass">New Password</label>
                    <input type="password" 
                           id="new_pass"
                           name="new_pass" 
                           class="form-control" 
                           placeholder="Enter your new password"
                           onblur="if (this.hasAttribute('readonly')==false) {this.setAttribute('readonly','readonly');}" 
                           readonly 
                           onclick="if (this.hasAttribute('readonly')) {
                               this.removeAttribute('readonly');
                               this.blur();
                               this.select();
                           }" />
                </div>

                <div class="form-group">
                    <label for="c_new_pass">Confirm New Password</label>
                    <input type="password" 
                           id="c_new_pass"
                           name="c_new_pass" 
                           class="form-control" 
                           placeholder="Confirm your new password"
                           onblur="if (this.hasAttribute('readonly')==false) {this.setAttribute('readonly','readonly');}" 
                           readonly 
                           onclick="if (this.hasAttribute('readonly')) {
                               this.removeAttribute('readonly');
                               this.blur();
                               this.select();
                           }" />
                </div>

                <div class="form-group">
                    <input type="button" name="btn_change_pwd" value="Update Password" class="btn-submit"/>
                    <label class="msgtask">&nbsp;</label>
                </div>
            </form>
        </div>
    </div>

    <script src="vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="js/common.js"></script>
    <script src="js/change_pwd.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.fixedtable.js"></script>
    
    <script>
    $(function() {
        $('form').attr('autocomplete', 'off');
        
        $(".form-control")
            .blur(function() {
                if (this.hasAttribute('readonly')==false) {
                    $(this).attr('readonly','readonly');
                }
            })
            .click(function() {
                if (this.hasAttribute('readonly')){
                    this.removeAttribute('readonly');
                }
            })
            .focus(function() {
                if($(this).val().length<=0){
                    //this.removeAttribute('readonly');
                }
            });
        
        // Highlight the Account section in navigation
        $("#account_page").addClass("active");
    });
    </script>
</body>
</html>