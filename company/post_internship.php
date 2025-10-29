<?php
require_once 'includes/company_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Job - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>Post New Job/Internship</h2>
        <form id="post-job-form">
            <div class="form-group">
                <label for="title">Job Title:</label>
                <input type="text" id="title" required>
            </div>
            <div class="form-group">
                <label for="description">Job Description:</label>
                <textarea id="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="requirements">Requirements:</label>
                <textarea id="requirements" required></textarea>
            </div>
            <div class="form-group">
                <label for="salary">Salary/Stipend:</label>
                <input type="text" id="salary" placeholder="e.g., $5000/month or Negotiable">
            </div>
            <div class="form-group">
                <label for="location">Location:</label>
                <input type="text" id="location">
            </div>
            <div class="form-group">
                <label for="type">Job Type:</label>
                <select id="type" required>
                    <option value="internship">Internship</option>
                    <option value="full-time">Full-time</option>
                    <option value="part-time">Part-time</option>
                    <option value="contract">Contract</option>
                </select>
            </div>
            <div class="form-group">
                <label for="application_deadline">Application Deadline:</label>
                <input type="date" id="application_deadline">
            </div>
            <button type="submit" class="btn">Post Job</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
