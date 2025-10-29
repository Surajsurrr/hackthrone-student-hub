// Communities Management JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeCommunities();
    loadTrendingCommunities();
    loadMyCommunities();
});

// Initialize community features
function initializeCommunities() {
    // Modal controls
    const openModalBtn = document.getElementById('openCommunityModal');
    const closeModalBtn = document.getElementById('closeCommunityModal');
    const cancelBtn = document.getElementById('cancelCommunityBtn');
    const modalOverlay = document.getElementById('modalOverlay');
    const modal = document.getElementById('createCommunityModal');
    const createForm = document.getElementById('createCommunityForm');

    if (openModalBtn) {
        openModalBtn.addEventListener('click', () => openModal());
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', () => closeModal());
    }

    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => closeModal());
    }

    if (modalOverlay) {
        modalOverlay.addEventListener('click', () => closeModal());
    }

    if (createForm) {
        createForm.addEventListener('submit', handleCreateCommunity);
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
            closeModal();
        }
    });
}

// Open modal
function openModal() {
    const modal = document.getElementById('createCommunityModal');
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

// Close modal
function closeModal() {
    const modal = document.getElementById('createCommunityModal');
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('createCommunityForm').reset();
    }
}

// Handle create community form submission
async function handleCreateCommunity(e) {
    e.preventDefault();

    const formData = new FormData(e.target);
    
    const communityData = {
        community_name: formData.get('community_name'),
        community_subject: formData.get('community_subject'),
        community_description: formData.get('community_description'),
        community_type: formData.get('community_type'),
        community_tags: formData.get('community_tags')
    };

    try {
        const response = await fetch('../api/student/createCommunity.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(communityData)
        });

        const result = await response.json();

        if (result.success) {
            showNotification('Community created successfully! 🎉', 'success');
            closeModal();
            loadMyCommunities();
            loadTrendingCommunities();
        } else {
            showNotification(result.message || 'Failed to create community', 'error');
        }
    } catch (error) {
        console.error('Error creating community:', error);
        showNotification('An error occurred. Please try again.', 'error');
    }
}

// Load trending communities
async function loadTrendingCommunities() {
    try {
        const response = await fetch('../api/student/getTrendingCommunities.php');
        const result = await response.json();

        const container = document.getElementById('trending-communities');
        
        if (result.success && result.communities && result.communities.length > 0) {
            container.innerHTML = result.communities.map(community => createCommunityCard(community)).join('');
        } else {
            container.innerHTML = '<p class="empty-state-text">No trending communities yet.</p>';
        }
    } catch (error) {
        console.error('Error loading trending communities:', error);
        document.getElementById('trending-communities').innerHTML = 
            '<p class="empty-state-text">Failed to load communities.</p>';
    }
}

// Load my communities
async function loadMyCommunities() {
    try {
        const response = await fetch('../api/student/getMyCommunities.php');
        const result = await response.json();

        const container = document.getElementById('my-communities');
        
        if (result.success && result.communities && result.communities.length > 0) {
            container.innerHTML = result.communities.map(community => createCommunityCard(community, true)).join('');
        } else {
            container.innerHTML = '<p class="empty-state-text">You haven\'t joined any communities yet.</p>';
        }
    } catch (error) {
        console.error('Error loading my communities:', error);
        document.getElementById('my-communities').innerHTML = 
            '<p class="empty-state-text">Failed to load communities.</p>';
    }
}

// Create community card HTML
function createCommunityCard(community, isMember = false) {
    const subjectEmojis = {
        'Computer Science': '💻',
        'Mathematics': '📐',
        'Physics': '⚛️',
        'Chemistry': '🧪',
        'Biology': '🧬',
        'English': '📖',
        'History': '🏛️',
        'Other': '📚'
    };

    const emoji = subjectEmojis[community.subject] || '📚';
    const memberText = community.member_count === 1 ? 'member' : 'members';
    const postText = community.post_count === 1 ? 'post' : 'posts';

    return `
        <div class="community-item" onclick="viewCommunity(${community.id})">
            <div class="community-item-header">
                <div class="community-icon">${emoji}</div>
                <div class="community-info">
                    <div class="community-name">${escapeHtml(community.name)}</div>
                    <div class="community-subject">${escapeHtml(community.subject)}</div>
                </div>
            </div>
            <div class="community-stats">
                <div class="community-stat">
                    <span>👥</span>
                    <span>${community.member_count || 0} ${memberText}</span>
                </div>
                <div class="community-stat">
                    <span>💬</span>
                    <span>${community.post_count || 0} ${postText}</span>
                </div>
                ${community.type === 'private' ? '<div class="community-stat"><span>🔒</span><span>Private</span></div>' : ''}
            </div>
        </div>
    `;
}

// View community details
function viewCommunity(communityId) {
    // Navigate to community page
    window.location.href = `community.php?id=${communityId}`;
}

// Show notification
function showNotification(message, type = 'info') {
    const notification = document.getElementById('notification');
    const notificationMessage = document.getElementById('notification-message');
    
    if (notification && notificationMessage) {
        notificationMessage.textContent = message;
        notification.className = `notification ${type} show`;
        
        setTimeout(() => {
            notification.classList.remove('show');
            notification.classList.add('hidden');
        }, 3000);
    }
}

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
