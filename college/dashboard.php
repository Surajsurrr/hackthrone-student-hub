<?php
require_once 'includes/college_auth.php';
$user = getCurrentUser();
$college = getCollegeProfile($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="sidebar-avatar">
                    <img src="<?php echo htmlspecialchars(!empty($college['logo']) ? $college['logo'] : '../assets/images/profile_pics/default.svg'); ?>" alt="College Logo">
                </div>
                <div class="sidebar-name">
                    <a href="profile.php"><?php echo htmlspecialchars($college['name'] ?? $user['username']); ?></a>
                    <div class="muted small"><?php echo htmlspecialchars($college['location'] ?? ''); ?></div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="#overview" class="active">Overview</a></li>
                    <li><a href="#events">Manage Events</a></li>
                    <li><a href="#applicants">Applicants</a></li>
                    <li><a href="#profile">Profile</a></li>
                    <li><a href="#settings">Settings</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <section id="overview" class="dashboard-section active">
                <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Events</h3>
                        <p id="events-count">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Active Events</h3>
                        <p id="active-events-count">Loading...</p>
                    </div>
                </div>
            </section>

            <section id="events" class="dashboard-section">
                <h2>Manage Events</h2>
                <div class="events-management">
                    <div class="create-event">
                        <h3>Create New Event</h3>
                        <form id="create-event-form">
                            <div class="form-group">
                                <label for="event-title">Title:</label>
                                <input type="text" id="event-title" required>
                            </div>
                            <div class="form-group">
                                <label for="event-description">Description:</label>
                                <textarea id="event-description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="event-date">Date:</label>
                                <input type="datetime-local" id="event-date" required>
                            </div>
                            <div class="form-group">
                                <label for="event-type">Type:</label>
                                <select id="event-type" required>
                                    <option value="hackathon">Hackathon</option>
                                    <option value="symposium">Symposium</option>
                                    <option value="project-expo">Project Expo</option>
                                    <option value="workshop">Workshop</option>
                                </select>
                            </div>
                            <button type="submit" class="btn">Create Event</button>
                        </form>
                    </div>
                    <div class="events-list">
                        <h3>Your Events</h3>
                        <div id="events-list">
                            <!-- Events will be loaded here -->
                        </div>
                    </div>
                </div>
            </section>

            <section id="profile" class="dashboard-section">
                <h2>College Profile</h2>
                <form id="profile-form">
                    <div class="form-group">
                        <label for="name">College Name:</label>
                        <input type="text" id="name" value="<?php echo htmlspecialchars($college['name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="location">Location:</label>
                        <input type="text" id="location" value="<?php echo htmlspecialchars($college['location'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description"><?php echo htmlspecialchars($college['description'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn">Update Profile</button>
                </form>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
