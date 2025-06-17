<?php
session_start();
if(isset($_SESSION['user_name'])==false){
    header('Location: login.php');
   return;
}
//$page_name = "Home";

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
    <title>Cloud Data Monitoring</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="images/icons/favicon.png"/>
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/common.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/dropdown.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-container {
            flex: 1;
            padding: 20px;
            max-width: 100%;
            margin: 0;
        }

        .report-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 20px;
            height: auto;
            min-height: auto;
            display: block;
        }

        .report-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
        }

        .form-container {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            display: inline-block;
            margin-right: 20px;
            vertical-align: top;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
        }

        .form-control {
            width: 250px;
            padding: 12px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #2d3748;
            background-color: #fff;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.2);
        }

        .input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .time-input {
            width: 100px !important;
            padding: 12px 10px;
            font-family: monospace;
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234a5568' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: linear-gradient(45deg, #0067ac, #0088e0);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            outline: none;
            min-height: 44px;  /* Fixed height to prevent movement */
            text-decoration: none;
            width: 100%;
            user-select: none;  /* Prevent text selection */
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #005a96, #0077c7);
        }

        .data-container {
            flex: none;
            background: #fff;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
            overflow: hidden;
        }

        .msg_task {
            display: inline-block;
            margin-left: 15px;
            color: #4a5568;
            font-size: 14px;
        }

        /* Datepicker improvements */
        .ui-datepicker {
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 15px;
            font-family: 'Segoe UI', sans-serif;
            width: 300px !important; /* Ensure consistent width */
            z-index: 1000 !important; /* Ensure calendar appears above other elements */
        }

        .ui-datepicker-header {
            background: linear-gradient(45deg, #0067ac, #0088e0);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            margin: -15px -15px 10px;
        }

        .ui-datepicker-title {
            font-weight: 600;
            font-size: 14px;
        }

        .ui-datepicker-prev, .ui-datepicker-next {
            cursor: pointer;
            position: absolute;
            top: 12px;
            width: 20px;
            height: 20px;
            text-align: center;
            color: #fff;
            text-decoration: none;
        }

        .ui-datepicker-prev {
            left: 12px;
        }

        .ui-datepicker-next {
            right: 12px;
        }

        .ui-datepicker-prev:hover, .ui-datepicker-next:hover {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .ui-datepicker-calendar {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .ui-datepicker-calendar th {
            color: #4a5568;
            font-weight: 600;
            font-size: 12px;
            padding: 8px 0;
            text-transform: uppercase;
        }

        .ui-datepicker-calendar td {
            padding: 2px;
        }

        .ui-datepicker-calendar .ui-state-default {
            display: block;
            padding: 8px;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            border: 1px solid transparent;
            color: #2d3748;
            background: #f7fafc;
        }

        .ui-datepicker-calendar .ui-state-hover {
            background: #edf2f7;
            border-color: #e2e8f0;
        }

        .ui-datepicker-calendar .ui-state-active {
            background: #0067ac;
            color: #fff;
        }

        .ui-datepicker-calendar .ui-state-highlight {
            background: #ebf8ff;
            color: #0067ac;
            border-color: #bee3f8;
        }

        .ui-datepicker-calendar .ui-state-disabled {
            opacity: 0.35;
            cursor: not-allowed;
        }

        .ui-datepicker select.ui-datepicker-month, 
        .ui-datepicker select.ui-datepicker-year {
            width: 45%;
            height: 30px;
            margin: 0 2px;
            padding: 5px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            background: #fff;
            color: #2d3748;
            font-size: 13px;
        }

        /* Position fix for datepicker */
        #ui-datepicker-div {
            position: absolute;
            top: 0;
            left: 0;
            display: none;
        }

        @media (max-width: 768px) {
            .form-group {
                display: block;
                margin-right: 0;
                margin-bottom: 15px;
                width: 100%;
            }

            .form-control {
                width: 100%;
            }

            .input-group {
                flex-direction: column;
                gap: 10px;
            }

            .time-input {
                width: 100% !important;
            }

            .report-card {
                padding: 15px;
                margin-top: 10px;
            }

            .report-title {
                font-size: 20px;
                margin-bottom: 20px;
            }

            .form-container {
                padding: 15px;
            }

            .btn-primary {
                width: 100%;
                margin-top: 10px;
            }

            .msg_task {
                display: block;
                margin: 10px 0 0 0;
                text-align: center;
            }

            .data-container {
                margin-top: 15px;
                padding: 15px;
            }

            .ui-datepicker {
                width: 280px !important;
                position: fixed !important;
                top: 50% !important;
                left: 50% !important;
                transform: translate(-50%, -50%) !important;
                z-index: 1000 !important;
            }

            .modal-content {
                width: 95%;
                padding: 20px;
            }

            .modal-title {
                font-size: 20px;
            }

            .modal-message {
                font-size: 14px;
            }

            .modal-button {
                width: 100%;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                padding: 10px;
            }

            .report-card {
                padding: 10px;
            }

            .report-title {
                font-size: 18px;
                margin-bottom: 15px;
            }

            .form-container {
                padding: 10px;
            }

            .form-group label {
                font-size: 13px;
            }

            .form-control {
                padding: 10px;
                font-size: 13px;
            }

            .btn-primary {
                font-size: 14px;
                padding: 8px 16px;
            }

            .modal-content {
                padding: 15px;
            }

            .modal-icon {
                font-size: 36px;
            }

            .modal-title {
                font-size: 18px;
            }

            .modal-message {
                font-size: 13px;
            }
        }

        /* Add touch-friendly styles */
        @media (hover: none) {
            .btn-primary:active {
                transform: scale(0.98);
            }

            .form-control {
                font-size: 16px; /* Prevent zoom on iOS */
            }

            select.form-control {
                padding-right: 30px;
            }

            .ui-datepicker-calendar .ui-state-default {
                padding: 10px; /* Larger touch target */
            }
        }

        /* Fix for iOS input zoom */
        @supports (-webkit-touch-callout: none) {
            .form-control {
                font-size: 16px;
            }
        }

        /* Loading animation */
        .loading {
            display: none;
            margin-left: 10px;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #0067ac;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom Modal Styles */
        .custom-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            text-align: center;
            position: relative;
            transform: scale(0.7);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .modal-content.show {
            transform: scale(1);
            opacity: 1;
        }

        .modal-icon {
            font-size: 48px;
            color: #f03e3e;
            margin-bottom: 20px;
        }

        .modal-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .modal-message {
            color: #4a5568;
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 25px;
        }

        .modal-button {
            background: linear-gradient(45deg, #f03e3e, #e03131);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-button:hover {
            background: linear-gradient(45deg, #e03131, #c92a2a);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <?php include_once 'header1.php';?>
    
    <!-- Custom Modal -->
    <div class="custom-modal" id="rechargeModal" onclick="closeModalOnOutsideClick(event)">
        <div class="modal-content">
            <div class="modal-icon">⚠️</div>
            <h2 class="modal-title">Recharge Required</h2>
            <p class="modal-message">Your recharge has expired. Please renew to access your data.</p>
            <button class="modal-button" onclick="closeModal()">OK</button>
        </div>
    </div>

    <div class="main-container">
        <div class="report-card">
            <h2 class="report-title">Date Wise Report</h2>
            <div class="form-container">
                <form autocomplete="off">
                    <div class="form-group">
                        <label>Device ID</label>
                        <select name="device_select" class="form-control">
                            <?php 
                            for($i=0;$i<count($deviceArr);$i++){
                                echo "<option value='".$deviceArr[$i]->device_id."'>".$deviceArr[$i]->device_id."</option>";    
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Start Date</label>    
                        <div class="input-group">
                            <input type="text" name="start_date" class="form-control donttype" placeholder="Select start date" autocomplete="off"/>
                            <input type="time" name="start_time" class="form-control time-input" value="00:00"/>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>End Date</label>
                        <div class="input-group">
                            <input type="text" name="end_date" class="form-control donttype" placeholder="Select end date" autocomplete="off"/>
                            <input type="time" name="end_time" class="form-control time-input" value="23:59"/>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="form-group" style="width: auto; margin-top: 20px;">
                        <button type="button" name="get_data_datewise" class="btn-primary" style="width: 200px;">
                            Get Data
                            <div class="loading"></div>
                        </button>
                        <label class="msg_task">&nbsp;</label>
                    </div>
                </form>
            </div>

            <div class="data-container">
                <div class="data"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="js/common.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
    <script src="js/jquery.fixedtable.js"></script>
    
    <script>
        // Move functions outside of document.ready to make them globally accessible
        function showModal(message) {
            const modal = document.getElementById('rechargeModal');
            const modalContent = modal.querySelector('.modal-content');
            
            if (message) {
                modal.querySelector('.modal-message').textContent = message;
            }
            
            modal.style.display = 'flex';
            setTimeout(() => {
                modalContent.classList.add('show');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('rechargeModal');
            const modalContent = modal.querySelector('.modal-content');
            modalContent.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        function closeModalOnOutsideClick(event) {
            const modal = document.getElementById('rechargeModal');
            const modalContent = modal.querySelector('.modal-content');
            if (event.target === modal) {
                closeModal();
            }
        }

        $(function() {
            $("#home_page").css("text-decoration","underline");
            
            // Common datepicker options
            const datepickerOptions = {
                changeMonth: true,
                changeYear: true,
                dateFormat: 'yy-mm-dd',
                minDate: new Date('2018-01-01'),
                maxDate: '+0d',
                showAnim: 'fadeIn',
                beforeShow: function(input, inst) {
                    // Position the datepicker relative to the input
                    const inputOffset = $(input).offset();
                    const inputHeight = $(input).outerHeight();
                    setTimeout(function() {
                        inst.dpDiv.css({
                            top: inputOffset.top + inputHeight + 5,
                            left: inputOffset.left
                        });
                    }, 0);
                }
            };
            
            $("input[name='start_date']").datepicker(datepickerOptions);
            $("input[name='end_date']").datepicker(datepickerOptions);

            $("button[name='get_data_datewise']").click(function(e){
                // Prevent default form submission
                e.preventDefault();
                
                var device_id = $('select[name="device_select"]').val();
                
                // Reset any previous state
                $('.msg_task').text('');
                $('.loading').hide();  // Hide loader immediately
                
                // First check recharge status
                $.ajax({
                    url: 'check_recharge.php',
                    type: 'POST',
                    data: {
                        device_id: device_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        try {
                            if (response && response.status === 'active') {
                                // Proceed with data fetch
                                fetchData(device_id);
                            } else {
                                // Show custom modal instead of alert
                                showModal(response ? response.message : 'Your recharge has expired. Please renew to access your data.');
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                            showModal('Error checking device status. Please try again.');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', textStatus, errorThrown);
                        showModal('Error checking device status. Please try again.');
                    }
                });
            });

            function fetchData(device_id) {
                var start_date = $('input[name="start_date"]').val();
                var end_date = $('input[name="end_date"]').val();
                var start_time = $('input[name="start_time"]').val() || '00:00';
                var end_time = $('input[name="end_time"]').val() || '23:59';
                
                // Remove loading text
                $('.msg_task').text('');
                
                $.ajax({
                    url: 'get_data_datewise.php',
                    type: 'POST',
                    data: {
                        device_id: device_id,
                        start_date: start_date,
                        start_time: start_time,
                        end_date: end_date,
                        end_time: end_time,
                        pg: 1
                    },
                    success: function(response) {
                        $('.data').html(response);
                        $('.loading').hide();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', textStatus, errorThrown);
                        $('.msg_task').text('Error loading data. Please try again.');
                        $('.loading').hide();
                    }
                });
            }

            // Add event listeners for modal
            document.getElementById('rechargeModal').addEventListener('click', closeModalOnOutsideClick);
            document.querySelector('.modal-button').addEventListener('click', function(e) {
                e.stopPropagation(); // Prevent event from bubbling to modal
                closeModal();
            });
        });
    </script>
</body>
</html>