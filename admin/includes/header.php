<header>
    <nav>
        <div class="nav-container">
            <h1><?php echo APP_NAME; ?> - Admin Portal</h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="manage_users.php">Manage Users</a></li>
                <li><a href="manage_events.php">Manage Events</a></li>
                <li><a href="?logout=1">Logout</a></li>
            </ul>
        </div>
    </nav>
</header>

<?php
if (isset($_GET['logout'])) {
    require_once __DIR__ . '/../../includes/session.php';
    logout();
}
?>
