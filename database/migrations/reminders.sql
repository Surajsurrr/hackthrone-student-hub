-- Reminders table for students
CREATE TABLE IF NOT EXISTS reminders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    text VARCHAR(500) NOT NULL,
    due_at DATETIME NULL,
    done BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_due (user_id, due_at),
    INDEX idx_done (done)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
