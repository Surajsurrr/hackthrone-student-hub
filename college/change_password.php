<?php
require_once 'includes/college_auth.php';
$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .password-container {
            max-width: 500px;
            width: 100%;
        }

        .password-card {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .lock-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(124, 58, 237, 0.3);
        }

        .card-header h1 {
            margin: 0 0 0.5rem 0;
            color: #0f172a;
            font-size: 1.75rem;
        }

        .card-header p {
            margin: 0;
            color: #64748b;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            font-family: 'Inter', sans-serif;
        }

        .form-group input:focus {
            outline: none;
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .password-requirements {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .password-requirements h4 {
            margin: 0 0 0.75rem 0;
            color: #1e293b;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .password-requirements ul {
            margin: 0;
            padding-left: 1.25rem;
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.8;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #7c3aed, #6d28d9);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(124, 58, 237, 0.4);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 1.5rem;
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #7c3aed;
        }

        .message {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            display: none;
            font-size: 0.95rem;
        }

        .message.success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .message.error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #ef4444;
        }
    </style>
</head>
<body>
    <div class="password-container">
        <div class="password-card">
            <div class="card-header">
                <div class="lock-icon">🔐</div>
                <h1>Change Password</h1>
                <p>Secure your account with a strong password</p>
            </div>

            <div id="message" class="message"></div>

            <form id="password-form">
                <div class="form-group">
                    <label for="current-password">Current Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="current-password" 
                            placeholder="Enter your current password"
                            required
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="new-password">New Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="new-password" 
                            placeholder="Enter new password"
                            required
                            minlength="8"
                        >
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm New Password</label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="confirm-password" 
                            placeholder="Re-enter new password"
                            required
                            minlength="8"
                        >
                    </div>
                </div>

                <div class="password-requirements">
                    <h4>⚠️ Password Requirements:</h4>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Include uppercase and lowercase letters</li>
                        <li>Include at least one number</li>
                        <li>Include at least one special character (!@#$%^&*)</li>
                    </ul>
                </div>

                <button type="submit" class="btn-submit" id="submit-btn">
                    🔒 Update Password
                </button>
            </form>

            <a href="dashboard.php#settings" class="back-link">← Back to Dashboard</a>
        </div>
    </div>

    <script>
        document.getElementById('password-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submit-btn');
            const messageDiv = document.getElementById('message');
            const currentPassword = document.getElementById('current-password').value;
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;
            
            // Validate passwords match
            if (newPassword !== confirmPassword) {
                messageDiv.className = 'message error';
                messageDiv.textContent = '❌ New passwords do not match';
                messageDiv.style.display = 'block';
                return;
            }
            
            // Validate password length
            if (newPassword.length < 8) {
                messageDiv.className = 'message error';
                messageDiv.textContent = '❌ Password must be at least 8 characters long';
                messageDiv.style.display = 'block';
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Updating...';
            messageDiv.style.display = 'none';
            
            try {
                const response = await fetch('../api/college/changePassword.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = '✅ Password updated successfully! Redirecting...';
                    messageDiv.style.display = 'block';
                    
                    document.getElementById('password-form').reset();
                    
                    setTimeout(() => {
                        window.location.href = 'dashboard.php#settings';
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Failed to update password');
                }
            } catch (error) {
                messageDiv.className = 'message error';
                messageDiv.textContent = '❌ ' + error.message;
                messageDiv.style.display = 'block';
                
                submitBtn.disabled = false;
                submitBtn.textContent = '🔒 Update Password';
            }
        });
    </script>
</body>
</html>
