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
                    <li><a href="#applications">Applications</a></li>
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
                        <h3>Total Job Postings</h3>
                        <p id="jobs-count">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Active Postings</h3>
                        <p id="active-jobs-count">Loading...</p>
                    </div>
                </div>

                <!-- Company Posts Section -->
                <div class="posts-section" style="margin-top: 2rem;">
                    <div class="posts-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 style="margin: 0; color: #1e293b; font-size: 1.5rem;">📢 Company Updates & Posts</h3>
                        <button class="btn btn-primary" onclick="openCreatePostModal()" style="padding: 0.75rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                            ✏️ Create Post
                        </button>
                    </div>

                    <!-- Posts Feed -->
                    <div id="posts-feed" class="posts-feed">
                        <p style="text-align: center; color: #64748b; padding: 2rem;">Loading posts...</p>
                    </div>
                </div>
            </section>

            <!-- Create Post Modal -->
            <div id="createPostModal" class="modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; z-index: 1000; overflow-y: auto;">
                <div class="modal-overlay" onclick="closeCreatePostModal()" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5);"></div>
                <div class="modal-container" style="position: relative; max-width: 600px; margin: 2rem auto; background: white; border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3); z-index: 1001;">
                    <div class="modal-header" style="padding: 1.5rem; border-bottom: 2px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <h3 style="margin: 0; color: #0f172a; font-size: 1.25rem;">✏️ Create New Post</h3>
                        <button class="modal-close" onclick="closeCreatePostModal()" style="background: none; border: none; font-size: 2rem; color: #64748b; cursor: pointer; line-height: 1;">&times;</button>
                    </div>
                    <form id="createPostForm" class="post-form" style="padding: 1.5rem;">
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="postCategory" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Post Category *</label>
                            <select id="postCategory" required style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                <option value="">Select a category...</option>
                                <option value="job-opening">💼 Job Openings</option>
                                <option value="company-news">📰 Company News</option>
                                <option value="achievement">🏆 Achievements & Milestones</option>
                                <option value="culture">🎯 Company Culture</option>
                                <option value="technology">💻 Technology & Innovation</option>
                                <option value="internship">🎓 Internship Programs</option>
                                <option value="announcement">📢 Announcements</option>
                                <option value="event">📅 Events & Workshops</option>
                            </select>
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="postTitle" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Post Title *</label>
                            <input 
                                type="text" 
                                id="postTitle" 
                                placeholder="e.g., Now Hiring: Senior Software Engineer" 
                                required
                                maxlength="200"
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                            >
                        </div>
                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="postContent" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">Content *</label>
                            <textarea 
                                id="postContent" 
                                placeholder="Share company updates, job openings, achievements, or announcements..."
                                required
                                rows="6"
                                maxlength="2000"
                                style="width: 100%; padding: 0.75rem; border: 2px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                            ></textarea>
                            <small style="color: #64748b; font-size: 0.85rem;"><span id="charCount">0</span>/2000 characters</small>
                        </div>
                        
                        <div id="postError" style="display: none; padding: 1rem; background: #fee2e2; color: #dc2626; border-radius: 8px; margin-bottom: 1rem;"></div>
                        <div id="postSuccess" style="display: none; padding: 1rem; background: #d1fae5; color: #065f46; border-radius: 8px; margin-bottom: 1rem;"></div>
                        
                        <div style="display: flex; gap: 1rem;">
                            <button type="button" onclick="closeCreatePostModal()" style="flex: 1; padding: 0.875rem; background: #f1f5f9; color: #1e293b; border: 2px solid #cbd5e1; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s;">
                                Cancel
                            </button>
                            <button type="submit" id="submitPostBtn" style="flex: 1; padding: 0.875rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);">
                                📤 Publish Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>

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

            <section id="applications" class="dashboard-section">
                <h2>Job Applications</h2>
                <div class="applications-management">
                    <!-- Filter Options -->
                    <div class="applications-filters" style="margin-bottom: 2rem;">
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <button class="filter-btn active" data-status="all" style="padding: 0.75rem 1.5rem; border: none; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                All Applications
                            </button>
                            <button class="filter-btn" data-status="pending" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Pending
                            </button>
                            <button class="filter-btn" data-status="reviewing" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Reviewing
                            </button>
                            <button class="filter-btn" data-status="accepted" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Accepted
                            </button>
                            <button class="filter-btn" data-status="rejected" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Rejected
                            </button>
                        </div>
                    </div>

                    <!-- Applications Stats -->
                    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Total Applications</h3>
                            <p id="total-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Pending Review</h3>
                            <p id="pending-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Accepted</h3>
                            <p id="accepted-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Rejected</h3>
                            <p id="rejected-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                    </div>

                    <!-- Applications List -->
                    <div class="applications-list" style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <h3 style="margin: 0 0 1.5rem 0; color: #0f172a;">Recent Applications</h3>
                        <div id="applications-list">
                            <!-- Applications will be loaded here dynamically -->
                            <div style="text-align: center; padding: 3rem; color: #64748b;">
                                <p style="font-size: 1.1rem; margin: 0;">📋 No applications yet</p>
                                <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Applications from students will appear here</p>
                            </div>
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

            <section id="settings" class="dashboard-section">
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
        // Applications Section JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Filter buttons functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.style.background = 'white';
                        btn.style.color = '#64748b';
                        btn.style.border = '2px solid #cbd5e1';
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    this.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                    this.style.color = 'white';
                    this.style.border = 'none';
                    
                    const status = this.getAttribute('data-status');
                    filterApplications(status);
                });
            });
            
            // Load applications when Applications section is opened
            const applicationsLink = document.querySelector('a[href="#applications"]');
            if (applicationsLink) {
                applicationsLink.addEventListener('click', function() {
                    loadApplicationsData();
                });
            }
            
            // Notification settings form
            const notificationForm = document.getElementById('notification-settings-form');
            if (notificationForm) {
                notificationForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('Notification preferences saved successfully! ✅');
                });
            }
        });
        
        async function loadApplicationsData() {
            try {
                // This is a placeholder - you'll need to create the actual API endpoint
                // For now, we'll show sample data
                const applicationsListDiv = document.getElementById('applications-list');
                
                // Sample applications data
                const sampleApplications = [
                    {
                        id: 1,
                        student_name: 'John Doe',
                        job_title: 'Software Engineering Intern',
                        status: 'pending',
                        applied_date: '2025-10-28',
                        email: 'john.doe@example.com',
                        resume: 'resume.pdf'
                    },
                    {
                        id: 2,
                        student_name: 'Jane Smith',
                        job_title: 'Frontend Developer',
                        status: 'reviewing',
                        applied_date: '2025-10-27',
                        email: 'jane.smith@example.com',
                        resume: 'resume.pdf'
                    }
                ];
                
                // Update stats
                document.getElementById('total-applications').textContent = sampleApplications.length;
                document.getElementById('pending-applications').textContent = sampleApplications.filter(a => a.status === 'pending').length;
                document.getElementById('accepted-applications').textContent = sampleApplications.filter(a => a.status === 'accepted').length;
                document.getElementById('rejected-applications').textContent = sampleApplications.filter(a => a.status === 'rejected').length;
                
                displayApplications(sampleApplications);
                
            } catch (error) {
                console.error('Error loading applications:', error);
            }
        }
        
        function displayApplications(applications) {
            const applicationsListDiv = document.getElementById('applications-list');
            
            if (applications.length === 0) {
                applicationsListDiv.innerHTML = `
                    <div style="text-align: center; padding: 3rem; color: #64748b;">
                        <p style="font-size: 1.1rem; margin: 0;">📋 No applications yet</p>
                        <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Applications from students will appear here</p>
                    </div>
                `;
                return;
            }
            
            const statusColors = {
                pending: { bg: '#fef3c7', text: '#92400e', border: '#fbbf24' },
                reviewing: { bg: '#dbeafe', text: '#1e3a8a', border: '#3b82f6' },
                accepted: { bg: '#dcfce7', text: '#065f46', border: '#10b981' },
                rejected: { bg: '#fee2e2', text: '#991b1b', border: '#ef4444' }
            };
            
            applicationsListDiv.innerHTML = applications.map(app => `
                <div class="application-card" data-status="${app.status}" style="background: white; border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; transition: all 0.3s;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h4 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size: 1.1rem;">${app.student_name}</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.9rem;">Applied for: <strong>${app.job_title}</strong></p>
                        </div>
                        <span style="padding: 0.5rem 1rem; background: ${statusColors[app.status].bg}; color: ${statusColors[app.status].text}; border: 1px solid ${statusColors[app.status].border}; border-radius: 20px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize;">
                            ${app.status}
                        </span>
                    </div>
                    <div style="display: flex; gap: 2rem; margin-bottom: 1rem; color: #64748b; font-size: 0.9rem;">
                        <div>📧 ${app.email}</div>
                        <div>📅 ${new Date(app.applied_date).toLocaleDateString()}</div>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button onclick="viewApplication(${app.id})" style="padding: 0.5rem 1rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                            View Details
                        </button>
                        <button onclick="downloadResume('${app.resume}')" style="padding: 0.5rem 1rem; background: #f1f5f9; color: #1e293b; border: 2px solid #cbd5e1; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                            📄 Resume
                        </button>
                        ${app.status === 'pending' || app.status === 'reviewing' ? `
                            <button onclick="updateApplicationStatus(${app.id}, 'accepted')" style="padding: 0.5rem 1rem; background: #dcfce7; color: #065f46; border: 2px solid #10b981; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                ✅ Accept
                            </button>
                            <button onclick="updateApplicationStatus(${app.id}, 'rejected')" style="padding: 0.5rem 1rem; background: #fee2e2; color: #991b1b; border: 2px solid #ef4444; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                ❌ Reject
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        }
        
        function filterApplications(status) {
            const applicationCards = document.querySelectorAll('.application-card');
            applicationCards.forEach(card => {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        
        function viewApplication(id) {
            alert('View application details for ID: ' + id);
            // TODO: Open modal or navigate to detailed view
        }
        
        function downloadResume(filename) {
            alert('Download resume: ' + filename);
            // TODO: Implement resume download
        }
        
        function updateApplicationStatus(id, status) {
            if (confirm(`Are you sure you want to ${status} this application?`)) {
                alert(`Application ${status} successfully! ✅`);
                // TODO: Call API to update status
                loadApplicationsData(); // Reload data
            }
        }

        // ============ POSTS FUNCTIONALITY ============
        let allPosts = [];

        // Load posts on page load
        window.addEventListener('DOMContentLoaded', function() {
            loadPosts();
            
            // Character counter for post content
            const postContent = document.getElementById('postContent');
            if (postContent) {
                postContent.addEventListener('input', function() {
                    document.getElementById('charCount').textContent = this.value.length;
                });
            }
        });

        function openCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('createPostForm').reset();
            document.getElementById('charCount').textContent = '0';
            document.getElementById('postError').style.display = 'none';
            document.getElementById('postSuccess').style.display = 'none';
        }

        // Handle post form submission
        document.getElementById('createPostForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitPostBtn');
            const errorDiv = document.getElementById('postError');
            const successDiv = document.getElementById('postSuccess');
            
            const category = document.getElementById('postCategory').value;
            const title = document.getElementById('postTitle').value;
            const content = document.getElementById('postContent').value;
            
            if (!category || !title || !content) {
                errorDiv.textContent = '❌ Please fill in all required fields';
                errorDiv.style.display = 'block';
                return;
            }
            
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Publishing...';
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';
            
            try {
                const response = await fetch('../api/company/createPost.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        category: category,
                        title: title,
                        content: content
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    successDiv.textContent = '✅ Post published successfully!';
                    successDiv.style.display = 'block';
                    
                    setTimeout(() => {
                        closeCreatePostModal();
                        loadPosts();
                    }, 1500);
                } else {
                    errorDiv.textContent = '❌ ' + (data.error || 'Failed to create post');
                    errorDiv.style.display = 'block';
                    submitBtn.disabled = false;
                    submitBtn.textContent = '📤 Publish Post';
                }
            } catch (error) {
                console.error('Error creating post:', error);
                errorDiv.textContent = '❌ Network error. Please try again.';
                errorDiv.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.textContent = '📤 Publish Post';
            }
        });

        async function loadPosts() {
            try {
                const response = await fetch('../api/company/getPosts.php');
                const data = await response.json();
                
                if (data.success && data.posts) {
                    allPosts = data.posts;
                    displayPosts(allPosts);
                } else {
                    showEmptyPosts();
                }
            } catch (error) {
                console.error('Error loading posts:', error);
                document.getElementById('posts-feed').innerHTML = `
                    <p style="text-align: center; color: #dc2626; padding: 2rem;">
                        Failed to load posts. Please refresh the page.
                    </p>
                `;
            }
        }

        function displayPosts(posts) {
            const container = document.getElementById('posts-feed');
            
            if (!posts || posts.length === 0) {
                showEmptyPosts();
                return;
            }

            const categoryLabels = {
                'job-opening': '💼 Job Opening',
                'company-news': '📰 Company News',
                'achievement': '🏆 Achievement',
                'culture': '🎯 Company Culture',
                'technology': '💻 Technology',
                'internship': '🎓 Internship',
                'announcement': '📢 Announcement',
                'event': '📅 Event'
            };

            container.innerHTML = posts.map(post => {
                const postDate = new Date(post.created_at);
                const timeAgo = getTimeAgo(postDate);
                const categoryClass = 'category-' + post.category;
                
                return `
                    <div class="post-card" style="background: white; border: 2px solid #e2e8f0; border-radius: 16px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05); transition: all 0.3s;">
                        <div class="post-header" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                            <img src="${post.company_logo || '../assets/images/logos/default-company.png'}" 
                                 alt="Company Logo" 
                                 style="width: 48px; height: 48px; border-radius: 8px; object-fit: cover; border: 2px solid #e2e8f0;"
                                 onerror="this.src='../assets/images/logos/default-company.png'">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 0.25rem 0; color: #0f172a; font-size: 1.1rem;">${escapeHtml(post.company_name)}</h4>
                                <div style="display: flex; gap: 0.75rem; align-items: center; color: #64748b; font-size: 0.9rem;">
                                    <span style="padding: 0.25rem 0.75rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                        ${categoryLabels[post.category] || post.category}
                                    </span>
                                    <span>•</span>
                                    <span>${timeAgo}</span>
                                </div>
                            </div>
                        </div>
                        
                        <h3 style="margin: 0 0 0.75rem 0; color: #0f172a; font-size: 1.25rem; font-weight: 700;">${escapeHtml(post.title)}</h3>
                        <p style="margin: 0; color: #475569; line-height: 1.6; white-space: pre-wrap;">${escapeHtml(post.content)}</p>
                        
                        <div class="post-actions" style="display: flex; gap: 1rem; margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid #e2e8f0;">
                            <button onclick="deletePost(${post.id})" style="padding: 0.5rem 1rem; background: #fee2e2; color: #dc2626; border: 2px solid #fecaca; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function showEmptyPosts() {
            document.getElementById('posts-feed').innerHTML = `
                <div style="text-align: center; padding: 4rem 2rem; background: white; border: 2px dashed #cbd5e1; border-radius: 16px;">
                    <div style="font-size: 4rem; margin-bottom: 1rem;">📝</div>
                    <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size: 1.5rem;">No Posts Yet</h3>
                    <p style="margin: 0 0 1.5rem 0; color: #64748b;">Start sharing your company updates, job openings, and announcements!</p>
                    <button class="btn btn-primary" onclick="openCreatePostModal()" style="padding: 0.875rem 1.75rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">
                        ✏️ Create Your First Post
                    </button>
                </div>
            `;
        }

        async function deletePost(postId) {
            if (!confirm('Are you sure you want to delete this post?')) {
                return;
            }
            
            try {
                const response = await fetch('../api/company/deletePost.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ post_id: postId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    loadPosts();
                } else {
                    alert('Failed to delete post: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting post:', error);
                alert('Network error. Please try again.');
            }
        }

        function getTimeAgo(date) {
            const seconds = Math.floor((new Date() - date) / 1000);
            
            const intervals = {
                year: 31536000,
                month: 2592000,
                week: 604800,
                day: 86400,
                hour: 3600,
                minute: 60
            };
            
            for (const [unit, secondsInUnit] of Object.entries(intervals)) {
                const interval = Math.floor(seconds / secondsInUnit);
                if (interval >= 1) {
                    return interval === 1 ? `1 ${unit} ago` : `${interval} ${unit}s ago`;
                }
            }
            
            return 'Just now';
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
