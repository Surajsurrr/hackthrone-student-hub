-- Company Posts Migration
-- This adds a table for company general posts (research, history, announcements, etc.)

CREATE TABLE IF NOT EXISTS company_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    company_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    post_type ENUM('research', 'history', 'announcement', 'culture', 'achievement', 'general') DEFAULT 'general',
    image_url VARCHAR(255),
    tags VARCHAR(255),
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    status ENUM('published', 'draft', 'archived') DEFAULT 'published',
    published_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    INDEX idx_company_posts_company_id (company_id),
    INDEX idx_company_posts_status (status),
    INDEX idx_company_posts_post_type (post_type),
    INDEX idx_company_posts_published_at (published_at)
);
