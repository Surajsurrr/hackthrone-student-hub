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
    <script>
        // Handle profile form submission
        document.getElementById('profile-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            
            // Get form data
            const profileData = {
                name: document.getElementById('name').value.trim(),
                industry: document.getElementById('industry').value.trim(),
                description: document.getElementById('description').value.trim()
            };

            // Validate
            if (!profileData.name) {
                alert('⚠️ Company name is required');
                return;
            }

            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Saving...';

            try {
                const response = await fetch('../api/company/updateProfile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(profileData)
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ ' + (result.message || 'Profile updated successfully!'));
                    // Optionally reload the page to show updated data
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('❌ ' + (result.error || 'Failed to update profile'));
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                alert('❌ An error occurred while updating your profile');
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        });
    </script>
</body>
</html>
