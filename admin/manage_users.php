<?php
require_once '../includes/session.php';
require_once '../includes/functions.php';

if (!hasRole('admin')) {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
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

    <div class="container">
        <h2>Manage Users</h2>
        <div id="users-list" class="users-grid">
            <!-- Users will be loaded here -->
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2025 <?php echo APP_NAME; ?>. All rights reserved.</p>
        </div>
    </footer>

    <?php
    if (isset($_GET['logout'])) {
        logout();
    }
    ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
