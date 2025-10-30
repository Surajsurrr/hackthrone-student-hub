<?php
// Get current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
    <nav>
        <ul>
            <li><a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">📊 Overview</a></li>
            <li><a href="manage_jobs.php" class="<?php echo ($current_page == 'manage_jobs.php') ? 'active' : ''; ?>">💼 Manage Jobs</a></li>
            <li><a href="applications.php" class="<?php echo ($current_page == 'applications.php') ? 'active' : ''; ?>">📋 Applications</a></li>
            <li><a href="profile.php" class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>">👤 Profile</a></li>
            <li><a href="settings.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">⚙️ Settings</a></li>
        </ul>
    </nav>
</aside>
