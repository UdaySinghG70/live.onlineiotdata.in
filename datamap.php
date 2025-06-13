<?php
session_start();
require_once 'model/querymanager.php';

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user_name'];

// Get all devices for the logged-in user
$devices_query = "SELECT device_id FROM devices WHERE user = '$user'";
$devices_result = QueryManager::getMultipleRow($devices_query);

// Get user department name for header
include_once 'model/logindao.php';
$ldao = new LoginDao();
$userObj = $ldao->getUserByUserName($user);
$department_name = $userObj ? $userObj->department_name : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Availability</title>
    <link rel="stylesheet" type="text/css" href="css/util.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
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
            background-color: #f5f5f5;
            padding: 0;
            min-height: 100vh;
        }

        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header h1 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }

        /* Data Graph Styles */
        .Data-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .data-summary {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .summary-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #495057;
        }

        .summary-text {
            display: flex;
            flex-direction: column;
        }

        .summary-label {
            font-size: 12px;
            color: #6c757d;
        }

        .summary-value {
            font-size: 16px;
            font-weight: 600;
            color: #212529;
        }



        .device-selector {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .device-selector select {
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        #deviceSelect {
            min-width: 200px;
        }

        #yearSelect {
            min-width: 100px;
            margin-left: 10px;
        }

        .check-button {
            padding: 8px 16px;
            background-color: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .check-button:hover {
            background-color: #1d4ed8;
        }

        .Data-graph {
            margin-top: 20px;
            overflow-x: auto;
        }

        .graph-container {
            min-width: 600px;
            padding: 20px;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .days-header {
            display: flex;
            margin-left: 40px;
            margin-bottom: 4px;
            color: #666;
            font-size: 12px;
            gap: 4px;
        }

        .day-number {
            width: 15px;
            text-align: center;
            margin-right: 6px;
        }

        .graph-content {
            display: flex;
        }

        .months {
            display: flex;
            flex-direction: column;
            gap: 8px;
            color: #666;
            font-size: 14px;
            padding-right: 10px;
            width: 40px;
            padding-top: 0;
        }

        .month {
            height: 15px;
            line-height: 15px;
            text-align: right;
            padding-right: 8px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .squares-container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            padding-top: 0;
        }

        .month-row {
            display: flex;
            gap: 4px;
            height: 15px;
            margin-bottom: 2px;
        }

        .square {
            width: 15px;
            height: 15px;
            background-color: #ebedf0;
            border-radius: 3px;
            transition: transform 0.1s ease;
            position: relative;
            flex-shrink: 0;
            margin-right: 6px;
        }

        .square.empty {
            background-color: transparent;
        }

        .square:not(.empty):hover {
            transform: scale(1.2);
            z-index: 1;
        }

        .square.empty {
            background-color: transparent;
        }

        /* Tooltip style */
        .square:not(.empty)[title]:hover::after {
            content: attr(title);
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            padding: 4px 8px;
            background-color: #24292e;
            color: white;
            font-size: 12px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 2;
            margin-bottom: 5px;
        }

        .square[data-level="0"] { background-color: #ebedf0; }
        .square[data-level="1"] { background-color: #b2f2bb; }
        .square[data-level="2"] { background-color: #69db7c; }
        .square[data-level="3"] { background-color: #4dabf7; }
        .square[data-level="4"] { background-color: #845ef7; }
        .square[data-level="5"] { background-color: #f03e3e; }

        .legend {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
            justify-content: center;
            font-size: 14px;
            color: #666;
            padding-bottom: 10px;
        }

        .legend-square {
            width: 15px;
            height: 15px;
            border-radius: 3px;
        }

        .legend-square[data-level="0"] { background-color: #ebedf0; }
        .legend-square[data-level="1"] { background-color: #b2f2bb; }
        .legend-square[data-level="2"] { background-color: #69db7c; }
        .legend-square[data-level="3"] { background-color: #4dabf7; }
        .legend-square[data-level="4"] { background-color: #845ef7; }
        .legend-square[data-level="5"] { background-color: #f03e3e; }

        .device-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .device-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }

        .device-id {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 600;
        }

        .availability-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .param-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
            border: 1px solid #e9ecef;
            height: 100px;
            display: flex;
            flex-direction: column;
        }

        .param-name {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            margin-bottom: 8px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            margin-top: 8px;
            font-size: 13px;
        }

        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-active {
            background-color: #10b981;
        }

        .status-inactive {
            background-color: #ef4444;
        }

        .last-received {
            font-size: 12px;
            color: #6b7280;
            margin-top: auto;
        }

        .no-data {
            text-align: center;
            padding: 40px;
            color: #6c757d;
            font-size: 16px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        @media (max-width: 768px) {
            .main-container {
                padding: 10px;
            }

            .header {
                padding: 15px;
                margin-bottom: 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .device-selector {
                flex-direction: column;
                gap: 10px;
            }

            .device-selector select,
            .device-selector button {
                width: 100%;
                margin-left: 0 !important;
            }

            .data-summary {
                flex-direction: column;
                gap: 15px;
            }

            .summary-item {
                width: 100%;
            }

            .Data-graph {
                margin-top: 15px;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .graph-container {
                min-width: 500px;
                padding: 10px;
            }

            .days-header {
                margin-left: 30px;
            }

            .months {
                width: 30px;
            }

            .square {
                width: 12px;
                height: 12px;
                margin-right: 4px;
            }

            .legend {
                flex-wrap: wrap;
                gap: 5px;
                padding: 10px;
            }

            .legend-square {
                width: 12px;
                height: 12px;
            }

            .device-section {
                padding: 15px;
            }

            .device-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .device-id {
                font-size: 16px;
            }

            .availability-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 10px;
            }

            .param-card {
                padding: 10px;
                height: auto;
                min-height: 90px;
            }

            .param-name {
                font-size: 13px;
            }

            .status-indicator {
                font-size: 12px;
            }

            .last-received {
                font-size: 11px;
                margin-top: 5px;
            }

            .no-data {
                padding: 20px;
                font-size: 14px;
            }
        }

        @media (max-width: 480px) {
            .main-container {
                padding: 5px;
            }

            .header {
                padding: 10px;
            }

            .header h1 {
                font-size: 18px;
            }

            .graph-container {
                min-width: 400px;
            }

            .square {
                width: 10px;
                height: 10px;
                margin-right: 3px;
            }

            .legend-square {
                width: 10px;
                height: 10px;
            }

            .availability-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            }

            .param-card {
                min-height: 80px;
            }
        }

        /* Add touch-friendly styles */
        @media (hover: none) {
            .square:not(.empty):hover {
                transform: none;
            }

            .square:not(.empty)[title]::after {
                display: none;
            }

            .check-button {
                padding: 12px 16px; /* Larger touch target */
            }

            select {
                padding: 12px; /* Larger touch target */
            }
        }
    </style>
</head>
<body>
    <?php include_once 'header1.php'; ?>

    <div class="main-container">
        <div class="header">
            <h1>Data Availability Status</h1>
        </div>

        <!-- Data Graph Section -->
        <div class="Data-section">
            <div class="device-selector">
                <select id="deviceSelect">
                    <option value="">Select a device</option>
                    <?php
                    if ($devices_result && mysqli_num_rows($devices_result) > 0) {
                        mysqli_data_seek($devices_result, 0);
                        while ($device = mysqli_fetch_assoc($devices_result)) {
                            echo "<option value='" . htmlspecialchars($device['device_id']) . "'>" . 
                                 htmlspecialchars($device['device_id']) . "</option>";
                        }
                    }
                    ?>
                </select>
                <select id="yearSelect">
                    <?php
                    $currentYear = date('Y');
                    for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                        echo "<option value='$year'" . ($year == $currentYear ? " selected" : "") . ">$year</option>";
                    }
                    ?>
                </select>
                <button class="check-button" id="checkData">Check Data Availability</button>
            </div>
            <div class="data-summary">
                <div class="summary-item">
                    <div class="summary-icon">📊</div>
                    <div class="summary-text">
                        <span class="summary-label">Total Data Points</span>
                        <span class="summary-value" id="totalDataPoints">-</span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-icon">📅</div>
                    <div class="summary-text">
                        <span class="summary-label">Active Days</span>
                        <span class="summary-value" id="activeDays">-</span>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-icon">📈</div>
                    <div class="summary-text">
                        <span class="summary-label">Average Daily Entries</span>
                        <span class="summary-value" id="avgEntries">-</span>
                    </div>
                </div>
            </div>
            <div class="Data-graph">
                <div class="graph-container">
                    <div class="days-header"></div>
                    <div class="graph-content">
                        <div class="months"></div>
                        <div class="squares-container"></div>
                    </div>
                </div>
                <div class="legend">
                    <span>Less</span>
                    <div class="legend-square" data-level="0"></div>
                    <div class="legend-square" data-level="1"></div>
                    <div class="legend-square" data-level="2"></div>
                    <div class="legend-square" data-level="3"></div>
                    <div class="legend-square" data-level="4"></div>
                    <div class="legend-square" data-level="5"></div>
                    <span>More</span>
                </div>
            </div>
        </div>

        <!-- Existing real-time status section -->
        <?php
        if ($devices_result && mysqli_num_rows($devices_result) > 0) {
            while ($device = mysqli_fetch_assoc($devices_result)) {
                $device_id = $device['device_id'];
                
                // Get parameters for this device
                $params_query = "SELECT param_name FROM modem_params WHERE device_id = '$device_id' ORDER BY position";
                $params_result = QueryManager::getMultipleRow($params_query);

                echo "<div class='device-section'>";
                echo "<div class='device-header'>";
                echo "<div class='device-id'>Device ID: " . htmlspecialchars($device_id) . "</div>";
                echo "</div>";

                if ($params_result && mysqli_num_rows($params_result) > 0) {
                    echo "<div class='availability-grid'>";
                    
                    while ($param = mysqli_fetch_assoc($params_result)) {
                        // Get last received data timestamp for this parameter
                        $last_data_query = "SELECT MAX(timestamp) as last_timestamp FROM device_data 
                                          WHERE device_id = '$device_id' AND param_name = '" . $param['param_name'] . "'";
                        $last_data_result = QueryManager::getSingleRow($last_data_query);
                        
                        $last_timestamp = $last_data_result ? $last_data_result['last_timestamp'] : null;
                        $current_time = time();
                        $is_active = $last_timestamp && (strtotime($last_timestamp) > ($current_time - 300)); // 5 minutes threshold

                        echo "<div class='param-card'>";
                        echo "<div class='param-name'>" . htmlspecialchars($param['param_name']) . "</div>";
                        echo "<div class='status-indicator'>";
                        echo "<span class='status-dot " . ($is_active ? 'status-active' : 'status-inactive') . "'></span>";
                        echo "<span>" . ($is_active ? 'Active' : 'Inactive') . "</span>";
                        echo "</div>";
                        if ($last_timestamp) {
                            echo "<div class='last-received'>Last data: " . date('Y-m-d H:i:s', strtotime($last_timestamp)) . "</div>";
                        } else {
                            echo "<div class='last-received'>No data received</div>";
                        }
                        echo "</div>";
                    }
                    
                    echo "</div>";
                } else {
                    echo "<p class='no-data'>No parameters found for this device.</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<div class='no-data'>No devices found for this user.</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#data_map").addClass("active");

            // Initialize the Data graph
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const monthsContainer = $('.months');
            const squaresContainer = $('.squares-container');
            const levelThresholds = [0, 1, 289, 578, 867, 1156];  // Updated thresholds
            const monthWidths = {
                'Jan': 5, 'Feb': 4, 'Mar': 4, 'Apr': 4, 'May': 4, 'Jun': 4,
                'Jul': 5, 'Aug': 4, 'Sep': 4, 'Oct': 4, 'Nov': 4, 'Dec': 5
            };

            // Add CSS for the new color scheme
            $('<style>')
                .text(`
                    .square[data-level="0"] { background-color: #ebedf0; }
                    .square[data-level="1"] { background-color: #b2f2bb; }
                    .square[data-level="2"] { background-color: #69db7c; }
                    .square[data-level="3"] { background-color: #4dabf7; }
                    .square[data-level="4"] { background-color: #845ef7; }
                    .square[data-level="5"] { background-color: #f03e3e; }
                    
                    .legend-square[data-level="0"] { background-color: #ebedf0; }
                    .legend-square[data-level="1"] { background-color: #b2f2bb; }
                    .legend-square[data-level="2"] { background-color: #69db7c; }
                    .legend-square[data-level="3"] { background-color: #4dabf7; }
                    .legend-square[data-level="4"] { background-color: #845ef7; }
                    .legend-square[data-level="5"] { background-color: #f03e3e; }
                `)
                .appendTo('head');

            function initializeGraph(selectedYear) {
                // Clear existing content
                $('.days-header').empty();
                $('.months').empty();
                $('.squares-container').empty();

                // Create day numbers header (1-31)
                const daysHeader = $('.days-header');
                for (let day = 1; day <= 31; day++) {
                    const dayEl = $('<div class="day-number"></div>').text(day);
                    daysHeader.append(dayEl);
                }

                // Create month labels vertically
                const monthsContainer = $('.months');
                months.forEach(month => {
                    const monthEl = $('<div class="month"></div>').text(month);
                    monthsContainer.append(monthEl);
                });

                // Create squares for each month and day
                const squaresContainer = $('.squares-container');
                months.forEach((month, monthIndex) => {
                    const monthRow = $('<div class="month-row"></div>');
                    const daysInMonth = new Date(selectedYear, monthIndex + 1, 0).getDate();

                    // Create squares for each day in the month
                    for (let day = 1; day <= 31; day++) {
                        const square = $('<div></div>');
                        if (day <= daysInMonth) {
                            square.addClass('square');
                            const currentDate = new Date(selectedYear, monthIndex, day);
                            
                            // Format the date string in YYYY-MM-DD format
                            const year = currentDate.getFullYear();
                            const monthStr = String(currentDate.getMonth() + 1).padStart(2, '0');
                            const dayStr = String(currentDate.getDate()).padStart(2, '0');
                            const dateStr = `${year}-${monthStr}-${dayStr}`;
                            
                            square.attr('data-date', dateStr);
                            square.attr('data-level', '0');
                            square.attr('title', `${months[monthIndex]} ${day}, ${year}`);
                        } else {
                            square.addClass('square empty');
                        }
                        monthRow.append(square);
                    }

                    squaresContainer.append(monthRow);
                });
            }

            // Initialize graph with current year
            const currentYear = new Date().getFullYear();
            initializeGraph(currentYear);

            // Handle year selection change
            $('#yearSelect').change(function() {
                const selectedYear = parseInt($(this).val());
                initializeGraph(selectedYear);
            });

            // Handle data check button click
            $('#checkData').click(function() {
                const deviceId = $('#deviceSelect').val();
                const selectedYear = $('#yearSelect').val();
                
                if (!deviceId) {
                    alert('Please select a device');
                    return;
                }

                if (!selectedYear) {
                    alert('Please select a year');
                    return;
                }

                // Fetch data availability for the selected device and year
                $.ajax({
                    url: 'get_data_availability.php',
                    method: 'GET',
                    data: { 
                        device_id: deviceId,
                        year: selectedYear
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.error) {
                            console.error('Server Error:', data.error);
                            alert('Error: ' + data.error);
                            return;
                        }
                        if (Object.keys(data).length === 0) {
                            alert('No data available for the selected device in the selected year.');
                            return;
                        }
                        updateContributionGraph(data);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', {
                            status: jqXHR.status,
                            statusText: jqXHR.statusText,
                            responseText: jqXHR.responseText,
                            textStatus: textStatus,
                            errorThrown: errorThrown
                        });
                        let errorMessage = 'Error fetching data availability.';
                        if (jqXHR.responseJSON && jqXHR.responseJSON.error) {
                            errorMessage += '\nDetails: ' + jqXHR.responseJSON.error;
                        }
                        alert(errorMessage);
                    }
                });
            });

            function updateContributionGraph(data) {
                // Reset non-empty squares
                $('.square:not(.empty)').attr('data-level', '0');

                // Calculate summary statistics
                let totalEntries = 0;
                let activeDaysCount = 0;

                Object.entries(data).forEach(([date, count]) => {
                    totalEntries += count;
                    if (count > 0) {
                        activeDaysCount++;
                    }
                });

                const avgEntriesPerDay = activeDaysCount > 0 ? Math.round(totalEntries / activeDaysCount) : 0;

                // Update summary statistics
                $('#totalDataPoints').text(totalEntries.toLocaleString());
                $('#activeDays').text(activeDaysCount);
                $('#avgEntries').text(avgEntriesPerDay.toLocaleString());

                // Update squares with simple tooltip
                $('.square:not(.empty)').each(function() {
                    const dateStr = $(this).attr('data-date');
                    if (dateStr) {
                        const count = data[dateStr] || 0;
                        let level = 0;
                        for (let i = 1; i < levelThresholds.length; i++) {
                            if (count >= levelThresholds[i]) {
                                level = i;
                            }
                        }
                        $(this).attr('data-level', level.toString());
                        $(this).attr('title', `${dateStr}: ${count} entries`);
                    }
                });
            }
        });
    </script>
</body>
</html> 