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
    <title>Hackathons - <?php echo APP_NAME; ?></title>
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

        .full-width-container {
            max-width: 100vw;
            width: 100%;
            margin: 0;
            padding: 1rem;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .hackathons-hero {
            background: linear-gradient(135deg, var(--accent1), var(--accent2));
            border-radius: var(--card-radius);
            padding: 3rem 2rem;
            margin-bottom: 2rem;
            color: white;
            text-align: center;
        }

        .hackathons-hero h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .hackathons-hero p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .filter-tabs {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            justify-content: center;
        }

        .filter-tab {
            padding: 0.75rem 1.5rem;
            border: 2px solid #e5e7eb;
            border-radius: 25px;
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

        .hackathons-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hackathon-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e5e7eb;
        }

        .hackathon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .hackathon-date {
            background: var(--accent1);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 1.5rem;
            display: inline-block;
            min-width: 80px;
        }

        .hackathon-date .day {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1;
        }

        .hackathon-date .month {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hackathon-title {
            color: #111827;
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .hackathon-description {
            color: #4b5563;
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        .hackathon-details {
            margin-bottom: 1.5rem;
        }

        .detail-item {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
            color: #6b7280;
        }

        .detail-icon {
            margin-right: 0.5rem;
            font-size: 1.1rem;
        }

        .college-name {
            color: var(--accent1);
            font-weight: 600;
        }

        .join-btn {
            background: var(--accent1);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
        }

        .join-btn:hover {
            background: #6d28d9;
            transform: scale(1.02);
        }

        .loading-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #6b7280;
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .hackathons-hero h1 {
                font-size: 2rem;
            }
            
            .hackathons-grid {
                grid-template-columns: 1fr;
            }
            
            .filter-tabs {
                flex-wrap: wrap;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="full-width-container">
        <div class="hackathons-hero">
            <h1>🏆 Campus Hackathons</h1>
            <p>Discover and participate in exciting hackathons posted by colleges</p>
        </div>

        <div class="filter-tabs">
            <div class="filter-tab active" data-filter="all">All Events</div>
            <div class="filter-tab" data-filter="hackathons">Hackathons</div>
            <div class="filter-tab" data-filter="workshops">Workshops</div>
            <div class="filter-tab" data-filter="symposiums">Symposiums</div>
            <div class="filter-tab" data-filter="project-expos">Project Expos</div>
        </div>

        <div id="hackathonsContent">
            <div class="loading-state">
                <div style="font-size: 2rem;">⏳</div>
                <p>Loading hackathon events...</p>
            </div>
        </div>
    </div>

    <script>
        let allHackathons = [];
        let currentFilter = 'hackathons';

        // Load hackathons data
        async function loadHackathons() {
            try {
                const response = await fetch('../api/student/getHackathons.php', {
                    credentials: 'same-origin'
                });
                
                const data = await response.json();
                
                if (data.success) {
                    allHackathons = data.hackathons;
                    displayHackathons(allHackathons);
                } else {
                    showError('Failed to load hackathons: ' + (data.error || 'Unknown error'));
                }
            } catch (error) {
                showError('Error loading hackathons: ' + error.message);
            }
        }

        function displayHackathons(hackathons) {
            const container = document.getElementById('hackathonsContent');
            
            if (!hackathons || hackathons.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-state-icon">🎯</div>
                        <h3>No Hackathons Available</h3>
                        <p>Check back later for exciting hackathon events posted by colleges!</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="hackathons-grid">
                    ${hackathons.map(hackathon => `
                        <div class="hackathon-card">
                            <div class="hackathon-date">
                                <div class="day">${hackathon.day}</div>
                                <div class="month">${hackathon.month}</div>
                            </div>
                            
                            <h3 class="hackathon-title">${hackathon.title}</h3>
                            <p class="hackathon-description">${hackathon.description || 'No description available'}</p>
                            
                            <div class="hackathon-details">
                                <div class="detail-item">
                                    <span class="detail-icon">🏫</span>
                                    <span class="college-name">${hackathon.college_name}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-icon">📍</span>
                                    <span>${hackathon.location || 'Location TBA'}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-icon">📅</span>
                                    <span>${hackathon.formatted_date} at ${hackathon.formatted_time}</span>
                                </div>
                                ${hackathon.max_participants ? `
                                    <div class="detail-item">
                                        <span class="detail-icon">👥</span>
                                        <span>Max ${hackathon.max_participants} participants</span>
                                    </div>
                                ` : ''}
                            </div>
                            
                            <button class="join-btn" onclick="joinHackathon(${hackathon.id})">
                                Join Hackathon
                            </button>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        function joinHackathon(hackathonId) {
            // In a real implementation, this would register the student for the hackathon
            alert('Registration functionality coming soon! Hackathon ID: ' + hackathonId);
        }

        function showError(message) {
            document.getElementById('hackathonsContent').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">❌</div>
                    <h3>Error Loading Hackathons</h3>
                    <p>${message}</p>
                    <button onclick="loadHackathons()" class="join-btn" style="margin-top: 1rem; width: auto; padding: 0.75rem 1.5rem;">Try Again</button>
                </div>
            `;
        }

        // Event listeners for filter tabs
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.addEventListener('click', (e) => {
                // Update active tab
                document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
                e.target.classList.add('active');
                
                // Filter hackathons (for now just showing hackathons, but this can be extended)
                currentFilter = e.target.dataset.filter;
                if (currentFilter === 'hackathons' || currentFilter === 'all') {
                    displayHackathons(allHackathons);
                } else {
                    // Show empty state for other filters for now
                    showEmpty('No ' + currentFilter.replace('-', ' ') + ' available at the moment.');
                }
            });
        });

        function showEmpty(message) {
            document.getElementById('hackathonsContent').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📅</div>
                    <h3>No Events Found</h3>
                    <p>${message}</p>
                </div>
            `;
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadHackathons);
    </script>
</body>
</html>
