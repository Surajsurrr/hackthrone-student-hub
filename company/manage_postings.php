<?php
require_once 'includes/company_auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Postings - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <style>
        .manage-postings-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            margin-bottom: 30px;
        }

        .page-header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        /* Use global .stat-card base styles from assets/css/dashboard.css for consistent theme */
        .stat-card {
            padding: 18px;
            color: #e6eef6;
            background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
            border: 1px solid rgba(255,255,255,0.04);
            border-radius: var(--card-radius);
            box-shadow: 0 8px 24px rgba(0,0,0,0.6);
        }

        .stat-card h3 {
            font-size: 1.75rem;
            margin-bottom: 6px;
            color: #fff;
        }

        .stat-card p {
            font-size: 0.95rem;
            color: var(--muted);
        }

        /* subtle accent dots for stat types to keep visual cue without heavy colors */
        .stat-card .stat-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }
        .stat-card.jobs .stat-dot { background: var(--accent1); }
        .stat-card.posts .stat-dot { background: var(--accent2); }
        .stat-card.active .stat-dot { background: #10b981; }

        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }

        .filters-row {
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-group label {
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
        }

        .filter-select {
            padding: 8px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
            min-width: 150px;
            cursor: pointer;
        }

        .filter-select:focus {
            outline: none;
            border-color: #667eea;
        }

        .search-box {
            flex: 1;
            min-width: 250px;
        }

        .search-box input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        .search-box input:focus {
            outline: none;
            border-color: #667eea;
        }

        .postings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .posting-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .posting-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.12);
            border-color: #667eea;
        }

        .posting-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .posting-type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-job {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-internship {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-post {
            background: #e0e7ff;
            color: #4338ca;
        }

        .badge-research {
            background: #fce7f3;
            color: #9f1239;
        }

        .badge-history {
            background: #f3e8ff;
            color: #6b21a8;
        }

        .badge-announcement {
            background: #fef9c3;
            color: #854d0e;
        }

        .posting-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .posting-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
            font-size: 0.85rem;
            color: #64748b;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .meta-item i {
            font-size: 1rem;
        }

        .posting-description {
            color: #475569;
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 15px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .posting-stats {
            display: flex;
            gap: 20px;
            padding: 12px 0;
            border-top: 1px solid #e2e8f0;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            color: #64748b;
        }

        .posting-actions {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            flex: 1;
            text-align: center;
        }

        .btn-edit {
            background: #667eea;
            color: white;
        }

        .btn-edit:hover {
            background: #5568d3;
        }

        .btn-delete {
            background: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background: #dc2626;
        }

        .btn-view {
            background: #10b981;
            color: white;
        }

        .btn-view:hover {
            background: #059669;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-closed {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-draft {
            background: #e5e7eb;
            color: #374151;
        }

        .status-published {
            background: #dbeafe;
            color: #1e40af;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #64748b;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #667eea;
        }

        .loading::after {
            content: '⏳';
            font-size: 2rem;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="manage-postings-container">
        <div class="page-header">
            <h1>📊 Manage Your Postings</h1>
            <p>View and manage all your job postings, internships, and company announcements</p>
        </div>

        <!-- Statistics -->
        <div class="stats-grid" id="stats-grid">
            <div class="stat-card">
                <div class="stat-title">
                    <span class="stat-dot"></span>
                    <h3 id="total-postings">0</h3>
                </div>
                <p>Total Postings</p>
            </div>
            <div class="stat-card jobs">
                <div class="stat-title">
                    <span class="stat-dot"></span>
                    <h3 id="total-jobs">0</h3>
                </div>
                <p>Job Listings</p>
            </div>
            <div class="stat-card posts">
                <div class="stat-title">
                    <span class="stat-dot"></span>
                    <h3 id="total-posts">0</h3>
                </div>
                <p>Company Posts</p>
            </div>
            <div class="stat-card active">
                <div class="stat-title">
                    <span class="stat-dot"></span>
                    <h3 id="active-jobs">0</h3>
                </div>
                <p>Active Jobs</p>
            </div>
        </div>

        <!-- Filters -->
        <div class="filters-section">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="type-filter">Type:</label>
                    <select id="type-filter" class="filter-select">
                        <option value="all">All Types</option>
                        <option value="job">Jobs Only</option>
                        <option value="post">Company Posts</option>
                        <option value="internship">Internships</option>
                        <option value="full-time">Full-time</option>
                        <option value="research">Research</option>
                        <option value="history">Company History</option>
                        <option value="announcement">Announcements</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="status-filter">Status:</label>
                    <select id="status-filter" class="filter-select">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="published">Published</option>
                        <option value="closed">Closed</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>

                <div class="search-box">
                    <input type="text" id="search-input" placeholder="🔍 Search by title, description, location...">
                </div>
            </div>
        </div>

        <!-- Postings Grid -->
        <div id="postings-container">
            <div class="loading">Loading postings...</div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script>
        let allPostings = [];
        let filteredPostings = [];

        // Load postings
        async function loadPostings() {
            try {
                const response = await fetch('../api/company/getAllPostings.php');
                const data = await response.json();

                if (data.success) {
                    allPostings = data.postings;
                    filteredPostings = allPostings;
                    
                    // Update stats
                    document.getElementById('total-postings').textContent = data.stats.total_postings;
                    document.getElementById('total-jobs').textContent = data.stats.total_jobs;
                    document.getElementById('total-posts').textContent = data.stats.total_posts;
                    document.getElementById('active-jobs').textContent = data.stats.active_jobs;
                    
                    displayPostings();
                } else {
                    showError(data.error || 'Failed to load postings');
                }
            } catch (error) {
                console.error('Error loading postings:', error);
                showError('Failed to load postings');
            }
        }

        // Display postings
        function displayPostings() {
            const container = document.getElementById('postings-container');
            
            if (filteredPostings.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i>📭</i>
                        <h3>No postings found</h3>
                        <p>Start by creating your first job posting or company announcement</p>
                    </div>
                `;
                return;
            }

            const html = `
                <div class="postings-grid">
                    ${filteredPostings.map(posting => createPostingCard(posting)).join('')}
                </div>
            `;
            
            container.innerHTML = html;
        }

        // Create posting card
        function createPostingCard(posting) {
            const isJob = posting.posting_type === 'job';
            const typeBadge = getTypeBadge(posting);
            const statusBadge = getStatusBadge(posting);
            const date = new Date(posting.created_at).toLocaleDateString('en-US', { 
                year: 'numeric', month: 'short', day: 'numeric' 
            });

            let metaHtml = '';
            if (isJob) {
                metaHtml = `
                    ${posting.location ? `<div class="meta-item">📍 ${posting.location}</div>` : ''}
                    ${posting.salary ? `<div class="meta-item">💰 ${posting.salary}</div>` : ''}
                    ${posting.application_deadline ? `<div class="meta-item">⏰ Deadline: ${new Date(posting.application_deadline).toLocaleDateString()}</div>` : ''}
                `;
            } else {
                metaHtml = `
                    <div class="meta-item">👁️ ${posting.views || 0} views</div>
                    <div class="meta-item">❤️ ${posting.likes || 0} likes</div>
                    ${posting.tags ? `<div class="meta-item">🏷️ ${posting.tags}</div>` : ''}
                `;
            }

            return `
                <div class="posting-card">
                    <div class="posting-header">
                        ${typeBadge}
                        ${statusBadge}
                    </div>
                    
                    <h3 class="posting-title">${escapeHtml(posting.title)}</h3>
                    
                    <div class="posting-meta">
                        <div class="meta-item">📅 ${date}</div>
                        ${metaHtml}
                    </div>
                    
                    <div class="posting-description">
                        ${escapeHtml(posting.description || posting.content || 'No description')}
                    </div>
                    
                    <div class="posting-actions">
                        <button class="btn btn-view" onclick="viewPosting(${posting.id}, '${posting.posting_type}')">
                            View Details
                        </button>
                        <button class="btn btn-edit" onclick="editPosting(${posting.id}, '${posting.posting_type}')">
                            Edit
                        </button>
                        <button class="btn btn-delete" onclick="deletePosting(${posting.id}, '${posting.posting_type}')">
                            Delete
                        </button>
                    </div>
                </div>
            `;
        }

        // Get type badge
        function getTypeBadge(posting) {
            if (posting.posting_type === 'job') {
                const typeClass = `badge-${posting.type.replace('-', '')}`;
                return `<span class="posting-type-badge ${typeClass}">${posting.type}</span>`;
            } else {
                const typeClass = `badge-${posting.post_type || 'post'}`;
                return `<span class="posting-type-badge ${typeClass}">${posting.post_type || 'post'}</span>`;
            }
        }

        // Get status badge
        function getStatusBadge(posting) {
            const status = posting.status;
            return `<span class="status-badge status-${status}">${status}</span>`;
        }

        // Filter postings
        function filterPostings() {
            const typeFilter = document.getElementById('type-filter').value;
            const statusFilter = document.getElementById('status-filter').value;
            const searchQuery = document.getElementById('search-input').value.toLowerCase();

            filteredPostings = allPostings.filter(posting => {
                // Type filter
                if (typeFilter !== 'all') {
                    if (typeFilter === 'job' && posting.posting_type !== 'job') return false;
                    if (typeFilter === 'post' && posting.posting_type !== 'post') return false;
                    if (posting.posting_type === 'job' && posting.type !== typeFilter && typeFilter !== 'job') return false;
                    if (posting.posting_type === 'post' && posting.post_type !== typeFilter && typeFilter !== 'post') return false;
                }

                // Status filter
                if (statusFilter !== 'all' && posting.status !== statusFilter) return false;

                // Search filter
                if (searchQuery) {
                    const searchText = `${posting.title} ${posting.description || ''} ${posting.location || ''} ${posting.tags || ''}`.toLowerCase();
                    if (!searchText.includes(searchQuery)) return false;
                }

                return true;
            });

            displayPostings();
        }

        // Event listeners
        document.getElementById('type-filter').addEventListener('change', filterPostings);
        document.getElementById('status-filter').addEventListener('change', filterPostings);
        document.getElementById('search-input').addEventListener('input', filterPostings);

        // Actions
        function viewPosting(id, type) {
            if (type === 'job') {
                window.location.href = `view_job.php?id=${id}`;
            } else {
                window.location.href = `view_post.php?id=${id}`;
            }
        }

        function editPosting(id, type) {
            if (type === 'job') {
                window.location.href = `post_internship.php?edit=${id}`;
            } else {
                window.location.href = `edit_post.php?id=${id}`;
            }
        }

        async function deletePosting(id, type) {
            if (!confirm('Are you sure you want to delete this posting?')) return;

            try {
                const endpoint = type === 'job' 
                    ? '../api/company/deleteJob.php' 
                    : '../api/company/deletePost.php';
                    
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });

                const data = await response.json();
                
                if (data.success) {
                    alert('Posting deleted successfully');
                    loadPostings();
                } else {
                    alert(data.error || 'Failed to delete posting');
                }
            } catch (error) {
                console.error('Error deleting posting:', error);
                alert('Failed to delete posting');
            }
        }

        // Helper functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showError(message) {
            document.getElementById('postings-container').innerHTML = `
                <div class="empty-state">
                    <i>❌</i>
                    <h3>Error</h3>
                    <p>${escapeHtml(message)}</p>
                </div>
            `;
        }

        // Load on page load
        document.addEventListener('DOMContentLoaded', loadPostings);
    </script>
</body>
</html>
