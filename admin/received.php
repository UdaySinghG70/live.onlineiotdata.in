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
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #fafafa;
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            margin-bottom: 30px;
            text-align: center;
        }

        .page-title {
            font-size: 28px;
            font-weight: 300;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .page-subtitle {
            color: #7f8c8d;
            font-size: 16px;
            font-weight: 400;
        }

        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .filters-section {
            padding: 24px;
            border-bottom: 1px solid #ecf0f1;
            background: #f8f9fa;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            align-items: end;
	}

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 13px;
            font-weight: 500;
            color: #34495e;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
            transition: border-color 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.1);
        }

        select.form-control {
            cursor: pointer;
        }

        .btn-primary {
            background: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .data-section {
            padding: 24px;
        }

        .data-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .data-title {
            font-size: 18px;
            font-weight: 500;
            color: #2c3e50;
	}

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
	}

        .data-table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #34495e;
            border-bottom: 2px solid #ecf0f1;
	}

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
            color: #2c3e50;
        }

        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #7f8c8d;
        }

        .empty-state .material-icons {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            visibility: hidden;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .loader.active {
            visibility: visible;
            opacity: 1;
        }

        .loader::after {
            content: '';
            width: 32px;
            height: 32px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .message {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .message .material-icons {
            font-size: 18px;
        }

        /* Datepicker styling */
        .ui-datepicker {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            padding: 12px;
            font-family: inherit;
            width: 280px !important;
        }

        .ui-datepicker-header {
            background: #3498db;
            color: white;
            border-radius: 6px;
            padding: 8px 12px;
            margin: -12px -12px 8px;
        }

        .ui-datepicker-title {
            font-weight: 500;
            font-size: 14px;
        }

        .ui-datepicker-prev, .ui-datepicker-next {
            cursor: pointer;
            position: absolute;
            top: 8px;
            width: 20px;
            height: 20px;
            text-align: center;
            color: white;
            text-decoration: none;
        }

        .ui-datepicker-prev { left: 12px; }
        .ui-datepicker-next { right: 12px; }

        .ui-datepicker-calendar {
            width: 100%;
            border-collapse: collapse;
        }

        .ui-datepicker-calendar th {
            color: #34495e;
            font-weight: 600;
            font-size: 12px;
            padding: 6px 0;
        }

        .ui-datepicker-calendar td {
            padding: 1px;
        }

        .ui-datepicker-calendar .ui-state-default {
            display: block;
            padding: 6px;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            border: 1px solid transparent;
            color: #2c3e50;
            background: #f8f9fa;
            font-size: 12px;
        }

        .ui-datepicker-calendar .ui-state-hover {
            background: #e9ecef;
            border-color: #dee2e6;
        }

        .ui-datepicker-calendar .ui-state-active {
            background: #3498db;
            color: white;
}

        @media (max-width: 768px) {
            .main-container {
                padding: 16px;
            }

            .filters-grid {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            .page-title {
                font-size: 24px;
            }

            .data-table {
                font-size: 12px;
            }

            .data-table th,
            .data-table td {
                padding: 8px;
            }
        }

        /* Improved table appearance and horizontal scrolling */
        .table-responsive {
                overflow-x: auto;
            max-width: 100vw;
        }
        .data-table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            min-width: 700px;
            background: #fff;
        }
        .data-table th, .data-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
                white-space: nowrap;
        }
        .data-table th {
            position: sticky;
            top: 0;
            background: #f3f4f6;
            z-index: 2;
            font-weight: 600;
            color: #222;
            border-bottom: 2px solid #d1d5db;
        }
        .data-table tr:nth-child(even) {
            background: #f9fafb;
        }
        .data-table tr:hover {
            background: #f1f5f9;
        }
        .data-table td {
            font-size: 15px;
        }
        .data-table th:first-child, .data-table td:first-child {
            border-left: 0;
        }
        .data-table th, .data-table td {
            border-right: 1px solid #f1f1f1;
        }
        .data-table th:last-child, .data-table td:last-child {
            border-right: 0;
        }
        @media (max-width: 768px) {
            .data-table {
                font-size: 13px;
                min-width: 600px;
            }
        }
        /* Add CSS for grab-to-scroll */
        .table-responsive {
            cursor: grab;
        }
        .table-responsive.grabbing {
            cursor: grabbing;
        }

        /* Add CSS for .filters-row for better alignment and responsiveness */
        .filters-row {
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }
        .filters-row .form-group {
            margin-bottom: 0;
        }
        .filters-row label {
            font-size: 13px;
            font-weight: 500;
            color: #34495e;
            margin-bottom: 4px;
        }
        .filters-row input[type='text'],
        .filters-row input[type='time'],
        .filters-row select {
            font-size: 14px;
        }
        @media (max-width: 900px) {
            .filters-row {
                flex-direction: column;
                align-items: stretch;
            }
        }

        /* Add or update CSS to center align all th and td in .data-table */
        if (!$('style#table-style-center').length) {
            $("<style id='table-style-center'>.data-table th, .data-table td { text-align: center !important; }</style>").appendTo('head');
        }

        /* Update the CSS for .data-table th to ensure both horizontal and vertical centering, including multi-line content */
        if (!$('style#table-style-th-center').length) {
            $("<style id='table-style-th-center'>.data-table th { text-align: center !important; vertical-align: middle !important; justify-content: center; align-items: center; } .data-table th > span { display: block; width: 100%; text-align: center; }</style>").appendTo('head');
        }

        // Add CSS to fix datepicker z-index overlap
        if (!$('style#datepicker-zindex-fix').length) {
            $("<style id='datepicker-zindex-fix'>.ui-datepicker{z-index:99999 !important;}</style>").appendTo('head');
        }

        // Add CSS for .download-btns if not present
        if (!$('style#download-btns-style').length) {
            $("<style id='download-btns-style'>.download-btns .btn-primary{min-width:150px;font-weight:600;box-shadow:0 1px 4px #0001;} .download-btns{margin-bottom:10px;}</style>").appendTo('head');
        }
    </style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <div class="loader"></div>

    <main class="main-container">
        <div class="page-header">
            <h1 class="page-title">Received Data</h1>
            <p class="page-subtitle">Monitor IoT device data and recharge status</p>
        </div>

        <div class="content-card">
            <div class="filters-section">
                <div class="filters-row">
                    <div class="form-group" style="min-width:140px;">
                        <label for="user_name">User</label>
                    <select id="user_name" name="user_name" class="form-control">
    					<?php 
                        foreach($userArr as $user){
                            echo "<option value='".htmlspecialchars($user->user_name)."'>".htmlspecialchars($user->user_name)."</option>";
    					}
    					?>
    					</select>
					</div>
					
                    <div class="form-group" style="min-width:140px;">
                        <label for="device_id">Device</label>
                    <select id="device_id" name="device_id" class="form-control">
                        <option value="">Select Device</option>
                    </select>
                </div>

                    <div class="form-group" style="display:flex;flex-direction:column;min-width:180px;">
                    <label for="start_date">Start Date</label>
                        <div style="display:flex;gap:6px;">
                            <input type="text" id="start_date" name="start_date" class="form-control datepicker" readonly style="min-width:100px;">
                            <input type="time" id="start_time" name="start_time" class="form-control" style="width:120px;" placeholder="Start Time">
                        </div>
                </div>

                    <div class="form-group" style="display:flex;flex-direction:column;min-width:180px;">
                    <label for="end_date">End Date</label>
                        <div style="display:flex;gap:6px;">
                            <input type="text" id="end_date" name="end_date" class="form-control datepicker" readonly style="min-width:100px;">
                            <input type="time" id="end_time" name="end_time" class="form-control" style="width:120px;" placeholder="End Time">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label>&nbsp;</label>
                        <button type="button" id="getData" class="btn-primary" style="height:40px;min-width:90px;"> <span class="material-icons">search</span> Search </button>
                    </div>
                </div>

                <div class="message" id="message" style="display: none;"></div>
            </div>

            <div class="data-section">
                <div class="data-header">
                    <h2 class="data-title">Results</h2>
                </div>
                
                <div id="dataContent">
                    <div class="empty-state">
                        <span class="material-icons">data_usage</span>
                        <p>Select filters and click Search to view data</p>
                    </div>
                </div>
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

            // Set default values for time inputs
            $('#start_time').val('00:00');
            $('#end_time').val('23:59');

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
                if (!username) {
                    $("#device_id").empty().append('<option value="">Select Device</option>');
                    return;
                }
                
                toggleLoader(true);

                $.ajax({
                    url: 'get_user_devices.php',
                    method: 'POST',
                    data: { username: username },
                    success: function(response) {
                        try {
                        const devices = JSON.parse(response);
                        const deviceSelect = $("#device_id");
                        deviceSelect.empty();
                        deviceSelect.append('<option value="">Select Device</option>');
                        
                            if (devices && devices.length > 0) {
                        devices.forEach(function(device) {
                            deviceSelect.append(`<option value="${device.device_id}">${device.device_id}</option>`);
                        });
                            } else {
                                deviceSelect.append('<option value="" disabled>No devices found for this user</option>');
                            }
                        } catch (e) {
                            showMessage("Error parsing device data", "error");
                        }
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
                $("#message")
                    .removeClass("success error")
                    .addClass(type)
                    .html(`<span class="material-icons">${icon}</span>${message}`)
                    .show();
                
                setTimeout(() => {
                    $("#message").fadeOut();
                }, 5000);
            }

            let currentPage = 1;
            let totalPages = 1;

            function renderPagination(total, page) {
                totalPages = Math.ceil(total / 50) || 1;
                let html = '';
                if (totalPages > 1) {
                    html += `<div class="pagination" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">`;
                    html += `<button class="page-btn nav-btn" id="firstPage" ${page <= 1 ? 'disabled' : ''} title="First Page">&laquo;</button>`;
                    html += `<button class="page-btn nav-btn" id="prevPage" ${page <= 1 ? 'disabled' : ''} title="Previous Page">&lsaquo;</button>`;
                    html += `<span class="page-label" style="background:#2563eb;color:#fff;padding:6px 18px;border-radius:20px;font-weight:600;box-shadow:0 1px 4px #2563eb22;letter-spacing:0.5px;">Page <strong>${page}</strong> of <strong>${totalPages}</strong></span>`;
                    html += `<button class="page-btn nav-btn" id="nextPage" ${page >= totalPages ? 'disabled' : ''} title="Next Page">&rsaquo;</button>`;
                    html += `<button class="page-btn nav-btn" id="lastPage" ${page >= totalPages ? 'disabled' : ''} title="Last Page">&raquo;</button>`;
                    html += `<span style='margin-left:12px;'>Go to <input type='number' min='1' max='${totalPages}' id='gotoPageInput' value='${page}' style='width:60px;padding:6px 8px;border-radius:8px;border:1px solid #bfcfff;margin-right:4px;'> <button class='page-btn nav-btn' id='gotoPageBtn'>Go</button></span>`;
                    html += '</div>';
                }
                return html;
            }

            let deviceParamNames = [];
            let deviceParamUnits = [];

            function fetchDeviceParams(deviceId, callback) {
                if (!deviceId) {
                    deviceParamNames = [];
                    deviceParamUnits = [];
                    if (typeof callback === 'function') callback([]);
                    return;
                }
                $.ajax({
                    url: 'get_device_params.php',
                    method: 'POST',
                    data: { device_id: deviceId },
                    success: function(response) {
                        try {
                            const resp = JSON.parse(response);
                            if (resp.params && Array.isArray(resp.params)) {
                                deviceParamNames = resp.params.map(p => p.name);
                                deviceParamUnits = resp.params.map(p => p.unit);
                                if (typeof callback === 'function') callback(deviceParamNames);
                            } else {
                                deviceParamNames = [];
                                deviceParamUnits = [];
                                if (typeof callback === 'function') callback([]);
                            }
                        } catch (e) {
                            deviceParamNames = [];
                            deviceParamUnits = [];
                            if (typeof callback === 'function') callback([]);
                        }
                    },
                    error: function() {
                        deviceParamNames = [];
                        deviceParamUnits = [];
                        if (typeof callback === 'function') callback([]);
                    }
                });
            }

            // When device changes, fetch params
            $('#device_id').change(function() {
                const deviceId = $(this).val();
                fetchDeviceParams(deviceId);
            });

            function fetchData(page = 1) {
                const deviceId = $("#device_id").val();
                if (!deviceId) {
                    showMessage("Please select a device", "error");
                    return;
                }
                // Fetch param names before fetching data
                fetchDeviceParams(deviceId, function() {
                    fetchDataWithParams(page);
                });
                }

            function fetchDataWithParams(page = 1) {
                const deviceId = $("#device_id").val();
                const startDate = $("#start_date").val();
                const endDate = $("#end_date").val();
                const startTime = $("#start_time").val();
                const endTime = $("#end_time").val();
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
                        end_date: endDate,
                        start_time: startTime,
                        end_time: endTime,
                        page: page
                    },
                    success: function(response) {
                        try {
                            const resp = JSON.parse(response);
                            const dataContent = $("#dataContent");
                            if (resp.error) {
                                showMessage(resp.error, "error");
                                dataContent.html(`
                                    <div class=\"empty-state\">
                                        <span class=\"material-icons\">error</span>
                                        <p>Error: ${resp.error}</p>
                                    </div>
                                `);
                                $("#paginationContainer").html("");
                                return;
                            }
                            const data = resp.data || [];
                            const total = resp.total || 0;
                            currentPage = page;
                            if (!data || data.length === 0) {
                                dataContent.html(`
                                    <div class=\"empty-state\">
                                        <span class=\"material-icons\">inbox</span>
                                        <p>No data found for the selected criteria</p>
                                    </div>
                                `);
                                $("#paginationContainer").html("");
                                return;
                            }
                            let deviceId = '';
                            if (data.length > 0) {
                                deviceId = data[0].device_id;
                            }
                            let deviceIdHtml = '';
                            if (deviceId) {
                                deviceIdHtml = `<div style=\"margin-bottom:12px;font-weight:500;\">Device ID: <code style=\"background:#f8f9fa;padding:2px 6px;border-radius:4px;\">${deviceId}</code></div>`;
                            }
                            // Use param names for headers
                            let csvHeaders = deviceParamNames.length > 0 ? deviceParamNames : [];
                            let csvUnits = deviceParamUnits.length > 0 ? deviceParamUnits : [];
                            let downloadBtnsHtml = `
                                <div class="download-btns" style="display:flex;gap:10px;margin-bottom:12px;">
                                    <button id="downloadExcel" class="btn-primary" style="background:#21ba45;">Download as Excel</button>
                                    <button id="downloadCSV" class="btn-primary" style="background:#2185d0;">Download as CSV</button>
                                </div>
                            `;
                            let tableHtml = `
                                <div style=\"display:flex;align-items:center;justify-content:space-between;gap:16px;margin-bottom:12px;\">
                                    ${deviceIdHtml}
                                    <span id=\"paginationContainer\"></span>
                                </div>
                                ${downloadBtnsHtml}
                                <div class=\"table-responsive\" style=\"overflow-x:auto;max-width:100vw;\">
                                    <table class=\"data-table\">
                                        <thead>
                                            <tr>
                                                <th>Sr. No.</th>
                                                <th>Date</th>
                                                <th>Time</th>
                                                ${csvHeaders.map((header, i) => `<th>${header}${csvUnits[i] ? `<br><span style='font-size:12px;color:#888;'>${csvUnits[i]}</span>` : ''}</th>`).join('')}
                                            </tr>
                                        </thead>
                                        <tbody>
                            `;
                            data.forEach(function(row, idx) {
                                const srNo = (currentPage - 1) * 50 + idx + 1;
                                const csvValues = row.data ? row.data.split(',') : [];
                                tableHtml += `
                                    <tr>
                                        <td>${srNo}</td>
                                        <td>${row.date}</td>
                                        <td>${row.time || ''}</td>
                                `;
                                csvValues.forEach(function(value, colIdx) {
                                    const cleanValue = value.trim();
                                    tableHtml += `<td>${cleanValue}</td>`;
                                });
                                tableHtml += `</tr>`;
                            });
                            tableHtml += '</tbody></table></div>';
                            dataContent.html(tableHtml);
                            $("#paginationContainer").html(renderPagination(total, page));
                        } catch (e) {
                            showMessage("Error processing data", "error");
                            $("#dataContent").html(`
                                <div class=\"empty-state\">
                                    <span class=\"material-icons\">error</span>
                                    <p>Error processing data</p>
                                </div>
                            `);
                            $("#paginationContainer").html("");
                        }
                    },
                    error: function() {
                        showMessage("Error fetching data", "error");
                        $("#dataContent").html(`
                            <div class=\"empty-state\">
                                <span class=\"material-icons\">error</span>
                                <p>Error fetching data</p>
                            </div>
                        `);
                        $("#paginationContainer").html("");
                    },
                    complete: function() {
                        toggleLoader(false);
                    }
                });
            }

            // Bind click event to Get Data button
            $("#getData").click(function() { fetchData(1); });

            // Pagination controls (event delegation)
            $(document).on('click', '#firstPage', function() {
                if (currentPage > 1) fetchData(1);
            });
            $(document).on('click', '#lastPage', function() {
                if (currentPage < totalPages) fetchData(totalPages);
            });
            $(document).on('click', '#prevPage', function() {
                if (currentPage > 1) fetchData(currentPage - 1);
            });
            $(document).on('click', '#nextPage', function() {
                if (currentPage < totalPages) fetchData(currentPage + 1);
            });
            $(document).on('click', '#gotoPageBtn', function() {
                let val = parseInt($('#gotoPageInput').val(), 10);
                if (!isNaN(val) && val >= 1 && val <= totalPages) {
                    fetchData(val);
                } else {
                    $('#gotoPageInput').val(currentPage);
                }
            });
            $(document).on('keypress', '#gotoPageInput', function(e) {
                if (e.which === 13) {
                    $('#gotoPageBtn').click();
                }
            });

            // Load initial devices without showing loader
            const initialUsername = $("#user_name").val();
            if (initialUsername) {
                $.ajax({
                    url: 'get_user_devices.php',
                    method: 'POST',
                    data: { username: initialUsername },
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
                        // Silently handle error without showing message
                    }
                });
            }

            // Add CSS for improved table appearance and horizontal scrolling
            if (!$('style#table-style').length) {
                $("<style id='table-style'>.table-responsive{overflow-x:auto;max-width:100vw;} .data-table{border-collapse:separate;border-spacing:0;width:100%;min-width:700px;background:#fff;} .data-table th,.data-table td{padding:8px 12px;border-bottom:1px solid #e5e7eb;white-space:nowrap;} .data-table th{position:sticky;top:0;background:#f3f4f6;z-index:2;font-weight:600;color:#222;border-bottom:2px solid #d1d5db;} .data-table tr:nth-child(even){background:#f9fafb;} .data-table tr:hover{background:#f1f5f9;} .data-table td{font-size:15px;} .data-table th:first-child,.data-table td:first-child{border-left:0;} .data-table th,.data-table td{border-right:1px solid #f1f1f1;} .data-table th:last-child,.data-table td:last-child{border-right:0;} @media (max-width: 768px){.data-table{font-size:13px;min-width:600px;}}</style>").appendTo('head');
            }

            // Add CSS for grab-to-scroll
            if (!$('style#grab-scroll-style').length) {
                $("<style id='grab-scroll-style'>.table-responsive{cursor:grab;} .table-responsive.grabbing{cursor:grabbing;}</style>").appendTo('head');
            }

            // Add JS for grab-to-scroll
            $(document).off('mousedown.grabscroll').on('mousedown.grabscroll', '.table-responsive', function(e) {
                const el = this;
                let startX = e.pageX - el.scrollLeft;
                let isDown = true;
                $(el).addClass('grabbing');
                function mousemove(ev) {
                    if (!isDown) return;
                    el.scrollLeft = ev.pageX - startX;
                }
                function mouseup() {
                    isDown = false;
                    $(el).removeClass('grabbing');
                    $(window).off('mousemove.grabscroll', mousemove);
                    $(window).off('mouseup.grabscroll', mouseup);
                }
                $(window).on('mousemove.grabscroll', mousemove);
                $(window).on('mouseup.grabscroll', mouseup);
            });

            // Add improved CSS for .nav-btn and .page-label
            if (!$('style#pagination-style-modern').length) {
                $("<style id='pagination-style-modern'>.pagination .nav-btn{background:#fff;border:1.5px solid #2563eb;color:#2563eb;padding:7px 16px;margin:0 1px;border-radius:18px;cursor:pointer;transition:background 0.18s,border 0.18s,box-shadow 0.18s;outline:none;font-size:16px;box-shadow:0 1px 4px #2563eb11;} .pagination .nav-btn:disabled{background:#f1f5f9;color:#b3b3b3;border:1.5px solid #e5e7eb;cursor:not-allowed;box-shadow:none;} .pagination .nav-btn:not(:disabled):hover,.pagination .nav-btn:not(:disabled):focus{background:#2563eb;color:#fff;border:1.5px solid #2563eb;box-shadow:0 2px 8px #2563eb22;} .pagination .page-label{margin:0 8px;display:inline-block;} .pagination input[type=number]{font-size:15px;} .pagination{margin-bottom:0.5em;}</style>").appendTo('head');
            }

            // Add click handlers for download buttons
            $(document).off('click.downloadExcel').on('click.downloadExcel', '#downloadExcel', function() {
                triggerDownload('excel');
            });
            $(document).off('click.downloadCSV').on('click.downloadCSV', '#downloadCSV', function() {
                triggerDownload('csv');
            });
            function triggerDownload(type) {
                const deviceId = $("#device_id").val();
                const userName = $("#user_name").val();
                const startDate = $("#start_date").val();
                const endDate = $("#end_date").val();
                const startTime = $("#start_time").val();
                const endTime = $("#end_time").val();
                if (!deviceId || !startDate || !endDate) {
                    showMessage("Please select all filters before downloading.", "error");
                    return;
                }
                const params = $.param({
                    user_name: userName,
                    device_id: deviceId,
                    start_date: startDate,
                    end_date: endDate,
                    start_time: startTime,
                    end_time: endTime,
                    type: type
                });
                window.open('export_received_data.php?' + params, '_blank');
            }
	});
</script>
</body>
</html>