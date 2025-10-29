<?php
require_once 'includes/company_auth.php';
$user = getCurrentUser();
$company = getCompanyProfile($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav>
                <ul>
                    <li><a href="#overview" class="active">Overview</a></li>
                    <li><a href="#jobs">Manage Jobs</a></li>
                    <li><a href="#profile">Profile</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <section id="overview" class="dashboard-section active">
                <h2>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Total Job Postings</h3>
                        <p id="jobs-count">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Active Postings</h3>
                        <p id="active-jobs-count">Loading...</p>
                    </div>
                </div>
            </section>

            <section id="jobs" class="dashboard-section">
                <h2>Manage Job Postings</h2>
                <div class="jobs-management">
                    <div class="create-job">
                        <h3>Post New Job</h3>
                        <form id="create-job-form">
                            <div class="form-group">
                                <label for="job-title">Job Title:</label>
                                <input type="text" id="job-title" required>
                            </div>
                            <div class="form-group">
                                <label for="job-description">Description:</label>
                                <textarea id="job-description" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="job-requirements">Requirements:</label>
                                <textarea id="job-requirements" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="job-salary">Salary (optional):</label>
                                <input type="text" id="job-salary" placeholder="e.g., $5000/month">
                            </div>
                            <div class="form-group">
                                <label for="job-type">Job Type:</label>
                                <select id="job-type" required>
                                    <option value="internship">Internship</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>
                            <button type="submit" class="btn">Post Job</button>
                        </form>
                    </div>
                    <div class="jobs-list">
                        <h3>Your Job Postings</h3>
                        <div id="jobs-list">
                            <!-- Jobs will be loaded here -->
                        </div>
                    </div>
                </div>
            </section>

            <section id="profile" class="dashboard-section">
                <h2>Company Profile</h2>
                <form id="profile-form">
                    <div class="form-group">
                        <label for="name">Company Name:</label>
                        <input type="text" id="name" value="<?php echo htmlspecialchars($company['name'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="industry">Industry:</label>
                        <input type="text" id="industry" value="<?php echo htmlspecialchars($company['industry'] ?? ''); ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description"><?php echo htmlspecialchars($company['description'] ?? ''); ?></textarea>
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
