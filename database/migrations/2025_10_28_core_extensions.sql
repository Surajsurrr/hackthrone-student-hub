-- Core extensions for MVP (skills, endorsements, achievements/xp, applications, notes ratings, reminders, profile extras)

-- Use with MySQL 8+

START TRANSACTION;

-- Skills and endorsements
CREATE TABLE IF NOT EXISTS skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  category VARCHAR(100) NULL,
  UNIQUE KEY (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_skills (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  skill_id INT NOT NULL,
  level TINYINT NULL, -- 1..5
  endorsements_count INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_skill (user_id, skill_id),
  INDEX idx_user_skills_user (user_id),
  CONSTRAINT fk_user_skills_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS endorsements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  endorsed_user_id INT NOT NULL,
  endorser_user_id INT NOT NULL,
  skill_id INT NOT NULL,
  comment VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_endorsements_endorsed (endorsed_user_id),
  INDEX idx_endorsements_endorser (endorser_user_id),
  CONSTRAINT fk_endorsements_skill FOREIGN KEY (skill_id) REFERENCES skills(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Achievements and XP
CREATE TABLE IF NOT EXISTS achievements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(64) NOT NULL,
  name VARCHAR(120) NOT NULL,
  description VARCHAR(255) NULL,
  points INT NOT NULL DEFAULT 0,
  UNIQUE KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_achievements (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  achievement_id INT NOT NULL,
  awarded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_user_achievement (user_id, achievement_id),
  INDEX idx_user_achievements_user (user_id),
  CONSTRAINT fk_user_achievements_achievement FOREIGN KEY (achievement_id) REFERENCES achievements(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS user_stats (
  user_id INT PRIMARY KEY,
  xp INT NOT NULL DEFAULT 0,
  coins INT NOT NULL DEFAULT 0,
  profile_score TINYINT NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Applications tracker
CREATE TABLE IF NOT EXISTS applications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type ENUM('job','internship','scholarship','competition','exam_alert') NOT NULL,
  entity_id INT NULL,
  title VARCHAR(200) NOT NULL,
  company_or_org VARCHAR(150) NULL,
  location VARCHAR(120) NULL,
  status ENUM('applied','in_process','selected','rejected') NOT NULL DEFAULT 'applied',
  deadline DATE NULL,
  applied_at TIMESTAMP NULL,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_applications_user (user_id),
  INDEX idx_applications_status (status),
  INDEX idx_applications_deadline (deadline)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Notes ratings and reviews
CREATE TABLE IF NOT EXISTS notes_ratings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  note_id INT NOT NULL,
  user_id INT NOT NULL,
  rating TINYINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
  review VARCHAR(500) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY uniq_note_user (note_id, user_id),
  INDEX idx_notes_ratings_note (note_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Reminders
CREATE TABLE IF NOT EXISTS reminders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  text VARCHAR(255) NOT NULL,
  due_at DATETIME NULL,
  done TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_reminders_user (user_id),
  INDEX idx_reminders_due (due_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Profile extensions (extend existing `students` table in base schema)
ALTER TABLE students
  ADD COLUMN IF NOT EXISTS branch VARCHAR(120) NULL,
  ADD COLUMN IF NOT EXISTS bio VARCHAR(500) NULL,
  ADD COLUMN IF NOT EXISTS interests VARCHAR(500) NULL; -- comma-separated tags for MVP

COMMIT;
