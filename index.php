<?php
require_once 'includes/session_simple.php';
require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Your Career Hub</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav>
            <div class="nav-container">
                <h1><?php echo APP_NAME; ?></h1>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php
                        $user = getCurrentUser();
                        switch ($user['role']) {
                            case 'student':
                                echo '<li><a href="student/dashboard.php">Dashboard</a></li>';
                                break;
                            case 'college':
                                echo '<li><a href="college/dashboard.php">Dashboard</a></li>';
                                break;
                            case 'company':
                                echo '<li><a href="company/dashboard.php">Dashboard</a></li>';
                                break;
                            case 'admin':
                                echo '<li><a href="admin/dashboard.php">Dashboard</a></li>';
                                break;
                        }
                        ?>
                        <li><a href="?logout=1">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero">
            <div class="hero-content">
                <h2>Welcome to <?php echo APP_NAME; ?></h2>
                <p>Your one-stop platform for career development, opportunities, and collaboration.</p>
                <?php if (!isLoggedIn()): ?>
                    <a href="register.php" class="btn btn-primary">Get Started</a>
                <?php endif; ?>
            </div>
        </section>

        <section class="features">
            <div class="container">
                <h3>What We Offer</h3>
                <div class="feature-grid">
                    <div class="feature-card">
                        <h4>For Students</h4>
                        <ul>
                            <li>Access to hackathons and internships</li>
                            <li>AI-powered career coaching</li>
                            <li>Note sharing with peers</li>
                            <li>Personalized dashboard</li>
                        </ul>
                    </div>
                    <div class="feature-card">
                        <h4>For Colleges</h4>
                        <ul>
                            <li>Post events and competitions</li>
                            <li>Manage student interactions</li>
                            <li>Promote your institution</li>
                            <li>Track event participation</li>
                        </ul>
                    </div>
                    <div class="feature-card">
                        <h4>For Companies</h4>
                        <ul>
                            <li>Post internships and jobs</li>
                            <li>Find talented students</li>
                            <li>Manage recruitment drives</li>
                            <li>Build employer brand</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

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
</body>
</html>
