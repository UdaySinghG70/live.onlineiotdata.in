<link rel="stylesheet" href="css/admin-style.css">

<header class="admin-header">
    <div class="header-container">
        <div class="header-top">
            <div class="brand">
                <h1>Cloud Monitoring System</h1>
                <div class="admin-badge">Admin Panel</div>
            </div>
        </div>
        <nav class="admin-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <span class="material-icons">dashboard</span>
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="create_user.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create_user.php' ? 'active' : ''; ?>">
                        <span class="material-icons">person_add</span>
                        Create User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="users.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'active' : ''; ?>">
                        <span class="material-icons">people</span>
                        Users
                    </a>
                </li>
                <li class="nav-item">
                    <a href="create_device.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'create_device.php' ? 'active' : ''; ?>">
                        <span class="material-icons">add_circle</span>
                        Create Device
                    </a>
                </li>
                <li class="nav-item">
                    <a href="devices.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'devices.php' ? 'active' : ''; ?>">
                        <span class="material-icons">devices</span>
                        Devices
                    </a>
                </li>
                <li class="nav-item">
                    <a href="received.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'received.php' ? 'active' : ''; ?>">
                        <span class="material-icons">data_usage</span>
                        Received
                    </a>
                </li>
                <li class="nav-item">
                    <a href="recharge.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'recharge.php' ? 'active' : ''; ?>">
                        <span class="material-icons">account_balance</span>
                        Recharge
                    </a>
                </li>
                <li class="nav-item">
                    <a href="backup_schedule.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'backup_schedule.php' ? 'active' : ''; ?>">
                        <span class="material-icons">backup</span>
                        Backup
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link logout">
                        <span class="material-icons">logout</span>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</header>

<div class="loader">
    <img src="../images/loader.gif" alt="Loading..." />
</div>