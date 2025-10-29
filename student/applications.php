<?php
require_once 'includes/student_auth.php';
$user = getCurrentUser();
$student = getStudentProfile($user['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Applications - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent1: #7c3aed;
            --accent2: #3b82f6;
            --card-radius: 15px;
        }

        /* Fix text visibility for all elements */
        body, * {
            color: inherit;
        }

        /* Override dark theme text colors for better visibility */
        .applications-container,
        .applications-container *,
        .form-group,
        .form-group *,
        input,
        select,
        textarea,
        label,
        .filter-tab,
        .applications-table,
        .applications-table *,
        .add-application-form,
        .add-application-form *,
        .form-section,
        .form-section * {
            color: #1f2937 !important;
        }

        /* Form elements styling - more comprehensive */
        input[type="text"],
        input[type="url"],
        input[type="date"],
        input[type="email"],
        input[type="tel"],
        input[type="number"],
        select,
        textarea,
        input,
        .form-control {
            background: white !important;
            color: #1f2937 !important;
            border: 2px solid #d1d5db !important;
            padding: 0.75rem !important;
            border-radius: 8px !important;
            font-size: 0.9rem !important;
            font-weight: 500 !important;
        }

        /* Ensure placeholder text is visible */
        input::placeholder,
        textarea::placeholder,
        select::placeholder {
            color: #9ca3af !important;
            font-weight: 400 !important;
        }

        /* Form labels and text */
        .form-label,
        label,
        .field-label,
        h1, h2, h3, h4, h5, h6 {
            color: #e6eef6 !important;
            font-weight: 600 !important;
            margin-bottom: 0.5rem !important;
            display: block !important;
        }

        /* White background sections */
        .add-application-form,
        .form-section,
        .white-section {
            background: white !important;
            padding: 1.5rem !important;
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
        }

        .add-application-form *,
        .form-section *,
        .white-section * {
            color: #1f2937 !important;
        }

        .add-application-form h1,
        .add-application-form h2,
        .add-application-form h3,
        .form-section h1,
        .form-section h2,
        .form-section h3 {
            color: #111827 !important;
        }

        /* Button styling */
        .btn {
            padding: 0.75rem 1.5rem !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
        }

        .btn-primary {
            background: var(--accent1) !important;
            color: white !important;
            border: none !important;
        }

        .btn-primary:hover {
            background: #6d28d9 !important;
        }
        
        .applications-hero {
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: var(--card-radius);
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            color: white;
            text-align: center;
        }
        
        .applications-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        
        .applications-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid var(--accent1);
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent1);
            line-height: 1;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }
        
        .applications-container {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            color: #1f2937;
        }
        
        .applications-container h2 {
            color: #111827;
        }
        
        .applications-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }
        
        .applications-header h2 {
            color: #111827;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .applications-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            background: white;
        }
        
        .applications-table th {
            background: #f8fafc !important;
            padding: 1rem !important;
            text-align: left !important;
            font-weight: 600 !important;
            color: #374151 !important;
            border-bottom: 2px solid #e5e7eb !important;
        }

        /* Ensure table column headers are visible */
        .applications-table thead th,
        .table-header,
        .column-header {
            color: #111827 !important;
            font-weight: 700 !important;
            background: #f9fafb !important;
        }

        /* Application cards or items */
        .application-card,
        .application-item {
            background: white !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            padding: 1rem !important;
            margin-bottom: 1rem !important;
        }

        .application-card *,
        .application-item * {
            color: #1f2937 !important;
        }

        /* Any dropdown or select options */
        option {
            color: #1f2937 !important;
            background: white !important;
        }

        /* Focus states for better visibility */
        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid var(--accent1) !important;
            border-color: var(--accent1) !important;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1) !important;
        }
        
        .applications-table td {
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            color: #1f2937;
        }
        
        .applications-table tr:hover {
            background: #f9fafb;
        }
        
        .applications-table td strong {
            color: #111827;
            font-weight: 600;
        }

        /* Ensure all card content is visible */
        .stat-card,
        .stat-card * {
            color: #1f2937 !important;
        }

        .stat-number {
            color: var(--accent1) !important;
        }

        .stat-label {
            color: #6b7280 !important;
        }

        /* Application item styling */
        .application-item,
        .application-item * {
            color: #1f2937 !important;
        }

        .application-title {
            color: #111827 !important;
            font-weight: 600 !important;
        }

        .application-company {
            color: #374151 !important;
        }

        .application-platform,
        .application-date {
            color: #6b7280 !important;
        }

        /* Status badges - keep colored backgrounds with white text */
        .status-badge {
            color: white !important;
        }

        /* Form sections if they exist */
        .form-section,
        .add-application-form {
            background: white !important;
            padding: 1.5rem !important;
            border-radius: 12px !important;
            border: 1px solid #e5e7eb !important;
        }

        .form-section h3,
        .form-section h2 {
            color: #111827 !important;
            margin-bottom: 1rem !important;
        }
        
        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            color: white;
            text-transform: uppercase;
        }
        
        .opportunity-type {
            padding: 0.25rem 0.75rem;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #374151;
            background: #f3f4f6;
            display: inline-block;
        }
        
        .days-ago {
            color: #4b5563;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .applications-table small {
            color: #6b7280;
            font-size: 0.8rem;
        }
        
        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.8rem;
        }
        
        .btn-outline {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }
        
        .btn-outline:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }
        
        .btn-primary {
            background: var(--accent1);
            color: white;
        }
        
        .btn-primary:hover {
            background: #4c1d95;
        }
        
        .loading-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }
        
        .empty-state h3 {
            color: #374151;
            margin-bottom: 0.5rem;
        }
        
        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }
        
        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .filter-tab {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            background: white;
            color: #374151;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        
        .filter-tab.active {
            background: var(--accent1);
            color: white;
            border-color: var(--accent1);
        }
        
        .filter-tab:hover {
            border-color: var(--accent1);
            color: var(--accent1);
        }
        
        .filter-tab.active:hover {
            color: white;
        }

        /* Full-width layout without sidebar */
        .full-width-container {
            max-width: 100vw;
            width: 100%;
            margin: 0;
            padding: 1rem;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .applications-hero {
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: var(--card-radius);
            padding: 2rem;
            margin-bottom: 1.5rem;
            color: white;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
        }

        .applications-container {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            color: #1f2937;
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .full-width-container {
                padding: 0.5rem;
            }
            
            .applications-hero {
                padding: 1.5rem;
                margin-bottom: 1rem;
            }
            
            .applications-hero h1 {
                font-size: 2rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .applications-container {
                padding: 1rem;
            }
            
            .applications-table {
                font-size: 0.9rem;
            }
            
            .applications-table th,
            .applications-table td {
                padding: 0.75rem 0.5rem;
            }
            
            .filter-tabs {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="full-width-container">
        <div class="applications-hero">
            <h1>📋 My Applications</h1>
            <p>Track all your opportunity applications in one place</p>
        </div>

        <div class="stats-grid" id="statsGrid">
            <div class="stat-card">
                <div class="stat-number" id="totalApplications">-</div>
                <div class="stat-label">Total Applied</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="pendingApplications">-</div>
                <div class="stat-label">Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="shortlistedApplications">-</div>
                <div class="stat-label">Shortlisted</div>
            </div>
            <div class="stat-card">
                <div class="stat-number" id="acceptedApplications">-</div>
                <div class="stat-label">Accepted</div>
            </div>
        </div>

        <div class="applications-container">
            <div class="applications-header">
                <h2>Application History</h2>
            </div>

            <div id="applicationsContent">
                <div class="loading-state">
                    <div style="font-size: 2rem;">⏳</div>
                    <p>Loading your applications...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let allApplications = [];
        let currentFilter = 'all';

        // Load applications data
        async function loadApplications() {
            try {
                const response = await fetch('../api/student/getApplications.php', {
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    allApplications = data.applications;
                    updateStats(data.stats);
                    displayApplications(allApplications);
                } else {
                    showError('Failed to load applications: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                showError('Error loading applications: ' + error.message);
            }
        }

        function updateStats(stats) {
            document.getElementById('totalApplications').textContent = stats.total_applications || 0;
            document.getElementById('pendingApplications').textContent = stats.pending || 0;
            document.getElementById('shortlistedApplications').textContent = stats.shortlisted || 0;
            document.getElementById('acceptedApplications').textContent = stats.accepted || 0;
        }

        function displayApplications(applications) {
            const container = document.getElementById('applicationsContent');
            
            if (!applications || applications.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">📭</div>
                        <h3>No Applications Yet</h3>
                        <p>Start applying to opportunities to see them here!</p>
                        <a href="opportunities.php" class="btn btn-primary" style="margin-top: 1rem;">Browse Opportunities</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <table class="applications-table">
                    <thead>
                        <tr>
                            <th>Opportunity</th>
                            <th>Type</th>
                            <th>Company/Organization</th>
                            <th>Status</th>
                            <th>Applied</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${applications.map(app => `
                            <tr>
                                <td>
                                    <strong>${app.opportunity_title}</strong>
                                </td>
                                <td>
                                    <span class="opportunity-type">${app.type_display}</span>
                                </td>
                                <td>${app.company_college_name || 'N/A'}</td>
                                <td>
                                    <span class="status-badge" style="background-color: ${app.status_color}">
                                        ${app.status.replace('_', ' ')}
                                    </span>
                                </td>
                                <td>
                                    <span class="days-ago">
                                        ${app.days_since_applied} day${app.days_since_applied !== 1 ? 's' : ''} ago
                                    </span>
                                    <br>
                                    <small>${new Date(app.application_date).toLocaleDateString()}</small>
                                </td>
                                <td>
                                    <button onclick="viewApplication(${app.id})" class="btn btn-sm btn-outline">View</button>
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        function filterApplications(status) {
            currentFilter = status;
            
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(tab => {
                tab.classList.remove('active');
            });
            document.querySelector(`[data-filter="${status}"]`).classList.add('active');
            
            // Filter applications
            let filtered = allApplications;
            if (status !== 'all') {
                filtered = allApplications.filter(app => app.status === status);
            }
            
            displayApplications(filtered);
        }

        function viewApplication(id) {
            // In a real implementation, this would open a detailed view
            alert('Application details for ID: ' + id);
        }

        function showError(message) {
            document.getElementById('applicationsContent').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">❌</div>
                    <h3>Error Loading Applications</h3>
                    <p>${message}</p>
                    <button onclick="loadApplications()" class="btn btn-primary" style="margin-top: 1rem;">Try Again</button>
                </div>
            `;
        }

        // Event listeners
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                filterApplications(e.target.dataset.filter);
            });
        });

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadApplications);
    </script>
</body>
</html>