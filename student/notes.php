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
    <style>
        /* Fix form field visibility while keeping dark theme */
        .form-group input,
        .form-group select,
        .form-group textarea {
            color: #1f2937 !important;
            background: white !important;
            border: 1px solid #d1d5db !important;
        }

        .form-group input::placeholder,
        .form-group textarea::placeholder {
            color: #6b7280 !important;
        }

        .form-group option {
            color: #1f2937 !important;
            background: white !important;
        }

        /* Fix file upload area text */
        .file-upload-content {
            color: #374151 !important;
        }

        .file-upload-content .primary-text {
            color: #111827 !important;
        }

        .file-upload-content .secondary-text {
            color: #6b7280 !important;
        }

        /* Fix form labels for better visibility */
        .form-group label {
            color: #e6eef6 !important;
            font-weight: 600;
        }

        .form-hint {
            color: #9aa4b2 !important;
        }

        /* Make sure selected topics area is readable */
        .selected-topics {
            background: white !important;
            color: #1f2937 !important;
        }

        /* Fix toggle buttons */
        .toggle-btn {
            color: #1f2937 !important;
            background: white !important;
        }

        .toggle-btn.active {
            background: var(--accent1) !important;
            color: white !important;
        }
    </style>
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
            <!-- Upload Section -->
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

            <!-- Browse Section -->
            <div class="browse-section">
                <div class="browse-header">
                    <h3>🔍 Discover Notes</h3>
                    <div class="browse-controls">
                        <div class="search-box">
                            <input type="text" id="search-notes" placeholder="Search notes, topics, or tags...">
                            <span class="search-icon">🔍</span>
                        </div>
                        <select id="filter-subject" class="filter-select">
                            <option value="">All Subjects</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Mathematics">Mathematics</option>
                            <option value="Physics">Physics</option>
                            <option value="Chemistry">Chemistry</option>
                            <option value="Biology">Biology</option>
                            <option value="English">English</option>
                            <option value="History">History</option>
                            <option value="Other">Other</option>
                        </select>
                        <select id="filter-topic" class="filter-select">
                            <option value="">All Topics</option>
                            <!-- Topics will be loaded dynamically -->
                        </select>
                        <select id="filter-difficulty" class="filter-select">
                            <option value="">All Levels</option>
                            <option value="Beginner">🟢 Beginner</option>
                            <option value="Intermediate">🟡 Intermediate</option>
                            <option value="Advanced">🔴 Advanced</option>
                        </select>
                        <select id="sort-notes" class="filter-select">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="popular">Most Popular</option>
                            <option value="title">Title A-Z</option>
                            <option value="subject">By Subject</option>
                        </select>
                    </div>
                </div>
                
                <!-- Active Filters -->
                <div class="active-filters" id="active-filters" style="display: none;">
                    <span class="filters-label">Active Filters:</span>
                    <div class="filters-list" id="filters-list">
                        <!-- Active filters will appear here -->
                    </div>
                    <button class="clear-filters" id="clear-filters">Clear All</button>
                </div>
                
                
            </div>
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
</body>
</html>
