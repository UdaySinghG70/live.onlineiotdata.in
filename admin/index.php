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
    <title>Admin Dashboard - Cloud Data Monitoring</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="../images/icons/favicon.png"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="css/admin-style.css">
    <style>
        /* Dashboard specific styles */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-2px);
        }

        .stats-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .stats-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            background: #e3f2fd;
            color: #0067ac;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-title {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        .stats-value {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
        }

        .welcome-message {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .welcome-message h2 {
            font-size: 1.5rem;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .welcome-message p {
            color: #64748b;
            line-height: 1.6;
        }

        @media (max-width: 768px) {
            .dashboard-grid {
                gap: 1rem;
            }

            .stats-card {
                padding: 1rem;
            }

            .welcome-message {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <?php include_once 'admin_header.php';?>

    <main class="dashboard">
        <div class="welcome-message">
            <h2>Welcome, <?php echo htmlspecialchars($adminDetails->admin_name); ?></h2>
            <p>Access and manage your cloud monitoring system from this admin dashboard. Use the navigation above to access different sections of the admin panel.</p>
        </div>

        <div class="dashboard-grid">
            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">devices</span>
                    </div>
                    <div class="stats-title">Total Devices</div>
                </div>
                <div class="stats-value">--</div>
            </div>

            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">people</span>
                    </div>
                    <div class="stats-title">Active Users</div>
                </div>
                <div class="stats-value">--</div>
            </div>

            <div class="stats-card">
                <div class="stats-header">
                    <div class="stats-icon">
                        <span class="material-icons">sync</span>
                    </div>
                    <div class="stats-title">Last Backup</div>
                </div>
                <div class="stats-value">--</div>
                <div class="backup-details" style="margin-top: 8px; font-size: 0.875rem; color: #64748b;">
                    <div class="backup-type">Type: --</div>
                    <div class="backup-tables">Tables: --</div>
                </div>
            </div>
        </div>
    </main>

    <script src="../vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery-ui.js"></script>
    <script>
        // Show/hide loader
        function toggleLoader(show) {
            const loader = document.querySelector('.loader');
            if (show) {
                loader.classList.add('active');
            } else {
                loader.classList.remove('active');
            }
        }

        // Function to update device count
        function updateDeviceCount() {
            return $.ajax({
                url: 'get_device_count.php',
                method: 'GET',
                success: function(response) {
                    if(response.count !== undefined) {
                        $('.stats-card:eq(0) .stats-value').text(response.count);
                    }
                },
                error: function() {
                    $('.stats-card:eq(0) .stats-value').text('Error');
                }
            });
        }

        // Function to update user count
        function updateUserCount() {
            return $.ajax({
                url: 'get_user_count.php',
                method: 'GET',
                success: function(response) {
                    if(response.count !== undefined) {
                        $('.stats-card:eq(1) .stats-value').text(response.count);
                    }
                },
                error: function() {
                    $('.stats-card:eq(1) .stats-value').text('Error');
                }
            });
        }

        // Function to update last backup details
        function updateLastBackup() {
            return $.ajax({
                url: 'get_last_backup.php',
                method: 'GET',
                success: function(response) {
                    const backupCard = $('.stats-card:eq(2)');
                    if(response.success) {
                        backupCard.find('.stats-value').text(response.date);
                        backupCard.find('.backup-type').text('Type: ' + response.schedule);
                        backupCard.find('.backup-tables').text('Tables: ' + response.tables);
                    } else {
                        backupCard.find('.stats-value').text('No Backup');
                        backupCard.find('.backup-type').text('Type: --');
                        backupCard.find('.backup-tables').text('Tables: --');
                    }
                },
                error: function() {
                    const backupCard = $('.stats-card:eq(2)');
                    backupCard.find('.stats-value').text('Error');
                    backupCard.find('.backup-type').text('Type: --');
                    backupCard.find('.backup-tables').text('Tables: --');
                }
            });
        }

        // Example of how to fetch and update stats
        function updateStats() {
            toggleLoader(true);
            
            // Update all stats
            Promise.all([
                updateDeviceCount(),
                updateUserCount(),
                updateLastBackup()
            ]).finally(() => {
                toggleLoader(false);
            });
        }

        // Update stats when page loads
        document.addEventListener('DOMContentLoaded', updateStats);

        // Update stats every 5 minutes
        setInterval(updateStats, 300000);
    </script>
</body>
</html>