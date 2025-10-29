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
                    <li><a href="#skills" class="nav-link" data-section="skills"> Skills</a></li>
                    <li><a href="#endorsements" class="nav-link" data-section="endorsements"> Endorsements</a></li>
                    <li><a href="#notes" class="nav-link" data-section="notes"> Notes</a></li>
                    <li><a href="#upload-notes" class="nav-link" data-section="upload-notes">⬆ Upload</a></li>
                    <li><a href="#ai-coach" class="nav-link" data-section="ai-coach"> AI Coach</a></li>
                    <li><a href="#achievements" class="nav-link" data-section="achievements"> Achievements</a></li>
                    <li><a href="#calendar" class="nav-link" data-section="calendar"> Calendar</a></li>
                    <li><a href="#reminders" class="nav-link" data-section="reminders"> Reminders</a></li>
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
                        <div class="stat-icon">🎯</div>
                        <h3>Opportunities Applied</h3>
                        <p id="opportunities-count">12</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">📚</div>
                        <h3>Notes Shared</h3>
                        <p id="notes-count">8</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">🤖</div>
                        <h3>AI Sessions</h3>
                        <p id="ai-sessions-count">15</p>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">⭐</div>
                        <h3>Profile Score</h3>
                        <p>85%</p>
                    </div>
                </div>

                <div class="quick-actions">
                    <h3>Quick Actions</h3>
                    <div class="actions-grid">
                        <div class="action-card">
                            <div class="icon">💼</div>
                            <h4>Find Internships</h4>
                            <p>Discover new internship opportunities</p>
                            <a href="#opportunities" class="btn nav-trigger" data-section="opportunities">Explore</a>
                        </div>
                        <div class="action-card">
                            <div class="icon">🏆</div>
                            <h4>Join Hackathons</h4>
                            <p>Participate in coding competitions</p>
                            <a href="hackathons.php" class="btn">Browse</a>
                        </div>
                        <div class="action-card">
                            <div class="icon">📝</div>
                            <h4>Share Notes</h4>
                            <p>Upload and share study materials</p>
                            <a href="#notes" class="btn nav-trigger" data-section="notes">Upload</a>
                        </div>
                        <div class="action-card">
                            <div class="icon">🎓</div>
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
                        <h3>🏆 Hackathons</h3>
                        <p>Participate in coding competitions and showcase your skills</p>
                        <div id="hackathons-preview">Loading latest hackathons...</div>
                        <a href="hackathons.php" class="btn">View All Hackathons</a>
                    </div>
                    <div class="content-card">
                        <h3>💼 Internships</h3>
                        <p>Find internships that match your skills and interests</p>
                        <div id="internships-preview">Loading latest internships...</div>
                        <a href="internships.php" class="btn">View All Internships</a>
                    </div>
                    <div class="content-card">
                        <h3>🎯 Job Recommendations</h3>
                        <p>AI-powered job suggestions based on your profile</p>
                        <div id="job-recommendations">Loading recommendations...</div>
                        <button class="btn" onclick="generateRecommendations()">Get New Recommendations</button>
                    </div>
                </div>
            </section>

            <!-- Notes Section -->
            <section id="notes" class="dashboard-section">
                <h2>Study Notes</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>📤 Upload Notes</h3>
                        <form id="upload-form" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="note-title">Note Title</label>
                                <input type="text" id="note-title" placeholder="Enter note title" required>
                            </div>
                            <div class="form-group">
                                <label for="note-subject">Subject</label>
                                <input type="text" id="note-subject" placeholder="e.g., Data Structures" required>
                            </div>
                            <div class="form-group">
                                <label for="note-description">Description</label>
                                <textarea id="note-description" placeholder="Brief description of the notes"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="note-file">Upload File</label>
                                <input type="file" id="note-file" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx" required>
                            </div>
                            <button type="submit" class="btn">Upload Notes</button>
                        </form>
                    </div>
                    <div class="content-card">
                        <h3>📚 My Notes</h3>
                        <div id="my-notes-list">Loading your notes...</div>
                    </div>
                    <div class="content-card">
                        <h3>🌟 Popular Notes</h3>
                        <div id="popular-notes-list">Loading popular notes...</div>
                    </div>
                </div>
            </section>

            <!-- AI Coach Section -->
            <section id="ai-coach" class="dashboard-section">
                <h2>🤖 AI Career Coach</h2>
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
                        <button class="suggestion-btn" onclick="sendSuggestion('How can I improve my resume?')">📝 Resume Tips</button>
                        <button class="suggestion-btn" onclick="sendSuggestion('What skills should I learn for my field?')">🎯 Skill Development</button>
                        <button class="suggestion-btn" onclick="sendSuggestion('How to prepare for technical interviews?')">💼 Interview Prep</button>
                        <button class="suggestion-btn" onclick="sendSuggestion('What career paths are suitable for me?')">🛤️ Career Paths</button>
                    </div>
                </div>
            </section>

            <!-- Achievements Section -->
            <section id="achievements" class="dashboard-section">
                <h2>🏆 Achievements & Progress</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>🎖️ Badges Earned</h3>
                        <div class="badges-grid">
                            <div class="badge-item">
                                <div class="badge-icon">📚</div>
                                <span>Knowledge Sharer</span>
                            </div>
                            <div class="badge-item">
                                <div class="badge-icon">💬</div>
                                <span>AI Chat Pro</span>
                            </div>
                            <div class="badge-item">
                                <div class="badge-icon">🎯</div>
                                <span>Goal Achiever</span>
                            </div>
                        </div>
                    </div>
                    <div class="content-card">
                        <h3>📈 Progress Tracking</h3>
                        <div class="progress-item">
                            <span>Profile Completion</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 85%"></div>
                            </div>
                            <span>85%</span>
                        </div>
                        <div class="progress-item">
                            <span>Skill Development</span>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 70%"></div>
                            </div>
                            <span>70%</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Calendar Section -->
            <section id="calendar" class="dashboard-section">
                <h2>📅 My Calendar</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>📋 Upcoming Events</h3>
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
                        <h3>⏰ Reminders</h3>
                        <div id="reminders-list">
                            <div class="reminder-item">
                                <input type="checkbox"> Update LinkedIn profile
                            </div>
                            <div class="reminder-item">
                                <input type="checkbox"> Practice coding problems
                            </div>
                            <div class="reminder-item">
                                <input type="checkbox"> Review Data Structures notes
                            </div>
                        </div>
                        <button class="btn" onclick="addReminder()">Add Reminder</button>
                    </div>
                </div>
            </section>

            <!-- Applications (placeholder for Applications tracker) -->
            <section id="applications" class="dashboard-section">
                <h2>🧾 Applications</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>My Applications</h3>
                        <div id="my-applications">Loading your applications...</div>
                        <p class="muted">Track job, internship and scholarship applications here.</p>
                        <a href="#" class="btn" onclick="document.querySelector('.nav-link[data-section=applications]').click();return false;">Refresh</a>
                    </div>
                    <div class="content-card">
                        <h3>Add Application</h3>
                        <form id="application-form">
                            <div class="form-group"><label>Title</label><input id="app-title" required></div>
                            <div class="form-group"><label>Type</label><select id="app-type"><option value="job">Job</option><option value="internship">Internship</option></select></div>
                            <div class="form-group"><label>Company/Org</label><input id="app-org"></div>
                            <button class="btn" type="submit">Add</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Skills -->
            <section id="skills" class="dashboard-section">
                <h2>💪 Skills</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Your Skills</h3>
                        <div id="user-skills">Loading skills...</div>
                    </div>
                    <div class="content-card">
                        <h3>Add Skill</h3>
                        <form id="skill-form"><div class="form-group"><label>Skill Name</label><input id="skill-name"></div><button class="btn" type="submit">Add Skill</button></form>
                    </div>
                </div>
            </section>

            <!-- Endorsements -->
            <section id="endorsements" class="dashboard-section">
                <h2>👏 Endorsements</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Received Endorsements</h3>
                        <div id="received-endorsements">Loading endorsements...</div>
                    </div>
                    <div class="content-card">
                        <h3>Give an Endorsement</h3>
                        <p>Endorse a peer's skill to help them stand out.</p>
                    </div>
                </div>
            </section>

            <!-- Upload Notes (quick access) -->
            <section id="upload-notes" class="dashboard-section">
                <h2>⬆️ Upload Notes</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <form id="quick-upload-form" enctype="multipart/form-data">
                            <div class="form-group"><label>Title</label><input id="quick-note-title"></div>
                            <div class="form-group"><label>Subject</label><input id="quick-note-subject"></div>
                            <div class="form-group"><label>File</label><input type="file" id="quick-note-file"></div>
                            <button class="btn" type="submit">Upload</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- Reminders -->
            <section id="reminders" class="dashboard-section">
                <h2>⏰ Reminders</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>My Reminders</h3>
                        <div id="reminders-list">Loading reminders...</div>
                        <button class="btn" onclick="addReminder()">Add Reminder</button>
                    </div>
                </div>
            </section>

            <!-- Messages -->
            <section id="messages" class="dashboard-section">
                <h2>✉️ Messages</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Inbox</h3>
                        <div id="inbox-list">Loading messages...</div>
                    </div>
                </div>
            </section>

            <!-- Settings -->
            <section id="settings" class="dashboard-section">
                <h2>⚙️ Settings</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Account Settings</h3>
                        <p>Change password, notification preferences and privacy settings.</p>
                    </div>
                </div>
            </section>

            <!-- Help -->
            <section id="help" class="dashboard-section">
                <h2>❓ Help & FAQs</h2>
                <div class="content-grid">
                    <div class="content-card">
                        <h3>Quick Help</h3>
                        <p>Find guides, FAQs and support contacts for LearnX.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/enhanced-dashboard.js"></script>
    <script src="../assets/js/ai_chat.js"></script>
</body>
</html>
