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

if(isset($_REQUEST['user_name'])==false){
	echo "no user name given";
	return;
}

include_once '../model/admindao.php';
$adao=new AdminDao();
include_once '../model/datadao.php';
$ddao=new Datadao();

$user_name=$_REQUEST['user_name'];

$deviceArr=$ddao->getDeviceByUserName($user_name);
if($deviceArr==null){
	echo "<div class='message error'>No device added for user: " . htmlspecialchars($user_name) . "</div>";
	return;
}
?>

<style>
.device-selector-container {
    padding: 20px;
    max-width: 400px;
    margin: 0 auto;
}

.device-selector-label {
    display: block;
    margin-bottom: 8px;
    color: #374151;
    font-weight: 500;
    font-size: 0.95rem;
}

.device-selector {
    width: 100%;
    padding: 10px 36px 10px 12px;
    font-size: 0.95rem;
    line-height: 1.5;
    color: #374151;
    background-color: #ffffff;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%236B7280' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    background-size: 16px;
    border: 2px solid #E5E7EB;
    border-radius: 6px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
    transition: all 0.2s ease;
}

.device-selector:hover {
    border-color: #D1D5DB;
}

.device-selector:focus {
    outline: none;
    border-color: #2563EB;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.device-selector option {
    padding: 8px;
}

.message {
    padding: 12px 16px;
    border-radius: 6px;
    margin: 16px 0;
    font-size: 0.95rem;
}

.message.error {
    background-color: #FEE2E2;
    color: #991B1B;
    border: 1px solid #FCA5A5;
}
</style>

<div class="device-selector-container">
    <label class="device-selector-label" for="device-select">Select Device</label>
    <select name="device_id" id="device-select" class="device-selector">
        <option value="-">Select a device...</option>
        <?php 
        foreach($deviceArr as $device) {
            echo "<option value='get_recharge_history.php?device_id=" . htmlspecialchars($device->device_id) . "&keepThis=true&TB_iframe=true&height=450&width=700'>" . 
                 htmlspecialchars($device->device_id) . 
                 "</option>";
        }
        ?>
    </select>
</div>