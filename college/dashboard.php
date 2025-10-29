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
                    <div class="stat-card">
                        <h3>Total Applications</h3>
                        <p id="applications-count">Loading...</p>
                    </div>
                    <div class="stat-card">
                        <h3>Pending Applications</h3>
                        <p id="pending-applications-count">Loading...</p>
                    </div>
                </div>

                <!-- College Posts Section (LinkedIn-style) -->
                <div class="posts-section" style="margin-top: 2rem;">
                    <div class="posts-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h3 style="margin: 0; color: #1e293b; font-size: 1.5rem;">📢 College Updates & Posts</h3>
                        <button class="btn btn-primary" onclick="openCreatePostModal()" style="padding: 0.75rem 1.5rem;">
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
            <div id="createPostModal" class="modal" style="display: none;">
                <div class="modal-overlay" onclick="closeCreatePostModal()"></div>
                <div class="modal-container" style="max-width: 600px;">
                    <div class="modal-header">
                        <h3>✏️ Create New Post</h3>
                        <button class="modal-close" onclick="closeCreatePostModal()">&times;</button>
                    </div>
                    <form id="createPostForm" class="post-form">
                        <div class="form-group">
                            <label for="postCategory">Post Category *</label>
                            <select id="postCategory" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;">
                                <option value="">Select a category...</option>
                                <option value="research">🔬 Research & Innovation</option>
                                <option value="achievement">🏆 Achievements & Awards</option>
                                <option value="event">📅 Campus Events</option>
                                <option value="placement">💼 Placements & Opportunities</option>
                                <option value="campus-life">🎓 Campus Life & Culture</option>
                                <option value="announcement">📢 Announcements</option>
                                <option value="facilities">🏫 Facilities & Infrastructure</option>
                                <option value="collaboration">🤝 Collaborations & Partnerships</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="postTitle">Post Title *</label>
                            <input 
                                type="text" 
                                id="postTitle" 
                                placeholder="e.g., New AI Research Lab Inaugurated" 
                                required
                                maxlength="200"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                            >
                        </div>
                        <div class="form-group">
                            <label for="postContent">Content *</label>
                            <textarea 
                                id="postContent" 
                                placeholder="Share your college's achievements, research breakthroughs, campus events, or any updates..."
                                required
                                rows="6"
                                maxlength="2000"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                            ></textarea>
                            <small style="color: #64748b; font-size: 0.85rem;"><span id="charCount">0</span>/2000 characters</small>
                        </div>
                        
                        <!-- File Attachments Section -->
                        <div class="form-group">
                            <label style="display: block; margin-bottom: 0.75rem; font-weight: 600;">📎 Attachments (Optional)</label>
                            
                            <!-- Image Upload -->
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                                <div style="flex: 1;">
                                    <label for="imageUpload" class="upload-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #3b82f6; color: white; border-radius: 8px; cursor: pointer; transition: background 0.3s;">
                                        🖼️ Upload Image
                                    </label>
                                    <input type="file" id="imageUpload" accept="image/*" style="display: none;">
                                    <div id="imagePreview" style="display: none; margin-top: 0.5rem;">
                                        <img id="previewImg" style="max-width: 200px; max-height: 150px; border-radius: 8px; border: 2px solid #e2e8f0;">
                                        <button type="button" onclick="removeImage()" style="display: block; margin-top: 0.5rem; color: #dc2626; background: none; border: none; cursor: pointer; font-size: 0.9rem;">✕ Remove</button>
                                    </div>
                                    <small style="display: block; margin-top: 0.5rem; color: #64748b; font-size: 0.85rem;">JPG, PNG, GIF, WEBP (Max 5MB)</small>
                                </div>

                                <!-- Document Upload -->
                                <div style="flex: 1;">
                                    <label for="documentUpload" class="upload-btn" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.25rem; background: #10b981; color: white; border-radius: 8px; cursor: pointer; transition: background 0.3s;">
                                        📄 Upload Document
                                    </label>
                                    <input type="file" id="documentUpload" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" style="display: none;">
                                    <div id="documentPreview" style="display: none; margin-top: 0.5rem; padding: 0.75rem; background: #f1f5f9; border-radius: 8px;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span>📄</span>
                                            <span id="docName" style="flex: 1; font-size: 0.9rem; color: #1e293b;"></span>
                                            <button type="button" onclick="removeDocument()" style="color: #dc2626; background: none; border: none; cursor: pointer; font-size: 1.2rem;">✕</button>
                                        </div>
                                    </div>
                                    <small style="display: block; margin-top: 0.5rem; color: #64748b; font-size: 0.85rem;">PDF, DOC, XLS, PPT, TXT (Max 10MB)</small>
                                </div>
                            </div>
                            <div id="uploadProgress" style="display: none; padding: 0.75rem; background: #fef3c7; border-radius: 8px; color: #92400e; margin-bottom: 1rem;">
                                ⏳ Uploading file...
                            </div>
                        </div>
                        
                        <div id="postError" class="error-message" style="display: none; color: #dc2626; background: #fee2e2; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem;"></div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-secondary" onclick="closeCreatePostModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submitPostBtn">
                                <span>📤 Publish Post</span>
                            </button>
                        </div>
                    </form>
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

            <section id="applicants" class="dashboard-section">
                <h2>👥 Event Applications</h2>
                <div class="applicants-card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <div>
                            <h3 style="color: #1e293b; margin: 0;">Event Registrations</h3>
                            <p style="color: #64748b; margin: 0.5rem 0 0 0;">View and manage students who have applied to your events</p>
                        </div>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <select id="filter-event" style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem;">
                                <option value="all">All Events</option>
                            </select>
                            <select id="filter-status" style="padding: 0.5rem 1rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 0.9rem;">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div id="applicants-list">
                        <div style="text-align: center; padding: 3rem; color: #64748b;">
                            <div style="font-size: 2rem;">⏳</div>
                            <p>Loading applications...</p>
                        </div>
                    </div>
                </div>
            </section>

            <section id="profile" class="dashboard-section">
                <h2>🏫 College Profile</h2>
                <div class="profile-card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <form id="profile-form">
                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label for="profile-name" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    College Name <span style="color: #dc2626;">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="profile-name" 
                                    value="<?php echo htmlspecialchars($college['name'] ?? ''); ?>" 
                                    required
                                    placeholder="e.g., MIT College of Engineering"
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #0f172a; background: #ffffff;"
                                >
                            </div>
                            <div class="form-group">
                                <label for="profile-location" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    Location
                                </label>
                                <input 
                                    type="text" 
                                    id="profile-location" 
                                    value="<?php echo htmlspecialchars($college['location'] ?? ''); ?>"
                                    placeholder="e.g., Pune, Maharashtra"
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #0f172a; background: #ffffff;"
                                >
                            </div>
                        </div>

                        <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div class="form-group">
                                <label for="profile-website" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    Website URL
                                </label>
                                <input 
                                    type="text" 
                                    id="profile-website" 
                                    value="<?php echo htmlspecialchars($college['website'] ?? ''); ?>"
                                    placeholder="https://www.college.edu"
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #0f172a; background: #ffffff;"
                                >
                            </div>
                            <div class="form-group">
                                <label for="profile-email" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    Contact Email
                                </label>
                                <input 
                                    type="text" 
                                    id="profile-email" 
                                    value="<?php echo htmlspecialchars($college['contact_email'] ?? ''); ?>"
                                    placeholder="info@college.edu"
                                    style="width: 100%; padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; color: #0f172a; background: #ffffff;"
                                >
                            </div>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="profile-logo" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                Logo URL
                            </label>
                            <input 
                                type="text" 
                                id="profile-logo" 
                                value="<?php echo htmlspecialchars($college['logo'] ?? ''); ?>"
                                placeholder="https://www.college.edu/logo.png"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                            >
                            <small style="color: #64748b; font-size: 0.85rem;">This logo will appear in your posts and profile</small>
                        </div>

                        <div class="form-group" style="margin-bottom: 1.5rem;">
                            <label for="profile-description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                Description
                            </label>
                            <textarea 
                                id="profile-description" 
                                rows="5"
                                placeholder="Tell students about your college, courses, achievements, and what makes you unique..."
                                style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem; resize: vertical;"
                            ><?php echo htmlspecialchars($college['description'] ?? ''); ?></textarea>
                        </div>

                        <div id="profile-message" style="display: none; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"></div>

                        <button type="submit" class="btn btn-primary" id="update-profile-btn" style="padding: 0.75rem 2rem; font-size: 1rem;">
                            💾 Save Profile
                        </button>
                    </form>
                </div>
            </section>

            <section id="settings" class="dashboard-section">
                <h2>⚙️ Settings</h2>
                <div class="settings-card" style="background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                    <h3 style="margin-top: 0; color: #1e293b;">Account Settings</h3>
                    <p style="color: #64748b; margin-bottom: 2rem;">Manage your account preferences and security settings</p>
                    
                    <div class="settings-section" style="padding: 1.5rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1.5rem;">
                        <h4 style="margin: 0 0 0.5rem 0; color: #1e293b;">Account Information</h4>
                        <p style="color: #64748b; font-size: 0.9rem; margin: 0;">
                            <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?><br>
                            <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?><br>
                            <strong>Role:</strong> College<br>
                            <strong>Member Since:</strong> <?php echo date('F Y', strtotime($user['created_at'])); ?>
                        </p>
                    </div>

                    <div class="settings-section" style="padding: 1.5rem; background: #f8fafc; border-radius: 8px;">
                        <h4 style="margin: 0 0 1rem 0; color: #1e293b;">Change Password</h4>
                        <form id="change-password-form">
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="current-password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    Current Password
                                </label>
                                <input 
                                    type="password" 
                                    id="current-password" 
                                    required
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                                >
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="new-password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    New Password
                                </label>
                                <input 
                                    type="password" 
                                    id="new-password" 
                                    required
                                    minlength="6"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                                >
                            </div>
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="confirm-password" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #1e293b;">
                                    Confirm New Password
                                </label>
                                <input 
                                    type="password" 
                                    id="confirm-password" 
                                    required
                                    minlength="6"
                                    style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                                >
                            </div>
                            <div id="password-message" style="display: none; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;"></div>
                            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem;">
                                🔒 Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <style>
        /* Section Navigation Styles */
        .dashboard-section {
            display: none;
        }
        
        .dashboard-section.active {
            display: block;
        }
        
        .sidebar nav a {
            transition: all 0.3s ease;
        }
        
        .sidebar nav a.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        /* Posts Section Styling */
        .posts-feed {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .post-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            transition: all 0.3s ease;
        }

        .post-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
        }

        .post-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .post-college-logo {
            width: 50px;
            height: 50px;
            border-radius: 8px;
            object-fit: cover;
        }

        .post-header-info h4 {
            margin: 0 0 0.25rem 0;
            color: #1e293b;
            font-size: 1rem;
            font-weight: 600;
        }

        .post-meta {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #64748b;
        }

        .post-category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .category-research { background: #dbeafe; color: #1e40af; }
        .category-achievement { background: #fef3c7; color: #92400e; }
        .category-event { background: #f3e8ff; color: #6b21a8; }
        .category-placement { background: #dcfce7; color: #15803d; }
        .category-campus-life { background: #fce7f3; color: #be185d; }
        .category-announcement { background: #fee2e2; color: #991b1b; }
        .category-facilities { background: #e0f2fe; color: #075985; }
        .category-collaboration { background: #fef9c3; color: #854d0e; }

        .post-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0 0 0.75rem 0;
            line-height: 1.4;
        }

        .post-content {
            color: #475569;
            line-height: 1.7;
            margin-bottom: 1rem;
            white-space: pre-wrap;
        }

        .post-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .post-actions {
            display: flex;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        .post-action-btn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: transparent;
            border: none;
            border-radius: 6px;
            color: #64748b;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .post-action-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .post-form .form-group {
            margin-bottom: 1.25rem;
        }

        .post-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1e293b;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .empty-posts {
            text-align: center;
            padding: 4rem 2rem;
        }

        .empty-posts-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-posts h3 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .empty-posts p {
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Upload Button Styles */
        .upload-btn:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .post-document {
            transition: all 0.3s ease;
        }

        .post-document:hover {
            background: #e2e8f0 !important;
        }
    </style>
    <script>
        // Post Management Functions
        let allPosts = [];

        function openCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'flex';
            document.getElementById('createPostForm').reset();
            document.getElementById('postError').style.display = 'none';
            document.getElementById('charCount').textContent = '0';
        }

        function closeCreatePostModal() {
            document.getElementById('createPostModal').style.display = 'none';
            document.getElementById('createPostForm').reset();
            
            // Reset file uploads
            uploadedImagePath = null;
            uploadedDocumentPath = null;
            uploadedDocumentName = null;
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('documentPreview').style.display = 'none';
            document.getElementById('imageUpload').value = '';
            document.getElementById('documentUpload').value = '';
        }

        // Character counter
        document.getElementById('postContent')?.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Global variables for file uploads
        let uploadedImagePath = null;
        let uploadedDocumentPath = null;
        let uploadedDocumentName = null;

        // Image upload handler
        document.getElementById('imageUpload')?.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const progressDiv = document.getElementById('uploadProgress');
            const errorDiv = document.getElementById('postError');
            progressDiv.style.display = 'block';
            errorDiv.style.display = 'none';

            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('../api/college/uploadPostImage.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    uploadedImagePath = data.file_path;
                    document.getElementById('previewImg').src = data.file_path;
                    document.getElementById('imagePreview').style.display = 'block';
                    progressDiv.style.display = 'none';
                } else {
                    throw new Error(data.error);
                }
            } catch (error) {
                errorDiv.textContent = '❌ Image upload failed: ' + error.message;
                errorDiv.style.display = 'block';
                progressDiv.style.display = 'none';
                e.target.value = '';
            }
        });

        // Document upload handler
        document.getElementById('documentUpload')?.addEventListener('change', async function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const progressDiv = document.getElementById('uploadProgress');
            const errorDiv = document.getElementById('postError');
            progressDiv.style.display = 'block';
            errorDiv.style.display = 'none';

            const formData = new FormData();
            formData.append('document', file);

            try {
                const response = await fetch('../api/college/uploadPostDocument.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    uploadedDocumentPath = data.file_path;
                    uploadedDocumentName = data.original_name;
                    document.getElementById('docName').textContent = data.original_name;
                    document.getElementById('documentPreview').style.display = 'block';
                    progressDiv.style.display = 'none';
                } else {
                    throw new Error(data.error);
                }
            } catch (error) {
                errorDiv.textContent = '❌ Document upload failed: ' + error.message;
                errorDiv.style.display = 'block';
                progressDiv.style.display = 'none';
                e.target.value = '';
            }
        });

        // Remove image
        function removeImage() {
            uploadedImagePath = null;
            document.getElementById('imagePreview').style.display = 'none';
            document.getElementById('imageUpload').value = '';
        }

        // Remove document
        function removeDocument() {
            uploadedDocumentPath = null;
            uploadedDocumentName = null;
            document.getElementById('documentPreview').style.display = 'none';
            document.getElementById('documentUpload').value = '';
        }

        // Handle post submission
        document.getElementById('createPostForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const category = document.getElementById('postCategory').value;
            const title = document.getElementById('postTitle').value;
            const content = document.getElementById('postContent').value;
            const errorDiv = document.getElementById('postError');
            const submitBtn = document.getElementById('submitPostBtn');
            
            errorDiv.style.display = 'none';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Publishing...</span>';
            
            try {
                const response = await fetch('../api/college/createPost.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        category,
                        title,
                        content,
                        image_url: uploadedImagePath || null,
                        document_url: uploadedDocumentPath || null,
                        document_name: uploadedDocumentName || null
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    closeCreatePostModal();
                    loadPosts();
                    showNotification('✓ Post published successfully!', 'success');
                    
                    // Reset file uploads
                    uploadedImagePath = null;
                    uploadedDocumentPath = null;
                    uploadedDocumentName = null;
                } else {
                    errorDiv.textContent = '❌ ' + (data.error || 'Failed to create post');
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                console.error('Error creating post:', error);
                errorDiv.textContent = '❌ An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span>📤 Publish Post</span>';
            }
        });

        // Load posts
        async function loadPosts() {
            try {
                const response = await fetch('../api/college/getPosts.php');
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

            container.innerHTML = posts.map(post => {
                const postDate = new Date(post.created_at);
                const timeAgo = getTimeAgo(postDate);
                const categoryClass = 'category-' + post.category;
                const categoryLabels = {
                    'research': '🔬 Research & Innovation',
                    'achievement': '🏆 Achievements',
                    'event': '📅 Campus Event',
                    'placement': '💼 Placements',
                    'campus-life': '🎓 Campus Life',
                    'announcement': '📢 Announcement',
                    'facilities': '🏫 Facilities',
                    'collaboration': '🤝 Collaboration'
                };
                
                return `
                    <div class="post-card">
                        <div class="post-header">
                            <img src="${post.college_logo || '../assets/images/profile_pics/default.svg'}" 
                                 alt="College Logo" 
                                 class="post-college-logo">
                            <div class="post-header-info">
                                <h4>${escapeHtml(post.college_name)}</h4>
                                <div class="post-meta">
                                    <span class="post-category-badge ${categoryClass}">
                                        ${categoryLabels[post.category] || post.category}
                                    </span>
                                    <span>•</span>
                                    <span>${timeAgo}</span>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="post-title">${escapeHtml(post.title)}</h3>
                        <p class="post-content">${escapeHtml(post.content)}</p>
                        
                        ${post.image_url ? `<img src="${escapeHtml(post.image_url)}" alt="Post image" class="post-image" onerror="this.style.display='none'">` : ''}
                        
                        ${post.document_url ? `
                            <div class="post-document" style="padding: 1rem; background: #f1f5f9; border-radius: 8px; margin: 1rem 0; display: flex; align-items: center; gap: 1rem;">
                                <span style="font-size: 2rem;">📄</span>
                                <div style="flex: 1;">
                                    <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.25rem;">${escapeHtml(post.document_name || 'Document')}</div>
                                    <div style="font-size: 0.85rem; color: #64748b;">Click to download</div>
                                </div>
                                <a href="${escapeHtml(post.document_url)}" download class="btn btn-primary" style="padding: 0.5rem 1rem; text-decoration: none;">
                                    ⬇️ Download
                                </a>
                            </div>
                        ` : ''}
                        
                        <div class="post-actions">
                            <button class="post-action-btn" onclick="deletePost(${post.id})">
                                🗑️ Delete
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function showEmptyPosts() {
            document.getElementById('posts-feed').innerHTML = `
                <div class="empty-posts">
                    <div class="empty-posts-icon">📝</div>
                    <h3>No Posts Yet</h3>
                    <p>Start sharing your college's achievements, research, and campus updates!</p>
                    <button class="btn btn-primary" onclick="openCreatePostModal()">
                        ✏️ Create Your First Post
                    </button>
                </div>
            `;
        }

        async function deletePost(postId) {
            if (!confirm('Are you sure you want to delete this post?')) return;
            
            try {
                const response = await fetch('../api/college/deletePost.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ post_id: postId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    loadPosts();
                    showNotification('✓ Post deleted successfully', 'success');
                } else {
                    alert('❌ Failed to delete post: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ An error occurred while deleting the post');
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

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 500;
                z-index: 10000;
                animation: slideIn 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            
            notification.style.background = type === 'success' 
                ? 'linear-gradient(135deg, #059669 0%, #047857 100%)'
                : 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Load statistics for overview
        async function loadStatistics() {
            try {
                const response = await fetch('../api/college/getStatistics.php', {
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('events-count').textContent = data.total_events || 0;
                    document.getElementById('active-events-count').textContent = data.active_events || 0;
                    document.getElementById('applications-count').textContent = data.total_applications || 0;
                    document.getElementById('pending-applications-count').textContent = data.pending_applications || 0;
                } else {
                    document.getElementById('events-count').textContent = '0';
                    document.getElementById('active-events-count').textContent = '0';
                    document.getElementById('applications-count').textContent = '0';
                    document.getElementById('pending-applications-count').textContent = '0';
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
                document.getElementById('events-count').textContent = 'Error';
                document.getElementById('active-events-count').textContent = 'Error';
                document.getElementById('applications-count').textContent = 'Error';
                document.getElementById('pending-applications-count').textContent = 'Error';
            }
        }

        // Load posts on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPosts();
            loadStatistics();
            
            // Section Navigation Handler
            const navLinks = document.querySelectorAll('.sidebar nav a');
            const sections = document.querySelectorAll('.dashboard-section');
            
            function showSection(sectionId) {
                // Hide all sections
                sections.forEach(section => {
                    section.classList.remove('active');
                    section.style.display = 'none';
                });
                
                // Show target section
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.add('active');
                    targetSection.style.display = 'block';
                }
                
                // Update active nav link
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href') === '#' + sectionId) {
                        link.classList.add('active');
                    }
                });
                
                // Update URL hash without scrolling
                history.pushState(null, null, '#' + sectionId);
            }
            
            // Add click handlers to nav links
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const sectionId = this.getAttribute('href').substring(1);
                    showSection(sectionId);
                    
                    // Reload statistics when overview section is shown
                    if (sectionId === 'overview') {
                        loadStatistics();
                    }
                    
                    // Load events when events section is shown
                    if (sectionId === 'events') {
                        loadEvents();
                    }
                    
                    // Load profile when profile section is shown
                    if (sectionId === 'profile') {
                        loadProfileData();
                    }
                    
                    // Load applications when applicants section is shown
                    if (sectionId === 'applicants') {
                        loadApplications();
                    }
                });
            });
            
            // Handle direct URL hash navigation
            const hash = window.location.hash.substring(1);
            if (hash) {
                showSection(hash);
                if (hash === 'profile') {
                    loadProfileData();
                }
            } else {
                showSection('overview');
            }
            
            // Handle browser back/forward buttons
            window.addEventListener('hashchange', function() {
                const newHash = window.location.hash.substring(1) || 'overview';
                showSection(newHash);
            });
            
            // Load profile data function
            async function loadProfileData() {
                try {
                    const response = await fetch('../api/college/getProfile.php', {
                        credentials: 'same-origin'
                    });
                    
                    const data = await response.json();
                    
                    if (data.success && data.profile) {
                        const profile = data.profile;
                        
                        // Populate form fields
                        document.getElementById('profile-name').value = profile.name || '';
                        document.getElementById('profile-location').value = profile.location || '';
                        document.getElementById('profile-website').value = profile.website || '';
                        document.getElementById('profile-email').value = profile.contact_email || '';
                        document.getElementById('profile-logo').value = profile.logo || '';
                        document.getElementById('profile-description').value = profile.description || '';
                    }
                } catch (error) {
                    console.error('Error loading profile:', error);
                }
            }
            
            // Profile form submission
            const profileForm = document.getElementById('profile-form');
            if (profileForm) {
                profileForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const submitBtn = document.getElementById('update-profile-btn');
                    const messageDiv = document.getElementById('profile-message');
                    
                    submitBtn.disabled = true;
                    submitBtn.textContent = '💾 Saving...';
                    
                    const profileData = {
                        name: document.getElementById('profile-name').value.trim(),
                        location: document.getElementById('profile-location').value.trim(),
                        website: document.getElementById('profile-website').value.trim(),
                        contact_email: document.getElementById('profile-email').value.trim(),
                        logo: document.getElementById('profile-logo').value.trim(),
                        description: document.getElementById('profile-description').value.trim()
                    };
                    
                    try {
                        const response = await fetch('../api/college/updateProfile.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(profileData)
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            messageDiv.style.display = 'block';
                            messageDiv.style.background = '#d1fae5';
                            messageDiv.style.color = '#065f46';
                            messageDiv.textContent = '✅ Profile updated successfully! Refreshing...';
                            
                            // Reload page after 1.5 seconds to update sidebar
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            throw new Error(data.error || 'Failed to update profile');
                        }
                    } catch (error) {
                        messageDiv.style.display = 'block';
                        messageDiv.style.background = '#fee2e2';
                        messageDiv.style.color = '#dc2626';
                        messageDiv.textContent = '❌ ' + error.message;
                        
                        submitBtn.disabled = false;
                        submitBtn.textContent = '💾 Save Profile';
                    }
                });
            }
            
            // Password change form submission
            const passwordForm = document.getElementById('change-password-form');
            if (passwordForm) {
                passwordForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const messageDiv = document.getElementById('password-message');
                    const currentPassword = document.getElementById('current-password').value;
                    const newPassword = document.getElementById('new-password').value;
                    const confirmPassword = document.getElementById('confirm-password').value;
                    
                    // Validate passwords match
                    if (newPassword !== confirmPassword) {
                        messageDiv.style.display = 'block';
                        messageDiv.style.background = '#fee2e2';
                        messageDiv.style.color = '#dc2626';
                        messageDiv.textContent = '❌ New passwords do not match';
                        return;
                    }
                    
                    // Validate password length
                    if (newPassword.length < 8) {
                        messageDiv.style.display = 'block';
                        messageDiv.style.background = '#fee2e2';
                        messageDiv.style.color = '#dc2626';
                        messageDiv.textContent = '❌ Password must be at least 8 characters';
                        return;
                    }
                    
                    messageDiv.style.display = 'block';
                    messageDiv.style.background = '#fef3c7';
                    messageDiv.style.color = '#92400e';
                    messageDiv.textContent = '🔄 Updating password...';
                    
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
                            messageDiv.style.background = '#d1fae5';
                            messageDiv.style.color = '#065f46';
                            messageDiv.textContent = '✅ Password updated successfully!';
                            passwordForm.reset();
                        } else {
                            throw new Error(data.error || 'Failed to update password');
                        }
                    } catch (error) {
                        messageDiv.style.background = '#fee2e2';
                        messageDiv.style.color = '#dc2626';
                        messageDiv.textContent = '❌ ' + error.message;
                    }
                });
            }
            
            // Event Management
            const eventForm = document.getElementById('create-event-form');
            if (eventForm) {
                eventForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const submitBtn = eventForm.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Creating...';
                    
                    const eventData = {
                        title: document.getElementById('event-title').value.trim(),
                        description: document.getElementById('event-description').value.trim(),
                        date: document.getElementById('event-date').value,
                        type: document.getElementById('event-type').value
                    };
                    
                    try {
                        const response = await fetch('../api/college/createEvent.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(eventData)
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            showNotification('✅ Event created successfully!', 'success');
                            eventForm.reset();
                            loadEvents();
                        } else {
                            showNotification('❌ ' + (data.error || 'Failed to create event'), 'error');
                        }
                    } catch (error) {
                        console.error('Error creating event:', error);
                        showNotification('❌ An error occurred. Please try again.', 'error');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }
                });
            }
            
            // Load events
            async function loadEvents() {
                try {
                    const response = await fetch('../api/college/getEvents.php');
                    const data = await response.json();
                    
                    if (data.events && data.events.length > 0) {
                        displayEvents(data.events);
                    } else {
                        document.getElementById('events-list').innerHTML = `
                            <div style="text-align: center; padding: 3rem; color: #64748b;">
                                <div style="font-size: 3rem; margin-bottom: 1rem;">📅</div>
                                <p>No events yet. Create your first event above!</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error loading events:', error);
                }
            }
            
            // Display events
            function displayEvents(events) {
                const container = document.getElementById('events-list');
                container.innerHTML = events.map(event => {
                    const eventDate = new Date(event.date);
                    const formattedDate = eventDate.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                    
                    const typeIcons = {
                        'hackathon': '💻',
                        'symposium': '🎓',
                        'project-expo': '🔬',
                        'workshop': '🛠️',
                        'other': '📋'
                    };
                    
                    const typeColors = {
                        'hackathon': '#3b82f6',
                        'symposium': '#8b5cf6',
                        'project-expo': '#10b981',
                        'workshop': '#f59e0b',
                        'other': '#6b7280'
                    };
                    
                    return `
                        <div class="event-card" style="background: white; border-radius: 12px; padding: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 1rem; border-left: 4px solid ${typeColors[event.type] || typeColors['other']};">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <span style="font-size: 1.5rem;">${typeIcons[event.type] || typeIcons['other']}</span>
                                        <span style="background: ${typeColors[event.type] || typeColors['other']}20; color: ${typeColors[event.type] || typeColors['other']}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                            ${event.type}
                                        </span>
                                        <span style="background: ${event.status === 'active' ? '#10b98120' : '#6b728020'}; color: ${event.status === 'active' ? '#10b981' : '#6b7280'}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                            ${event.status}
                                        </span>
                                    </div>
                                    <h3 style="margin: 0 0 0.5rem 0; color: #1e293b; font-size: 1.25rem;">${escapeHtml(event.title)}</h3>
                                    <p style="color: #64748b; margin: 0 0 0.75rem 0; line-height: 1.6;">${escapeHtml(event.description)}</p>
                                    <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; font-size: 0.9rem; color: #64748b;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                            <span>📅</span>
                                            <span>${formattedDate}</span>
                                        </div>
                                        ${event.location ? `
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span>📍</span>
                                                <span>${escapeHtml(event.location)}</span>
                                            </div>
                                        ` : ''}
                                        ${event.max_participants ? `
                                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                                <span>👥</span>
                                                <span>Max: ${event.max_participants}</span>
                                            </div>
                                        ` : ''}
                                    </div>
                                </div>
                                <button onclick="deleteEvent(${event.id})" style="padding: 0.5rem 1rem; background: #fee2e2; color: #dc2626; border: none; border-radius: 6px; cursor: pointer; font-size: 0.9rem; transition: all 0.3s;">
                                    🗑️ Delete
                                </button>
                            </div>
                        </div>
                    `;
                }).join('');
            }
            
            // Delete event
            window.deleteEvent = async function(eventId) {
                if (!confirm('Are you sure you want to delete this event?')) return;
                
                try {
                    const response = await fetch('../api/college/deleteEvent.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ event_id: eventId })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification('✅ Event deleted successfully!', 'success');
                        loadEvents();
                    } else {
                        showNotification('❌ ' + (data.error || 'Failed to delete event'), 'error');
                    }
                } catch (error) {
                    console.error('Error deleting event:', error);
                    showNotification('❌ An error occurred. Please try again.', 'error');
                }
            };
            
            // Load events when events section is shown
            if (window.location.hash === '#events') {
                loadEvents();
            }
            
            // Applications Management
            let allApplications = [];
            let collegeEvents = [];
            
            async function loadApplications() {
                try {
                    const response = await fetch('../api/college/getApplications.php', {
                        credentials: 'same-origin'
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        allApplications = data.applications || [];
                        collegeEvents = data.events || [];
                        
                        // Populate event filter dropdown
                        const eventFilter = document.getElementById('filter-event');
                        eventFilter.innerHTML = '<option value="all">All Events</option>';
                        collegeEvents.forEach(event => {
                            eventFilter.innerHTML += `<option value="${event.id}">${event.title}</option>`;
                        });
                        
                        displayApplications(allApplications);
                    } else {
                        document.getElementById('applicants-list').innerHTML = `
                            <div style="text-align: center; padding: 3rem; color: #dc2626;">
                                <div style="font-size: 2rem;">❌</div>
                                <p>${data.error || 'Failed to load applications'}</p>
                            </div>
                        `;
                    }
                } catch (error) {
                    console.error('Error loading applications:', error);
                    document.getElementById('applicants-list').innerHTML = `
                        <div style="text-align: center; padding: 3rem; color: #dc2626;">
                            <div style="font-size: 2rem;">❌</div>
                            <p>Error loading applications. Please try again.</p>
                        </div>
                    `;
                }
            }
            
            function displayApplications(applications) {
                const container = document.getElementById('applicants-list');
                
                if (!applications || applications.length === 0) {
                    container.innerHTML = `
                        <div style="text-align: center; padding: 3rem; color: #64748b;">
                            <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                            <p>No applications yet. When students register for your events, they will appear here.</p>
                        </div>
                    `;
                    return;
                }
                
                const statusColors = {
                    'pending': { bg: '#fef3c7', color: '#92400e', icon: '⏳' },
                    'approved': { bg: '#d1fae5', color: '#065f46', icon: '✅' },
                    'rejected': { bg: '#fee2e2', color: '#dc2626', icon: '❌' }
                };
                
                container.innerHTML = applications.map(app => {
                    const status = statusColors[app.status] || statusColors['pending'];
                    const appliedDate = new Date(app.applied_at).toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                    
                    return `
                        <div class="application-card" style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;">
                            <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.5rem;">
                                        <h3 style="margin: 0; color: #1e293b; font-size: 1.1rem;">${escapeHtml(app.full_name)}</h3>
                                        <span style="background: ${status.bg}; color: ${status.color}; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600;">
                                            ${status.icon} ${app.status.toUpperCase()}
                                        </span>
                                    </div>
                                    <div style="color: #64748b; font-size: 0.9rem; margin-bottom: 0.75rem;">
                                        <strong>Event:</strong> ${escapeHtml(app.event_title)} | Applied: ${appliedDate}
                                    </div>
                                </div>
                                ${app.status === 'pending' ? `
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button onclick="updateApplicationStatus(${app.id}, 'approved')" style="padding: 0.5rem 1rem; background: #10b981; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                            ✅ Approve
                                        </button>
                                        <button onclick="updateApplicationStatus(${app.id}, 'rejected')" style="padding: 0.5rem 1rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem;">
                                            ❌ Reject
                                        </button>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px; margin-bottom: 1rem;">
                                <div>
                                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Email</div>
                                    <div style="color: #1e293b; font-size: 0.9rem;">${escapeHtml(app.email)}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Phone</div>
                                    <div style="color: #1e293b; font-size: 0.9rem;">${escapeHtml(app.phone)}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">College</div>
                                    <div style="color: #1e293b; font-size: 0.9rem;">${escapeHtml(app.college)}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Year</div>
                                    <div style="color: #1e293b; font-size: 0.9rem;">${escapeHtml(app.year_of_study)}</div>
                                </div>
                                <div>
                                    <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Branch</div>
                                    <div style="color: #1e293b; font-size: 0.9rem;">${escapeHtml(app.branch)}</div>
                                </div>
                                ${app.team_name ? `
                                    <div>
                                        <div style="font-size: 0.75rem; color: #64748b; text-transform: uppercase; margin-bottom: 0.25rem;">Team</div>
                                        <div style="color: #1e293b; font-size: 0.9rem;">${escapeHtml(app.team_name)} (${app.team_size} members)</div>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <details style="margin-top: 1rem;">
                                <summary style="cursor: pointer; color: #7c3aed; font-weight: 600; font-size: 0.9rem; padding: 0.5rem 0;">
                                    View Full Application
                                </summary>
                                <div style="margin-top: 1rem; padding: 1rem; background: #f8fafc; border-radius: 8px;">
                                    <div style="margin-bottom: 1rem;">
                                        <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">Motivation:</div>
                                        <div style="color: #64748b; line-height: 1.6;">${escapeHtml(app.motivation)}</div>
                                    </div>
                                    ${app.experience ? `
                                        <div style="margin-bottom: 1rem;">
                                            <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">Experience:</div>
                                            <div style="color: #64748b; line-height: 1.6;">${escapeHtml(app.experience)}</div>
                                        </div>
                                    ` : ''}
                                    ${app.skills ? `
                                        <div style="margin-bottom: 1rem;">
                                            <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.5rem;">Skills:</div>
                                            <div style="color: #64748b;">${escapeHtml(app.skills)}</div>
                                        </div>
                                    ` : ''}
                                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                                        ${app.github ? `
                                            <a href="${escapeHtml(app.github)}" target="_blank" style="color: #7c3aed; text-decoration: none; font-size: 0.9rem;">
                                                🔗 GitHub Profile
                                            </a>
                                        ` : ''}
                                        ${app.linkedin ? `
                                            <a href="${escapeHtml(app.linkedin)}" target="_blank" style="color: #7c3aed; text-decoration: none; font-size: 0.9rem;">
                                                🔗 LinkedIn Profile
                                            </a>
                                        ` : ''}
                                    </div>
                                </div>
                            </details>
                        </div>
                    `;
                }).join('');
            }
            
            // Filter applications
            document.getElementById('filter-event')?.addEventListener('change', filterApplications);
            document.getElementById('filter-status')?.addEventListener('change', filterApplications);
            
            function filterApplications() {
                const eventFilter = document.getElementById('filter-event').value;
                const statusFilter = document.getElementById('filter-status').value;
                
                let filtered = allApplications;
                
                if (eventFilter !== 'all') {
                    filtered = filtered.filter(app => app.event_id == eventFilter);
                }
                
                if (statusFilter !== 'all') {
                    filtered = filtered.filter(app => app.status === statusFilter);
                }
                
                displayApplications(filtered);
            }
            
            // Update application status
            window.updateApplicationStatus = async function(applicationId, newStatus) {
                if (!confirm(`Are you sure you want to ${newStatus} this application?`)) return;
                
                try {
                    const response = await fetch('../api/college/updateApplicationStatus.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            application_id: applicationId, 
                            status: newStatus 
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showNotification(`✅ Application ${newStatus} successfully!`, 'success');
                        loadApplications();
                    } else {
                        showNotification('❌ ' + (data.error || 'Failed to update status'), 'error');
                    }
                } catch (error) {
                    console.error('Error updating application:', error);
                    showNotification('❌ An error occurred. Please try again.', 'error');
                }
            };
        });
    </script>
</body>
</html>
