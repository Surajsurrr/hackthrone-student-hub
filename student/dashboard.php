<?php
define('DEBUG', true);
require_once 'includes/student_auth.php';
$user = getCurrentUser();
$student = getStudentProfile($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <!-- Modern Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-profile">
                <div class="sidebar-avatar">
                    <img src="<?php echo htmlspecialchars(!empty($student['profile_pic']) ? $student['profile_pic'] : '../assets/images/profile_pics/default.svg'); ?>" alt="Profile">
                </div>
                <div class="sidebar-name">
                    <a href="profile.php"><?php echo htmlspecialchars($student['name'] ?? $user['username']); ?></a>
                    <div class="muted small"><?php echo htmlspecialchars($student['college'] ?? ''); ?></div>
                </div>
            </div>
            <nav>
                <ul>
                    <li><a href="#overview" class="nav-link active" data-section="overview"> Overview</a></li>
                    <li><a href="#profile" class="nav-link" data-section="profile"> Profile</a></li>
                    <li><a href="#opportunities" class="nav-link" data-section="opportunities"> Opportunities</a></li>
                    <li><a href="#applications" class="nav-link" data-section="applications"> Applications</a></li>
                    <li><a href="#events" class="nav-link" data-section="events"> Events</a></li>
                    <li><a href="#endorsements" class="nav-link" data-section="endorsements"> Endorsements</a></li>
                    <li><a href="#notes" class="nav-link" data-section="notes"> Notes</a></li>
                    <li><a href="#upload-notes" class="nav-link" data-section="upload-notes"> Upload</a></li>
                    <li><a href="#ai-coach" class="nav-link" data-section="ai-coach"> AI Coach</a></li>
                    <li><a href="#achievements" class="nav-link" data-section="achievements"> Achievements</a></li>
                    <li><a href="#calendar" class="nav-link" data-section="calendar"> Calendar</a></li>
                    <li><a href="#messages" class="nav-link" data-section="messages"> Messages</a></li>
                    <li><a href="#settings" class="nav-link" data-section="settings"> Settings</a></li>
                    <li><a href="#help" class="nav-link" data-section="help"> Help</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <!-- Overview Section -->
            <section id="overview" class="dashboard-section active">
                <div class="welcome-section">
                    <h2>Welcome back, <?php echo htmlspecialchars($user['username']); ?>! 👋</h2>
                    <p>Here's what's happening with your career journey today</p>
                </div>

                <div class="stats-grid">
                    <div class="stat-card">
                        <h3>Opportunities Applied</h3>
                        <p id="opportunities-count">12</p>
                    </div>
                    <div class="stat-card">
                        <h3>Notes Shared</h3>
                        <p id="notes-count">8</p>
                    </div>
                    <div class="stat-card">
                        <h3>AI Sessions</h3>
                        <p id="ai-sessions-count">15</p>
                    </div>
                    <div class="stat-card">
                        <h3>Profile Score</h3>
                        <p>85%</p>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="actions-grid">
                        <div class="action-card">
                            <h4>Find Internships</h4>
                            <p>Discover new internship opportunities</p>
                            <a href="#opportunities" class="btn nav-trigger" data-section="opportunities">Explore</a>
                        </div>
                        <div class="action-card">
                            <h4>Join Hackathons</h4>
                            <p>Participate in coding competitions</p>
                            <a href="hackathons.php" class="btn">Browse</a>
                        </div>
                        <div class="action-card">
                            <h4>Share Notes</h4>
                            <p>Upload and share study materials</p>
                            <a href="#notes" class="btn nav-trigger" data-section="notes">Upload</a>
                        </div>
                        <div class="action-card">
                            <h4>AI Mentoring</h4>
                            <p>Get personalized career guidance</p>
                            <a href="#ai-coach" class="btn nav-trigger" data-section="ai-coach">Start Chat</a>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Profile Section -->
            <section id="profile" class="dashboard-section">
                <h2>My Profile</h2>
                <div class="profile-section">
                    <div class="profile-pic-section">
                        <div class="profile-pic">
                            <img src="../assets/images/profile_pics/default.svg" alt="Profile Picture" id="profile-image">
                        </div>
                        <button class="btn" onclick="document.getElementById('profile-pic-input').click()">Change Photo</button>
                        <input type="file" id="profile-pic-input" accept="image/*" style="display: none;">
                    </div>
                    <div class="profile-form-section">
                        <form id="profile-form" class="content-card">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" placeholder="Enter your full name">
                            </div>
                            <div class="form-group">
                                <label for="college">College</label>
                                <input type="text" id="college" value="<?php echo htmlspecialchars($student['college'] ?? ''); ?>" placeholder="Your college name">
                            </div>
                            <div class="form-group">
                                <label for="year">Year of Study</label>
                                <select id="year">
                                    <option value="">Select Year</option>
                                    <option value="1" <?php echo ($student['year'] ?? '') == '1' ? 'selected' : ''; ?>>1st Year</option>
                                    <option value="2" <?php echo ($student['year'] ?? '') == '2' ? 'selected' : ''; ?>>2nd Year</option>
                                    <option value="3" <?php echo ($student['year'] ?? '') == '3' ? 'selected' : ''; ?>>3rd Year</option>
                                    <option value="4" <?php echo ($student['year'] ?? '') == '4' ? 'selected' : ''; ?>>4th Year</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="branch">Branch</label>
                                <input type="text" id="branch" value="<?php echo htmlspecialchars($student['branch'] ?? ''); ?>" placeholder="e.g., Computer Science">
                            </div>
                            <div class="form-group">
                                <label for="skills">Skills</label>
                                <textarea id="skills" placeholder="List your skills separated by commas"><?php echo htmlspecialchars($student['skills'] ?? ''); ?></textarea>
                            </div>
                            <div class="form-group">
                                <label for="bio">Bio</label>
                                <textarea id="bio" placeholder="Tell us about yourself"><?php echo htmlspecialchars($student['bio'] ?? ''); ?></textarea>
                            </div>
                            <button type="submit" class="btn">Update Profile</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Opportunities Section -->
            <section id="opportunities" class="dashboard-section">
                <h2>Career Opportunities</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Award-Winning Hackathons</h3>
                        <p>Participate in coding competitions and showcase your skills</p>
                        <div id="hackathons-preview">Loading latest hackathons...</div>
                        <a href="hackathons.php" class="btn">View All Hackathons</a>
                    </div>
                    <div class="content-card">
                        <h3>Career Internships</h3>
                        <p>Find internships that match your skills and interests</p>
                        <div id="internships-preview">Loading latest internships...</div>
                        <a href="internships.php" class="btn">View All Internships</a>
                    </div>
                    <div class="content-card">
                        <h3>Targeted Job Matches</h3>
                        <p>AI-powered job suggestions based on your profile</p>
                        <div id="job-recommendations">Loading recommendations...</div>
                        <button class="btn" onclick="generateRecommendations()">Get New Recommendations</button>
                    </div>
                </div>
            </section>

            <!-- Notes Section -->
            <section id="notes" class="dashboard-section">
                <div class="section-header">
                    <h2>📚 Study Notes Hub</h2>
                    <p>Share your knowledge and discover amazing study resources</p>
                </div>
                
                <div class="notes-content-grid">
                    <!-- Modern Upload Card -->
                    <div class="upload-hero-card">
                        <div class="upload-hero-header">
                            <div class="upload-icon-circle">
                                <span class="upload-icon">🚀</span>
                            </div>
                            <div class="upload-hero-text">
                                <h3>Share Your Knowledge</h3>
                                <p>Upload study materials and help your peers excel in their academic journey</p>
                            </div>
                        </div>
                        
                        <form id="upload-form" enctype="multipart/form-data" class="modern-upload-form">
                            <div class="upload-form-grid">
                                <div class="form-floating">
                                    <input type="text" id="note-title" placeholder="e.g., Advanced Data Structures Notes" required>
                                    <label for="note-title">📝 Note Title</label>
                                    <div class="form-glow"></div>
                                </div>
                                
                                <div class="form-floating">
                                    <select id="note-subject" required>
                                        <option value="">Choose Subject</option>
                                        <option value="Computer Science">💻 Computer Science</option>
                                        <option value="Mathematics">📐 Mathematics</option>
                                        <option value="Physics">⚛️ Physics</option>
                                        <option value="Chemistry">🧪 Chemistry</option>
                                        <option value="Biology">🧬 Biology</option>
                                        <option value="Engineering">⚙️ Engineering</option>
                                        <option value="Business">💼 Business</option>
                                        <option value="Other">📚 Other</option>
                                    </select>
                                    <label for="note-subject">🎯 Subject Category</label>
                                    <div class="form-glow"></div>
                                </div>
                            </div>
                            
                            <div class="form-floating full-width">
                                <textarea id="note-description" placeholder="Describe what topics are covered, difficulty level, and any special insights..." rows="3"></textarea>
                                <label for="note-description">📋 Description & Details</label>
                                <div class="form-glow"></div>
                            </div>
                            
                            <!-- Enhanced File Upload Zone -->
                            <div class="file-drop-zone" id="file-drop-zone">
                                <input type="file" id="note-file" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx" required hidden>
                                <div class="drop-zone-content">
                                    <div class="drop-zone-icon">
                                        <span class="file-icon">📎</span>
                                        <div class="upload-animation"></div>
                                    </div>
                                    <div class="drop-zone-text">
                                        <h4>Drop your files here</h4>
                                        <p>or <span class="browse-link">click to browse</span></p>
                                        <small>Supports: PDF, DOC, DOCX, TXT, PPT • Max 10MB</small>
                                    </div>
                                </div>
                                <div class="file-preview" id="file-preview" style="display: none;">
                                    <div class="file-info">
                                        <span class="file-name"></span>
                                        <span class="file-size"></span>
                                    </div>
                                    <button type="button" class="remove-file" onclick="removeFile()">✕</button>
                                </div>
                            </div>
                            
                            <button type="submit" class="upload-submit-btn">
                                <span class="btn-content">
                                    <span class="btn-icon">🚀</span>
                                    <span class="btn-text">Share with Community</span>
                                </span>
                                <div class="btn-shine"></div>
                            </button>
                        </form>
                        
                        <!-- Upload Progress -->
                        <div class="upload-progress" id="upload-progress" style="display: none;">
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                            <span class="progress-text">Uploading... 0%</span>
                        </div>
                    </div>
                    
                    <!-- My Notes Section -->
                    <div class="notes-display-card">
                        <div class="card-header">
                            <h3>📖 My Study Materials</h3>
                            <div class="view-toggle">
                                <button class="toggle-btn active" data-view="grid">⊞</button>
                                <button class="toggle-btn" data-view="list">☰</button>
                            </div>
                        </div>
                        <div id="my-notes-list" class="notes-container">
                            <div class="loading-state">
                                <div class="loading-spinner"></div>
                                <p>Loading your notes...</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured Notes Section -->
                    <div class="notes-display-card featured-notes">
                        <div class="card-header">
                            <h3>⭐ Community Favorites</h3>
                            <a href="notes.php" class="view-all-link">View All →</a>
                        </div>
                        <div id="popular-notes-list" class="notes-container">
                            <div class="loading-state">
                                <div class="loading-spinner"></div>
                                <p>Loading popular notes...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- AI Coach Section -->
            <section id="ai-coach" class="dashboard-section">
                <h2>AI Career Coach</h2>
                <div class="ai-chat-container">
                    <div class="chat-messages" id="chat-messages">
                        <div class="message bot-message">
                            <strong>AI Coach:</strong> Hello! I'm your personal career coach. I can help you with career guidance, interview preparation, skill development, and much more. What would you like to discuss today?
                        </div>
                    </div>
                    <div class="chat-input-container">
                        <textarea id="chat-input" class="chat-input" placeholder="Ask me anything about your career, skills, interviews, or job search..." rows="3"></textarea>
                        <button id="send-btn" class="btn">Send</button>
                    </div>
                </div>
                <div class="ai-suggestions">
                    <h4>Quick Questions:</h4>
                    <div class="suggestion-buttons">
                        <button class="suggestion-btn" onclick="sendSuggestion('How can I improve my resume?')">Resume Tips</button>
                        <button class="suggestion-btn" onclick="sendSuggestion('What skills should I learn for my field?')">Skill Development</button>
                        <button class="suggestion-btn" onclick="sendSuggestion('How to prepare for technical interviews?')">Interview Prep</button>
                        <button class="suggestion-btn" onclick="sendSuggestion('What career paths are suitable for me?')">Career Paths</button>
                    </div>
                </div>
            </section>

            <!-- Achievements Section -->
            <section id="achievements" class="dashboard-section">
                <!-- Achievement Hero Section -->
                <div class="achievement-hero">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h2>🏆 Your Journey to Excellence</h2>
                            <p>Level up your skills and unlock amazing achievements!</p>
                        </div>
                        <div class="hero-stats">
                            <div class="stat-circle">
                                <div class="circle-progress" data-percentage="75">
                                    <span class="percentage">75%</span>
                                </div>
                                <span class="stat-label">Overall Progress</span>
                            </div>
                        </div>
                    </div>
                    <div class="hero-background">
                        <div class="floating-icons">
                            <span class="float-icon">🌟</span>
                            <span class="float-icon">🎯</span>
                            <span class="float-icon">🚀</span>
                            <span class="float-icon">💎</span>
                            <span class="float-icon">⚡</span>
                        </div>
                    </div>
                </div>

                <!-- Level & XP Section -->
                <div class="level-section">
                    <div class="level-card">
                        <div class="level-info">
                            <div class="current-level">
                                <span class="level-badge">Level 5</span>
                                <span class="level-title">Knowledge Explorer</span>
                            </div>
                            <div class="xp-bar">
                                <div class="xp-progress" style="width: 65%"></div>
                                <div class="xp-text">
                                    <span>1,250 XP</span>
                                    <span>/ 2,000 XP</span>
                                </div>
                            </div>
                            <div class="next-level">
                                <span>🎯 750 XP to Level 6 - "Skill Master"</span>
                            </div>
                        </div>
                        <div class="level-rewards">
                            <h4>🎁 Next Level Rewards</h4>
                            <div class="reward-items">
                                <span class="reward-item">🏅 Special Badge</span>
                                <span class="reward-item">💰 500 Coins</span>
                                <span class="reward-item">🔓 New Features</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Achievements Grid -->
                <div class="achievements-grid">
                    <!-- Recently Earned -->
                    <div class="achievement-category">
                        <h3>🌟 Recently Earned</h3>
                        <div class="badges-container">
                            <div class="badge-card earned pulse">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon">📚</div>
                                    <div class="achievement-glow"></div>
                                </div>
                                <div class="badge-info">
                                    <h4>Knowledge Sharer</h4>
                                    <p>Uploaded 5 study notes</p>
                                    <span class="earned-date">Earned 2 days ago</span>
                                </div>
                                <div class="badge-points">+100 XP</div>
                            </div>

                            <div class="badge-card earned shine">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon">🤖</div>
                                    <div class="achievement-glow"></div>
                                </div>
                                <div class="badge-info">
                                    <h4>AI Chat Pro</h4>
                                    <p>Had 20+ AI conversations</p>
                                    <span class="earned-date">Earned 1 week ago</span>
                                </div>
                                <div class="badge-points">+150 XP</div>
                            </div>

                            <div class="badge-card earned">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon">🎯</div>
                                    <div class="achievement-glow"></div>
                                </div>
                                <div class="badge-info">
                                    <h4>Goal Achiever</h4>
                                    <p>Completed 3 learning goals</p>
                                    <span class="earned-date">Earned 2 weeks ago</span>
                                </div>
                                <div class="badge-points">+200 XP</div>
                            </div>
                        </div>
                    </div>

                    <!-- In Progress -->
                    <div class="achievement-category">
                        <h3>⏳ In Progress</h3>
                        <div class="badges-container">
                            <div class="badge-card in-progress">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon locked">🔒</div>
                                    <div class="progress-ring">
                                        <div class="progress-circle" style="--progress: 60"></div>
                                    </div>
                                </div>
                                <div class="badge-info">
                                    <h4>Social Connector</h4>
                                    <p>Connect with 10 students</p>
                                    <div class="progress-bar-small">
                                        <div class="progress-fill" style="width: 60%"></div>
                                    </div>
                                    <span class="progress-text">6/10 connections</span>
                                </div>
                                <div class="badge-points">+300 XP</div>
                            </div>

                            <div class="badge-card in-progress">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon locked">🔒</div>
                                    <div class="progress-ring">
                                        <div class="progress-circle" style="--progress: 40"></div>
                                    </div>
                                </div>
                                <div class="badge-info">
                                    <h4>Event Enthusiast</h4>
                                    <p>Attend 5 events</p>
                                    <div class="progress-bar-small">
                                        <div class="progress-fill" style="width: 40%"></div>
                                    </div>
                                    <span class="progress-text">2/5 events</span>
                                </div>
                                <div class="badge-points">+250 XP</div>
                            </div>

                            <div class="badge-card in-progress">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon locked">🔒</div>
                                    <div class="progress-ring">
                                        <div class="progress-circle" style="--progress: 80"></div>
                                    </div>
                                </div>
                                <div class="badge-info">
                                    <h4>Skill Builder</h4>
                                    <p>Add 10 skills to profile</p>
                                    <div class="progress-bar-small">
                                        <div class="progress-fill" style="width: 80%"></div>
                                    </div>
                                    <span class="progress-text">8/10 skills</span>
                                </div>
                                <div class="badge-points">+400 XP</div>
                            </div>
                        </div>
                    </div>

                    <!-- Locked Achievements -->
                    <div class="achievement-category">
                        <h3>🔐 Unlock Next</h3>
                        <div class="badges-container">
                            <div class="badge-card locked">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon mystery">❓</div>
                                </div>
                                <div class="badge-info">
                                    <h4>Community Leader</h4>
                                    <p>Help 25 students with notes</p>
                                    <span class="unlock-condition">🔓 Upload 10 more notes</span>
                                </div>
                                <div class="badge-points">+500 XP</div>
                            </div>

                            <div class="badge-card locked">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon mystery">❓</div>
                                </div>
                                <div class="badge-info">
                                    <h4>Tech Innovator</h4>
                                    <p>Master 5 technical skills</p>
                                    <span class="unlock-condition">🔓 Add 3 more tech skills</span>
                                </div>
                                <div class="badge-points">+750 XP</div>
                            </div>

                            <div class="badge-card locked legendary">
                                <div class="badge-icon-wrapper">
                                    <div class="badge-icon mystery">❓</div>
                                    <div class="legendary-glow"></div>
                                </div>
                                <div class="badge-info">
                                    <h4>Legend</h4>
                                    <p>Reach the pinnacle of excellence</p>
                                    <span class="unlock-condition">🔓 Reach Level 20</span>
                                </div>
                                <div class="badge-points legendary-points">+2000 XP</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Achievement Stats -->
                <div class="achievement-stats">
                    <div class="stats-grid">
                        <div class="stat-item">
                            <div class="stat-icon">🏆</div>
                            <div class="stat-number">12</div>
                            <div class="stat-label">Achievements</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">⚡</div>
                            <div class="stat-number">1,250</div>
                            <div class="stat-label">Total XP</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🔥</div>
                            <div class="stat-number">7</div>
                            <div class="stat-label">Day Streak</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">📈</div>
                            <div class="stat-number">85%</div>
                            <div class="stat-label">This Month</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Calendar Section -->
            <section id="calendar" class="dashboard-section">
                <h2>📅 My Calendar</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>📝 Upcoming Events</h3>
                        <div id="upcoming-events">
                            <div class="event-item">
                                <div class="event-date">Dec 15</div>
                                <div class="event-details">
                                    <h4>Google Summer of Code Application</h4>
                                    <p>Application deadline</p>
                                </div>
                            </div>
                            <div class="event-item">
                                <div class="event-date">Dec 20</div>
                                <div class="event-details">
                                    <h4>TechCrunch Hackathon</h4>
                                    <p>Registration closes</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="content-card">
                        <div class="card-header">
                            <h3>🔔 Reminders</h3>
                            <button class="btn btn-primary btn-sm" onclick="openAddReminderModal()">+ Add Reminder</button>
                        </div>
                        <div id="reminders-list">
                            <div class="reminder-item">
                                <div class="reminder-content">
                                    <input type="checkbox" class="reminder-checkbox">
                                    <span class="reminder-text">Complete project proposal</span>
                                </div>
                                <div class="reminder-meta">
                                    <span class="reminder-due">Due: Dec 18, 2025</span>
                                </div>
                            </div>
                            <div class="reminder-item">
                                <div class="reminder-content">
                                    <input type="checkbox" class="reminder-checkbox">
                                    <span class="reminder-text">Review internship applications</span>
                                </div>
                                <div class="reminder-meta">
                                    <span class="reminder-due">Due: Dec 22, 2025</span>
                                </div>
                            </div>
                            <div class="reminder-item completed">
                                <div class="reminder-content">
                                    <input type="checkbox" class="reminder-checkbox" checked>
                                    <span class="reminder-text">Update resume</span>
                                </div>
                                <div class="reminder-meta">
                                    <span class="reminder-due completed">Completed</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Applications (placeholder for Applications tracker) -->
            <section id="applications" class="dashboard-section">
                <h2>Applications Tracker</h2>
                <div class="applications-container">
                    <div class="content-card applications-list-card">
                        <h3>My Applications</h3>
                        <div id="my-applications" class="applications-grid">
                            <div class="application-item">
                                <div class="app-header">
                                    <h4>Software Engineer Intern</h4>
                                    <span class="app-status status-applied">Applied</span>
                                </div>
                                <p class="app-company">Google Inc.</p>
                                <p class="app-platform">Platform: LinkedIn</p>
                                <small class="app-date">Applied: Oct 25, 2025</small>
                            </div>
                            <div class="application-item">
                                <div class="app-header">
                                    <h4>Data Science Intern</h4>
                                    <span class="app-status status-in-process">In Process</span>
                                </div>
                                <p class="app-company">Microsoft</p>
                                <p class="app-platform">Platform: Company Website</p>
                                <small class="app-date">Applied: Oct 20, 2025</small>
                            </div>
                        </div>
                        <p class="muted">Track your job, internship and scholarship applications</p>
                        <button class="btn btn-secondary" onclick="loadApplications()">Refresh Applications</button>
                    </div>
                    
                    <div class="content-card application-form-card">
                        <h3>Add New Application</h3>
                        <form id="application-form" class="enhanced-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="app-title">Position Title *</label>
                                    <input type="text" id="app-title" placeholder="e.g., Software Engineer Intern" required>
                                </div>
                                <div class="form-group">
                                    <label for="app-type">Application Type *</label>
                                    <select id="app-type" required>
                                        <option value="">Select Type</option>
                                        <option value="job">Full-time Job</option>
                                        <option value="internship">Internship</option>
                                        <option value="scholarship">Scholarship</option>
                                        <option value="competition">Competition</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="app-org">Company/Organization *</label>
                                    <input type="text" id="app-org" placeholder="e.g., Google Inc." required>
                                </div>
                                <div class="form-group">
                                    <label for="app-platform">Platform/Source *</label>
                                    <select id="app-platform" required>
                                        <option value="">Select Platform</option>
                                        <option value="linkedin">LinkedIn</option>
                                        <option value="indeed">Indeed</option>
                                        <option value="glassdoor">Glassdoor</option>
                                        <option value="company-website">Company Website</option>
                                        <option value="naukri">Naukri.com</option>
                                        <option value="internshala">Internshala</option>
                                        <option value="referral">Employee Referral</option>
                                        <option value="campus">Campus Placement</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="app-link">Application Link</label>
                                <input type="url" id="app-link" placeholder="https://careers.company.com/job-id">
                            </div>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="app-location">Location</label>
                                    <input type="text" id="app-location" placeholder="e.g., Bangalore, India">
                                </div>
                                <div class="form-group">
                                    <label for="app-deadline">Application Deadline</label>
                                    <input type="date" id="app-deadline">
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="app-notes">Notes (Optional)</label>
                                <textarea id="app-notes" placeholder="Add any additional notes about this application..." rows="3"></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Add Application</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Events -->
            <section id="events" class="dashboard-section">
                <div class="events-header">
                    <h2>Campus Events</h2>
                    <p class="section-subtitle">Discover and participate in exciting events happening around you</p>
                </div>
                
                <!-- Event Categories Filter -->
                <div class="event-filters">
                    <button class="filter-btn active" data-filter="all">All Events</button>
                    <button class="filter-btn" data-filter="hackathon">Hackathons</button>
                    <button class="filter-btn" data-filter="workshop">Workshops</button>
                    <button class="filter-btn" data-filter="symposium">Symposiums</button>
                    <button class="filter-btn" data-filter="project-expo">Project Expos</button>
                </div>

                <div class="events-container">
                    <!-- Featured Events -->
                    <div class="featured-events">
                        <h3>Featured Events</h3>
                        <div class="featured-events-grid">
                            <div class="featured-event-card">
                                <div class="event-image">
                                    <div class="event-category hackathon">Hackathon</div>
                                    <div class="event-date">
                                        <span class="day">15</span>
                                        <span class="month">Nov</span>
                                    </div>
                                </div>
                                <div class="event-content">
                                    <h4>CodeCraft 2025</h4>
                                    <p class="event-desc">48-hour coding marathon with exciting prizes and networking opportunities</p>
                                    <div class="event-meta">
                                        <span class="event-location">Tech Hub, Bangalore</span>
                                        <span class="event-participants">250+ participants</span>
                                    </div>
                                    <div class="event-actions">
                                        <button class="btn btn-primary">Register Now</button>
                                        <button class="btn btn-outline">Learn More</button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="featured-event-card">
                                <div class="event-image">
                                    <div class="event-category workshop">Workshop</div>
                                    <div class="event-date">
                                        <span class="day">22</span>
                                        <span class="month">Nov</span>
                                    </div>
                                </div>
                                <div class="event-content">
                                    <h4>AI & Machine Learning Summit</h4>
                                    <p class="event-desc">Learn from industry experts about the latest trends in AI and ML</p>
                                    <div class="event-meta">
                                        <span class="event-location">Virtual Event</span>
                                        <span class="event-participants">500+ attendees</span>
                                    </div>
                                    <div class="event-actions">
                                        <button class="btn btn-primary">Register Now</button>
                                        <button class="btn btn-outline">Learn More</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Events Lists -->
                    <div class="events-lists">
                        <div class="content-card events-list-card">
                            <div class="card-header">
                                <h3>Upcoming Events</h3>
                                <div class="view-toggle">
                                    <button class="toggle-btn active" data-view="list">List</button>
                                    <button class="toggle-btn" data-view="grid">Grid</button>
                                </div>
                            </div>
                            <div id="upcoming-events" class="events-list">
                                <div class="event-item">
                                    <div class="event-item-date">
                                        <span class="date-day">28</span>
                                        <span class="date-month">Oct</span>
                                    </div>
                                    <div class="event-item-content">
                                        <h4>Web Development Bootcamp</h4>
                                        <p>Full-stack development intensive course</p>
                                        <div class="event-tags">
                                            <span class="tag workshop">Workshop</span>
                                            <span class="tag-location">Room 401, CS Block</span>
                                        </div>
                                    </div>
                                    <div class="event-item-actions">
                                        <button class="btn-small btn-primary">Join</button>
                                    </div>
                                </div>
                                
                                <div class="event-item">
                                    <div class="event-item-date">
                                        <span class="date-day">30</span>
                                        <span class="date-month">Oct</span>
                                    </div>
                                    <div class="event-item-content">
                                        <h4>Startup Pitch Competition</h4>
                                        <p>Present your innovative ideas to investors</p>
                                        <div class="event-tags">
                                            <span class="tag competition">Competition</span>
                                            <span class="tag-location">Auditorium</span>
                                        </div>
                                    </div>
                                    <div class="event-item-actions">
                                        <button class="btn-small btn-primary">Join</button>
                                    </div>
                                </div>
                                
                                <div class="event-item">
                                    <div class="event-item-date">
                                        <span class="date-day">05</span>
                                        <span class="date-month">Nov</span>
                                    </div>
                                    <div class="event-item-content">
                                        <h4>Tech Talk: Future of Computing</h4>
                                        <p>Industry leaders discuss emerging technologies</p>
                                        <div class="event-tags">
                                            <span class="tag symposium">Symposium</span>
                                            <span class="tag-location">Main Hall</span>
                                        </div>
                                    </div>
                                    <div class="event-item-actions">
                                        <button class="btn-small btn-primary">Join</button>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-secondary load-more">Load More Events</button>
                        </div>
                        
                        <div class="content-card registered-events-card">
                            <div class="card-header">
                                <h3>My Registered Events</h3>
                                <span class="event-count">3 events</span>
                            </div>
                            <div id="registered-events" class="registered-events-list">
                                <div class="registered-event-item">
                                    <div class="event-status confirmed">Confirmed</div>
                                    <h4>CodeCraft 2025</h4>
                                    <p>Nov 15, 2025 • Tech Hub, Bangalore</p>
                                    <div class="event-countdown">
                                        <span class="countdown-label">Starts in:</span>
                                        <span class="countdown-time">17 days</span>
                                    </div>
                                </div>
                                
                                <div class="registered-event-item">
                                    <div class="event-status pending">Pending</div>
                                    <h4>AI & ML Summit</h4>
                                    <p>Nov 22, 2025 • Virtual Event</p>
                                    <div class="event-countdown">
                                        <span class="countdown-label">Starts in:</span>
                                        <span class="countdown-time">24 days</span>
                                    </div>
                                </div>
                                
                                <div class="registered-event-item">
                                    <div class="event-status completed">Completed</div>
                                    <h4>React.js Workshop</h4>
                                    <p>Oct 20, 2025 • CS Lab 2</p>
                                    <button class="btn-small btn-outline">View Certificate</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Endorsements -->
            <section id="endorsements" class="dashboard-section">
                <div class="endorsements-header">
                    <h2>Professional Endorsements</h2>
                    <p class="section-subtitle">Build credibility through peer recognition and skill validation</p>
                </div>

                <!-- Endorsement Stats -->
                <div class="endorsement-stats">
                    <div class="stat-card">
                        <div class="stat-icon">⭐</div>
                        <div class="stat-content">
                            <h3>24</h3>
                            <p>Total Endorsements</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🏆</div>
                        <div class="stat-content">
                            <h3>8</h3>
                            <p>Skills Endorsed</p>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">👥</div>
                        <div class="stat-content">
                            <h3>15</h3>
                            <p>Endorsers</p>
                        </div>
                    </div>
                </div>

                <div class="endorsements-container">
                    <!-- My Skills with Endorsements -->
                    <div class="endorsements-main">
                        <div class="content-card skills-endorsements-card">
                            <div class="card-header">
                                <h3>My Skills & Endorsements</h3>
                                <button class="btn btn-outline btn-small">Manage Skills</button>
                            </div>
                            
                            <div class="skills-grid">
                                <div class="skill-endorsement-item">
                                    <div class="skill-info">
                                        <div class="skill-name">
                                            <span class="skill-icon">💻</span>
                                            <h4>JavaScript</h4>
                                        </div>
                                        <div class="skill-level">
                                            <div class="level-bar">
                                                <div class="level-fill" style="width: 85%"></div>
                                            </div>
                                            <span class="level-text">Expert</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-count">
                                        <span class="count">8</span>
                                        <span class="label">endorsements</span>
                                    </div>
                                    <div class="recent-endorsers">
                                        <div class="endorser-avatars">
                                            <div class="avatar">A</div>
                                            <div class="avatar">M</div>
                                            <div class="avatar">S</div>
                                            <div class="avatar-more">+5</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="skill-endorsement-item">
                                    <div class="skill-info">
                                        <div class="skill-name">
                                            <span class="skill-icon">⚛️</span>
                                            <h4>React.js</h4>
                                        </div>
                                        <div class="skill-level">
                                            <div class="level-bar">
                                                <div class="level-fill" style="width: 75%"></div>
                                            </div>
                                            <span class="level-text">Advanced</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-count">
                                        <span class="count">6</span>
                                        <span class="label">endorsements</span>
                                    </div>
                                    <div class="recent-endorsers">
                                        <div class="endorser-avatars">
                                            <div class="avatar">J</div>
                                            <div class="avatar">R</div>
                                            <div class="avatar">K</div>
                                            <div class="avatar-more">+3</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="skill-endorsement-item">
                                    <div class="skill-info">
                                        <div class="skill-name">
                                            <span class="skill-icon">🐍</span>
                                            <h4>Python</h4>
                                        </div>
                                        <div class="skill-level">
                                            <div class="level-bar">
                                                <div class="level-fill" style="width: 90%"></div>
                                            </div>
                                            <span class="level-text">Expert</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-count">
                                        <span class="count">5</span>
                                        <span class="label">endorsements</span>
                                    </div>
                                    <div class="recent-endorsers">
                                        <div class="endorser-avatars">
                                            <div class="avatar">P</div>
                                            <div class="avatar">L</div>
                                            <div class="avatar-more">+3</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="skill-endorsement-item">
                                    <div class="skill-info">
                                        <div class="skill-name">
                                            <span class="skill-icon">🗄️</span>
                                            <h4>Database Design</h4>
                                        </div>
                                        <div class="skill-level">
                                            <div class="level-bar">
                                                <div class="level-fill" style="width: 70%"></div>
                                            </div>
                                            <span class="level-text">Intermediate</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-count">
                                        <span class="count">3</span>
                                        <span class="label">endorsements</span>
                                    </div>
                                    <div class="recent-endorsers">
                                        <div class="endorser-avatars">
                                            <div class="avatar">D</div>
                                            <div class="avatar">N</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Endorsements -->
                        <div class="content-card recent-endorsements-card">
                            <div class="card-header">
                                <h3>Recent Endorsements</h3>
                                <a href="#" class="view-all-link">View All</a>
                            </div>
                            
                            <div class="endorsements-feed">
                                <div class="endorsement-item">
                                    <div class="endorser-info">
                                        <div class="endorser-avatar">AM</div>
                                        <div class="endorser-details">
                                            <h4>Arjun Mehta</h4>
                                            <p>Full Stack Developer at TechCorp</p>
                                            <span class="endorsement-time">2 hours ago</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-content">
                                        <div class="endorsed-skill">
                                            <span class="skill-badge">JavaScript</span>
                                        </div>
                                        <p class="endorsement-message">"Excellent problem-solving skills and deep understanding of JavaScript frameworks. Great team player!"</p>
                                    </div>
                                </div>

                                <div class="endorsement-item">
                                    <div class="endorser-info">
                                        <div class="endorser-avatar">SK</div>
                                        <div class="endorser-details">
                                            <h4>Sneha Kumar</h4>
                                            <p>Product Manager at InnovateLabs</p>
                                            <span class="endorsement-time">1 day ago</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-content">
                                        <div class="endorsed-skill">
                                            <span class="skill-badge">React.js</span>
                                        </div>
                                        <p class="endorsement-message">"Outstanding React development skills. Delivered high-quality components with clean, maintainable code."</p>
                                    </div>
                                </div>

                                <div class="endorsement-item">
                                    <div class="endorser-info">
                                        <div class="endorser-avatar">RG</div>
                                        <div class="endorser-details">
                                            <h4>Rahul Gupta</h4>
                                            <p>Senior Developer at DataFlow</p>
                                            <span class="endorsement-time">3 days ago</span>
                                        </div>
                                    </div>
                                    <div class="endorsement-content">
                                        <div class="endorsed-skill">
                                            <span class="skill-badge">Python</span>
                                        </div>
                                        <p class="endorsement-message">"Exceptional Python programming skills and machine learning expertise. Highly recommended!"</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Give Endorsement Sidebar -->
                    <div class="endorsements-sidebar">
                        <div class="content-card give-endorsement-card">
                            <div class="card-header">
                                <h3>Give an Endorsement</h3>
                                <p class="card-subtitle">Help your peers grow their professional network</p>
                            </div>
                            
                            <form id="endorsement-form" class="endorsement-form">
                                <div class="form-group">
                                    <label for="endorsee-search">Search Student</label>
                                    <div class="search-input-container">
                                        <input type="text" id="endorsee-search" placeholder="Type name or email..." autocomplete="off">
                                        <div class="search-results" id="endorsee-results"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="skill-select">Select Skill</label>
                                    <select id="skill-select" required>
                                        <option value="">Choose a skill...</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="python">Python</option>
                                        <option value="react">React.js</option>
                                        <option value="nodejs">Node.js</option>
                                        <option value="database">Database Design</option>
                                        <option value="ui-ux">UI/UX Design</option>
                                        <option value="project-management">Project Management</option>
                                        <option value="leadership">Leadership</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="endorsement-message">Endorsement Message</label>
                                    <textarea id="endorsement-message" rows="4" placeholder="Write a meaningful endorsement highlighting their skills and achievements..." required></textarea>
                                    <div class="character-count">
                                        <span id="char-count">0</span>/500 characters
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-full-width">
                                    <span class="btn-icon">🌟</span>
                                    Send Endorsement
                                </button>
                            </form>
                        </div>

                        <!-- Endorsement Suggestions -->
                        <div class="content-card suggestions-card">
                            <div class="card-header">
                                <h3>Suggested Endorsements</h3>
                                <p class="card-subtitle">People you've worked with recently</p>
                            </div>
                            
                            <div class="suggestions-list">
                                <div class="suggestion-item">
                                    <div class="suggestion-info">
                                        <div class="suggestion-avatar">VT</div>
                                        <div class="suggestion-details">
                                            <h4>Vikram Thakur</h4>
                                            <p>Hackathon teammate</p>
                                        </div>
                                    </div>
                                    <button class="btn-small btn-primary">Endorse</button>
                                </div>

                                <div class="suggestion-item">
                                    <div class="suggestion-info">
                                        <div class="suggestion-avatar">PS</div>
                                        <div class="suggestion-details">
                                            <h4>Priya Singh</h4>
                                            <p>Project partner</p>
                                        </div>
                                    </div>
                                    <button class="btn-small btn-primary">Endorse</button>
                                </div>

                                <div class="suggestion-item">
                                    <div class="suggestion-info">
                                        <div class="suggestion-avatar">AK</div>
                                        <div class="suggestion-details">
                                            <h4>Anil Kumar</h4>
                                            <p>Study group member</p>
                                        </div>
                                    </div>
                                    <button class="btn-small btn-primary">Endorse</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Upload Notes (quick access) -->
            <section id="upload-notes" class="dashboard-section">
                <div class="quick-upload-hero">
                    <div class="quick-upload-background">
                        <div class="floating-shapes">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                            <div class="shape shape-3"></div>
                        </div>
                    </div>
                    
                    <div class="quick-upload-content">
                        <div class="quick-upload-header">
                            <h2>⚡ Quick Upload</h2>
                            <p>Fast track to share your study materials with the community</p>
                        </div>
                        
                        <div class="quick-upload-container">
                            <form id="quick-upload-form" enctype="multipart/form-data" class="quick-form">
                                <div class="quick-form-grid">
                                    <div class="quick-input-group">
                                        <div class="input-icon">📝</div>
                                        <input type="text" id="quick-note-title" placeholder="Note title..." required>
                                    </div>
                                    
                                    <div class="quick-input-group">
                                        <div class="input-icon">🎯</div>
                                        <select id="quick-note-subject" required>
                                            <option value="">Subject</option>
                                            <option value="Computer Science">💻 CS</option>
                                            <option value="Mathematics">📐 Math</option>
                                            <option value="Physics">⚛️ Physics</option>
                                            <option value="Chemistry">🧪 Chemistry</option>
                                            <option value="Biology">🧬 Biology</option>
                                            <option value="Other">📚 Other</option>
                                        </select>
                                    </div>
                                    
                                    <div class="quick-file-upload">
                                        <input type="file" id="quick-note-file" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx" required hidden>
                                        <label for="quick-note-file" class="file-upload-btn">
                                            <span class="upload-icon">📎</span>
                                            <span class="upload-text">Choose File</span>
                                        </label>
                                        <span class="file-name-display" id="quick-file-name">No file selected</span>
                                    </div>
                                    
                                    <button type="submit" class="quick-submit-btn">
                                        <span class="quick-btn-icon">🚀</span>
                                        <span>Upload Now</span>
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Quick Stats -->
                            <div class="quick-stats">
                                <div class="stat-item">
                                    <span class="stat-number" id="total-uploads">0</span>
                                    <span class="stat-label">Your Uploads</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number" id="total-downloads">0</span>
                                    <span class="stat-label">Downloads</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number" id="community-rank">#-</span>
                                    <span class="stat-label">Rank</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Messages -->
            <section id="messages" class="dashboard-section">
                <div class="messages-hero">
                    <div class="hero-content">
                        <h2>✉️ Messages & Communication</h2>
                        <p>Stay connected with colleges, companies, and fellow students</p>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon">📨</div>
                            <div class="stat-number">3</div>
                            <div class="stat-label">Unread</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">💬</div>
                            <div class="stat-number">12</div>
                            <div class="stat-label">Conversations</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">⏰</div>
                            <div class="stat-number">2h</div>
                            <div class="stat-label">Avg Response</div>
                        </div>
                    </div>
                </div>

                <div class="messages-grid">
                    <!-- Inbox -->
                    <div class="messages-sidebar">
                        <div class="messages-nav">
                            <button class="nav-btn active" onclick="showInbox()">
                                <span class="nav-icon">📥</span>
                                <span class="nav-text">Inbox</span>
                                <span class="nav-badge">3</span>
                            </button>
                            <button class="nav-btn" onclick="showSent()">
                                <span class="nav-icon">📤</span>
                                <span class="nav-text">Sent</span>
                            </button>
                            <button class="nav-btn" onclick="showDrafts()">
                                <span class="nav-icon">📝</span>
                                <span class="nav-text">Drafts</span>
                                <span class="nav-badge">1</span>
                            </button>
                            <button class="nav-btn" onclick="showArchived()">
                                <span class="nav-icon">📦</span>
                                <span class="nav-text">Archived</span>
                            </button>
                        </div>

                        <div class="messages-list">
                            <div class="message-item unread">
                                <div class="message-avatar">
                                    <img src="../assets/images/logos/google.png" alt="Google">
                                </div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="message-sender">Google Recruitment</span>
                                        <span class="message-time">2h ago</span>
                                    </div>
                                    <div class="message-preview">Your application for Software Engineer Intern position...</div>
                                </div>
                            </div>

                            <div class="message-item">
                                <div class="message-avatar">
                                    <img src="../assets/images/logos/microsoft.png" alt="Microsoft">
                                </div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="message-sender">Microsoft Careers</span>
                                        <span class="message-time">1d ago</span>
                                    </div>
                                    <div class="message-preview">Thank you for your interest in our Data Science...</div>
                                </div>
                            </div>

                            <div class="message-item unread">
                                <div class="message-avatar">
                                    <span class="avatar-text">CU</span>
                                </div>
                                <div class="message-content">
                                    <div class="message-header">
                                        <span class="message-sender">Chandigarh University</span>
                                        <span class="message-time">3h ago</span>
                                    </div>
                                    <div class="message-preview">Placement drive information for CSE students...</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message View -->
                    <div class="message-view">
                        <div class="message-view-header">
                            <div class="message-info">
                                <h3>Google Recruitment</h3>
                                <p>Software Engineer Intern Application Update</p>
                            </div>
                            <div class="message-actions">
                                <button class="btn btn-secondary btn-sm">Reply</button>
                                <button class="btn btn-secondary btn-sm">Forward</button>
                                <button class="btn btn-secondary btn-sm">Archive</button>
                            </div>
                        </div>

                        <div class="message-thread">
                            <div class="message-bubble received">
                                <div class="bubble-header">
                                    <span class="bubble-sender">Google Recruitment</span>
                                    <span class="bubble-time">Dec 10, 2025 2:30 PM</span>
                                </div>
                                <div class="bubble-content">
                                    <p>Dear Candidate,</p>
                                    <p>Thank you for your application to the Software Engineer Intern position at Google. We have reviewed your application and would like to invite you for a technical interview.</p>
                                    <p>The interview is scheduled for December 20th, 2025 at 10:00 AM PST. Please confirm your availability by replying to this message.</p>
                                    <p>Best regards,<br>Google Recruitment Team</p>
                                </div>
                            </div>

                            <div class="message-bubble sent">
                                <div class="bubble-header">
                                    <span class="bubble-sender">You</span>
                                    <span class="bubble-time">Dec 10, 2025 3:15 PM</span>
                                </div>
                                <div class="bubble-content">
                                    <p>Thank you for the invitation! I confirm my availability for the interview on December 20th at 10:00 AM PST.</p>
                                    <p>Looking forward to speaking with you.</p>
                                    <p>Best regards,<br>[Your Name]</p>
                                </div>
                            </div>
                        </div>

                        <div class="message-compose">
                            <div class="compose-header">
                                <h4>Reply to Google Recruitment</h4>
                            </div>
                            <textarea placeholder="Type your message here..." rows="4"></textarea>
                            <div class="compose-actions">
                                <button class="btn btn-secondary btn-sm">Attach File</button>
                                <button class="btn btn-primary">Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Settings -->
            <section id="settings" class="dashboard-section">
                <div class="settings-hero">
                    <div class="hero-content">
                        <h2>⚙️ Settings & Preferences</h2>
                        <p>Customize your experience and manage your account settings</p>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon">🔒</div>
                            <div class="stat-label">Security</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🔔</div>
                            <div class="stat-label">Notifications</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🎨</div>
                            <div class="stat-label">Appearance</div>
                        </div>
                    </div>
                </div>

                <div class="settings-grid">
                    <!-- Account Settings -->
                    <div class="settings-category">
                        <div class="category-header">
                            <div class="category-icon">👤</div>
                            <h3>Account Settings</h3>
                        </div>
                        <div class="settings-cards">
                            <div class="settings-card">
                                <div class="card-icon">🔑</div>
                                <div class="card-content">
                                    <h4>Change Password</h4>
                                    <p>Update your account password for better security</p>
                                    <button class="btn btn-primary" onclick="openPasswordModal()">Change Password</button>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">📧</div>
                                <div class="card-content">
                                    <h4>Email Preferences</h4>
                                    <p>Manage your email notifications and preferences</p>
                                    <button class="btn btn-secondary" onclick="openEmailSettings()">Configure</button>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">📱</div>
                                <div class="card-content">
                                    <h4>Profile Information</h4>
                                    <p>Update your personal information and profile details</p>
                                    <button class="btn btn-secondary" onclick="openProfileSettings()">Edit Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy & Security -->
                    <div class="settings-category">
                        <div class="category-header">
                            <div class="category-icon">🔒</div>
                            <h3>Privacy & Security</h3>
                        </div>
                        <div class="settings-cards">
                            <div class="settings-card">
                                <div class="card-icon">👁️</div>
                                <div class="card-content">
                                    <h4>Privacy Settings</h4>
                                    <p>Control who can see your profile and activity</p>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Profile Visibility</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">🔐</div>
                                <div class="card-content">
                                    <h4>Two-Factor Authentication</h4>
                                    <p>Add an extra layer of security to your account</p>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Enable 2FA</label>
                                        <label class="toggle">
                                            <input type="checkbox">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">📊</div>
                                <div class="card-content">
                                    <h4>Data & Privacy</h4>
                                    <p>Manage your data and download your information</p>
                                    <button class="btn btn-secondary" onclick="openDataSettings()">Manage Data</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="settings-category">
                        <div class="category-header">
                            <div class="category-icon">🔔</div>
                            <h3>Notifications</h3>
                        </div>
                        <div class="settings-cards">
                            <div class="settings-card">
                                <div class="card-icon">💬</div>
                                <div class="card-content">
                                    <h4>Messages & Chat</h4>
                                    <p>Get notified about new messages and replies</p>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Push Notifications</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Email Notifications</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">📅</div>
                                <div class="card-content">
                                    <h4>Events & Opportunities</h4>
                                    <p>Stay updated on hackathons, internships, and events</p>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Event Reminders</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="toggle-container">
                                        <label class="toggle-label">New Opportunities</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">🏆</div>
                                <div class="card-content">
                                    <h4>Achievements & Progress</h4>
                                    <p>Celebrate your milestones and track progress</p>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Achievement Alerts</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance -->
                    <div class="settings-category">
                        <div class="category-header">
                            <div class="category-icon">🎨</div>
                            <h3>Appearance</h3>
                        </div>
                        <div class="settings-cards">
                            <div class="settings-card">
                                <div class="card-icon">🌙</div>
                                <div class="card-content">
                                    <h4>Theme</h4>
                                    <p>Choose your preferred theme</p>
                                    <div class="theme-options">
                                        <label class="theme-option active">
                                            <input type="radio" name="theme" value="light" checked>
                                            <span>Light</span>
                                        </label>
                                        <label class="theme-option">
                                            <input type="radio" name="theme" value="dark">
                                            <span>Dark</span>
                                        </label>
                                        <label class="theme-option">
                                            <input type="radio" name="theme" value="auto">
                                            <span>Auto</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-card">
                                <div class="card-icon">📏</div>
                                <div class="card-content">
                                    <h4>Display Settings</h4>
                                    <p>Customize your dashboard layout and appearance</p>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Compact Mode</label>
                                        <label class="toggle">
                                            <input type="checkbox">
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                    <div class="toggle-container">
                                        <label class="toggle-label">Show Animations</label>
                                        <label class="toggle">
                                            <input type="checkbox" checked>
                                            <span class="toggle-slider"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Management -->
                    <div class="settings-category danger-zone">
                        <div class="category-header">
                            <div class="category-icon">⚠️</div>
                            <h3>Account Management</h3>
                        </div>
                        <div class="settings-cards">
                            <div class="settings-card danger">
                                <div class="card-icon">🗂️</div>
                                <div class="card-content">
                                    <h4>Export Data</h4>
                                    <p>Download all your data in JSON format</p>
                                    <button class="btn btn-outline" onclick="exportUserData()">Export Data</button>
                                </div>
                            </div>
                            <div class="settings-card danger">
                                <div class="card-icon">🚫</div>
                                <div class="card-content">
                                    <h4>Delete Account</h4>
                                    <p>Permanently delete your account and all associated data</p>
                                    <button class="btn btn-danger" onclick="confirmDeleteAccount()">Delete Account</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Help -->
            <section id="help" class="dashboard-section">
                <!-- Help Hero -->
                <div class="help-hero">
                    <div class="hero-content">
                        <h2>🆘 Help Center</h2>
                        <p>Find answers, get support, and make the most of LearnX</p>
                        <div class="help-search">
                            <input type="text" id="help-search" placeholder="Search for help...">
                            <button class="search-btn" onclick="searchHelp()">
                                <span>🔍</span>
                            </button>
                        </div>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <div class="stat-icon">📚</div>
                            <div class="stat-label">Guides</div>
                            <div class="stat-number">50+</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">🎥</div>
                            <div class="stat-label">Videos</div>
                            <div class="stat-number">25+</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-icon">💬</div>
                            <div class="stat-label">Support</div>
                            <div class="stat-number">24/7</div>
                        </div>
                    </div>
                </div>

                <!-- Popular Topics -->
                <div class="help-section">
                    <h3>🔥 Popular Topics</h3>
                    <div class="topics-grid">
                        <div class="topic-card" onclick="showTopic('getting-started')">
                            <div class="topic-icon">🚀</div>
                            <div class="topic-content">
                                <h4>Getting Started</h4>
                                <p>New to LearnX? Start here</p>
                            </div>
                            <div class="topic-arrow">→</div>
                        </div>
                        <div class="topic-card" onclick="showTopic('notes-sharing')">
                            <div class="topic-icon">📝</div>
                            <div class="topic-content">
                                <h4>Notes Sharing</h4>
                                <p>Upload and share study materials</p>
                            </div>
                            <div class="topic-arrow">→</div>
                        </div>
                        <div class="topic-card" onclick="showTopic('opportunities')">
                            <div class="topic-icon">💼</div>
                            <div class="topic-content">
                                <h4>Finding Opportunities</h4>
                                <p>Discover internships and jobs</p>
                            </div>
                            <div class="topic-arrow">→</div>
                        </div>
                        <div class="topic-card" onclick="showTopic('ai-coach')">
                            <div class="topic-icon">🤖</div>
                            <div class="topic-content">
                                <h4>AI Career Coach</h4>
                                <p>Get personalized career guidance</p>
                            </div>
                            <div class="topic-arrow">→</div>
                        </div>
                        <div class="topic-card" onclick="showTopic('achievements')">
                            <div class="topic-icon">🏆</div>
                            <div class="topic-content">
                                <h4>Achievements</h4>
                                <p>Earn badges and track progress</p>
                            </div>
                            <div class="topic-arrow">→</div>
                        </div>
                        <div class="topic-card" onclick="showTopic('profile')">
                            <div class="topic-icon">👤</div>
                            <div class="topic-content">
                                <h4>Profile Setup</h4>
                                <p>Complete your student profile</p>
                            </div>
                            <div class="topic-arrow">→</div>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="help-section">
                    <h3>❓ Frequently Asked Questions</h3>
                    <div class="faq-container">
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>How do I upload study notes?</span>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Go to the Notes section, click "Upload Notes", fill in the title, subject, and description, then select your file. You can also add tags to help others find your notes.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>How do I apply for internships?</span>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Navigate to the Opportunities section, browse available internships, and click "Apply" on positions that interest you. Your applications will be tracked in the Applications section.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>How does the AI Coach work?</span>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>The AI Coach is your personal career advisor. Ask questions about resume building, interview preparation, skill development, or career planning. It's available 24/7 in the AI Coach section.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>How do I earn achievements?</span>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Achievements are earned by completing various activities like uploading notes, applying to opportunities, using the AI coach, and participating in events. Check your Achievements section to see your progress.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>How do I update my profile?</span>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Go to your Profile section or click on your name/avatar in the sidebar. You can update your personal information, upload a profile picture, and manage your preferences.</p>
                            </div>
                        </div>
                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFAQ(this)">
                                <span>How do I join hackathons?</span>
                                <span class="faq-toggle">+</span>
                            </div>
                            <div class="faq-answer">
                                <p>Visit the Hackathons page from the main navigation. Browse available hackathons, read the requirements, and register for events that match your skills and interests.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help Resources -->
                <div class="help-section">
                    <h3>📖 Help Resources</h3>
                    <div class="resources-grid">
                        <div class="resource-card">
                            <div class="resource-icon">📹</div>
                            <div class="resource-content">
                                <h4>Video Tutorials</h4>
                                <p>Step-by-step video guides</p>
                                <button class="btn btn-secondary" onclick="openVideoTutorials()">Watch Videos</button>
                            </div>
                        </div>
                        <div class="resource-card">
                            <div class="resource-icon">📋</div>
                            <div class="resource-content">
                                <h4>User Guides</h4>
                                <p>Detailed written guides</p>
                                <button class="btn btn-secondary" onclick="openUserGuides()">Read Guides</button>
                            </div>
                        </div>
                        <div class="resource-card">
                            <div class="resource-icon">💬</div>
                            <div class="resource-content">
                                <h4>Live Support</h4>
                                <p>Get help from our team</p>
                                <button class="btn btn-primary" onclick="openLiveSupport()">Contact Support</button>
                            </div>
                        </div>
                        <div class="resource-card">
                            <div class="resource-icon">📧</div>
                            <div class="resource-content">
                                <h4>Email Support</h4>
                                <p>Send us an email</p>
                                <button class="btn btn-outline" onclick="openEmailSupport()">Send Email</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="help-section contact-section">
                    <div class="contact-card">
                        <div class="contact-header">
                            <h3>👋 Need More Help?</h3>
                            <p>Our support team is here to assist you</p>
                        </div>
                        <div class="contact-options">
                            <div class="contact-option">
                                <div class="contact-icon">💬</div>
                                <div class="contact-info">
                                    <h4>Live Chat</h4>
                                    <p>Available 24/7</p>
                                    <button class="btn btn-primary" onclick="startLiveChat()">Start Chat</button>
                                </div>
                            </div>
                            <div class="contact-option">
                                <div class="contact-icon">📧</div>
                                <div class="contact-info">
                                    <h4>Email Support</h4>
                                    <p>Response within 24 hours</p>
                                    <button class="btn btn-secondary" onclick="sendSupportEmail()">Send Email</button>
                                </div>
                            </div>
                            <div class="contact-option">
                                <div class="contact-icon">📞</div>
                                <div class="contact-info">
                                    <h4>Phone Support</h4>
                                    <p>Mon-Fri, 9AM-6PM</p>
                                    <button class="btn btn-outline" onclick="callSupport()">Call Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/enhanced-dashboard.js"></script>
    <script src="../assets/js/achievement-system.js"></script>
    <script src="../assets/js/ai_chat.js"></script>
</body>
</html>
