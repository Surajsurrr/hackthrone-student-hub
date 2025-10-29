<header>
    <nav>
        <div class="nav-container">
            <h1><?php echo APP_NAME; ?> - Student Portal</h1>
            <div class="ui-controls">
                <button id="compact-toggle" class="ui-btn" title="Toggle compact mode">⌖</button>
                <button id="theme-toggle" class="ui-btn" title="Toggle neon theme">🌈</button>
            </div>
            <ul>
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="hackathons.php">Hackathons</a></li>
                <li><a href="internships.php">Internships</a></li>
                <li><a href="notes.php">Notes</a></li>
                <li><a href="ai_coach.php">AI Coach</a></li>
                <li><a href="profile.php">Profile</a></li>
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
