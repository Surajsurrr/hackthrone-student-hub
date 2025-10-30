<?php
require_once 'includes/student_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Internships - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .jobs-grid {
            display: grid;
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .job-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid #667eea;
            transition: all 0.3s;
        }

        .job-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 1rem;
        }

        .job-title {
            font-size: 1.5rem;
            color: #0f172a;
            margin: 0 0 0.5rem 0;
        }

        .company-name {
            color: #64748b;
            font-size: 1rem;
            margin: 0;
        }

        .job-badges {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
        }

        .badge-internship { background: linear-gradient(135deg, #667eea, #764ba2); }
        .badge-full-time { background: linear-gradient(135deg, #10b981, #059669); }
        .badge-part-time { background: linear-gradient(135deg, #f59e0b, #d97706); }
        .badge-contract { background: linear-gradient(135deg, #ef4444, #dc2626); }

        .salary-badge {
            color: #10b981;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .job-description {
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .job-requirements {
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .job-requirements h4 {
            margin: 0 0 0.5rem 0;
            color: #475569;
            font-size: 0.9rem;
        }

        .job-requirements p {
            margin: 0;
            color: #64748b;
            line-height: 1.6;
        }

        .job-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .posted-date {
            color: #64748b;
            font-size: 0.875rem;
        }

        .apply-btn {
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .apply-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .apply-btn:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            overflow-y: auto;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h2 {
            margin: 0;
            color: #0f172a;
        }

        .close-btn {
            font-size: 2rem;
            color: #64748b;
            cursor: pointer;
            border: none;
            background: none;
            line-height: 1;
        }

        .close-btn:hover {
            color: #0f172a;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #475569;
            font-weight: 600;
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 0.75rem;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            background: #f8fafc;
            cursor: pointer;
        }

        .form-group textarea {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            font-size: 1rem;
            resize: vertical;
            min-height: 120px;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .alert {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #10b981;
        }

        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #ef4444;
        }

        .loading-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #64748b;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container">
        <h2>💼 Available Internships & Jobs</h2>
        <div id="internships-list" class="jobs-grid">
            <div class="loading-state">
                <p style="font-size: 1.2rem;">⏳ Loading available positions...</p>
            </div>
        </div>
    </div>

    <!-- Application Modal -->
    <div id="applicationModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📝 Apply for Position</h2>
                <button class="close-btn" onclick="closeApplicationModal()">&times;</button>
            </div>
            <div id="applicationAlert"></div>
            <form id="applicationForm" enctype="multipart/form-data">
                <input type="hidden" id="jobId" name="job_id">
                
                <div id="jobDetails" style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem;">
                    <!-- Job details will be inserted here -->
                </div>

                <div class="form-group">
                    <label for="resume">Resume (PDF, DOC, DOCX) *</label>
                    <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                    <small style="color: #64748b; display: block; margin-top: 0.5rem;">
                        Upload your latest resume (Max 5MB)
                    </small>
                </div>

                <div class="form-group">
                    <label for="coverLetter">Cover Letter (Optional)</label>
                    <textarea id="coverLetter" name="cover_letter" placeholder="Tell us why you're a great fit for this position..."></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    🚀 Submit Application
                </button>
            </form>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        let currentJobId = null;

        // Load jobs on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadJobs();
        });

        async function loadJobs() {
            try {
                const response = await fetch('../api/student/getAvailableJobs.php');
                const data = await response.json();

                if (data.success && data.jobs && data.jobs.length > 0) {
                    displayJobs(data.jobs);
                } else {
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading jobs:', error);
                document.getElementById('internships-list').innerHTML = `
                    <div class="empty-state">
                        <p style="font-size: 1.2rem; color: #ef4444;">❌ Failed to load jobs. Please try again later.</p>
                    </div>
                `;
            }
        }

        function displayJobs(jobs) {
            const container = document.getElementById('internships-list');
            
            const jobsHTML = jobs.map(job => {
                const typeColors = {
                    'internship': 'badge-internship',
                    'full-time': 'badge-full-time',
                    'part-time': 'badge-part-time',
                    'contract': 'badge-contract'
                };

                const badgeClass = typeColors[job.type] || 'badge-internship';
                const formattedDate = new Date(job.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                return `
                    <div class="job-card">
                        <div class="job-header">
                            <div>
                                <h3 class="job-title">${escapeHtml(job.title)}</h3>
                                <p class="company-name">🏢 ${escapeHtml(job.company_name)}</p>
                            </div>
                        </div>

                        <div class="job-badges">
                            <span class="badge ${badgeClass}">
                                ${job.type.replace('-', ' ')}
                            </span>
                            ${job.salary ? `<span class="salary-badge">💰 ${escapeHtml(job.salary)}</span>` : ''}
                        </div>

                        <div class="job-description">
                            <strong style="color: #475569;">About the Role:</strong><br>
                            ${escapeHtml(job.description)}
                        </div>

                        <div class="job-requirements">
                            <h4>📋 Requirements:</h4>
                            <p>${escapeHtml(job.requirements)}</p>
                        </div>

                        <div class="job-footer">
                            <span class="posted-date">📅 Posted ${formattedDate}</span>
                            <button class="apply-btn" onclick="openApplicationModal(${job.id}, '${escapeHtml(job.title)}', '${escapeHtml(job.company_name)}')">
                                ✉️ Apply Now
                            </button>
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = jobsHTML;
        }

        function showEmptyState() {
            document.getElementById('internships-list').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">💼</div>
                    <p style="font-size: 1.2rem;">No job openings available at the moment</p>
                    <p>Check back later for new opportunities!</p>
                </div>
            `;
        }

        function openApplicationModal(jobId, jobTitle, companyName) {
            currentJobId = jobId;
            document.getElementById('jobId').value = jobId;
            document.getElementById('jobDetails').innerHTML = `
                <strong style="color: #0f172a; font-size: 1.1rem;">${escapeHtml(jobTitle)}</strong><br>
                <span style="color: #64748b;">at ${escapeHtml(companyName)}</span>
            `;
            document.getElementById('applicationModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closeApplicationModal() {
            document.getElementById('applicationModal').style.display = 'none';
            document.body.style.overflow = 'auto';
            document.getElementById('applicationForm').reset();
            document.getElementById('applicationAlert').innerHTML = '';
        }

        // Handle form submission
        document.getElementById('applicationForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = e.target.querySelector('.submit-btn');
            const alertDiv = document.getElementById('applicationAlert');
            
            // Disable submit button
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Submitting...';
            alertDiv.innerHTML = '';

            const formData = new FormData(this);

            try {
                const response = await fetch('../api/student/applyForJob.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.success) {
                    alertDiv.innerHTML = `
                        <div class="alert alert-success">
                            ✅ ${result.message || 'Application submitted successfully!'}
                        </div>
                    `;
                    
                    // Reset form and close modal after 2 seconds
                    setTimeout(() => {
                        closeApplicationModal();
                        loadJobs(); // Reload jobs
                    }, 2000);
                } else {
                    alertDiv.innerHTML = `
                        <div class="alert alert-error">
                            ❌ ${result.error || 'Failed to submit application'}
                        </div>
                    `;
                    submitBtn.disabled = false;
                    submitBtn.textContent = '🚀 Submit Application';
                }
            } catch (error) {
                console.error('Error submitting application:', error);
                alertDiv.innerHTML = `
                    <div class="alert alert-error">
                        ❌ An error occurred while submitting your application
                    </div>
                `;
                submitBtn.disabled = false;
                submitBtn.textContent = '🚀 Submit Application';
            }
        });

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('applicationModal');
            if (event.target === modal) {
                closeApplicationModal();
            }
        };
    </script>
</body>
</html>
