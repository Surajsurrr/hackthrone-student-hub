<header>
    <nav>
        <div class="nav-container">
            <h1><?php echo APP_NAME; ?> - College Portal</h1>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="post_event.php">Post Event</a></li>
                <li><a href="manage_posts.php">Manage Posts</a></li>
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
