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
            background-color: #e3f2fd;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .password-card {
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

        .error-message {
            color: #e53e3e;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            color: #38a169;
            font-size: 14px;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .password-card {
                padding: 20px;
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