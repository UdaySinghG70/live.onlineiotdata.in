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

if(isset($_POST['username'])==false){
    echo "user name ";
    header('Location: users.php');
    return;
}
$username=$_POST['username'];

include_once '../model/datadao.php';
$ddao=new Datadao();

$deviceArr=$ddao->getDeviceByUserName($username);
if($deviceArr==null){
    echo "No device found";
    
    return;
}
?>

<style>
    .devices-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-top: 1rem;
    }

    .devices-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }

    .devices-table th {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        font-weight: 600;
        color: #1e293b;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 1;
    }

    .devices-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #4b5563;
        vertical-align: middle;
    }

    .devices-table tr:last-child td {
        border-bottom: none;
    }

    .devices-table tr:hover td {
        background: #f8fafc;
    }

    .device-id {
        font-family: monospace;
        font-weight: 500;
        color: #0067ac;
    }

    .location-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .location-label {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .location-value {
        font-weight: 500;
        color: #1e293b;
    }

    .date-time {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .date {
        font-weight: 500;
        color: #1e293b;
    }

    .timezone {
        font-size: 0.75rem;
        color: #6b7280;
    }

    .mobile-number {
        font-family: monospace;
        color: #1e293b;
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
    }

    .action-btn.edit {
        background: #e0f2fe;
        color: #0369a1;
    }

    .action-btn.edit:hover {
        background: #bae6fd;
    }

    .action-btn.delete {
        background: #fee2e2;
        color: #dc2626;
    }

    .action-btn.delete:hover {
        background: #fecaca;
    }

    .action-btn.recharge {
        background: #f0fdf4;
        color: #059669;
    }

    .action-btn.recharge:hover {
        background: #dcfce7;
    }

    .action-btn .material-icons {
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .devices-table-container {
            margin: 0 -1rem;
            border-radius: 0;
        }

        .devices-table {
            font-size: 0.75rem;
        }

        .devices-table th,
        .devices-table td {
            padding: 0.5rem 0.75rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 0.375rem;
        }

        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="devices-table-container">
    <table class="devices-table">
        <thead>
            <tr>
                <th>Device ID</th>
                <th>User</th>
                <th>Location</th>
                <th>Date & Time</th>
                <th>Mobile</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            for($i=0; $i<count($deviceArr); $i++){
                echo "<tr>";
                echo "<td><span class='device-id'>".htmlspecialchars($deviceArr[$i]->device_id)."</span></td>";
                echo "<td>".htmlspecialchars($deviceArr[$i]->user)."</td>";
                echo "<td>
                    <div class='location-info'>
                        <div>
                            <span class='location-label'>Place:</span>
                            <span class='location-value'>".htmlspecialchars($deviceArr[$i]->place)."</span>
                        </div>
                        <div>
                            <span class='location-label'>City:</span>
                            <span class='location-value'>".htmlspecialchars($deviceArr[$i]->city)."</span>
                        </div>
                        <div>
                            <span class='location-label'>Country:</span>
                            <span class='location-value'>".htmlspecialchars($deviceArr[$i]->country)."</span>
                        </div>
                        <div>
                            <span class='location-label'>Coordinates:</span>
                            <span class='location-value'>".htmlspecialchars($deviceArr[$i]->latitude).", ".htmlspecialchars($deviceArr[$i]->longitude)."</span>
                        </div>
                    </div>
                </td>";
                $date_time = strtotime($deviceArr[$i]->date_time);
                $date_time = date('Y-M-d', $date_time);
                echo "<td>
                    <div class='date-time'>
                        <span class='date'>".htmlspecialchars($date_time)."</span>
                        <span class='timezone'>Timezone: ".htmlspecialchars($deviceArr[$i]->timezone_minute)."</span>
                    </div>
                </td>";
                echo "<td><span class='mobile-number'>".htmlspecialchars($deviceArr[$i]->mobile_no)."</span></td>";
                echo "<td>
                    <div class='action-buttons'>
                        <input type='button' name='btn_delete' value='Delete' class='action-btn delete' device='".htmlspecialchars($deviceArr[$i]->device_id)."'/>
                        <a href='edit_device.php?device_id=".htmlspecialchars($deviceArr[$i]->device_id)."' class='action-btn edit'>
                            <span class='material-icons'>edit</span>
                            Edit
                        </a>
                        <button type='button' class='action-btn recharge-btn' data-device='".htmlspecialchars($deviceArr[$i]->device_id)."'>
                            <span class='material-icons'>bolt</span>
                            Recharge
                        </button>
                    </div>
                </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
