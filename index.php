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
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <style>
        /* Hero overrides to match dashboard template */
        .hero {
            padding: 6rem 0;
            background: linear-gradient(135deg, rgba(11,18,32,1) 0%, rgba(15,23,36,1) 100%);
            border-radius: 0 0 0 0;
            box-shadow: inset 0 -40px 80px rgba(2,6,23,0.6);
        }
        .hero .hero-content {
            max-width: 1100px;
            margin: 0 auto;
            padding: 2rem;
            text-align: center;
        }
        .hero .hero-content h2 {
            font-size: 3.2rem;
            line-height: 1.05;
            font-weight: 800;
            margin-bottom: 1rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero .hero-content p {
            color: #cbd5e1;
            font-size: 1.05rem;
            margin-bottom: 1.75rem;
        }
        .hero .btn-primary {
            padding: 0.9rem 1.25rem;
            font-size: 1rem;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(102,126,234,0.18);
        }
        /* Decorative accent on wide screens */
        .hero-accent {
            position: absolute;
            right: 2rem;
            top: 30px;
            width: 360px;
            height: 360px;
            background: radial-gradient(ellipse at center, rgba(124,58,237,0.12), rgba(6,182,212,0.06));
            border-radius: 50%;
            filter: blur(18px);
            pointer-events: none;
            display: none;
        }
        @media (min-width: 1100px) {
            .hero-accent { display: block; }
        }
    </style>
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
