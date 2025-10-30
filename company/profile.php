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
    <title>Company Profile - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <section class="dashboard-section active">
                <h2>Company Profile</h2>
                <form id="profile-form" style="max-width: 800px;">
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Company Name:</label>
                        <input type="text" id="name" value="<?php echo htmlspecialchars($company['name'] ?? ''); ?>" style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="industry" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Industry:</label>
                        <input type="text" id="industry" value="<?php echo htmlspecialchars($company['industry'] ?? ''); ?>" style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;">
                    </div>
                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Description:</label>
                        <textarea id="description" rows="6" style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500; resize: vertical;"><?php echo htmlspecialchars($company['description'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn" style="padding: 0.875rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">💾 Update Profile</button>
                </form>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
