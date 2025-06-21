<link rel="stylesheet" href="css/admin-style.css">

<header class="admin-header">
    <div class="header-container">
        <div class="header-top">
            <div class="brand">
                <h1>Cloud Monitoring System</h1>
                <div class="admin-badge">Admin Panel</div>
            </div>
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <span class="material-icons">menu</span>
            </button>
        </div>
        <nav class="admin-nav" id="adminNav">
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

<script>
// Mobile menu toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const adminNav = document.getElementById('adminNav');
    
    if (mobileMenuToggle && adminNav) {
        mobileMenuToggle.addEventListener('click', function() {
            adminNav.classList.toggle('active');
            
            // Change icon based on menu state
            const icon = this.querySelector('.material-icons');
            if (adminNav.classList.contains('active')) {
                icon.textContent = 'close';
            } else {
                icon.textContent = 'menu';
            }
        });
        
        // Close menu when clicking on a nav link (mobile)
        const navLinks = adminNav.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    adminNav.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('.material-icons');
                    icon.textContent = 'menu';
                }
            });
        });
        
        // Close menu when clicking outside (mobile)
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!adminNav.contains(event.target) && !mobileMenuToggle.contains(event.target)) {
                    adminNav.classList.remove('active');
                    const icon = mobileMenuToggle.querySelector('.material-icons');
                    icon.textContent = 'menu';
                }
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                adminNav.classList.remove('active');
                const icon = mobileMenuToggle.querySelector('.material-icons');
                icon.textContent = 'menu';
            }
        });
    }
});
</script>