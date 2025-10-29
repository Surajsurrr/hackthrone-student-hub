-- College Posts Table for LinkedIn-style posts

CREATE TABLE IF NOT EXISTS college_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    college_id INT NOT NULL,
    category ENUM('research', 'achievement', 'event', 'placement', 'campus-life', 'announcement', 'facilities', 'collaboration') NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    image_url VARCHAR(500) NULL,
    status ENUM('published', 'draft', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (college_id) REFERENCES colleges(id) ON DELETE CASCADE,
    INDEX idx_college_posts (college_id, created_at),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
