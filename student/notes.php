<?php
require_once 'includes/student_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <!-- Header Section -->
        <div class="notes-header">
            <div class="header-content">
                <h1>📚 Study Notes Hub</h1>
                <p>Organize, share and discover study resources by topics and subjects</p>
            </div>
            <div class="stats-cards">
                <div class="stat-card">
                    <div class="stat-icon">📖</div>
                    <div class="stat-info">
                        <span class="stat-number" id="total-notes">156</span>
                        <span class="stat-label">Total Notes</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🏷️</div>
                    <div class="stat-info">
                        <span class="stat-number" id="total-topics">24</span>
                        <span class="stat-label">Topics</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">📚</div>
                    <div class="stat-info">
                        <span class="stat-number" id="total-subjects">8</span>
                        <span class="stat-label">Subjects</span>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⭐</div>
                    <div class="stat-info">
                        <span class="stat-number" id="my-notes">12</span>
                        <span class="stat-label">My Notes</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subject Categories Navigation -->
        <div class="subject-categories">
            <div class="categories-header">
                <h3>📋 Browse by Subject</h3>
                <div class="view-toggle">
                    <button class="toggle-btn active" data-view="grid">Grid View</button>
                    <button class="toggle-btn" data-view="list">List View</button>
                </div>
            </div>
            <div class="categories-grid" id="categories-grid">
                <!-- Categories will be loaded here -->
            </div>
        </div>

        <!-- Main Content -->
        <div class="notes-main-content">
            <!-- Upload Section (Left Column) -->
            <div class="upload-section">
                <div class="upload-card">
                    <div class="upload-header">
                        <h3>📝 Share Your Knowledge</h3>
                        <p>Upload notes to help your fellow students succeed</p>
                    </div>
                    
                    <form id="upload-form" enctype="multipart/form-data" class="upload-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="note-title">📌 Title</label>
                                <input type="text" id="note-title" placeholder="e.g., Data Structures Complete Notes" required>
                            </div>
                            <div class="form-group">
                                <label for="note-subject">📚 Subject</label>
                                <select id="note-subject" required>
                                    <option value="">Select Subject</option>
                                    <option value="Computer Science">Computer Science</option>
                                    <option value="Mathematics">Mathematics</option>
                                    <option value="Physics">Physics</option>
                                    <option value="Chemistry">Chemistry</option>
                                    <option value="Biology">Biology</option>
                                    <option value="English">English</option>
                                    <option value="History">History</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="note-difficulty">📊 Difficulty Level</label>
                                <select id="note-difficulty" required>
                                    <option value="">Select Level</option>
                                    <option value="Beginner">🟢 Beginner</option>
                                    <option value="Intermediate">🟡 Intermediate</option>
                                    <option value="Advanced">🔴 Advanced</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group full-width">
                                <label for="note-topics">🏷️ Topics</label>
                                <div class="topics-selector">
                                    <select id="note-topics-select" multiple>
                                        <!-- Topics will be loaded dynamically -->
                                    </select>
                                    <div class="selected-topics" id="selected-topics">
                                        <!-- Selected topics will appear here -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="note-tags">🔖 Tags</label>
                            <input type="text" id="note-tags" placeholder="e.g., exam-prep, tutorial, examples (separate with commas)">
                            <small class="form-hint">Add relevant tags to help others find your notes</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="note-description">📋 Description</label>
                            <textarea id="note-description" placeholder="Brief description of the content, topics covered, key concepts, etc." rows="3"></textarea>
                        </div>
                        
                        <div class="file-upload-area">
                            <input type="file" id="note-file" accept=".pdf,.doc,.docx,.txt,.ppt,.pptx" required>
                            <div class="file-upload-content">
                                <div class="file-upload-icon">📎</div>
                                <div class="file-upload-text">
                                    <span class="primary-text">Click to upload or drag & drop</span>
                                    <span class="secondary-text">PDF, DOC, DOCX, TXT, PPT files (Max 10MB)</span>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="upload-btn">
                            <span class="btn-icon">🚀</span>
                            Share Notes
                        </button>
                    </form>
                </div>
            </div>

            <!-- Community Sidebar (Right Column, on top) -->
            <div class="community-sidebar">
                <!-- Create Community Card -->
                <div class="community-card create-community-card">
                    <div class="card-header">
                        <h3>👥 Create Community</h3>
                        <p>Build a study group around your interests</p>
                    </div>
                    <button class="btn-create-community" id="openCommunityModal">
                        <span class="btn-icon">✨</span>
                        Start New Community
                    </button>
                </div>

                <!-- Trending Communities -->
                <div class="community-card trending-communities">
                    <div class="card-header">
                        <h3>🔥 Trending Communities</h3>
                    </div>
                    <div class="communities-list" id="trending-communities">
                        <!-- Communities will be loaded here -->
                    </div>
                </div>

                <!-- My Communities -->
                <div class="community-card my-communities">
                    <div class="card-header">
                        <h3>📌 My Communities</h3>
                    </div>
                    <div class="communities-list" id="my-communities">
                        <p class="empty-state-text">You haven't joined any communities yet.</p>
                    </div>
                </div>
            </div>

            <!-- Browse Section (Right Column, below community) -->
        </div>
    </div>

    <!-- Create Community Modal -->
    <div class="modal" id="createCommunityModal">
        <div class="modal-overlay" id="modalOverlay"></div>
        <div class="modal-content community-modal">
            <div class="modal-header">
                <h2>✨ Create New Community</h2>
                <button class="close-modal" id="closeCommunityModal">×</button>
            </div>
            <form id="createCommunityForm">
                <div class="form-group">
                    <label for="community-name">Community Name *</label>
                    <input type="text" id="community-name" name="community_name" placeholder="e.g., Data Structures Study Group" required>
                </div>
                
                <div class="form-group">
                    <label for="community-subject">Related Subject *</label>
                    <select id="community-subject" name="community_subject" required>
                        <option value="">Choose Subject</option>
                        <option value="Computer Science">💻 Computer Science</option>
                        <option value="Mathematics">📐 Mathematics</option>
                        <option value="Physics">⚛️ Physics</option>
                        <option value="Chemistry">🧪 Chemistry</option>
                        <option value="Biology">🧬 Biology</option>
                        <option value="English">📖 English</option>
                        <option value="History">🏛️ History</option>
                        <option value="Other">📚 Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="community-description">Description *</label>
                    <textarea id="community-description" name="community_description" rows="4" placeholder="Describe the purpose and goals of this community..." required></textarea>
                </div>

                <div class="form-group">
                    <label for="community-type">Community Type *</label>
                    <select id="community-type" name="community_type" required>
                        <option value="">Choose Type</option>
                        <option value="public">🌍 Public - Anyone can join</option>
                        <option value="private">🔒 Private - Invite only</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="community-tags">Tags (comma separated)</label>
                    <input type="text" id="community-tags" name="community_tags" placeholder="e.g., algorithms, coding, dsa">
                    <small>Add up to 5 tags to help others discover your community</small>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelCommunityBtn">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <span class="btn-icon">🚀</span>
                        Create Community
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Notification System -->
    <div id="notification" class="notification hidden">
        <span id="notification-message"></span>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <script src="../assets/js/enhanced-dashboard.js"></script>
    <script src="../assets/js/notes-organization.js"></script>
    <script src="../assets/js/communities.js"></script>
</body>
</html>
