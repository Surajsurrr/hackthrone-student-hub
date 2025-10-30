<?php
require_once 'includes/company_auth.php';
$user = getCurrentUser();
$company = getCompanyProfile($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Jobs - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <section class="dashboard-section active">
                <h2>Manage Job Postings</h2>
                <div class="jobs-management">
                    <div class="create-job">
                        <h3>Post New Job</h3>
                        <form id="create-job-form">
                            <div class="form-group">
                                <label for="job-title">Job Title:</label>
                                <input type="text" id="job-title" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500; width: 100%;">
                            </div>
                            <div class="form-group">
                                <label for="job-description">Description:</label>
                                <textarea id="job-description" rows="5" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500; width: 100%; resize: vertical; min-height: 120px;"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="job-requirements">Requirements:</label>
                                <textarea id="job-requirements" rows="5" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500; width: 100%; resize: vertical; min-height: 120px;"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="job-salary">Salary (optional):</label>
                                <input type="text" id="job-salary" placeholder="e.g., $5000/month" style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500; width: 100%;">
                            </div>
                            <div class="form-group">
                                <label for="job-type">Job Type:</label>
                                <select id="job-type" required style="padding: 0.75rem; border: 2px solid #cbd5e1; border-radius: 8px; font-size: 1rem; background: white; color: #0f172a; font-weight: 500;">
                                    <option value="internship">Internship</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                </select>
                            </div>
                            <button type="submit" class="btn" style="padding: 0.875rem 1.5rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 10px; font-weight: 600; cursor: pointer;">Post Job</button>
                        </form>
                    </div>
                    <div class="jobs-list">
                        <h3>Your Job Postings</h3>
                        <div id="jobs-list">
                            <!-- Jobs will be loaded here -->
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        // Load jobs when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadJobs();
        });

        // Handle job form submission
        document.getElementById('create-job-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const jobData = {
                title: document.getElementById('job-title').value.trim(),
                description: document.getElementById('job-description').value.trim(),
                requirements: document.getElementById('job-requirements').value.trim(),
                salary: document.getElementById('job-salary').value.trim(),
                type: document.getElementById('job-type').value
            };

            // Basic validation
            if (!jobData.title || !jobData.description || !jobData.requirements) {
                alert('⚠️ Please fill in all required fields');
                return;
            }

            try {
                const response = await fetch('../api/company/createJob.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jobData)
                });

                const result = await response.json();

                if (result.success || response.ok) {
                    alert('✅ Job posted successfully!');
                    // Clear form
                    document.getElementById('create-job-form').reset();
                    // Reload jobs list
                    loadJobs();
                } else {
                    alert('❌ ' + (result.error || result.message || 'Failed to post job'));
                }
            } catch (error) {
                console.error('Error posting job:', error);
                alert('❌ An error occurred while posting the job');
            }
        });

        // Load and display jobs
        async function loadJobs() {
            try {
                const response = await fetch('../api/company/getJobs.php');
                const data = await response.json();

                if (data.jobs && Array.isArray(data.jobs)) {
                    displayJobs(data.jobs);
                } else {
                    document.getElementById('jobs-list').innerHTML = '<p style="text-align: center; color: #64748b; padding: 2rem;">No jobs posted yet</p>';
                }
            } catch (error) {
                console.error('Error loading jobs:', error);
                document.getElementById('jobs-list').innerHTML = '<p style="text-align: center; color: #ef4444; padding: 2rem;">Failed to load jobs</p>';
            }
        }

        // Display jobs in the list
        function displayJobs(jobs) {
            const jobsList = document.getElementById('jobs-list');
            
            if (jobs.length === 0) {
                jobsList.innerHTML = '<p style="text-align: center; color: #64748b; padding: 2rem;">📝 No jobs posted yet</p>';
                return;
            }

            jobsList.innerHTML = jobs.map(job => {
                const typeColors = {
                    'internship': 'background: linear-gradient(135deg, #667eea, #764ba2);',
                    'full-time': 'background: linear-gradient(135deg, #10b981, #059669);',
                    'part-time': 'background: linear-gradient(135deg, #f59e0b, #d97706);',
                    'contract': 'background: linear-gradient(135deg, #ef4444, #dc2626);'
                };

                const typeColor = typeColors[job.type] || typeColors['internship'];
                const formattedDate = new Date(job.created_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                });

                return `
                    <div class="job-card" style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid #667eea;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div style="flex: 1;">
                                <h4 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size: 1.25rem;">${job.title}</h4>
                                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                                    <span style="${typeColor} color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">
                                        ${job.type.replace('-', ' ')}
                                    </span>
                                    ${job.salary ? `<span style="color: #10b981; font-weight: 600; font-size: 0.9rem;">💰 ${job.salary}</span>` : ''}
                                    <span style="color: #64748b; font-size: 0.875rem;">📅 ${formattedDate}</span>
                                </div>
                            </div>
                            <button onclick="deleteJob(${job.id})" style="background: #fee2e2; color: #dc2626; border: none; padding: 0.5rem 1rem; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;" onmouseover="this.style.background='#fecaca'" onmouseout="this.style.background='#fee2e2'">
                                🗑️ Delete
                            </button>
                        </div>
                        
                        <div style="margin-bottom: 1rem;">
                            <h5 style="margin: 0 0 0.5rem 0; color: #475569; font-size: 0.9rem; font-weight: 600;">Description:</h5>
                            <p style="margin: 0; color: #64748b; line-height: 1.6;">${job.description}</p>
                        </div>
                        
                        <div>
                            <h5 style="margin: 0 0 0.5rem 0; color: #475569; font-size: 0.9rem; font-weight: 600;">Requirements:</h5>
                            <p style="margin: 0; color: #64748b; line-height: 1.6;">${job.requirements}</p>
                        </div>
                        
                        <div style="margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                            <span style="color: #64748b; font-size: 0.875rem;">Status: <span style="color: #10b981; font-weight: 600;">${job.status || 'Active'}</span></span>
                            <span style="color: #64748b; font-size: 0.875rem;">Applications: <span style="color: #667eea; font-weight: 600;">0</span></span>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Delete job function
        async function deleteJob(jobId) {
            if (!confirm('Are you sure you want to delete this job posting?')) {
                return;
            }

            try {
                const response = await fetch('../api/company/deleteJob.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ job_id: jobId })
                });

                const result = await response.json();

                if (result.success || response.ok) {
                    alert('✅ Job deleted successfully!');
                    loadJobs();
                } else {
                    alert('❌ ' + (result.error || result.message || 'Failed to delete job'));
                }
            } catch (error) {
                console.error('Error deleting job:', error);
                alert('❌ An error occurred while deleting the job');
            }
        }
    </script>
</body>
</html>
