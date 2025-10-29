-- Student Hub Backend Features Database Schema
-- Create tables for tracking applications, notes, AI sessions, and profile scoring

-- Table for tracking opportunity applications
CREATE TABLE IF NOT EXISTS applications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    opportunity_type ENUM('event', 'job', 'internship') NOT NULL,
    opportunity_id INT NOT NULL,
    opportunity_title VARCHAR(255) NOT NULL,
    company_college_name VARCHAR(255),
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('applied', 'under_review', 'shortlisted', 'rejected', 'accepted', 'withdrawn') DEFAULT 'applied',
    notes TEXT,
    follow_up_date DATE,
    response_date TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_student_id (student_id),
    INDEX idx_status (status),
    INDEX idx_application_date (application_date)
);

-- Table for tracking shared notes analytics
CREATE TABLE IF NOT EXISTS shared_notes_analytics (
    id INT PRIMARY KEY AUTO_INCREMENT,
    note_id INT NOT NULL,
    student_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    subject VARCHAR(100),
    file_name VARCHAR(255),
    file_size INT,
    upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    download_count INT DEFAULT 0,
    view_count INT DEFAULT 0,
    like_count INT DEFAULT 0,
    share_count INT DEFAULT 0,
    visibility ENUM('public', 'university', 'friends', 'private') DEFAULT 'public',
    featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_student_id (student_id),
    INDEX idx_subject (subject),
    INDEX idx_upload_date (upload_date),
    INDEX idx_featured (featured)
);

-- Table for AI session tracking
CREATE TABLE IF NOT EXISTS ai_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    session_title VARCHAR(255),
    conversation_topic VARCHAR(255),
    message_count INT DEFAULT 0,
    total_characters INT DEFAULT 0,
    session_duration INT DEFAULT 0, -- in seconds
    session_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    session_end TIMESTAMP NULL,
    last_activity TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    session_quality ENUM('excellent', 'good', 'average', 'poor') DEFAULT 'average',
    topics_covered JSON, -- Store array of topics discussed
    helpful_rating INT DEFAULT 0, -- 1-5 rating
    session_type ENUM('career_guidance', 'interview_prep', 'skill_assessment', 'general_chat', 'homework_help') DEFAULT 'general_chat',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_student_id (student_id),
    INDEX idx_session_start (session_start),
    INDEX idx_session_type (session_type),
    INDEX idx_is_active (is_active)
);

-- Table for individual AI messages within sessions
CREATE TABLE IF NOT EXISTS ai_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id INT NOT NULL,
    student_id INT NOT NULL,
    message_type ENUM('user', 'assistant') NOT NULL,
    message_content TEXT NOT NULL,
    response_time DECIMAL(5,2), -- Response time in seconds
    message_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    sentiment ENUM('positive', 'neutral', 'negative'),
    keywords JSON, -- Extract key topics from message
    helpful BOOLEAN DEFAULT NULL, -- User can mark if helpful
    FOREIGN KEY (session_id) REFERENCES ai_sessions(id) ON DELETE CASCADE,
    INDEX idx_session_id (session_id),
    INDEX idx_student_id (student_id),
    INDEX idx_message_timestamp (message_timestamp)
);

-- Table for profile scoring system
CREATE TABLE IF NOT EXISTS profile_scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL UNIQUE,
    total_score INT DEFAULT 0,
    achievements_score INT DEFAULT 0,
    notes_score INT DEFAULT 0,
    ai_sessions_score INT DEFAULT 0,
    applications_score INT DEFAULT 0,
    engagement_score INT DEFAULT 0,
    consistency_score INT DEFAULT 0,
    last_calculated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    score_breakdown JSON, -- Detailed breakdown of how score was calculated
    rank_in_university INT DEFAULT 0,
    percentile DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    INDEX idx_total_score (total_score),
    INDEX idx_rank (rank_in_university)
);

-- Table for tracking student achievements
CREATE TABLE IF NOT EXISTS student_achievements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    achievement_type ENUM('first_note', 'popular_note', 'helpful_contributor', 'active_learner', 'ai_explorer', 'job_seeker', 'networking_pro', 'consistent_user') NOT NULL,
    achievement_title VARCHAR(255) NOT NULL,
    achievement_description TEXT,
    points_earned INT DEFAULT 0,
    badge_icon VARCHAR(100),
    date_earned TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_featured BOOLEAN DEFAULT FALSE,
    criteria_met JSON, -- What criteria were met to earn this
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student_id (student_id),
    INDEX idx_achievement_type (achievement_type),
    INDEX idx_date_earned (date_earned)
);

-- Table for tracking note interactions (likes, downloads, views)
CREATE TABLE IF NOT EXISTS note_interactions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    note_id INT NOT NULL,
    student_id INT NOT NULL,
    interaction_type ENUM('view', 'download', 'like', 'share', 'bookmark') NOT NULL,
    interaction_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    INDEX idx_note_id (note_id),
    INDEX idx_student_id (student_id),
    INDEX idx_interaction_type (interaction_type),
    INDEX idx_timestamp (interaction_timestamp),
    UNIQUE KEY unique_like (note_id, student_id, interaction_type)
);

-- Table for application status tracking
CREATE TABLE IF NOT EXISTS application_status_history (
    id INT PRIMARY KEY AUTO_INCREMENT,
    application_id INT NOT NULL,
    old_status VARCHAR(50),
    new_status VARCHAR(50) NOT NULL,
    changed_by ENUM('system', 'student', 'company', 'college') DEFAULT 'system',
    change_reason TEXT,
    change_timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (application_id) REFERENCES applications(id) ON DELETE CASCADE,
    INDEX idx_application_id (application_id),
    INDEX idx_change_timestamp (change_timestamp)
);