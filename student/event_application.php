<?php
require_once 'includes/student_auth.php';
$user = getCurrentUser();
$student = getStudentProfile($user['id']);

// Get event ID from URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

if (!$event_id) {
    header('Location: hackathons.php');
    exit;
}

// Fetch event details
require_once '../includes/db_connect.php';
$stmt = $pdo->prepare("
    SELECT e.*, c.name as college_name, c.location as college_location, c.logo as college_logo
    FROM events e
    INNER JOIN colleges c ON e.college_id = c.id
    WHERE e.id = ? AND e.status = 'active'
");
$stmt->execute([$event_id]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    header('Location: hackathons.php');
    exit;
}

$eventDate = new DateTime($event['date']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for <?php echo htmlspecialchars($event['title']); ?> - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent1: #7c3aed;
            --accent2: #3b82f6;
        }

        .application-container {
            max-width: 900px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .event-header {
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: 20px;
            padding: 2rem;
            color: white;
            margin-bottom: 2rem;
        }

        .event-header h1 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .event-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .application-form {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .form-header {
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }

        .form-header h2 {
            color: #1e293b;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-group label .required {
            color: #ef4444;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Inter', sans-serif;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .submit-btn {
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .cancel-btn {
            background: #94a3b8;
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: transform 0.2s;
            margin-top: 1rem;
        }

        .cancel-btn:hover {
            background: #64748b;
            transform: translateY(-2px);
        }

        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            display: none;
        }

        .message.success {
            background: #d1fae5;
            color: #065f46;
        }

        .message.error {
            background: #fee2e2;
            color: #dc2626;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="application-container">
        <div class="event-header">
            <h1>📝 Apply for <?php echo htmlspecialchars($event['title']); ?></h1>
            <div class="event-info">
                <div class="info-item">
                    <span>🏫</span>
                    <span><?php echo htmlspecialchars($event['college_name']); ?></span>
                </div>
                <div class="info-item">
                    <span>📅</span>
                    <span><?php echo $eventDate->format('M d, Y'); ?></span>
                </div>
                <div class="info-item">
                    <span>📍</span>
                    <span><?php echo htmlspecialchars($event['location'] ?: 'TBA'); ?></span>
                </div>
                <div class="info-item">
                    <span>🏆</span>
                    <span><?php echo ucwords(str_replace('-', ' ', $event['type'])); ?></span>
                </div>
            </div>
        </div>

        <div class="application-form">
            <div class="form-header">
                <h2>Application Form</h2>
                <p style="color: #64748b;">Please fill in all required fields to complete your application</p>
            </div>

            <div id="message" class="message"></div>

            <form id="applicationForm">
                <input type="hidden" id="event_id" value="<?php echo $event_id; ?>">

                <div class="form-row">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" id="full_name" value="<?php echo htmlspecialchars($student['name'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Email <span class="required">*</span></label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <input type="tel" id="phone" placeholder="+91 XXXXX XXXXX" required>
                    </div>
                    <div class="form-group">
                        <label>College/University <span class="required">*</span></label>
                        <input type="text" id="college" value="<?php echo htmlspecialchars($student['college'] ?? ''); ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Year of Study <span class="required">*</span></label>
                        <select id="year_of_study" required>
                            <option value="">Select Year</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                            <option value="Graduate">Graduate</option>
                            <option value="Post Graduate">Post Graduate</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Branch/Major <span class="required">*</span></label>
                        <input type="text" id="branch" placeholder="e.g., Computer Science" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Team Name (if participating as a team)</label>
                    <input type="text" id="team_name" placeholder="Leave empty for solo participation">
                </div>

                <div class="form-group">
                    <label>Team Size</label>
                    <select id="team_size">
                        <option value="1">Solo (1 member)</option>
                        <option value="2">2 members</option>
                        <option value="3">3 members</option>
                        <option value="4">4 members</option>
                        <option value="5">5 members</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Why do you want to participate? <span class="required">*</span></label>
                    <textarea id="motivation" placeholder="Tell us about your interest and goals..." required></textarea>
                </div>

                <div class="form-group">
                    <label>Previous Experience (if any)</label>
                    <textarea id="experience" placeholder="Share any relevant experience or projects..."></textarea>
                </div>

                <div class="form-group">
                    <label>Skills/Technologies</label>
                    <input type="text" id="skills" placeholder="e.g., Python, React, Machine Learning">
                </div>

                <div class="form-group">
                    <label>GitHub Profile (optional)</label>
                    <input type="text" id="github" placeholder="https://github.com/username">
                </div>

                <div class="form-group">
                    <label>LinkedIn Profile (optional)</label>
                    <input type="text" id="linkedin" placeholder="https://linkedin.com/in/username">
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    🚀 Submit Application
                </button>

                <button type="button" class="cancel-btn" onclick="window.location.href='hackathons.php'">
                    ← Back to Events
                </button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('applicationForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const messageDiv = document.getElementById('message');
            
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Submitting...';
            
            const formData = {
                event_id: document.getElementById('event_id').value,
                full_name: document.getElementById('full_name').value.trim(),
                email: document.getElementById('email').value.trim(),
                phone: document.getElementById('phone').value.trim(),
                college: document.getElementById('college').value.trim(),
                year_of_study: document.getElementById('year_of_study').value,
                branch: document.getElementById('branch').value.trim(),
                team_name: document.getElementById('team_name').value.trim(),
                team_size: document.getElementById('team_size').value,
                motivation: document.getElementById('motivation').value.trim(),
                experience: document.getElementById('experience').value.trim(),
                skills: document.getElementById('skills').value.trim(),
                github: document.getElementById('github').value.trim(),
                linkedin: document.getElementById('linkedin').value.trim()
            };
            
            try {
                const response = await fetch('../api/student/submitEventApplication.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    messageDiv.className = 'message success';
                    messageDiv.textContent = '✅ Application submitted successfully! Redirecting...';
                    messageDiv.style.display = 'block';
                    
                    setTimeout(() => {
                        window.location.href = 'hackathons.php';
                    }, 2000);
                } else {
                    throw new Error(data.error || 'Failed to submit application');
                }
            } catch (error) {
                messageDiv.className = 'message error';
                messageDiv.textContent = '❌ ' + error.message;
                messageDiv.style.display = 'block';
                
                submitBtn.disabled = false;
                submitBtn.textContent = '🚀 Submit Application';
            }
        });
    </script>
</body>
</html>
