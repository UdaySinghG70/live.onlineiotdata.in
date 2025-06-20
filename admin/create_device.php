<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$aldao=new AdminLoginDao();

$adminDetails=$aldao->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}

include_once '../model/admindao.php';
$adao= new AdminDao();

$users=$adao->getAllUsers();

if($users==null){
    echo "<a href='create_user.php'>Create User</a>";
    return;
}

$preset_names = $adao->getUniquePresetNames();

// Add this endpoint at the top of the file for AJAX
if (isset($_GET['get_preset_params']) && isset($_GET['preset_name'])) {
    $preset_name = $_GET['preset_name'];
    $params = array();
    $qry = "SELECT param_name, param_type, param_unit FROM preset WHERE preset_name='" . addslashes($preset_name) . "'";
    include_once '../model/querymanager.php';
    $rows = QueryManager::getMultipleRow($qry);
    if ($rows && mysqli_num_rows($rows) > 0) {
        while ($row = mysqli_fetch_assoc($rows)) {
            $params[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($params);
    exit;
}

//echo "Welcome Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Device - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <!-- Add Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Add country flags CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flag-icon-css@4.1.7/css/flag-icons.min.css" rel="stylesheet" />
    <!-- Flatpickr modern calendar -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">
    <style>
        .device-form-container {
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

        .form-section {
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.1rem;
            color: #0067ac;
            font-weight: 500;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e2e8f0;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
	}

        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.875rem;
            transition: all 0.2s;
            background-color: #fff;
	}

        .form-control:focus {
            outline: none;
            border-color: #0067ac;
            box-shadow: 0 0 0 3px rgba(0, 103, 172, 0.1);
	}
	 
        .params-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1rem;
        }

        .params-table th {
            background: #f8fafc;
            padding: 0.75rem;
            font-weight: 500;
            color: #4b5563;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .params-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        .params-table tr:hover {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .row_id_lbl, .row_id_lbl_db {
            font-weight: 500;
            color: #6b7280;
        }

        .btn-remove {
            background: #fff0f0;
            border: 1.5px solid #ffd6d6;
            color: #e03131;
            border-radius: 6px;
            font-size: 1.2rem;
            padding: 0.4rem 0.9rem;
            margin-left: 0.2rem;
            transition: background 0.2s, color 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            min-height: 36px;
            box-sizing: border-box;
        }

        .btn-remove .material-icons {
            font-size: 22px;
            vertical-align: middle;
            line-height: 1;
        }

        .btn-remove:hover {
            background: #ffe3e3;
            color: #b91c1c;
            border-color: #e03131;
        }

        .params-table td:last-child, .params-table th:last-child {
            text-align: center;
            vertical-align: middle;
            width: 56px;
        }

        .add-row-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #0067ac;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .add-row-btn:hover {
            background: #005291;
	} 

        .remove-row-btn {
            color: #dc2626;
            cursor: pointer;
            transition: all 0.2s;
	}

        .remove-row-btn:hover {
            color: #b91c1c;
        }

        .submit-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: #0067ac;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
		cursor: pointer;
            transition: all 0.2s;
        }

        .submit-btn:hover {
            background: #005291;
        }

        .msg-task {
            margin-left: 1rem;
            color: #059669;
        }

        @media (max-width: 768px) {
            .device-form-container {
                padding: 1rem;
}

            .page-header {
                flex-direction: column;
                gap: 1rem;
            }

            .params-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }

        /* Country Dropdown Styles */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 45px;
            padding: 8px;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background-color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;
            color: #4b5563;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 43px;
        }

        .select2-dropdown {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .select2-search--dropdown {
            padding: 8px;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px;
        }

        .select2-results__option {
            padding: 8px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #0067ac;
        }

        /* Responsive table */
        @media (max-width: 768px) {
            .table-responsive {
                margin: 0;
                padding: 0;
            }

            .params-table {
                min-width: 800px;
            }

            .params-table td, 
            .params-table th {
                white-space: nowrap;
            }
        }

        .preset-dropdown-group {
            position: relative;
            display: inline-block;
        }
        .use-preset-btn {
            background: #fff;
            color: #0067ac;
            border: 1.5px solid #b6e0fe;
            border-radius: 6px;
            font-weight: 500;
            padding: 0.5rem 1.2rem 0.5rem 1rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
            transition: background 0.2s, color 0.2s, border 0.2s;
            position: relative;
        }
        .use-preset-btn .caret {
            margin-left: 0.3em;
            font-size: 1.1em;
            transition: transform 0.2s;
        }
        .use-preset-btn.active .caret {
            transform: rotate(180deg);
        }
        .use-preset-btn:hover, .use-preset-btn.active {
            background: #e3f6ff;
            color: #005a8f;
            border-color: #90cdf4;
        }
        .preset-dropdown {
            display: none;
            position: absolute;
            left: 0;
            top: 110%;
            z-index: 10;
            min-width: 210px;
            width: max-content;
            max-width: 320px;
            background: #fff;
            border: 1.5px solid #b6e0fe;
            border-radius: 8px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12), 0 1.5px 6px rgba(0,0,0,0.08);
            padding: 0.3rem 0;
            font-size: 0.97rem;
        }
        .preset-dropdown option {
            padding: 0.7em 1.2em;
            background: #fff;
            color: #1e293b;
            border-radius: 0;
        }
        .preset-dropdown option:hover, .preset-dropdown option:focus {
            background: #e3f6ff;
            color: #0067ac;
        }
        @media (max-width: 600px) {
            .preset-dropdown {
                min-width: 98vw;
                left: -10vw;
            }
        }
        .calendar-input-group {
            position: relative;
            display: flex;
            align-items: center;
        }
        .calendar-input-group input[type="text"] {
            padding-right: 2.5rem;
        }
        .calendar-input-group .calendar-icon {
            position: absolute;
            right: 0.8rem;
            color: #0067ac;
            font-size: 1.3rem;
            pointer-events: none;
        }
        .flatpickr-calendar {
            z-index: 9999 !important;
        }
    </style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <main class="dashboard">
        <div class="device-form-container">
            <div class="page-header">
                <h1 class="page-title">
                    <span class="material-icons">devices</span>
                    Create New Device
                </h1>
            </div>

				<form id="data_frm">
                <!-- Basic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">Basic Information</h2>
				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="user_name_field">User Name</label>
                                <select id="user_name_field" name="user_name" class="form-control">
								<?php 
                                    foreach($users as $user){
                                        echo "<option value='".htmlspecialchars($user->user_name)."'>".htmlspecialchars($user->user_name)."</option>";
								}
								?>
								</select>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="device_id_field">Device ID</label>
                                <input id="device_id_field" type="text" name="device_id" class="form-control" placeholder="Enter device ID" required>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="project_id_field">Project ID</label>
                                <input id="project_id_field" type="text" name="project_id" class="form-control" placeholder="Enter project ID" required>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="project_name_field">Project Name</label>
                                <input id="project_name_field" type="text" name="project_name" class="form-control" placeholder="Enter project name" required>
                            </div>
						</div>
					</div>
				</div>
				
                <!-- Location Information Section -->
                <div class="form-section">
                    <h2 class="section-title">Location Information</h2>
				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="location_id_field">Location ID</label>
                                <input id="location_id_field" type="text" name="location_id" class="form-control" placeholder="Enter location ID" required>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="place_field">Place</label>
                                <input id="place_field" type="text" name="place" class="form-control" placeholder="Enter place name" required>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="latitude_field">Latitude</label>
                                <input id="latitude_field" type="text" name="latitude" class="form-control" placeholder="e.g., 28.6139" required>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="longitude_field">Longitude</label>
                                <input id="longitude_field" type="text" name="longitude" class="form-control" placeholder="e.g., 77.2090" required>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="city_field">City</label>
                                <input id="city_field" type="text" name="city" class="form-control" placeholder="Enter city name" required>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="country_field"></label>
                                <?php include_once '../countries.php';?>
                            </div>
						</div>
					</div>
				</div>
				
                <!-- Device Details Section -->
                <div class="form-section">
                    <h2 class="section-title">Device Details</h2>
				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="imei_nr_field">IMEI Number</label>
                                <input id="imei_nr_field" type="text" name="imei_nr" class="form-control" placeholder="Enter IMEI number" required>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="mobile_nr_field">Mobile Number</label>
                                <input id="mobile_nr_field" type="text" name="mobile_nr" class="form-control" placeholder="Enter mobile number" required>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="timezone_field">Timezone (minutes)</label>
                                <input id="timezone_field" type="number" name="timezone" class="form-control" placeholder="e.g., 330 for UTC+5:30" required>
						</div>
					</div>
					<div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="date_time_field">Installation Date</label>
                                <div class="calendar-input-group">
                                    <input id="date_time_field" type="text" name="date_time" class="form-control" placeholder="YYYY-MM-DD" required autocomplete="off">
                                    <span class="material-icons calendar-icon">event</span>
                                </div>
                            </div>
						</div>
					</div>
				</div>
				
                <!-- Live Data Parameters Section -->
                <div class="form-section">
                    <h2 class="section-title">Live Data Parameters</h2>
                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                        <button type="button" class="add-row-btn add_row">
                            <span class="material-icons">add</span>
                            Add Parameter
                        </button>
                        <div class="preset-dropdown-group">
                            <button type="button" class="add-row-btn use-preset-btn" id="usePresetLiveBtn">
                                <span class="material-icons">list</span>
                                Use Preset Parameters
                            </button>
                            <select class="form-control preset-dropdown" id="presetDropdownLive" style="display:none; min-width:180px;">
                                <option value="">Select Preset</option>
                                <?php foreach($preset_names as $pname): ?>
                                    <option value="<?php echo htmlspecialchars($pname); ?>"><?php echo htmlspecialchars($pname); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="params-table">
							<thead>
							<tr>
								<th>Sr</th>
                                    <th>Parameter Name</th>
                                    <th>Parameter Type</th>
								<th>Unit</th>
								<th>Position</th>
                                    <th>Action</th>
							</tr>
							</thead>
                            <tbody class="params_tbody"></tbody>
						</table>
						</div>
                    <input type="hidden" value="0" name="count"/>
				</div>
				
                <!-- Database Parameters Section -->
                <div class="form-section">
                    <h2 class="section-title">Database Parameters</h2>
                    <div style="display: flex; gap: 1rem; align-items: center; margin-bottom: 1rem;">
                        <button type="button" class="add-row-btn add_row_db">
                            <span class="material-icons">add</span>
                            Add Parameter
                        </button>
                        <div class="preset-dropdown-group">
                            <button type="button" class="add-row-btn use-preset-btn" id="usePresetDbBtn">
                                <span class="material-icons">list</span>
                                Use Preset Parameters
                            </button>
                            <select class="form-control preset-dropdown" id="presetDropdownDb" style="display:none; min-width:180px;">
                                <option value="">Select Preset</option>
                                <?php foreach($preset_names as $pname): ?>
                                    <option value="<?php echo htmlspecialchars($pname); ?>"><?php echo htmlspecialchars($pname); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="params-table">
							<thead>
							<tr>
								<th>Sr</th>
                                    <th>Parameter Name</th>
                                    <th>Parameter Type</th>
								<th>Unit</th>
								<th>Position</th>
                                    <th>Action</th>
							</tr>
							</thead>
                            <tbody class="params_tbody_db"></tbody>
						</table>
						</div>
                    <input type="hidden" value="0" name="count_db"/>
				</div>
				
                <!-- Submit Section -->
                <div class="form-section">
                    <button type="button" class="submit-btn" name="create_device">
                        <span class="material-icons">save</span>
                        Create Device
                    </button>
                    <span class="msg_task"></span>
				</div>
				</form>
			</div>
    </main>

	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	<script src="js/create_device1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/en.js"></script>
    <script>
        $(function() {
            // Initialize datepicker
            $("#date_time_field").flatpickr({
                dateFormat: "Y-m-d",
                allowInput: true,
                altInput: true,
                altFormat: "F j, Y",
                theme: "material_blue",
                maxDate: "today",
                locale: "en"
            });

            // Initialize Select2 for country dropdown
            $('#countryoptions').select2({
                placeholder: 'Select a country'
            });

            // Form validation
            function validateForm() {
                let isValid = true;
                $('.form-control[required]').each(function() {
                    if (!$(this).val()) {
                        $(this).addClass('error');
                        isValid = false;
                    } else {
                        $(this).removeClass('error');
                    }
                });
                return isValid;
            }

            // Add validation to form submission
            $('button[name="create_device"]').click(function() {
                if (validateForm()) {
                    // Your existing form submission logic
                } else {
                    $('.msg_task').html('<span style="color: #dc2626;">Please fill in all required fields</span>');
                }
            });

            // Real-time validation
            $('.form-control[required]').on('input', function() {
                if ($(this).val()) {
                    $(this).removeClass('error');
                }
            });

            // Toggle preset dropdown for Live Data Parameters
            $('#usePresetLiveBtn').on('click', function(e) {
                e.stopPropagation();
                $(this).toggleClass('active');
                $('#presetDropdownLive').toggle();
                $('#presetDropdownDb').hide();
                $('#usePresetDbBtn').removeClass('active');
            });
            // Toggle preset dropdown for Database Parameters
            $('#usePresetDbBtn').on('click', function(e) {
                e.stopPropagation();
                $(this).toggleClass('active');
                $('#presetDropdownDb').toggle();
                $('#presetDropdownLive').hide();
                $('#usePresetLiveBtn').removeClass('active');
            });
            // Hide dropdown if clicked outside
            $(document).on('mousedown', function(e) {
                if (!$(e.target).closest('.preset-dropdown-group').length) {
                    $('.preset-dropdown').hide();
                    $('.use-preset-btn').removeClass('active');
                }
            });
            // Hide dropdown on selection
            $('.preset-dropdown').on('change', function() {
                $(this).hide();
                $('.use-preset-btn').removeClass('active');
            });

            // Helper to fill parameter table with correct field names
            function fillParamsTable(params, tbodySelector, type) {
                var html = '';
                for (var i = 0; i < params.length; i++) {
                    var idx = i + 1;
                    if (type === 'live') {
                        html += '<tr>' +
                            '<td class="row_id_lbl">' + idx + '.</td>' +
                            '<td><input type="text" class="form-control param_name" name="param_name' + idx + '" value="' + params[i].param_name + '" required></td>' +
                            '<td><input type="text" class="form-control param_type" name="param_type' + idx + '" value="' + params[i].param_type + '" required></td>' +
                            '<td><input type="text" class="form-control unit" name="unit' + idx + '" value="' + (params[i].param_unit || '') + '"></td>' +
                            '<td><input type="number" class="form-control position" name="position' + idx + '" value="' + idx + '" min="1"></td>' +
                            '<td><button type="button" class="btn-remove remove-row-btn"><span class="material-icons">delete</span></button></td>' +
                        '</tr>';
                    } else {
                        html += '<tr>' +
                            '<td class="row_id_lbl_db">' + idx + '.</td>' +
                            '<td><input type="text" class="form-control param_name_db" name="param_name_db' + idx + '" value="' + params[i].param_name + '" required></td>' +
                            '<td><input type="text" class="form-control param_type_db" name="param_type_db' + idx + '" value="' + params[i].param_type + '" required></td>' +
                            '<td><input type="text" class="form-control unit_db" name="unit_db' + idx + '" value="' + (params[i].param_unit || '') + '"></td>' +
                            '<td><input type="number" class="form-control position_db" name="position_db' + idx + '" value="' + idx + '" min="1"></td>' +
                            '<td><button type="button" class="btn-remove remove-row-btn"><span class="material-icons">delete</span></button></td>' +
                        '</tr>';
                    }
                }
                $(tbodySelector).html(html);
                if (type === 'live') resetNames();
                else resetNamesDb();
            }
            $('#presetDropdownLive').on('change', function() {
                var preset = $(this).val();
                if (!preset) return;
                $.get('create_device.php', {get_preset_params:1, preset_name:preset}, function(params) {
                    fillParamsTable(params, '.params_tbody', 'live');
                }, 'json');
            });
            $('#presetDropdownDb').on('change', function() {
                var preset = $(this).val();
                if (!preset) return;
                $.get('create_device.php', {get_preset_params:1, preset_name:preset}, function(params) {
                    fillParamsTable(params, '.params_tbody_db', 'db');
                }, 'json');
            });

            // Helper to update S.No. and Position fields after row deletion
            function updateParamTableIndexes(tbodySelector) {
                $(tbodySelector).find('tr').each(function(idx) {
                    $(this).find('td:first').text(idx+1); // S.No.
                    $(this).find('input[name^="paramPosition_db"]')
                        .val(idx+1);
                });
            }
            // Delegate delete button click for both tables
            $(document).on('click', '.params_tbody .remove-row-btn', function() {
                $(this).closest('tr').remove();
                updateParamTableIndexes('.params_tbody');
            });
            $(document).on('click', '.params_tbody_db .remove-row-btn', function() {
                $(this).closest('tr').remove();
                updateParamTableIndexes('.params_tbody_db');
            });
        });
    </script>
</body>
</html>