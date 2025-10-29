-- Enhanced Notes Organization System
-- Create topics and tags for better categorization

START TRANSACTION;

-- Create topics table for better subject organization
CREATE TABLE IF NOT EXISTS topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    subject_category VARCHAR(100) NOT NULL,
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#3498db',
    icon VARCHAR(50) DEFAULT '📚',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_topic_subject (name, subject_category),
    INDEX idx_topics_subject (subject_category)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create tags table for flexible tagging
CREATE TABLE IF NOT EXISTS tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    usage_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create note_topics junction table
CREATE TABLE IF NOT EXISTS note_topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note_id INT NOT NULL,
    topic_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_note_topic (note_id, topic_id),
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create note_tags junction table
CREATE TABLE IF NOT EXISTS note_tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    note_id INT NOT NULL,
    tag_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_note_tag (note_id, tag_id),
    FOREIGN KEY (note_id) REFERENCES notes(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add additional fields to notes table
ALTER TABLE notes 
ADD COLUMN topics TEXT NULL AFTER subject,
ADD COLUMN tags TEXT NULL AFTER topics,
ADD COLUMN difficulty_level ENUM('Beginner', 'Intermediate', 'Advanced') DEFAULT 'Beginner' AFTER tags;

-- Insert default topics for common subjects
INSERT INTO topics (name, subject_category, description, color, icon) VALUES
-- Computer Science
('Data Structures', 'Computer Science', 'Arrays, Linked Lists, Trees, Graphs', '#e74c3c', '🌳'),
('Algorithms', 'Computer Science', 'Sorting, Searching, Dynamic Programming', '#9b59b6', '⚡'),
('Web Development', 'Computer Science', 'HTML, CSS, JavaScript, Frameworks', '#3498db', '🌐'),
('Database Systems', 'Computer Science', 'SQL, NoSQL, Design Principles', '#2ecc71', '🗄️'),
('Operating Systems', 'Computer Science', 'Process Management, Memory, File Systems', '#f39c12', '💻'),
('Computer Networks', 'Computer Science', 'Protocols, Security, Architecture', '#34495e', '🌐'),
('Software Engineering', 'Computer Science', 'Design Patterns, Testing, Project Management', '#16a085', '⚙️'),
('Machine Learning', 'Computer Science', 'AI, Deep Learning, Neural Networks', '#8e44ad', '🤖'),

-- Mathematics
('Calculus', 'Mathematics', 'Differential and Integral Calculus', '#e67e22', '📐'),
('Linear Algebra', 'Mathematics', 'Matrices, Vectors, Eigenvalues', '#27ae60', '📊'),
('Statistics', 'Mathematics', 'Probability, Distributions, Hypothesis Testing', '#2980b9', '📈'),
('Discrete Mathematics', 'Mathematics', 'Logic, Sets, Graph Theory', '#c0392b', '🔢'),
('Number Theory', 'Mathematics', 'Prime Numbers, Modular Arithmetic', '#7f8c8d', '🔤'),

-- Physics
('Mechanics', 'Physics', 'Classical Mechanics, Motion, Forces', '#e74c3c', '⚙️'),
('Thermodynamics', 'Physics', 'Heat, Energy, Entropy', '#f39c12', '🔥'),
('Electromagnetism', 'Physics', 'Electric and Magnetic Fields', '#3498db', '⚡'),
('Quantum Physics', 'Physics', 'Quantum Mechanics, Wave Functions', '#9b59b6', '🌊'),
('Optics', 'Physics', 'Light, Lenses, Wave Properties', '#f1c40f', '🔍'),

-- Chemistry
('Organic Chemistry', 'Chemistry', 'Carbon Compounds, Reactions', '#27ae60', '🧪'),
('Inorganic Chemistry', 'Chemistry', 'Elements, Compounds, Periodic Table', '#e67e22', '⚗️'),
('Physical Chemistry', 'Chemistry', 'Chemical Kinetics, Thermodynamics', '#3498db', '🔬'),
('Analytical Chemistry', 'Chemistry', 'Qualitative and Quantitative Analysis', '#8e44ad', '📊'),

-- Biology
('Cell Biology', 'Biology', 'Cell Structure, Function, Division', '#2ecc71', '🔬'),
('Genetics', 'Biology', 'DNA, RNA, Heredity', '#e74c3c', '🧬'),
('Ecology', 'Biology', 'Ecosystems, Environment', '#27ae60', '🌿'),
('Human Anatomy', 'Biology', 'Body Systems, Organs', '#f39c12', '🫀'),
('Microbiology', 'Biology', 'Bacteria, Viruses, Microorganisms', '#9b59b6', '🦠');

-- Insert common tags
INSERT INTO tags (name) VALUES
('exam-prep'), ('tutorial'), ('cheat-sheet'), ('comprehensive'),
('beginner-friendly'), ('advanced'), ('practical'), ('theory'),
('examples'), ('exercises'), ('solutions'), ('quick-reference'),
('diagrams'), ('formulas'), ('concepts'), ('applications');

COMMIT;