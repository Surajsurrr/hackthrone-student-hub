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
                        <div class="form-group">
                            <label for="postImage">Image URL (Optional)</label>
                            <input 
                                type="url" 
                                id="postImage" 
                                placeholder="https://example.com/image.jpg"
                                style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 1rem;"
                            >
                            <small style="color: #64748b; font-size: 0.85rem;">Add an image URL to make your post more engaging</small>
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
    <style>
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
        }

        // Character counter
        document.getElementById('postContent')?.addEventListener('input', function() {
            document.getElementById('charCount').textContent = this.value.length;
        });

        // Handle post submission
        document.getElementById('createPostForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const category = document.getElementById('postCategory').value;
            const title = document.getElementById('postTitle').value;
            const content = document.getElementById('postContent').value;
            const image = document.getElementById('postImage').value;
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
                        image_url: image || null
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    closeCreatePostModal();
                    loadPosts();
                    showNotification('✓ Post published successfully!', 'success');
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
                        
                        ${post.image_url ? `<img src="${escapeHtml(post.image_url)}" alt="Post image" class="post-image">` : ''}
                        
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

        // Load posts on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadPosts();
        });
    </script>
</body>
</html>
