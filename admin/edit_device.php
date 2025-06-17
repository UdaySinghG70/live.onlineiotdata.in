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

if(isset($_REQUEST['device_id'])==null){
    echo "Device ID not provided.";
    header('Location: devices.php');
    return;
}

$device_id=$_REQUEST['device_id'];

include_once '../model/admindao.php';
$adao=new AdminDao();
$device_detail=$adao->getDeviceByDeviceID($device_id);
if($device_detail==null){
    echo "No device data found";
    header('Location: devices.php');
    return ;
}
$users=$adao->getAllUsers();

$deviceParams = $adao->getDeviceParamsForAdmin($device_detail->device_id);
$logParams = $adao->getLogParamsForAdmin($device_detail->device_id);

include_once 'data_types_arr.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Device - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <!-- Add Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Add country flags CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flag-icon-css@4.1.7/css/flag-icons.min.css" rel="stylesheet" />
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

        .row_id_lbl {
            font-weight: 500;
            color: #6b7280;
        }

        .btn-remove {
            background: none;
            border: none;
            color: #dc2626;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 4px;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-remove:hover {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .btn-remove .material-icons {
            font-size: 20px;
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
</style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
		
    <main class="dashboard">
        <div class="device-form-container">
            <div class="page-header">
                <h1 class="page-title">
                    <span class="material-icons">edit</span>
                    Edit Device
                </h1>
            </div>

				<form id="data_frm">
				<input type="hidden" value="<?php echo $device_detail->id;?>" name="device_rowid">
				<input type="hidden" value="<?php echo $device_detail->device_id;?>" name="device_id_got">
				<input type="hidden" value="<?php echo $device_detail->user;?>" name="user_name_got">

                <!-- Basic Information Section -->
                <div class="form-section">
                    <h2 class="section-title">Basic Information</h2>
				<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="user_name_field">User Name</label>
                                <select id="user_name_field" name="user_name" class="form-control">
						<?php 
                                    foreach($users as $user) {
                                        $selected = $user->user_name == $device_detail->user ? 'selected' : '';
                                        echo "<option value='".htmlspecialchars($user->user_name)."' $selected>".htmlspecialchars($user->user_name)."</option>";
                                    }
						?>
						</select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="device_id_field">Device ID</label>
                                <input id="device_id_field" type="text" name="device_id" class="form-control" value="<?php echo htmlspecialchars($device_detail->device_id);?>"/>
                            </div>
				</div>
				</div>
				
				<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="project_id_field">Project ID</label>
                                <input id="project_id_field" type="text" name="project_id" class="form-control" value="<?php echo htmlspecialchars($device_detail->project_id);?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="project_name_field">Project Name</label>
                                <input id="project_name_field" type="text" name="project_name" class="form-control" value="<?php echo htmlspecialchars($device_detail->project_name);?>"/>
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
                                <input id="location_id_field" type="text" name="location_id" class="form-control" value="<?php echo htmlspecialchars($device_detail->location_id);?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="place_field">Place</label>
                                <input id="place_field" type="text" name="place" class="form-control" value="<?php echo htmlspecialchars($device_detail->place);?>"/>
                            </div>
                        </div>
				</div>
				
				<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="latitude_field">Latitude</label>
                                <input id="latitude_field" type="text" name="latitude" class="form-control" value="<?php echo htmlspecialchars($device_detail->latitude);?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="longitude_field">Longitude</label>
                                <input id="longitude_field" type="text" name="longitude" class="form-control" value="<?php echo htmlspecialchars($device_detail->longitude);?>"/>
                            </div>
                        </div>
				</div>
								
				<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="city_field">City</label>
                                <input id="city_field" type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($device_detail->city);?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="country_field">Country</label>
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
                                <input id="imei_nr_field" type="text" name="imei_nr" class="form-control" value="<?php echo htmlspecialchars($device_detail->imei_nr);?>"/>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="mobile_nr_field">Mobile Number</label>
                                <input id="mobile_nr_field" type="text" name="mobile_nr" class="form-control" value="<?php echo htmlspecialchars($device_detail->mobile_no);?>"/>
                            </div>
				</div>
				</div>
				
				<div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="timezone_field">Timezone (minutes)</label>
                                <input id="timezone_field" type="number" name="timezone" class="form-control" value="<?php echo htmlspecialchars($device_detail->timezone_minute);?>"/>
				</div>
				</div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="date_time_field">Installation Date</label>
                                <input id="date_time_field" type="text" name="date_time" class="form-control" value="<?php echo htmlspecialchars($device_detail->date_time);?>"/>
				</div>
				</div>
				</div>
				</div>
				
                <!-- Device Parameters Section -->
                <div class="form-section">
                    <h2 class="section-title">Live Data Parameters</h2>
                    <button type="button" class="add-row-btn" onclick="addRow()">
                        <span class="material-icons">add</span>
                        Add Parameter
                    </button>
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
							<tbody class="params_tbody">
								<?php 
                                $row_id = 0;
                                foreach($deviceParams as $param) {
                                    $row_id++;
                                    echo "<tr class='row_".$row_id."'>"
										."<td><label class='row_id_lbl row_id_lbl".$row_id."'>".$row_id."</label></td>"
                                        ."<td><input type='text' class='form-control param_name' name='param_name".$row_id."' value='".htmlspecialchars($param->param_name)."'></td>"
                                        ."<td><select class='form-control param_type' name='param_type".$row_id."' onchange='changeUnit(this)'>";
                                    
                                    foreach($data_types_arr as $type) {
                                        $selected = $type[0] == $param->param_type ? 'selected' : '';
                                        echo "<option value='".$type[0]."' data-unit='".$type[3]."' ".$selected.">".$type[1]."</option>";
                                    }
                                    
                                    echo "</select></td>"
                                        ."<td><input type='text' class='form-control unit' name='unit".$row_id."' value='".htmlspecialchars($param->unit)."'></td>"
                                        ."<td><input type='text' class='form-control position' name='position".$row_id."' value='".htmlspecialchars($param->position)."'></td>"
                                        ."<td><button type='button' class='btn-remove' onclick='removeRow(this)' data-row='".$row_id."'><span class='material-icons'>delete</span></button></td>"
                                        ."</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" value="<?php echo $row_id;?>" name="count"/>
                </div>

                <!-- Database Parameters Section -->
                <div class="form-section">
                    <h2 class="section-title">Database Parameters</h2>
                    <button type="button" class="add-row-btn add_row_db" onclick="addRowDB()">
                        <span class="material-icons">add</span>
                        Add Parameter
                    </button>
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
                            <tbody class="params_tbody_db">
                                <?php 
                                $row_id_db = 0;
                                if($logParams) {
                                    foreach($logParams as $param) {
                                        $row_id_db++;
                                        echo "<tr class='row_db_".$row_id_db."'>"
                                            ."<td><label class='row_id_lbl_db row_id_lbl_db".$row_id_db."'>".$row_id_db."</label></td>"
                                            ."<td><input type='text' class='form-control param_name' name='param_name_db".$row_id_db."' value='".htmlspecialchars($param->param_name)."'></td>"
                                            ."<td><select class='form-control param_type' name='param_type_db".$row_id_db."' onchange='changeUnit(this)'>";
                                        
                                        foreach($data_types_arr as $type) {
                                            $selected = $type[0] == $param->param_type ? 'selected' : '';
                                            echo "<option value='".$type[0]."' data-unit='".$type[3]."' ".$selected.">".$type[1]."</option>";
                                        }
                                        
                                        echo "</select></td>"
                                            ."<td><input type='text' class='form-control unit' name='unit_db".$row_id_db."' value='".htmlspecialchars($param->unit)."'></td>"
                                            ."<td><input type='text' class='form-control position' name='position_db".$row_id_db."' value='".htmlspecialchars($param->position)."'></td>"
                                            ."<td><button type='button' class='btn-remove' onclick='removeRowDB(this)' data-row='".$row_id_db."'><span class='material-icons'>delete</span></button></td>"
										."</tr>";
								}
                                }
								?>
							</tbody>
						</table>
						</div>
                    <input type="hidden" value="<?php echo $row_id_db;?>" name="count_db"/>
				</div>
				
                <!-- Submit Section -->
                <div class="form-section">
                    <button type="button" class="submit-btn" name="edit_device">
                        <span class="material-icons">save</span>
                        Update Device
                    </button>
                    <span class="msg_task"></span>
				</div>
				</form>
			</div>
    </main>

	<script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="js/create_device1.js"></script>
	<script src="../js/jquery-ui.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function() {
            // Initialize datepicker
		$("input[name='date_time']").datepicker({
			dateFormat: 'yy-mm-dd',
		});

            // Initialize country selection
            $("#countryoptions").val("<?php echo htmlspecialchars($device_detail->country);?>");

            // Initialize Select2 for dropdowns
            $('.form-control select').select2({
                width: '100%'
            });
	});
</script>
</body>
</html>
