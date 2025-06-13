<style>
    .main-header {
        background: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin: 0;
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .header-top {
        background: linear-gradient(to right, #0067ac, #02568e);
        padding: 8px 0;
        color: #fff;
    }

    .header-top-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .welcome-text {
        font-size: 14px;
        opacity: 0.9;
    }

    .main-nav {
        background: #fff;
        padding: 15px 0;
    }

    .nav-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .brand {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
    }

    .brand-logo {
        height: 40px;
        width: auto;
    }

    .brand-text {
        color: #0067ac;
        font-size: 20px;
        font-weight: 600;
        margin: 0;
    }

    .nav-links {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .nav-link {
        color: #4a5568;
        text-decoration: none;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .nav-link:hover {
        background: #f7fafc;
        color: #0067ac;
    }

    .nav-link.active {
        background: #ebf8ff;
        color: #0067ac;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-btn {
        background: none;
        border: none;
        color: #4a5568;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s ease;
    }

    .dropdown-btn:after {
        content: '';
        display: inline-block;
        width: 0;
        height: 0;
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid currentColor;
        margin-left: 4px;
    }

    .dropdown-btn:hover {
        background: #f7fafc;
        color: #0067ac;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: #fff;
        min-width: 180px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        border-radius: 6px;
        padding: 4px;
        z-index: 1000;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown-link {
        display: block;
        padding: 8px 16px;
        color: #4a5568;
        text-decoration: none;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .dropdown-link:hover {
        background: #f7fafc;
        color: #0067ac;
    }

    .loading-indicator {
        position: fixed;
        top: 20px;
        right: 20px;
        width: 24px;
        height: 24px;
        display: none;
    }

    .loading-indicator.active {
        display: block;
    }

    @media (max-width: 768px) {
        .header-top-content, .nav-content {
            flex-direction: column;
            text-align: center;
            gap: 10px;
        }

        .nav-links {
            flex-wrap: wrap;
            justify-content: center;
        }

        .brand {
            justify-content: center;
            margin-bottom: 10px;
        }
    }
</style>

<div class="main-header">
    <div class="header-top">
        <div class="header-container header-top-content">
            <div class="welcome-text">
                Welcome, <?php echo htmlspecialchars(strtoupper($_SESSION['user_name'])); ?>
            </div>
        </div>
    </div>

    <nav class="main-nav">
        <div class="header-container nav-content">
            <a href="index.php" class="brand">
                <img src="images/sound logo.jpg" alt="Logo" class="brand-logo">
                <h1 class="brand-text">Sound Monitoring System</h1>
            </a>

            <div class="nav-links">
                <a href="index.php" class="nav-link">Home</a>
                
                <div class="dropdown">
                    <button class="dropdown-btn">Account</button>
                    <div class="dropdown-content">
                        <a href="change_password.php" class="dropdown-link">Change Password</a>
                        <a href="recharge.php" class="dropdown-link">Recharge</a>
                    </div>
                </div>
                
                <a href="logout.php" class="nav-link">Logout</a>
            </div>
        </div>
    </nav>
</div>

<img src="images/loader.gif" class="loading-indicator" alt="Loading..." />