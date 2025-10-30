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
    </script>
</body>
</html>
