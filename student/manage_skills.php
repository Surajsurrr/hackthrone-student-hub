<?php
require_once '../includes/session.php';
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and has student role
if (!isLoggedIn() || !hasRole('student')) {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Skills - Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .skills-management-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: var(--text-primary);
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: var(--text-secondary);
            font-size: 1rem;
        }

        .skills-actions {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .add-skill-form {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            margin-bottom: 2rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select {
            padding: 0.75rem;
            background: var(--bg-secondary);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .skills-list {
            display: grid;
            gap: 1rem;
        }

        .skill-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: transform 0.2s;
        }

        .skill-card:hover {
            transform: translateY(-2px);
            border-color: var(--primary-color);
        }

        .skill-info {
            flex: 1;
        }

        .skill-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .skill-details {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .skill-level {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        .level-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .level-beginner {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        .level-intermediate {
            background: rgba(33, 150, 243, 0.2);
            color: #2196f3;
        }

        .level-advanced {
            background: rgba(76, 175, 80, 0.2);
            color: #4caf50;
        }

        .level-expert {
            background: rgba(156, 39, 176, 0.2);
            color: #9c27b0;
        }

        .endorsement-count {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-secondary);
        }

        .endorsement-count span {
            color: var(--primary-color);
            font-weight: 600;
        }

        .skill-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: var(--bg-secondary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }

        .btn-secondary:hover {
            background: var(--card-hover);
        }

        .btn-danger {
            background: rgba(244, 67, 54, 0.2);
            color: #f44336;
        }

        .btn-danger:hover {
            background: rgba(244, 67, 54, 0.3);
        }

        .btn-icon {
            margin-right: 0.5rem;
        }

        .notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1000;
            animation: slideIn 0.3s ease;
        }

        .notification.success {
            background: #4caf50;
        }

        .notification.error {
            background: #f44336;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        /* Edit Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 12px;
            max-width: 500px;
            width: 90%;
            border: 1px solid var(--border-color);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h2 {
            color: var(--text-primary);
            font-size: 1.5rem;
        }

        .close-modal {
            background: none;
            border: none;
            color: var(--text-secondary);
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            color: var(--text-primary);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="skills-management-container">
        <div class="page-header">
            <h1>🎯 Manage Your Skills</h1>
            <p>Add, edit, and showcase your professional skills</p>
        </div>

        <!-- Add New Skill Form -->
        <div class="add-skill-form">
            <h2 style="color: var(--text-primary); margin-bottom: 1.5rem;">➕ Add New Skill</h2>
            <form id="add-skill-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="skill-name">Skill Name *</label>
                        <input type="text" id="skill-name" name="skill_name" placeholder="e.g., JavaScript" required>
                    </div>
                    <div class="form-group">
                        <label for="skill-level">Proficiency Level *</label>
                        <select id="skill-level" name="skill_level" required>
                            <option value="">Select level...</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <span class="btn-icon">✨</span>
                    Add Skill
                </button>
            </form>
        </div>

        <!-- Skills List -->
        <div class="content-card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="color: var(--text-primary);">Your Skills</h3>
                <span id="skills-count" style="color: var(--text-secondary);">Loading...</span>
            </div>
            <div class="skills-list" id="skills-list">
                <!-- Skills will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Edit Skill Modal -->
    <div class="modal" id="edit-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Skill</h2>
                <button class="close-modal" onclick="closeEditModal()">×</button>
            </div>
            <form id="edit-skill-form">
                <input type="hidden" id="edit-skill-id">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="edit-skill-name">Skill Name *</label>
                    <input type="text" id="edit-skill-name" name="skill_name" required>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="edit-skill-level">Proficiency Level *</label>
                    <select id="edit-skill-level" name="skill_level" required>
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="expert">Expert</option>
                    </select>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        Save Changes
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load skills on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadSkills();
        });

        // Add new skill
        document.getElementById('add-skill-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('skill_name', document.getElementById('skill-name').value);
            formData.append('skill_level', document.getElementById('skill-level').value);

            try {
                const response = await fetch('../api/student/addSkill.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('Skill added successfully!', 'success');
                    this.reset();
                    loadSkills();
                } else {
                    showNotification(result.message || 'Failed to add skill', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        });

        // Load all skills
        async function loadSkills() {
            try {
                const response = await fetch('../api/student/getSkills.php');
                const result = await response.json();

                if (result.success) {
                    displaySkills(result.skills);
                    document.getElementById('skills-count').textContent = `${result.skills.length} skills`;
                } else {
                    document.getElementById('skills-list').innerHTML = `
                        <div class="empty-state">
                            <div class="empty-state-icon">🎯</div>
                            <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">No skills added yet</h3>
                            <p>Add your first skill to get started!</p>
                        </div>
                    `;
                    document.getElementById('skills-count').textContent = '0 skills';
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Failed to load skills', 'error');
            }
        }

        // Display skills
        function displaySkills(skills) {
            const skillsList = document.getElementById('skills-list');
            
            if (skills.length === 0) {
                skillsList.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🎯</div>
                        <h3 style="color: var(--text-primary); margin-bottom: 0.5rem;">No skills added yet</h3>
                        <p>Add your first skill to get started!</p>
                    </div>
                `;
                return;
            }

            skillsList.innerHTML = skills.map(skill => `
                <div class="skill-card">
                    <div class="skill-info">
                        <div class="skill-name">${escapeHtml(skill.skill_name)}</div>
                        <div class="skill-details">
                            <div class="skill-level">
                                <span class="level-badge level-${skill.skill_level}">${capitalizeFirst(skill.skill_level)}</span>
                            </div>
                            <div class="endorsement-count">
                                🌟 <span>${skill.endorsement_count || 0}</span> endorsements
                            </div>
                        </div>
                    </div>
                    <div class="skill-actions">
                        <button class="btn btn-secondary" onclick="editSkill(${skill.id}, '${escapeHtml(skill.skill_name)}', '${skill.skill_level}')">
                            ✏️ Edit
                        </button>
                        <button class="btn btn-danger" onclick="deleteSkill(${skill.id})">
                            🗑️ Delete
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Edit skill
        function editSkill(id, name, level) {
            document.getElementById('edit-skill-id').value = id;
            document.getElementById('edit-skill-name').value = name;
            document.getElementById('edit-skill-level').value = level;
            document.getElementById('edit-modal').classList.add('active');
        }

        // Close edit modal
        function closeEditModal() {
            document.getElementById('edit-modal').classList.remove('active');
        }

        // Submit edit form
        document.getElementById('edit-skill-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('skill_id', document.getElementById('edit-skill-id').value);
            formData.append('skill_name', document.getElementById('edit-skill-name').value);
            formData.append('skill_level', document.getElementById('edit-skill-level').value);

            try {
                const response = await fetch('../api/student/updateSkill.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('Skill updated successfully!', 'success');
                    closeEditModal();
                    loadSkills();
                } else {
                    showNotification(result.message || 'Failed to update skill', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        });

        // Delete skill
        async function deleteSkill(id) {
            if (!confirm('Are you sure you want to delete this skill?')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('skill_id', id);

                const response = await fetch('../api/student/deleteSkill.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    showNotification('Skill deleted successfully!', 'success');
                    loadSkills();
                } else {
                    showNotification(result.message || 'Failed to delete skill', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('An error occurred', 'error');
            }
        }

        // Show notification
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Utility functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    </script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
