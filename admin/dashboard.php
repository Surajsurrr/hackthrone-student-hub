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
    <title>Admin Dashboard - <?php echo APP_NAME; ?></title>
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

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="#overview" class="active">Overview</a></li>
                    <li><a href="#users">Users</a></li>
                    <li><a href="#events">Events</a></li>
                    <li><a href="#jobs">Jobs</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <section id="overview" class="dashboard-section active">
                <h2>Admin Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <p id="total-users">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Events</h3>
                        <p id="total-events">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Jobs</h3>
                        <p id="total-jobs">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Shared Notes</h3>
                        <p id="total-notes">Loading...</p>
                    </div>
                </div>
            </section>

            <section id="users" class="dashboard-section">
                <h2>Manage Users</h2>
                <div id="users-list">
                    <!-- Users will be loaded here -->
                </div>
            </section>

            <section id="events" class="dashboard-section">
                <h2>Manage Events</h2>
                <div id="events-list">
                    <!-- Events will be loaded here -->
                </div>
            </section>

            <section id="jobs" class="dashboard-section">
                <h2>Manage Jobs</h2>
                <div id="jobs-list">
                    <!-- Jobs will be loaded here -->
                </div>
            </section>
        </main>
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
