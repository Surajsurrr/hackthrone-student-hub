<?php
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
    <style>
        /* Fix form text visibility in application form */
        .application-form-card input[type="text"],
        .application-form-card input[type="url"],
        .application-form-card input[type="date"],
        .application-form-card select,
        .application-form-card textarea {
            background: white !important;
            color: #1f2937 !important;
            border: 2px solid #d1d5db !important;
            padding: 0.75rem !important;
            border-radius: 8px !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
        }

        .application-form-card input::placeholder,
        .application-form-card textarea::placeholder {
            color: #9ca3af !important;
            font-weight: 400 !important;
        }

        .application-form-card label {
            color: #e6eef6 !important;
            font-weight: 600 !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        .application-form-card option {
            color: #1f2937 !important;
            background: white !important;
        }

        .application-form-card select option {
            color: #1f2937 !important;
            background: white !important;
        }

        /* Focus states */
        .application-form-card input:focus,
        .application-form-card select:focus,
        .application-form-card textarea:focus {
            outline: 2px solid var(--accent1) !important;
            border-color: var(--accent1) !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1) !important;
        }

        /* Ensure form title is visible */
        .application-form-card h3 {
            color: #e6eef6 !important;
            margin-bottom: 1.5rem !important;
        }

        /* Enhanced form styling */
        .enhanced-form .form-group {
            margin-bottom: 1rem !important;
        }

        .enhanced-form .form-row {
            display: flex !important;
            gap: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .enhanced-form .form-row .form-group {
            flex: 1 !important;
        }

        /* Button styling for form */
        .application-form-card .btn {
            background: var(--accent1) !important;
            color: white !important;
            border: none !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
        }

        .application-form-card .btn:hover {
            background: #6d28d9 !important;
        }

        /* Fix text visibility for events section */
        .events-list-card,
        .events-list-card * {
            color: inherit;
        }

        .events-list-card .event-item {
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 12px !important;
            padding: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .events-list-card .event-item * {
            color: #1f2937 !important;
        }

        .events-list-card .event-item h4 {
            color: #111827 !important;
            font-weight: 600 !important;
            margin-bottom: 0.5rem !important;
        }

        .events-list-card .event-item p {
            color: #4b5563 !important;
            margin-bottom: 0.75rem !important;
        }

        .events-list-card .event-item-date {
            background: var(--accent1) !important;
            color: white !important;
            border-radius: 8px !important;
            padding: 0.5rem !important;
            text-align: center !important;
            min-width: 60px !important;
        }

        .events-list-card .date-day {
            font-size: 1.5rem !important;
            font-weight: 700 !important;
            color: white !important;
        }

        .events-list-card .date-month {
            font-size: 0.8rem !important;
            color: white !important;
            text-transform: uppercase !important;
        }

        .events-list-card .tag {
            padding: 0.25rem 0.75rem !important;
            border-radius: 20px !important;
            font-size: 0.8rem !important;
            font-weight: 500 !important;
            margin-right: 0.5rem !important;
        }

        .events-list-card .tag.workshop {
            background: #dcfce7 !important;
            color: #166534 !important;
        }

        .events-list-card .tag-location {
            color: #6b7280 !important;
        }

        .events-list-card .btn-small {
            background: var(--accent1) !important;
            color: white !important;
            border: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
            font-weight: 600 !important;
        }

        .events-list-card .btn-small:hover {
            background: #6d28d9 !important;
        }

        /* View toggle buttons */
        .events-list-card .toggle-btn {
            background: white !important;
            color: #374151 !important;
            border: 1px solid #d1d5db !important;
            padding: 0.5rem 1rem !important;
            border-radius: 6px !important;
            font-size: 0.875rem !important;
        }

        .events-list-card .toggle-btn.active {
            background: var(--accent1) !important;
            color: white !important;
            border-color: var(--accent1) !important;
        }

        /* Load More button */
        .load-more-events {
            background: white !important;
            color: var(--accent1) !important;
            border: 2px solid var(--accent1) !important;
            padding: 0.75rem 1.5rem !important;
            border-radius: 25px !important;
            font-weight: 600 !important;
            text-align: center !important;
            display: block !important;
            width: 100% !important;
            margin-top: 1rem !important;
        }

        .load-more-events:hover {
            background: var(--accent1) !important;
            color: white !important;
        }
    </style>
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
                    <li><a href="#applications" class="nav-link" data-section="applications"> Applications</a></li>
                    <li><a href="#events" class="nav-link" data-section="events"> Events</a></li>
                    <li><a href="#endorsements" class="nav-link" data-section="endorsements"> Endorsements</a></li>
                    <li><a href="#notes" class="nav-link" data-section="notes"> Notes</a></li>
                    <li><a href="#ai-coach" class="nav-link" data-section="ai-coach"> AI Coach</a></li>
                    <li><a href="#achievements" class="nav-link" data-section="achievements"> Achievements</a></li>
                    <li><a href="#calendar" class="nav-link" data-section="calendar"> Calendar</a></li>
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
                    <a href="notes.php" class="stat-card clickable">
                        <h3>Notes Shared</h3>
                        <p id="notes-count">8</p>
                    </a>
                    <a href="ai_coach.php" class="stat-card clickable">
                        <h3>AI Sessions</h3>
                        <p id="ai-sessions-count">15</p>
                    </a>
                    <a href="profile.php" class="stat-card clickable">
                        <h3>Profile Score</h3>
                        <p>85%</p>
                    </a>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="actions-grid">
                        <div class="action-card">
                            <h4>Join Hackathons</h4>
                            <p>Participate in coding competitions</p>
                            <a href="hackathons.php" class="btn">Browse</a>
                        </div>
                        <div class="action-card">
                            <h4>AI Mentoring</h4>
                            <p>Get personalized career guidance</p>
                            <a href="#ai-coach" class="btn nav-trigger" data-section="ai-coach">Start Chat</a>
                        </div>
                        <div class="action-card">
                            <h4>Browse Notes</h4>
                            <p>Discover study materials</p>
                            <a href="notes.php" class="btn">Explore</a>
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

            <!-- Notes Section -->
            <section id="notes" class="dashboard-section">
                <div class="section-header">
                    <h2>📚 Study Notes Hub</h2>
                    <p>Share your knowledge and discover amazing study resources</p>
                </div>
                
                <div class="notes-content-grid">
                    
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
                                    <h4>Knowledge Seeker</h4>
                                    <p>Explored 10 study notes</p>
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
                                    <span class="unlock-condition">🔓 Engage with 15 more students</span>
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
                                <a href="manage_skills.php" class="btn btn-outline btn-small">Manage Skills</a>
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
                                            <input type="checkbox" id="profileVisibilityToggle" checked onchange="togglePrivacySetting('profile_visibility', this.checked)">
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
                                            <input type="checkbox" id="twoFactorToggle" onchange="toggleTwoFactor(this.checked)">
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
                                <p>Achievements are earned by completing various activities like engaging with notes, applying to opportunities, using the AI coach, and participating in events. Check your Achievements section to see your progress.</p>
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

        <!-- Add Reminder Modal -->
        <div id="reminderModal" class="modal" style="display: none;">
            <div class="modal-overlay" onclick="closeReminderModal()"></div>
            <div class="modal-container">
                <div class="modal-header">
                    <h3>📅 Add New Reminder</h3>
                    <button class="modal-close" onclick="closeReminderModal()">&times;</button>
                </div>
                <form id="reminderForm" class="reminder-form">
                    <div class="form-group">
                        <label for="reminderText">Reminder *</label>
                        <input 
                            type="text" 
                            id="reminderText" 
                            placeholder="e.g., Complete project proposal" 
                            required
                            maxlength="500"
                        >
                    </div>
                    <div class="form-group">
                        <label for="reminderDate">Due Date (Optional)</label>
                        <input 
                            type="datetime-local" 
                            id="reminderDate"
                            min="<?php echo date('Y-m-d\TH:i'); ?>"
                        >
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeReminderModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="btn-icon">✓</span>
                            <span>Add Reminder</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div id="passwordModal" class="modal" style="display: none;">
            <div class="modal-overlay" onclick="closePasswordModal()"></div>
            <div class="modal-container">
                <div class="modal-header">
                    <h3>🔑 Change Password</h3>
                    <button class="modal-close" onclick="closePasswordModal()">&times;</button>
                </div>
                <form id="passwordForm" class="reminder-form">
                    <div class="form-group">
                        <label for="currentPassword">Current Password *</label>
                        <input 
                            type="password" 
                            id="currentPassword" 
                            placeholder="Enter your current password" 
                            required
                            autocomplete="current-password"
                        >
                    </div>
                    <div class="form-group">
                        <label for="newPassword">New Password *</label>
                        <input 
                            type="password" 
                            id="newPassword"
                            placeholder="Enter new password (min. 6 characters)"
                            required
                            minlength="6"
                            autocomplete="new-password"
                        >
                        <small style="color: #64748b; font-size: 0.85rem; margin-top: 0.25rem; display: block;">
                            Password must be at least 6 characters long
                        </small>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Confirm New Password *</label>
                        <input 
                            type="password" 
                            id="confirmPassword"
                            placeholder="Re-enter new password"
                            required
                            minlength="6"
                            autocomplete="new-password"
                        >
                    </div>
                    <div id="passwordError" class="error-message" style="display: none; color: #dc2626; background: #fee2e2; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;"></div>
                    <div id="passwordSuccess" class="success-message" style="display: none; color: #059669; background: #d1fae5; padding: 0.75rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.9rem;"></div>
                    <div class="modal-actions">
                        <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="changePasswordBtn">
                            <span class="btn-icon">✓</span>
                            <span>Change Password</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/enhanced-dashboard.js"></script>
    <script src="../assets/js/achievement-system.js"></script>
    <script src="../assets/js/ai_chat.js"></script>
    
    <script>
        // Reminder Management Functions
        function openAddReminderModal() {
            document.getElementById('reminderModal').style.display = 'flex';
            document.getElementById('reminderText').focus();
        }

        function closeReminderModal() {
            document.getElementById('reminderModal').style.display = 'none';
            document.getElementById('reminderForm').reset();
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeReminderModal();
            }
        });

        // Handle reminder form submission
        document.getElementById('reminderForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const text = document.getElementById('reminderText').value.trim();
            const dueDate = document.getElementById('reminderDate').value;
            
            if (!text) {
                showNotification('Please enter a reminder text', 'error');
                return;
            }

            try {
                const response = await fetch('../api/student/addReminder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        text: text,
                        due_at: dueDate || null
                    })
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('✓ Reminder added successfully!', 'success');
                    closeReminderModal();
                    loadReminders(); // Refresh the reminders list
                    
                    // Request notification permission if due date is set
                    if (dueDate && 'Notification' in window) {
                        Notification.requestPermission();
                    }
                } else {
                    showNotification(result.error || 'Failed to add reminder', 'error');
                }
            } catch (error) {
                console.error('Error adding reminder:', error);
                showNotification('Failed to add reminder. Please try again.', 'error');
            }
        });

        // Load reminders from server
        async function loadReminders() {
            try {
                const response = await fetch('../api/student/getReminders.php');
                const result = await response.json();

                if (result.success) {
                    displayReminders(result.reminders);
                    checkUpcomingReminders(result.reminders);
                }
            } catch (error) {
                console.error('Error loading reminders:', error);
            }
        }

        // Display reminders in the list
        function displayReminders(reminders) {
            const remindersList = document.getElementById('reminders-list');
            
            if (!reminders || reminders.length === 0) {
                remindersList.innerHTML = '<p style="text-align: center; color: var(--text-secondary); padding: 2rem;">No reminders yet. Click "+ Add Reminder" to create one.</p>';
                return;
            }

            remindersList.innerHTML = reminders.map(reminder => `
                <div class="reminder-item ${reminder.done ? 'completed' : ''}" data-id="${reminder.id}">
                    <div class="reminder-content">
                        <input 
                            type="checkbox" 
                            class="reminder-checkbox" 
                            ${reminder.done ? 'checked' : ''}
                            onchange="toggleReminder(${reminder.id}, this.checked)"
                        >
                        <span class="reminder-text">${escapeHtml(reminder.text)}</span>
                    </div>
                    <div class="reminder-meta">
                        ${reminder.due_at ? `
                            <span class="reminder-due ${reminder.done ? 'completed' : (reminder.is_overdue ? 'overdue' : '')}">
                                ${reminder.done ? 'Completed' : formatDueDate(reminder.due_at)}
                            </span>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        }

        // Toggle reminder completion
        async function toggleReminder(id, done) {
            try {
                const response = await fetch('../api/student/updateReminder.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id,
                        done: done
                    })
                });

                const result = await response.json();

                if (result.success) {
                    loadReminders(); // Refresh the list
                } else {
                    showNotification(result.error || 'Failed to update reminder', 'error');
                    loadReminders(); // Reload to reset checkbox state
                }
            } catch (error) {
                console.error('Error updating reminder:', error);
                loadReminders(); // Reload to reset checkbox state
            }
        }

        // Format due date for display
        function formatDueDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());
            const reminderDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            
            const diffDays = Math.floor((reminderDate - today) / (1000 * 60 * 60 * 24));
            
            if (diffDays < 0) {
                return 'Overdue';
            } else if (diffDays === 0) {
                return 'Due Today';
            } else if (diffDays === 1) {
                return 'Due Tomorrow';
            } else {
                return `Due: ${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}`;
            }
        }

        // Check for upcoming reminders and send notifications
        function checkUpcomingReminders(reminders) {
            if (!('Notification' in window) || Notification.permission !== 'granted') {
                return;
            }

            const now = new Date();
            const oneDayFromNow = new Date(now.getTime() + 24 * 60 * 60 * 1000);

            reminders.forEach(reminder => {
                if (!reminder.done && reminder.due_at) {
                    const dueDate = new Date(reminder.due_at);
                    
                    // Notify if due within 24 hours
                    if (dueDate <= oneDayFromNow && dueDate > now) {
                        const hours = Math.floor((dueDate - now) / (1000 * 60 * 60));
                        
                        if (hours <= 1) {
                            new Notification('Reminder Due Soon!', {
                                body: reminder.text,
                                icon: '../assets/icons/reminder.png',
                                tag: `reminder-${reminder.id}`
                            });
                        }
                    }
                }
            });
        }

        // Request notification permission on page load
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Load reminders on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadReminders();
            
            // Refresh reminders every 5 minutes
            setInterval(loadReminders, 5 * 60 * 1000);
        });

        // Password Change Functions
        function openPasswordModal() {
            document.getElementById('passwordModal').style.display = 'flex';
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordError').style.display = 'none';
            document.getElementById('passwordSuccess').style.display = 'none';
        }

        function closePasswordModal() {
            document.getElementById('passwordModal').style.display = 'none';
            document.getElementById('passwordForm').reset();
            document.getElementById('passwordError').style.display = 'none';
            document.getElementById('passwordSuccess').style.display = 'none';
        }

        // Handle password change form submission
        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const currentPassword = document.getElementById('currentPassword').value;
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            const errorDiv = document.getElementById('passwordError');
            const successDiv = document.getElementById('passwordSuccess');
            const submitBtn = document.getElementById('changePasswordBtn');
            
            // Hide previous messages
            errorDiv.style.display = 'none';
            successDiv.style.display = 'none';
            
            // Validate passwords match
            if (newPassword !== confirmPassword) {
                errorDiv.textContent = '❌ New passwords do not match';
                errorDiv.style.display = 'block';
                return;
            }
            
            // Validate password length
            if (newPassword.length < 6) {
                errorDiv.textContent = '❌ Password must be at least 6 characters long';
                errorDiv.style.display = 'block';
                return;
            }
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span>Changing...</span>';
            
            try {
                const response = await fetch('../api/student/changePassword.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        current_password: currentPassword,
                        new_password: newPassword,
                        confirm_password: confirmPassword
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    successDiv.textContent = '✓ ' + data.message;
                    successDiv.style.display = 'block';
                    document.getElementById('passwordForm').reset();
                    
                    // Close modal after 2 seconds
                    setTimeout(() => {
                        closePasswordModal();
                    }, 2000);
                } else {
                    errorDiv.textContent = '❌ ' + data.message;
                    errorDiv.style.display = 'block';
                }
            } catch (error) {
                console.error('Error changing password:', error);
                errorDiv.textContent = '❌ An error occurred. Please try again.';
                errorDiv.style.display = 'block';
            } finally {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<span class="btn-icon">✓</span><span>Change Password</span>';
            }
        });

        // Helper function to escape HTML
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Privacy Settings Toggle Function
        async function togglePrivacySetting(settingName, isEnabled) {
            const toggle = document.getElementById('profileVisibilityToggle');
            
            try {
                const response = await fetch('../api/student/updatePrivacySettings.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        setting_name: settingName,
                        value: isEnabled
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success notification
                    showNotification(
                        isEnabled ? '✓ Profile visibility enabled' : '✓ Profile visibility disabled',
                        'success'
                    );
                } else {
                    // Revert toggle on error
                    toggle.checked = !isEnabled;
                    showNotification('❌ ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error updating privacy setting:', error);
                // Revert toggle on error
                toggle.checked = !isEnabled;
                showNotification('❌ Failed to update privacy setting', 'error');
            }
        }

        // Two-Factor Authentication Toggle Function
        async function toggleTwoFactor(isEnabled) {
            const toggle = document.getElementById('twoFactorToggle');
            
            try {
                const response = await fetch('../api/student/updateTwoFactor.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        enabled: isEnabled
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Show success notification
                    showNotification('✓ ' + data.message, 'success');
                } else {
                    // Revert toggle on error
                    toggle.checked = !isEnabled;
                    showNotification('❌ ' + data.message, 'error');
                }
            } catch (error) {
                console.error('Error updating 2FA:', error);
                // Revert toggle on error
                toggle.checked = !isEnabled;
                showNotification('❌ Failed to update Two-Factor Authentication', 'error');
            }
        }

        // Show notification helper function
        function showNotification(message, type = 'success') {
            // Create notification element
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
            
            if (type === 'success') {
                notification.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
            } else {
                notification.style.background = 'linear-gradient(135deg, #dc2626 0%, #b91c1c 100%)';
            }
            
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Load current settings on page load
        document.addEventListener('DOMContentLoaded', async function() {
            try {
                // Fetch current privacy settings
                const privacyResponse = await fetch('../api/student/getPrivacySettings.php');
                if (privacyResponse.ok) {
                    const privacyData = await privacyResponse.json();
                    if (privacyData.success) {
                        document.getElementById('profileVisibilityToggle').checked = privacyData.profile_visibility;
                    }
                }
                
                // Fetch current 2FA status
                const twoFactorResponse = await fetch('../api/student/getTwoFactorStatus.php');
                if (twoFactorResponse.ok) {
                    const twoFactorData = await twoFactorResponse.json();
                    if (twoFactorData.success) {
                        document.getElementById('twoFactorToggle').checked = twoFactorData.enabled;
                    }
                }
            } catch (error) {
                console.error('Error loading settings:', error);
            }
        });

        // Endorsement Form Functionality
        let selectedStudentId = null;
        
        // Character counter for endorsement message
        const endorsementMessage = document.getElementById('endorsement-message');
        const charCount = document.getElementById('char-count');
        
        if (endorsementMessage && charCount) {
            endorsementMessage.addEventListener('input', function() {
                const count = this.value.length;
                charCount.textContent = count;
                
                if (count > 500) {
                    charCount.style.color = '#f44336';
                } else {
                    charCount.style.color = 'var(--text-secondary)';
                }
            });
        }
        
        // Search students
        const endorseeSearch = document.getElementById('endorsee-search');
        const endorseeResults = document.getElementById('endorsee-results');
        let searchTimeout;
        
        if (endorseeSearch) {
            endorseeSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const searchTerm = this.value.trim();
                
                if (searchTerm.length < 2) {
                    endorseeResults.innerHTML = '';
                    endorseeResults.style.display = 'none';
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    searchStudents(searchTerm);
                }, 300);
            });
        }
        
        async function searchStudents(searchTerm) {
            try {
                const response = await fetch(`../api/student/searchStudents.php?search=${encodeURIComponent(searchTerm)}`);
                const result = await response.json();
                
                if (result.success && result.students.length > 0) {
                    displaySearchResults(result.students);
                } else {
                    endorseeResults.innerHTML = '<div class="search-result-item" style="padding: 1rem; color: var(--text-secondary);">No students found</div>';
                    endorseeResults.style.display = 'block';
                }
            } catch (error) {
                console.error('Error searching students:', error);
            }
        }
        
        function displaySearchResults(students) {
            endorseeResults.innerHTML = students.map(student => `
                <div class="search-result-item" onclick="selectStudent(${student.id}, '${escapeHtml(student.name)}')">
                    <div class="student-info">
                        <div class="student-name">${escapeHtml(student.name)}</div>
                        <div class="student-details">${escapeHtml(student.college)} • ${escapeHtml(student.course)}</div>
                    </div>
                </div>
            `).join('');
            endorseeResults.style.display = 'block';
        }
        
        async function selectStudent(studentId, studentName) {
            selectedStudentId = studentId;
            endorseeSearch.value = studentName;
            endorseeResults.style.display = 'none';
            
            // Load student's skills
            try {
                const response = await fetch(`../api/student/getStudentSkills.php?student_id=${studentId}`);
                const result = await response.json();
                
                if (result.success && result.skills.length > 0) {
                    updateSkillDropdown(result.skills);
                } else {
                    updateSkillDropdown([]);
                    showNotification('This student has no skills to endorse', 'error');
                }
            } catch (error) {
                console.error('Error loading skills:', error);
            }
        }
        
        function updateSkillDropdown(skills) {
            const skillSelect = document.getElementById('skill-select');
            
            if (skills.length === 0) {
                skillSelect.innerHTML = '<option value="">No skills available</option>';
                skillSelect.disabled = true;
                return;
            }
            
            skillSelect.disabled = false;
            skillSelect.innerHTML = '<option value="">Choose a skill...</option>' + 
                skills.map(skill => `<option value="${escapeHtml(skill)}">${escapeHtml(skill)}</option>`).join('');
        }
        
        // Submit endorsement
        const endorsementForm = document.getElementById('endorsement-form');
        
        if (endorsementForm) {
            endorsementForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                if (!selectedStudentId) {
                    showNotification('Please select a student', 'error');
                    return;
                }
                
                const skill = document.getElementById('skill-select').value;
                const message = endorsementMessage.value.trim();
                
                if (!skill || !message) {
                    showNotification('Please fill in all fields', 'error');
                    return;
                }
                
                if (message.length > 500) {
                    showNotification('Message is too long (max 500 characters)', 'error');
                    return;
                }
                
                try {
                    const formData = new FormData();
                    formData.append('endorsed_id', selectedStudentId);
                    formData.append('skill_name', skill);
                    formData.append('message', message);
                    
                    const response = await fetch('../api/student/sendEndorsement.php', {
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showNotification('Endorsement sent successfully! 🌟', 'success');
                        endorsementForm.reset();
                        selectedStudentId = null;
                        charCount.textContent = '0';
                        document.getElementById('skill-select').disabled = true;
                        document.getElementById('skill-select').innerHTML = '<option value="">Choose a skill...</option>';
                    } else {
                        showNotification(result.message || 'Failed to send endorsement', 'error');
                    }
                } catch (error) {
                    console.error('Error sending endorsement:', error);
                    showNotification('An error occurred', 'error');
                }
            });
        }
        
        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (endorseeSearch && endorseeResults && !endorseeSearch.contains(e.target) && !endorseeResults.contains(e.target)) {
                endorseeResults.style.display = 'none';
            }
        });
        
        // Helper function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        // Notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = 'notification ' + type;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 2rem;
                right: 2rem;
                padding: 1rem 1.5rem;
                border-radius: 8px;
                color: white;
                font-weight: 600;
                z-index: 10000;
                animation: slideIn 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            `;
            
            if (type === 'success') {
                notification.style.background = '#4caf50';
            } else if (type === 'error') {
                notification.style.background = '#f44336';
            }
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
    
    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
        
        .search-input-container {
            position: relative;
        }
        
        .search-results {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-top: 0.5rem;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        
        .search-result-item {
            padding: 1rem;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
            transition: background 0.2s;
        }
        
        .search-result-item:last-child {
            border-bottom: none;
        }
        
        .search-result-item:hover {
            background: var(--card-hover);
        }
        
        .student-name {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.25rem;
        }
        
        .student-details {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .character-count {
            text-align: right;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.5rem;
        }
    </style>
</body>
</html>
