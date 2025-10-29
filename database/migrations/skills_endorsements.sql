-- Skills table
CREATE TABLE IF NOT EXISTS student_skills (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    skill_level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_skill (student_id, skill_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Endorsements table
CREATE TABLE IF NOT EXISTS endorsements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endorser_id INT NOT NULL,
    endorsed_id INT NOT NULL,
    skill_name VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (endorser_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (endorsed_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_endorsed (endorsed_id),
    INDEX idx_endorser (endorser_id),
    INDEX idx_skill (skill_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
