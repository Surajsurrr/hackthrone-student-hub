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
                <h1>📚 Knowledge Hub</h1>
                <p>Share and discover amazing study resources with your peers</p>
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
                    <div class="stat-icon">👥</div>
                    <div class="stat-info">
                        <span class="stat-number" id="contributors">89</span>
                        <span class="stat-label">Contributors</span>
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
                        </div>
                        
                        <div class="form-group">
                            <label for="note-description">📋 Description</label>
                            <textarea id="note-description" placeholder="Brief description of the content, topics covered, etc." rows="3"></textarea>
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
                            <input type="text" id="search-notes" placeholder="Search notes...">
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
                        <select id="sort-notes" class="filter-select">
                            <option value="newest">Newest First</option>
                            <option value="oldest">Oldest First</option>
                            <option value="popular">Most Popular</option>
                            <option value="title">Title A-Z</option>
                        </select>
                    </div>
                </div>
                
                <!-- Featured Notes -->
                <div class="featured-notes">
                    <h4>⭐ Featured Notes</h4>
                    <div class="featured-grid">
                        <div class="featured-card">
                            <div class="featured-badge">🏆 Top Rated</div>
                            <h5>Advanced Algorithms</h5>
                            <p>Comprehensive guide to complex algorithms</p>
                            <div class="featured-stats">
                                <span>👤 Dr. Smith</span>
                                <span>⭐ 4.9</span>
                                <span>📥 342</span>
                            </div>
                        </div>
                        <div class="featured-card">
                            <div class="featured-badge">🔥 Trending</div>
                            <h5>Machine Learning Basics</h5>
                            <p>Perfect introduction to ML concepts</p>
                            <div class="featured-stats">
                                <span>👤 Sarah Wilson</span>
                                <span>⭐ 4.8</span>
                                <span>📥 289</span>
                            </div>
                        </div>
                        <div class="featured-card">
                            <div class="featured-badge">🆕 Latest</div>
                            <h5>React.js Complete Guide</h5>
                            <p>From basics to advanced concepts</p>
                            <div class="featured-stats">
                                <span>👤 Alex Chen</span>
                                <span>⭐ 4.7</span>
                                <span>📥 156</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Grid -->
                <div class="notes-grid-container">
                    <div id="notes-list" class="modern-notes-grid">
                        <!-- Notes will be loaded here -->
                    </div>
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
</body>
</html>
