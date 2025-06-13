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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recharge History</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <link rel="stylesheet" type="text/css" href="../css/thickbox.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui.css">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --danger-color: #dc2626;
            --danger-hover: #b91c1c;
            --success-color: #059669;
            --border-color: #e5e7eb;
            --text-primary: #111827;
            --text-secondary: #4b5563;
            --bg-hover: #f8fafc;
            --bg-gray: #f3f4f6;
        }

        /* Reset styles */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background: var(--bg-gray);
            color: var(--text-primary);
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            line-height: 1.5;
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        /* Main container */
        .recharge-history {
            width: 100%;
            max-width: 800px;
            margin: 1rem auto;
            padding: 0 1rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
        }

        /* Message container */
        .msg-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 0.75rem;
            border-radius: 6px;
            background: #f0fdf4;
            color: var(--success-color);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            animation: slideIn 0.3s ease-out;
            border: 1px solid #bbf7d0;
            font-size: 0.875rem;
            text-align: center;
        }

        .msg-container.error {
            background: #fef2f2;
            color: var(--danger-color);
            border-color: #fecaca;
        }

        /* Table container with shadow */
        .table-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            overflow: auto;
            max-height: calc(100vh - 22rem);
            scrollbar-width: none;
            -ms-overflow-style: none;
            width: 100%;
        }

        /* Table styles */
        .history-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
            font-size: 0.875rem;
        }

        .history-table th {
            background: white;
            padding: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
            text-align: left;
            border-bottom: 2px solid var(--border-color);
            white-space: nowrap;
            position: sticky;
            top: 0;
            z-index: 1;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .history-table td {
            padding: 0.625rem 0.75rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 0.875rem;
        }

        .history-table tr:last-child td {
            border-bottom: none;
        }

        .history-table tr:hover td {
            background: var(--bg-hover);
        }

        /* Form styles */
        .recharge-form {
            background: white;
            padding: 1.25rem;
            border-radius: 8px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        .form-label {
            display: block;
            font-size: 0.75rem;
            font-weight: 500;
            color: var(--text-primary);
            margin-bottom: 0.375rem;
        }

        .form-control {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            box-sizing: border-box;
            background: white;
            height: 36px;
        }

        .form-control:hover {
            border-color: var(--primary-color);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .submit-button {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.5rem 1rem;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            width: 100%;
            justify-content: center;
            height: 36px;
        }

        .submit-button .material-icons {
            font-size: 18px;
        }

        .submit-button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.1);
        }

        .submit-button:active {
            transform: translateY(0);
        }

        /* Loading indicator */
        .loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(to right, var(--primary-color), var(--primary-hover));
            animation: loading 1.5s infinite ease-in-out;
            z-index: 1000;
            display: none;
        }

        @keyframes loading {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* Datepicker customization */
        .ui-datepicker {
            padding: 0.75rem;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 8px;
            font-family: inherit;
            font-size: 0.75rem;
        }

        .ui-datepicker .ui-datepicker-header {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            padding: 0.5rem;
            margin: -0.75rem -0.75rem 0.75rem -0.75rem;
        }

        .ui-datepicker th {
            color: var(--text-secondary);
            font-weight: 500;
            padding: 0.375rem;
        }

        .ui-datepicker td span, .ui-datepicker td a {
            text-align: center;
            border-radius: 4px;
            padding: 0.375rem;
            transition: all 0.2s ease;
        }

        .ui-state-default, .ui-widget-content .ui-state-default {
            border: none;
            background: transparent;
            font-weight: normal;
            color: var(--text-primary);
        }

        .ui-state-highlight, .ui-widget-content .ui-state-highlight {
            background: var(--bg-hover);
            color: var(--primary-color);
        }

        .ui-state-active, .ui-widget-content .ui-state-active {
            background: var(--primary-color);
            color: white;
        }

        /* Center align table headers and content */
        .history-table th {
            text-align: center;
        }

        .history-table td {
            text-align: center;
        }

        /* Adjust the actions column */
        .history-table td:last-child {
            text-align: center;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        /* Message task container */
        .msg_task {
            width: 100%;
            text-align: center;
            margin-bottom: 1rem;
        }

        /* Submit button container */
        .submit-button-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .submit-button {
            max-width: 200px;
        }

        /* Responsive styles */
        @media (max-width: 768px) {
            .recharge-history {
                margin: 0.5rem auto;
                padding: 0 0.75rem;
            }

            .table-container {
                margin: 0;
                width: 100%;
            }

            .recharge-form {
                padding: 1rem;
                width: calc(100% - 1.5rem);
            }

            .action-buttons {
                flex-direction: row;
                gap: 0.375rem;
            }

            .submit-button {
                max-width: 100%;
            }
        }

        @keyframes slideIn {
            from { transform: translateY(-10px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        /* Button styles */
        .action-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: 0.375rem 0.75rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            white-space: nowrap;
        }

        .action-button .material-icons {
            font-size: 16px;
        }

        .edit-button {
            background: var(--primary-color);
            color: white;
        }

        .edit-button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .delete-button {
            background: white;
            color: var(--danger-color);
            border: 1px solid var(--danger-color);
        }

        .delete-button:hover {
            background: var(--danger-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(220, 38, 38, 0.1);
        }

        /* Additional responsive styles */
        @media (max-width: 768px) {
            .history-table th,
            .history-table td {
                padding: 0.5rem 0.75rem;
            }

            .action-button {
                padding: 0.375rem 0.5rem;
                font-size: 0.75rem;
            }

            .form-group {
                margin-bottom: 0.75rem;
            }
        }
    </style>
</head>
<body>
<div class="loading-indicator"></div>

<?php 
if(isset($_REQUEST['device_id'])==false){
    echo '<div class="msg-container error">
        <span class="material-icons">error</span>
        <span>No device ID provided</span>
    </div>';
    return;
}

$device_id=$_REQUEST['device_id'];
include_once '../model/admindao.php';

$adao=new AdminDao();
$rechargeArr=$adao->getRechargeHistoryByDeviceId($device_id);

$c_height = isset($_REQUEST['c_height']) ? $_REQUEST['c_height']/2 : 350;
$c_width = isset($_REQUEST['c_width']) ? $_REQUEST['c_width']/2 : 400;
?>

<div class="recharge-history">
    <input type="hidden" name="device_id_url" value="<?php echo "get_recharge_history.php?device_id=".$device_id."&keepThis=true&TB_iframe=true&height=".$c_height."&width=".$c_width."";?>"/>

    <?php if($rechargeArr==null): ?>
        <div class="msg-container">
            <span class="material-icons">info</span>
            <span>No recharge history available</span>
        </div>
    <?php else: ?>
        <div class="table-container">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Device ID</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rechargeArr as $recharge): 
                        $start_date = date('Y-M-d', strtotime($recharge->start_date));
                        $end_date = date('Y-M-d', strtotime($recharge->end_date));
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($recharge->device_id); ?></td>
                            <td><?php echo htmlspecialchars($start_date); ?></td>
                            <td><?php echo htmlspecialchars($end_date); ?></td>
                            <td>
                                <input type="hidden" name="reload_href<?php echo $recharge->id; ?>" 
                                    value="get_recharge_history.php?device_id=<?php echo $recharge->device_id; ?>&keepThis=true&TB_iframe=true&height=<?php echo $c_height; ?>&width=<?php echo $c_width; ?>"/>
                                <div class="action-buttons">
                                    <a href="#" class="action-button edit-button thickbox_link" title="Update Recharge" id="<?php echo $recharge->id; ?>">
                                        <span class="material-icons">edit</span>
                                        Edit
                                    </a>
                                    <a href="#" class="action-button delete-button delete_recharge" id="<?php echo $recharge->id; ?>">
                                        <span class="material-icons">delete</span>
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

    <div class="recharge-form">
        <form id="recahrge_frm">
            <input type="hidden" name="device_id" value="<?php echo htmlspecialchars($device_id); ?>"/>
            
            <div class="form-group">
                <label class="form-label" for="start_date">Start Date</label>
                <input type="text" id="start_date" class="form-control" name="start_date" placeholder="Select start date" autocomplete="off">
            </div>
            
            <div class="form-group">
                <label class="form-label" for="end_date">End Date</label>
                <input type="text" id="end_date" class="form-control" name="end_date" placeholder="Select end date" autocomplete="off">
            </div>

            <div class="msg_task"></div>
            
            <button type="button" name="recharge_device" class="submit-button">
                <span class="material-icons">save</span>
                Submit
            </button>
        </form>
    </div>
</div>

<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/jquery.fixedtable.js"></script>
<script src="../js/thickbox.js"></script>
<script src="js/update_recharge.js"></script>
<script>
    $(function() {
        // Initialize datepickers
        $("input[name='start_date'], input[name='end_date']").datepicker({
            dateFormat: 'yy-mm-dd',
            changeMonth: true,
            changeYear: true,
            showAnim: 'fadeIn'
        });

        // Show loading indicator during AJAX requests
        $(document).ajaxStart(function() {
            $('.loading-indicator').fadeIn();
        }).ajaxStop(function() {
            $('.loading-indicator').fadeOut();
        });

        // Initialize message handling
        function showMessage(message, isError = false) {
            $('.msg_task')
                .html(`<div class="msg-container ${isError ? 'error' : ''}">
                    <span class="material-icons">${isError ? 'error' : 'check_circle'}</span>
                    <span>${message}</span>
                </div>`)
                .hide()
                .fadeIn();
        }
    });
</script>
</body>
</html>
