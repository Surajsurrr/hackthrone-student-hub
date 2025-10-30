<?php
require_once 'includes/company_auth.php';
$user = getCurrentUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="dashboard-container">
        <?php include 'includes/sidebar.php'; ?>

        <main class="main-content">
            <section class="dashboard-section active">
                <h2>Job Applications</h2>
                <div class="applications-management">
                    <!-- Filter Options -->
                    <div class="applications-filters" style="margin-bottom: 2rem;">
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <button class="filter-btn active" data-status="all" style="padding: 0.75rem 1.5rem; border: none; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                All Applications
                            </button>
                            <button class="filter-btn" data-status="pending" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Pending
                            </button>
                            <button class="filter-btn" data-status="reviewing" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Reviewing
                            </button>
                            <button class="filter-btn" data-status="accepted" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Accepted
                            </button>
                            <button class="filter-btn" data-status="rejected" style="padding: 0.75rem 1.5rem; border: 2px solid #cbd5e1; background: white; color: #64748b; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s;">
                                Rejected
                            </button>
                        </div>
                    </div>

                    <!-- Applications Stats -->
                    <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                        <div class="stat-card" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Total Applications</h3>
                            <p id="total-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b, #d97706); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Pending Review</h3>
                            <p id="pending-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Accepted</h3>
                            <p id="accepted-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                        <div class="stat-card" style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                            <h3 style="margin: 0 0 0.5rem 0; font-size: 0.9rem; opacity: 0.9;">Rejected</h3>
                            <p id="rejected-applications" style="font-size: 2rem; font-weight: 700; margin: 0;">0</p>
                        </div>
                    </div>

                    <!-- Applications List -->
                    <div class="applications-list" style="background: white; border-radius: 12px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                        <h3 style="margin: 0 0 1.5rem 0; color: #0f172a;">Recent Applications</h3>
                        <div id="applications-list">
                            <!-- Applications will be loaded here dynamically -->
                            <div style="text-align: center; padding: 3rem; color: #64748b;">
                                <p style="font-size: 1.1rem; margin: 0;">📋 No applications yet</p>
                                <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Applications from students will appear here</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        // Applications Section JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            loadApplicationsData();
            
            // Filter buttons functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => {
                        btn.classList.remove('active');
                        btn.style.background = 'white';
                        btn.style.color = '#64748b';
                        btn.style.border = '2px solid #cbd5e1';
                    });
                    
                    // Add active class to clicked button
                    this.classList.add('active');
                    this.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
                    this.style.color = 'white';
                    this.style.border = 'none';
                    
                    const status = this.getAttribute('data-status');
                    filterApplications(status);
                });
            });
        });
        
        async function loadApplicationsData() {
            // Sample data - replace with actual API call
            const sampleApplications = [
                {
                    id: 1,
                    student_name: 'John Doe',
                    job_title: 'Software Engineering Intern',
                    status: 'pending',
                    applied_date: '2025-10-28',
                    email: 'john.doe@example.com',
                    resume: 'resume.pdf'
                }
            ];
            
            document.getElementById('total-applications').textContent = sampleApplications.length;
            document.getElementById('pending-applications').textContent = sampleApplications.filter(a => a.status === 'pending').length;
            document.getElementById('accepted-applications').textContent = sampleApplications.filter(a => a.status === 'accepted').length;
            document.getElementById('rejected-applications').textContent = sampleApplications.filter(a => a.status === 'rejected').length;
            
            displayApplications(sampleApplications);
        }
        
        function displayApplications(applications) {
            const applicationsListDiv = document.getElementById('applications-list');
            
            if (applications.length === 0) {
                applicationsListDiv.innerHTML = `
                    <div style="text-align: center; padding: 3rem; color: #64748b;">
                        <p style="font-size: 1.1rem; margin: 0;">📋 No applications yet</p>
                        <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Applications from students will appear here</p>
                    </div>
                `;
                return;
            }
            
            const statusColors = {
                pending: { bg: '#fef3c7', text: '#92400e', border: '#fbbf24' },
                reviewing: { bg: '#dbeafe', text: '#1e3a8a', border: '#3b82f6' },
                accepted: { bg: '#dcfce7', text: '#065f46', border: '#10b981' },
                rejected: { bg: '#fee2e2', text: '#991b1b', border: '#ef4444' }
            };
            
            applicationsListDiv.innerHTML = applications.map(app => `
                <div class="application-card" data-status="${app.status}" style="background: white; border: 2px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem;">
                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                        <div>
                            <h4 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size: 1.1rem;">${app.student_name}</h4>
                            <p style="margin: 0; color: #64748b; font-size: 0.9rem;">Applied for: <strong>${app.job_title}</strong></p>
                        </div>
                        <span style="padding: 0.5rem 1rem; background: ${statusColors[app.status].bg}; color: ${statusColors[app.status].text}; border: 1px solid ${statusColors[app.status].border}; border-radius: 20px; font-size: 0.85rem; font-weight: 600; text-transform: capitalize;">
                            ${app.status}
                        </span>
                    </div>
                    <div style="display: flex; gap: 2rem; margin-bottom: 1rem; color: #64748b; font-size: 0.9rem;">
                        <div>📧 ${app.email}</div>
                        <div>📅 ${new Date(app.applied_date).toLocaleDateString()}</div>
                    </div>
                    <div style="display: flex; gap: 1rem;">
                        <button onclick="alert('View details')" style="padding: 0.5rem 1rem; background: linear-gradient(135deg, #667eea, #764ba2); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                            View Details
                        </button>
                        <button onclick="alert('Download resume')" style="padding: 0.5rem 1rem; background: #f1f5f9; color: #1e293b; border: 2px solid #cbd5e1; border-radius: 8px; cursor: pointer; font-weight: 600;">
                            📄 Resume
                        </button>
                    </div>
                </div>
            `).join('');
        }
        
        function filterApplications(status) {
            const applicationCards = document.querySelectorAll('.application-card');
            applicationCards.forEach(card => {
                if (status === 'all' || card.getAttribute('data-status') === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // NEW: Load actual applications from database
        document.addEventListener('DOMContentLoaded', function() {
            loadActualApplications();
        });

        async function loadActualApplications() {
            try {
                const response = await fetch('../api/company/getApplications.php');
                const data = await response.json();

                if (data.success) {
                    // Update stats
                    document.getElementById('total-applications').textContent = data.stats.total;
                    document.getElementById('pending-applications').textContent = data.stats.pending;
                    document.getElementById('accepted-applications').textContent = data.stats.accepted;
                    document.getElementById('rejected-applications').textContent = data.stats.rejected;

                    // Display applications
                    displayRealApplications(data.applications);
                } else {
                    console.error('Failed to load applications:', data.error);
                }
            } catch (error) {
                console.error('Error loading applications:', error);
            }
        }

        function displayRealApplications(applications) {
            const container = document.getElementById('applications-list');
            
            if (!applications || applications.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 3rem; color: #64748b;">
                        <p style="font-size: 1.1rem; margin: 0;">📋 No applications received yet</p>
                        <p style="font-size: 0.9rem; margin: 0.5rem 0 0 0;">Student applications will appear here</p>
                    </div>
                `;
                return;
            }

            const statusColors = {
                'pending': { bg: '#fef3c7', color: '#92400e', border: '#fbbf24' },
                'reviewing': { bg: '#dbeafe', color: '#1e3a8a', border: '#3b82f6' },
                'accepted': { bg: '#d1fae5', color: '#065f46', border: '#10b981' },
                'rejected': { bg: '#fee2e2', color: '#991b1b', border: '#ef4444' }
            };

            const applicationsHTML = applications.map(app => {
                const statusStyle = statusColors[app.status.toLowerCase()] || statusColors['pending'];
                const formattedDate = new Date(app.applied_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <div class="application-card" data-status="${app.status.toLowerCase()}" style="background: white; border-radius: 12px; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 4px solid ${statusStyle.border};">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                            <div style="flex: 1;">
                                <h3 style="margin: 0 0 0.5rem 0; color: #0f172a; font-size: 1.25rem;">👤 ${escapeHtml(app.student_name)}</h3>
                                <p style="margin: 0 0 0.5rem 0; color: #64748b;">Applied for: <strong style="color: #667eea;">${escapeHtml(app.job_title)}</strong></p>
                                <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                                    <span style="color: #64748b; font-size: 0.875rem;">📧 ${escapeHtml(app.student_email)}</span>
                                    <span style="color: #64748b; font-size: 0.875rem;">📅 ${formattedDate}</span>
                                </div>
                            </div>
                            <span style="background: ${statusStyle.bg}; color: ${statusStyle.color}; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; font-size: 0.875rem; text-transform: capitalize; border: 1px solid ${statusStyle.border};">
                                ${app.status}
                            </span>
                        </div>

                        ${app.cover_letter ? `
                        <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                            <h4 style="margin: 0 0 0.5rem 0; color: #475569; font-size: 0.9rem;">📝 Cover Letter:</h4>
                            <p style="margin: 0; color: #64748b; line-height: 1.6;">${escapeHtml(app.cover_letter)}</p>
                        </div>
                        ` : ''}

                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <a href="../student/${app.resume_path}" target="_blank" style="padding: 0.5rem 1rem; background: #f1f5f9; color: #1e293b; border: 2px solid #cbd5e1; border-radius: 8px; text-decoration: none; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                📄 View Resume
                            </a>
                            ${app.status.toLowerCase() !== 'accepted' && app.status.toLowerCase() !== 'rejected' ? `
                            <button onclick="updateApplicationStatus(${app.id}, 'reviewing')" style="padding: 0.5rem 1rem; background: #dbeafe; color: #1e40af; border: 2px solid #3b82f6; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                👀 Review
                            </button>
                            <button onclick="updateApplicationStatus(${app.id}, 'accepted')" style="padding: 0.5rem 1rem; background: #d1fae5; color: #065f46; border: 2px solid #10b981; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                ✅ Accept
                            </button>
                            <button onclick="updateApplicationStatus(${app.id}, 'rejected')" style="padding: 0.5rem 1rem; background: #fee2e2; color: #991b1b; border: 2px solid #ef4444; border-radius: 8px; font-weight: 600; cursor: pointer;">
                                ❌ Reject
                            </button>
                            ` : ''}
                        </div>
                    </div>
                `;
            }).join('');

            container.innerHTML = applicationsHTML;
        }

        async function updateApplicationStatus(applicationId, newStatus) {
            if (!confirm(`Are you sure you want to mark this application as ${newStatus}?`)) {
                return;
            }

            try {
                const response = await fetch('../api/company/updateApplicationStatus.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        application_id: applicationId,
                        status: newStatus
                    })
                });

                const result = await response.json();

                if (result.success) {
                    alert(`✅ Application status updated to ${newStatus}!`);
                    loadActualApplications(); // Reload applications
                } else {
                    alert('❌ ' + (result.error || 'Failed to update status'));
                }
            } catch (error) {
                console.error('Error updating status:', error);
                alert('❌ An error occurred while updating the application status');
            }
        }

        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    </script>
</body>
</html>
