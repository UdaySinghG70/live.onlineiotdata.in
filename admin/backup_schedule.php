<?php
session_start();

if(isset($_SESSION['admin_name'])==false){
    echo "Invalid Login";
    header('Location: login.php');
    return;
}

$admin_name=$_SESSION['admin_name'];

include_once '../model/adminlogin_dao.php';
$adaologin=new AdminLoginDao();

$adminDetails=$adaologin->getAdminByUserName($admin_name);
if($adminDetails==null){
    echo "Invalid Login";
    header('Location: login.php?msg=error&admin_name='.$admin_name);
    return;
}

include_once '../model/IniFileHelper.php';
include_once '../model/BackUpConfig.php';
include_once '../model/admindao.php';
$adao = new AdminDao();

if(isset($_POST['schedule'])){
	//echo $_POST['schedule'];
	IniFileHelper::WriteBackupSchedule($_POST['schedule']);
	
}
$pg=1;
if(isset($_POST['pg'])){
	$pg=$_POST['pg'];
	if($pg<=0){
		$pg=1;
	}
}
$recordsToDisplay = 20;
$recordCount = $adao->getBackupsCount();

$linkCount = $recordCount % $recordsToDisplay == 0 ? ( int )( $recordCount / $recordsToDisplay ) : ( int ) ( $recordCount / $recordsToDisplay ) + 1;

if($pg>$linkCount && $linkCount>0){
	echo $pg=$linkCount;
}
//echo $pg."<br/>";
$starttingRecord = ($pg-1) * $recordsToDisplay;

if($linkCount - $pg > 10){
	$linkStart=$pg;
}else{
	if($linkCount > 10){
		$linkStart = $linkCount - 10;
	}else {
		$linkStart=1;
	}
}

$backupArr = $adao->getBackups($starttingRecord, $recordsToDisplay);

//echo "Welcome Admin";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Backup Schedule - Cloud Data Monitoring</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        .backup-container {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin: 2rem auto;
            max-width: 1200px;
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

        .backup-info {
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
        }

        .backup-info p {
            font-weight: 500;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .backup-info ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .backup-info li {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 0;
            color: #4b5563;
        }

        .backup-info li:before {
            content: "•";
            color: #0067ac;
            font-weight: bold;
        }

        .backup-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 1.5rem;
        }

        .backup-table th {
            background: #f8fafc;
            padding: 0.75rem;
            font-weight: 500;
            color: #4b5563;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        .backup-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .backup-table tr:hover td {
            background: #f8fafc;
        }

        .backup-link {
            color: #0067ac;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: color 0.2s;
        }

        .backup-link:hover {
            color: #005291;
        }

        .no-backups {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 1.5rem;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .pagination-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f1f5f9;
            color: #0067ac;
            border: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .pagination-button:hover {
            background: #e2e8f0;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-input {
            width: 4rem;
            padding: 0.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .backup-container {
                padding: 1rem;
                margin: 1rem;
            }

            .pagination {
                flex-direction: column;
                gap: 1rem;
            }

            .pagination-controls {
                width: 100%;
                justify-content: space-between;
            }

            .backup-table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
		<?php include_once 'admin_header.php';?>
    
    <main class="dashboard">
        <div class="backup-container">
            <div class="page-header">
                <h1 class="page-title">
                    <span class="material-icons">backup</span>
                    Backup History
                </h1>
            </div>

            <?php if($backupArr != null): ?>
                <div class="backup-list">
                    <div class="backup-info">
                        <p>Automatic backup schedules:</p>
                        <ul>
                            <li>Daily backups at 2:30 AM (kept for 3 days)</li>
                            <li>Weekly backups on Sundays at 2:30 AM (kept for 5 weeks)</li>
                            <li>Monthly backups on 1st of month at 2:30 AM</li>
                        </ul>
                    </div>
                    <table class="backup-table">
                        <thead>
                            <tr>
                                <th>Filename</th>
                                <th>Backup Date</th>
                                <th>Schedule</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($backupArr as $backup): ?>
                                <tr>
                                    <td>
                                        <a href="../<?php echo htmlspecialchars($backup->file_name); ?>" class="backup-link">
                                            <span class="material-icons">download</span>
                                            <?php echo htmlspecialchars($backup->file_name); ?>
                                        </a>
                                    </td>
                                    <td><?php echo htmlspecialchars($backup->backup_date); ?></td>
                                    <td><?php echo htmlspecialchars($backup->schedule); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="pagination">
                        <div class="pagination-controls">
                            <?php if($pg > 1): ?>
                                <a href="backup_schedule.php?pg=<?php echo ($pg-1); ?>" class="pagination-button">
                                    <span class="material-icons">chevron_left</span>
                                    Previous
                                </a>
                            <?php endif; ?>

                            <?php if($pg < $linkCount): ?>
                                <a href="backup_schedule.php?pg=<?php echo ($pg+1); ?>" class="pagination-button">
                                    Next
                                    <span class="material-icons">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="pagination-info">
                            <input type="number" value="<?php echo $pg; ?>" name="go_to_page" class="page-input" min="1" max="<?php echo $linkCount; ?>">
                            <span>of <?php echo $linkCount; ?></span>
                            <button class="pagination-button btn_goto_page">Go</button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-backups">
                    <p>No backup history available.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script>
        $(function() {
            $(".btn_goto_page").click(function() {
                const page = $("input[name='go_to_page']").val();
                window.location.href = `backup_schedule.php?pg=${page}`;
            });

            $("input[name='go_to_page']").keypress(function(e) {
                if(e.which == 13) {
                    $(".btn_goto_page").click();
                }
            });
        });
    </script>
</body>
</html>