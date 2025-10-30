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
    <title>Manage Jobs - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <section class="dashboard-section active">
                <h2>Manage Job Postings</h2>
                <div class="jobs-management">
                    <div class="create-job">
                        <h3>Post New Job</h3>
                        <form id="create-job-form">
                            <div class="form-group">
                                <label for="job-title">Job Title:</label>
                                <input type="text" id="job-title" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;">
                            </div>
                            <div class="form-group">
                                <label for="job-description">Description:</label>
                                <textarea id="job-description" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="job-requirements">Requirements:</label>
                                <textarea id="job-requirements" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="job-salary">Salary (optional):</label>
                                <input type="text" id="job-salary" placeholder="e.g., $5000/month" style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;">
                            </div>
                            <div class="form-group">
                                <label for="job-type">Job Type:</label>
                                <select id="job-type" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;">
                                    <option value="internship">Internship</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>
                            <button type="submit" class="btn" style="padding: 0.875rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Post Job</button>
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
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
