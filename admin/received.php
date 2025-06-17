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
    <title>Received Data - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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
            height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
        }

        .report-title {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 30px;
            font-weight: 600;
            padding-bottom: 15px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
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

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%234a5568' viewBox='0 0 16 16'%3E%3Cpath d='M8 11L3 6h10l-5 5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 15px center;
            padding-right: 40px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #0067ac, #0088e0);
            color: #fff;
            border: none;
            padding: 12px 15px;
            border-radius: 8px;
            font-weight: 500;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            margin-top: 0;
        }

        .btn-primary:hover {
            background: linear-gradient(45deg, #005491, #0077c7);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 103, 172, 0.2);
        }

        .data-container {
            flex: 1;
            overflow: auto;
            background: #fff;
            border-radius: 10px;
            margin-top: 20px;
            padding: 20px;
	}

        .data-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
	}

        .data-table th {
            background: #f8fafc;
            padding: 12px 15px;
            font-weight: 600;
            color: #4a5568;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
	}

        .data-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e2e8f0;
            color: #2d3748;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s;
        }

        .loader.active {
            visibility: visible;
            opacity: 1;
        }

        .loader::after {
            content: '';
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #0067ac;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .msg_task {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 14px;
            margin-left: 15px;
        }

        .msg_task.success {
            background-color: #f0fdf4;
            color: #059669;
        }

        .msg_task.error {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .msg_task .material-icons {
            font-size: 18px;
        }

        /* Datepicker improvements */
        .ui-datepicker {
            background: #fff;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            padding: 15px;
            font-family: 'Segoe UI', sans-serif;
            width: 300px !important;
            z-index: 1000 !important;
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

        @media (max-width: 768px) {
            .form-group {
                display: block;
                margin-right: 0;
                width: 100%;
            }

            .form-control {
                width: 100%;
            }

            .btn-primary {
                width: 100%;
                justify-content: center;
            }

            .data-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <div class="loader"></div>

    <main class="main-container">
        <div class="report-card">
            <h1 class="report-title">
                    <span class="material-icons">data_usage</span>
                    Received Data
                </h1>

            <div class="form-container">
                <div class="form-group">
                    <label for="user_name">User Name</label>
                    <select id="user_name" name="user_name" class="form-control">
    					<?php 
                        foreach($userArr as $user){
                            echo "<option value='".htmlspecialchars($user->user_name)."'>".htmlspecialchars($user->user_name)."</option>";
    					}
    					?>
    					</select>
					</div>
					
                <div class="form-group">
                    <label for="device_id">Device ID</label>
                    <select id="device_id" name="device_id" class="form-control">
                        <option value="">Select Device</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="text" id="start_date" name="start_date" class="form-control datepicker" readonly>
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="text" id="end_date" name="end_date" class="form-control datepicker" readonly>
                </div>

                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="button" id="getData" class="btn-primary form-control">
                    <span class="material-icons">search</span>
                    Get Data
                </button>

                <div class="msg_task"></div>
            </div>

            <div class="data-container">
                <table class="data-table">
                    <thead id="tableHeader" style="display: none;">
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Device ID</th>
                            <th>Data</th>
                            <th>Recharge Status</th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                    </tbody>
                </table>
			</div>
		</div>
    </main>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize datepickers
            $(".datepicker").datepicker({
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                maxDate: new Date()
            });

            // Set default dates
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            $("#end_date").datepicker("setDate", today);
            $("#start_date").datepicker("setDate", yesterday);

            // Show/hide loader
            function toggleLoader(show) {
                if (show) {
                    $(".loader").addClass("active");
                } else {
                    $(".loader").removeClass("active");
                }
            }

            // Update devices dropdown when user changes
            $("#user_name").change(function() {
                const username = $(this).val();
                toggleLoader(true);

                $.ajax({
                    url: 'get_user_devices.php',
                    method: 'POST',
                    data: { username: username },
                    success: function(response) {
                        const devices = JSON.parse(response);
                        const deviceSelect = $("#device_id");
                        deviceSelect.empty();
                        deviceSelect.append('<option value="">Select Device</option>');
                        
                        devices.forEach(function(device) {
                            deviceSelect.append(`<option value="${device.device_id}">${device.device_id}</option>`);
                        });
                    },
                    error: function() {
                        showMessage("Error fetching devices", "error");
                    },
                    complete: function() {
                        toggleLoader(false);
                    }
                });
            });

            // Show message
            function showMessage(message, type = 'success') {
                const icon = type === 'success' ? 'check_circle' : 'error';
                $(".msg_task")
                    .removeClass("success error")
                    .addClass(type)
                    .html(`<span class="material-icons">${icon}</span>${message}`)
                    .show();
                
                setTimeout(() => {
                    $(".msg_task").fadeOut();
                }, 5000);
            }

            // Fetch and display data
            function fetchData() {
                const deviceId = $("#device_id").val();
                if (!deviceId) {
                    showMessage("Please select a device", "error");
                    return;
                }

                const startDate = $("#start_date").val();
                const endDate = $("#end_date").val();

                if (!startDate || !endDate) {
                    showMessage("Please select date range", "error");
                    return;
                }

                toggleLoader(true);

                $.ajax({
                    url: 'get_received_data.php',
                    method: 'POST',
                    data: {
                        device_id: deviceId,
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        try {
                            const data = JSON.parse(response);
                            const tbody = $("#dataTableBody");
                            const thead = $("#tableHeader");
                            tbody.empty();

                            if (data.length === 0) {
                                thead.hide();
                                tbody.append('<tr><td colspan="5" style="text-align: center;">No data found</td></tr>');
                                return;
                            }

                            thead.show();
                            data.forEach(function(row) {
                                tbody.append(`
                                    <tr>
                                        <td>${row.date}</td>
                                        <td>${row.time}</td>
                                        <td>${row.device_id}</td>
                                        <td>${row.data}</td>
                                        <td>${row.recharge_status === 'y' ? 'Yes' : 'No'}</td>
                                    </tr>
                                `);
                            });
                        } catch (e) {
                            showMessage("Error processing data", "error");
                            $("#tableHeader").hide();
                        }
                    },
                    error: function() {
                        showMessage("Error fetching data", "error");
                        $("#tableHeader").hide();
                    },
                    complete: function() {
                        toggleLoader(false);
                    }
                });
            }

            // Bind click event to Get Data button
            $("#getData").click(fetchData);

            // Trigger user change to load initial devices
            $("#user_name").trigger('change');
	});
</script>
</body>
</html>