<?php
require_once 'includes/company_auth.php';
$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <section class="dashboard-section active">
                <h2>Settings</h2>
                <div class="settings-container" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                    
                    <!-- Account Information Card -->
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <h3 style="margin: 0 0 1.5rem 0; color: #0f172a; font-size: 1.25rem;">Account Information</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <div style="padding: 1rem; background: #fefce8; border-left: 4px solid #fbbf24; border-radius: 8px;">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">USERNAME</div>
                                <div style="font-size: 1rem; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($user['username']); ?></div>
                            </div>
                            <div style="padding: 1rem; background: #dbeafe; border-left: 4px solid #3b82f6; border-radius: 8px;">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #1e3a8a; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">EMAIL</div>
                                <div style="font-size: 1rem; font-weight: 600; color: #0f172a;"><?php echo htmlspecialchars($user['email'] ?? 'Not set'); ?></div>
                            </div>
                            <div style="padding: 1rem; background: #dcfce7; border-left: 4px solid #10b981; border-radius: 8px;">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #065f46; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">ACCOUNT TYPE</div>
                                <div style="font-size: 1rem; font-weight: 600; color: #0f172a;">Company</div>
                            </div>
                            <div style="padding: 1rem; background: #fae8ff; border-left: 4px solid #a855f7; border-radius: 8px;">
                                <div style="font-size: 0.75rem; font-weight: 600; color: #6b21a8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem;">MEMBER SINCE</div>
                                <div style="font-size: 1rem; font-weight: 600; color: #0f172a;"><?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Security Settings Card -->
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #ef4444, #dc2626); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">🔒</div>
                            <h3 style="margin: 0; color: #0f172a; font-size: 1.25rem;">Security Settings</h3>
                        </div>
                        
                        <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #fbbf24;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <span style="font-size: 1.25rem;">⚠️</span>
                                <strong style="color: #92400e; font-size: 0.95rem;">Security Recommendation</strong>
                            </div>
                            <p style="margin: 0; color: #78350f; font-size: 0.875rem; line-height: 1.5;">Change your password regularly to keep your account secure.</p>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <a href="change_password.php" style="display: block; width: 100%; padding: 1rem; background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white; text-align: center; border-radius: 10px; text-decoration: none; font-weight: 600; transition: all 0.3s; box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);">
                                🔐 Change Password
                            </a>
                        </div>

                        <div style="padding: 1rem; background: #f8fafc; border-radius: 10px; border: 1px solid #e2e8f0;">
                            <div style="font-size: 0.875rem; color: #64748b; margin-bottom: 0.5rem;">
                                <strong style="color: #1e293b;">Last password change:</strong> Never
                            </div>
                        </div>

                        <div style="margin-top: 1.5rem; padding: 1rem; background: #f1f5f9; border-radius: 10px;">
                            <h4 style="margin: 0 0 0.75rem 0; color: #1e293b; font-size: 0.9rem;">🛡️ Security Tips:</h4>
                            <ul style="margin: 0; padding-left: 1.25rem; color: #475569; font-size: 0.85rem; line-height: 1.8;">
                                <li>Use a strong, unique password</li>
                                <li>Never share your password with anyone</li>
                                <li>Enable two-factor authentication when available</li>
                                <li>Review your account activity regularly</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Notification Preferences -->
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <h3 style="margin: 0 0 1.5rem 0; color: #0f172a; font-size: 1.25rem;">Notification Preferences</h3>
                        <form id="notification-settings-form">
                            <div style="display: flex; flex-direction: column; gap: 1rem;">
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                                    <input type="checkbox" checked style="width: 20px; height: 20px; cursor: pointer;">
                                    <div>
                                        <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Email Notifications</div>
                                        <div style="font-size: 0.85rem; color: #64748b;">Receive email updates about new applications</div>
                                    </div>
                                </label>
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                                    <input type="checkbox" checked style="width: 20px; height: 20px; cursor: pointer;">
                                    <div>
                                        <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Application Alerts</div>
                                        <div style="font-size: 0.85rem; color: #64748b;">Get notified when students apply to your jobs</div>
                                    </div>
                                </label>
                                <label style="display: flex; align-items: center; gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; cursor: pointer; transition: all 0.3s;">
                                    <input type="checkbox" style="width: 20px; height: 20px; cursor: pointer;">
                                    <div>
                                        <div style="font-weight: 600; color: #0f172a; margin-bottom: 0.25rem;">Weekly Summary</div>
                                        <div style="font-size: 0.85rem; color: #64748b;">Receive weekly reports about your job postings</div>
                                    </div>
                                </label>
                            </div>
                            <button type="submit" class="btn" style="width: 100%; margin-top: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                💾 Save Preferences
                            </button>
                        </form>
                    </div>

                    <!-- Account Actions -->
                    <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <h3 style="margin: 0 0 1.5rem 0; color: #0f172a; font-size: 1.25rem;">Account Actions</h3>
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            <button class="btn" style="width: 100%; padding: 1rem; background: #f1f5f9; color: #1e293b; border: 2px solid #cbd5e1; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                📥 Download Account Data
                            </button>
                            <button class="btn" style="width: 100%; padding: 1rem; background: #fef2f2; color: #dc2626; border: 2px solid #fecaca; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                🗑️ Delete Account
                            </button>
                        </div>
                        <div style="margin-top: 1.5rem; padding: 1rem; background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px;">
                            <p style="margin: 0; color: #991b1b; font-size: 0.85rem; line-height: 1.6;">
                                ⚠️ <strong>Warning:</strong> Deleting your account is permanent and cannot be undone. All your data will be permanently removed.
                            </p>
                        </div>
                    </div>

                </div>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        document.getElementById('notification-settings-form').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Notification preferences saved successfully! ✅');
        });
    </script>
</body>
</html>
