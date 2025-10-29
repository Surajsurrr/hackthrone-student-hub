// Enhanced Dashboard JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Initialize dashboard
    initDashboard();
    loadDashboardData();
    setupEventListeners();
});

function initDashboard() {
    // Handle navigation
    const navLinks = document.querySelectorAll('.nav-link, .nav-trigger');
    const sections = document.querySelectorAll('.dashboard-section');

    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetSection = this.getAttribute('data-section') || this.getAttribute('href').substring(1);
            
            // Update active nav link
            document.querySelectorAll('.nav-link').forEach(nav => nav.classList.remove('active'));
            if (this.classList.contains('nav-link')) {
                this.classList.add('active');
            } else {
                // Find corresponding nav link and activate it
                const correspondingNav = document.querySelector(`.nav-link[data-section="${targetSection}"]`);
                if (correspondingNav) {
                    correspondingNav.classList.add('active');
                }
            }
            
            // Show target section
            sections.forEach(section => {
                section.classList.remove('active');
                if (section.id === targetSection) {
                    section.classList.add('active');
                }
            });
        });
    });

    // Add smooth scrolling animations
    // sections.forEach(section => {
    //     section.style.transition = 'opacity 0.3s ease-in-out';
    // });
}

function setupEventListeners() {
    // Profile form submission
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', handleProfileUpdate);
    }

    // Notes upload form
    const uploadForm = document.getElementById('upload-form');
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleNotesUpload);
    }

    // Profile picture upload
    const profilePicInput = document.getElementById('profile-pic-input');
    if (profilePicInput) {
        profilePicInput.addEventListener('change', handleProfilePicUpload);
    }

    // Application form submission
    const applicationForm = document.getElementById('application-form');
    if (applicationForm) {
        applicationForm.addEventListener('submit', handleApplicationSubmit);
    }

    // Event filters
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            filterEvents(this.dataset.filter);
        });
    });

    // View toggles for events
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const parent = this.closest('.view-toggle');
            parent.querySelectorAll('.toggle-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            toggleEventsView(this.dataset.view);
        });
    });

    // Endorsement form submission
    const endorsementForm = document.getElementById('endorsement-form');
    if (endorsementForm) {
        endorsementForm.addEventListener('submit', handleEndorsementSubmit);
    }

    // Character counter for endorsement message
    const endorsementMessage = document.getElementById('endorsement-message');
    const charCount = document.getElementById('char-count');
    if (endorsementMessage && charCount) {
        endorsementMessage.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;
            
            if (count > 500) {
                charCount.style.color = '#ef4444';
            } else if (count > 400) {
                charCount.style.color = '#f59e0b';
            } else {
                charCount.style.color = '#64748b';
            }
        });
    }

    // Endorsee search functionality
    const endorseeSearch = document.getElementById('endorsee-search');
    if (endorseeSearch) {
        endorseeSearch.addEventListener('input', debounce(searchEndorsees, 300));
    }

    // AI Chat enhancement
    setupAIChat();
}

function loadDashboardData() {
    // Load opportunities count
    fetch('../api/student/getDashboard.php', { credentials: 'same-origin' })
        .then(response => {
            if (response.status === 401) {
                showNotification('Unauthorized - please login', 'error');
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Map API response keys to expected format
                const stats = {
                    opportunities: data.opportunities_count || 0,
                    notes: data.notes_count || 0,
                    ai_sessions: data.ai_sessions_count || 0
                };
                updateDashboardStats(stats);
                loadOpportunitiesPreview();
                loadNotesPreview();
                loadApplications();
            }
        })
        .catch(error => console.error('Error loading dashboard data:', error));
}

function updateDashboardStats(stats) {
    const elements = {
        'opportunities-count': stats.opportunities || '0',
        'notes-count': stats.notes || '0',
        'ai-sessions-count': stats.ai_sessions || '0'
    };

    Object.entries(elements).forEach(([id, value]) => {
        const element = document.getElementById(id);
        if (element) {
            // Set text directly - animation handled by dashboard-ui.js
            element.textContent = value;
        }
    });
}

// Animation function removed - handled by dashboard-ui.js to prevent conflicts

function handleProfileUpdate(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('name', document.getElementById('name').value);
    formData.append('college', document.getElementById('college').value);
    formData.append('year', document.getElementById('year').value);
    formData.append('branch', document.getElementById('branch').value);
    formData.append('skills', document.getElementById('skills').value);
    formData.append('bio', document.getElementById('bio').value);

    showLoading('Updating profile...');

    fetch('../api/student/createProfile.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        hideLoading();
        if (response.status === 401) {
            showNotification('Unauthorized - please login', 'error');
            throw new Error('Unauthorized');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Profile updated successfully!', 'success');
        } else {
            // Support both `message` and `error` keys from various endpoints
            const msg = data.message || data.error || 'Unknown error';
            showNotification('Error updating profile: ' + msg, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('Error updating profile', 'error');
    });
}

function handleNotesUpload(e) {
    e.preventDefault();
    
    const formData = new FormData();
    formData.append('title', document.getElementById('note-title').value);
    formData.append('subject', document.getElementById('note-subject').value);
    formData.append('description', document.getElementById('note-description').value);
    formData.append('file', document.getElementById('note-file').files[0]);

    showLoading('Uploading notes...');

    fetch('../api/student/uploadNotes.php', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(response => {
        hideLoading();
        if (response.status === 401) {
            showNotification('Unauthorized - please login', 'error');
            throw new Error('Unauthorized');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Notes uploaded successfully!', 'success');
            document.getElementById('upload-form').reset();
            loadNotesPreview();
        } else {
            showNotification('Error uploading notes: ' + data.message, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('Error uploading notes', 'error');
    });
}

function handleProfilePicUpload(e) {
    const file = e.target.files[0];
    if (file) {
        const formData = new FormData();
        formData.append('profile_pic', file);

        showLoading('Uploading profile picture...');

        fetch('../api/student/uploadProfilePic.php', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            hideLoading();
            if (response.status === 401) {
                showNotification('Unauthorized - please login', 'error');
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                document.getElementById('profile-image').src = data.image_url;
                showNotification('Profile picture updated!', 'success');
            } else {
                showNotification('Error uploading picture: ' + data.message, 'error');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showNotification('Error uploading picture', 'error');
        });
    }
}

function loadOpportunitiesPreview() {
    fetch('../api/student/getOpportunities.php?limit=3', { credentials: 'same-origin' })
        .then(response => {
            if (response.status === 401) {
                console.warn('Unauthorized when loading opportunities');
                return { success: false };
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateOpportunitiesPreview(data.opportunities);
            }
        })
        .catch(error => console.error('Error loading opportunities:', error));
}

function updateOpportunitiesPreview(opportunities) {
    const hackathonsContainer = document.getElementById('hackathons-preview');
    const internshipsContainer = document.getElementById('internships-preview');

    if (hackathonsContainer && opportunities.hackathons) {
        hackathonsContainer.innerHTML = opportunities.hackathons.map(h => `
            <div class="opportunity-item">
                <h4>${h.title}</h4>
                <p>${h.description}</p>
                <span class="date">Due: ${new Date(h.deadline).toLocaleDateString()}</span>
            </div>
        `).join('');
    }

    if (internshipsContainer && opportunities.internships) {
        internshipsContainer.innerHTML = opportunities.internships.map(i => `
            <div class="opportunity-item">
                <h4>${i.title}</h4>
                <p>${i.company}</p>
                <span class="location">${i.location}</span>
            </div>
        `).join('');
    }
}

function loadNotesPreview() {
    fetch('../api/student/fetchNotes.php?limit=5', { credentials: 'same-origin' })
        .then(response => {
            if (response.status === 401) {
                console.warn('Unauthorized when loading notes');
                return { success: false };
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateNotesPreview(data.notes);
            }
        })
        .catch(error => console.error('Error loading notes:', error));
}

function updateNotesPreview(notes) {
    const myNotesContainer = document.getElementById('my-notes-list');
    const popularNotesContainer = document.getElementById('popular-notes-list');

    if (myNotesContainer) {
        myNotesContainer.innerHTML = notes.my_notes.map(note => `
            <div class="note-item">
                <h4>${note.title}</h4>
                <p>${note.subject}</p>
                <small>Uploaded: ${new Date(note.created_at).toLocaleDateString()}</small>
            </div>
        `).join('') || '<p>No notes uploaded yet.</p>';
    }

    if (popularNotesContainer) {
        popularNotesContainer.innerHTML = notes.popular_notes.map(note => `
            <div class="note-item">
                <h4>${note.title}</h4>
                <p>${note.subject}</p>
                <small>By: ${note.uploader}</small>
            </div>
        `).join('') || '<p>No popular notes available.</p>';
    }
}

function setupAIChat() {
    const chatInput = document.getElementById('chat-input');
    const sendBtn = document.getElementById('send-btn');

    if (chatInput && sendBtn) {
        chatInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        sendBtn.addEventListener('click', sendMessage);
    }
}

function sendMessage() {
    const chatInput = document.getElementById('chat-input');
    const message = chatInput.value.trim();
    
    if (message) {
        addMessageToChat('user', message);
        chatInput.value = '';
        
        // Show typing indicator
        showTypingIndicator();
        
        // Send to AI
        fetch('../api/student/getAIResponse.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(response => {
            if (response.status === 401) {
                hideTypingIndicator();
                addMessageToChat('bot', 'Unauthorized - please login');
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            hideTypingIndicator();
            if (data.success) {
                addMessageToChat('bot', data.response);
            } else {
                addMessageToChat('bot', 'Sorry, I encountered an error. Please try again.');
            }
        })
        .catch(error => {
            hideTypingIndicator();
            console.error('Error:', error);
            addMessageToChat('bot', 'Sorry, I encountered an error. Please try again.');
        });
    }
}

function sendSuggestion(suggestion) {
    const chatInput = document.getElementById('chat-input');
    chatInput.value = suggestion;
    sendMessage();
}

function addMessageToChat(type, message) {
    const chatMessages = document.getElementById('chat-messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${type}-message`;
    
    if (type === 'user') {
        messageDiv.innerHTML = `<strong>You:</strong> ${message}`;
    } else {
        messageDiv.innerHTML = `<strong>AI Coach:</strong> ${message}`;
    }
    
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function showTypingIndicator() {
    const chatMessages = document.getElementById('chat-messages');
    const typingDiv = document.createElement('div');
    typingDiv.id = 'typing-indicator';
    typingDiv.className = 'message bot-message';
    typingDiv.innerHTML = '<strong>AI Coach:</strong> <em>Typing...</em>';
    chatMessages.appendChild(typingDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function hideTypingIndicator() {
    const typingIndicator = document.getElementById('typing-indicator');
    if (typingIndicator) {
        typingIndicator.remove();
    }
}

function handleApplicationSubmit(e) {
    e.preventDefault();
    
    const formData = {
        title: document.getElementById('app-title').value,
        type: document.getElementById('app-type').value,
        company: document.getElementById('app-org').value,
        platform: document.getElementById('app-platform').value,
        link: document.getElementById('app-link').value,
        location: document.getElementById('app-location').value,
        deadline: document.getElementById('app-deadline').value,
        notes: document.getElementById('app-notes').value
    };

    showLoading('Adding application...');

    fetch('../api/student/addApplication.php', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => {
        hideLoading();
        if (response.status === 401) {
            showNotification('Unauthorized - please login', 'error');
            throw new Error('Unauthorized');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Application added successfully!', 'success');
            document.getElementById('application-form').reset();
            loadApplications();
        } else {
            const msg = data.message || data.error || 'Unknown error';
            showNotification('Error adding application: ' + msg, 'error');
        }
    })
    .catch(error => {
        hideLoading();
        console.error('Error:', error);
        showNotification('Error adding application', 'error');
    });
}

function loadApplications() {
    fetch('../api/student/getApplications.php', { credentials: 'same-origin' })
        .then(response => {
            if (response.status === 401) {
                console.warn('Unauthorized when loading applications');
                return { success: false };
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                updateApplicationsList(data.applications);
            }
        })
        .catch(error => console.error('Error loading applications:', error));
}

function updateApplicationsList(applications) {
    const container = document.getElementById('my-applications');
    if (!container) return;

    if (!applications || applications.length === 0) {
        container.innerHTML = '<p class="muted">No applications added yet. Start by adding your first application!</p>';
        return;
    }

    container.innerHTML = applications.map(app => `
        <div class="application-item">
            <div class="app-header">
                <h4>${app.title}</h4>
                <span class="app-status status-${app.status}">${app.status.replace('_', ' ')}</span>
            </div>
            <p class="app-company">${app.company}</p>
            <p class="app-platform">Platform: ${app.platform}</p>
            ${app.location ? `<p class="app-platform">Location: ${app.location}</p>` : ''}
            <small class="app-date">Applied: ${new Date(app.applied_at).toLocaleDateString()}</small>
        </div>
    `).join('');
}

function filterEvents(filter) {
    const eventItems = document.querySelectorAll('.event-item');
    const featuredCards = document.querySelectorAll('.featured-event-card');
    
    // Filter regular event items
    eventItems.forEach(item => {
        const eventType = item.querySelector('.tag').textContent.toLowerCase();
        if (filter === 'all' || eventType.includes(filter)) {
            item.style.display = 'flex';
        } else {
            item.style.display = 'none';
        }
    });
    
    // Filter featured cards
    featuredCards.forEach(card => {
        const category = card.querySelector('.event-category').textContent.toLowerCase();
        if (filter === 'all' || category.includes(filter)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

function toggleEventsView(view) {
    const eventsList = document.querySelector('.events-list');
    if (!eventsList) return;
    
    if (view === 'grid') {
        eventsList.style.display = 'grid';
        eventsList.style.gridTemplateColumns = 'repeat(auto-fill, minmax(300px, 1fr))';
        eventsList.style.gap = '1rem';
    } else {
        eventsList.style.display = 'flex';
        eventsList.style.flexDirection = 'column';
        eventsList.style.gap = '1rem';
        eventsList.style.gridTemplateColumns = 'unset';
    }
}

function loadEvents() {
    // Placeholder function for loading events from API
    // This would typically fetch from ../api/student/getEvents.php
    console.log('Loading events...');
}

function registerForEvent(eventId) {
    // Placeholder function for event registration
    showLoading('Registering for event...');
    
    // Simulate API call
    setTimeout(() => {
        hideLoading();
        showNotification('Successfully registered for event!', 'success');
    }, 1000);
}

function generateRecommendations() {
    const container = document.getElementById('job-recommendations');
    container.innerHTML = 'Generating personalized recommendations...';
    
    fetch('../api/student/getJobRecommendations.php', { credentials: 'same-origin' })
        .then(response => {
            if (response.status === 401) {
                container.innerHTML = 'Please login to view recommendations.';
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                container.innerHTML = data.recommendations.map(rec => `
                    <div class="recommendation-item">
                        <h4>${rec.title}</h4>
                        <p>${rec.company}</p>
                        <span class="match-score">Match: ${rec.match_score}%</span>
                    </div>
                `).join('');
            } else {
                container.innerHTML = 'Unable to generate recommendations at this time.';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = 'Error generating recommendations.';
        });
}

// Utility functions
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 10px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        max-width: 400px;
    `;
    
    if (type === 'success') {
        notification.style.background = 'linear-gradient(135deg, #10b981, #059669)';
    } else if (type === 'error') {
        notification.style.background = 'linear-gradient(135deg, #ef4444, #dc2626)';
    } else {
        notification.style.background = 'linear-gradient(135deg, #667eea, #764ba2)';
    }
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => notification.remove(), 300);
    }, 4000);
}

function showLoading(message = 'Loading...') {
    const loading = document.createElement('div');
    loading.id = 'loading-overlay';
    loading.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 10001;
        backdrop-filter: blur(5px);
    `;
    loading.innerHTML = `
        <div style="background: white; padding: 30px; border-radius: 20px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.3);">
            <div style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #667eea; border-radius: 50%; animation: spin 1s linear infinite; margin: 0 auto 20px;"></div>
            <p style="margin: 0; color: #333; font-weight: 500;">${message}</p>
        </div>
    `;
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.getElementById('loading-overlay');
    if (loading) {
        loading.remove();
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); opacity: 1; }
        to { transform: translateX(100%); opacity: 0; }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .opportunity-item, .note-item, .recommendation-item {
        padding: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }
    
    .opportunity-item:hover, .note-item:hover, .recommendation-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 15px;
    }
    
    .badge-item {
        text-align: center;
        padding: 20px;
        border-radius: 15px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .badge-icon {
        font-size: 2rem;
        margin-bottom: 10px;
    }
    
    .progress-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .progress-bar {
        flex: 1;
        height: 10px;
        background: #e2e8f0;
        border-radius: 5px;
        overflow: hidden;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #667eea, #764ba2);
        transition: width 0.3s ease;
    }
    
    .event-item {
        display: flex;
        gap: 15px;
        padding: 15px;
        border-left: 4px solid #667eea;
        margin-bottom: 15px;
        background: #f8fafc;
        border-radius: 10px;
    }
    
    .event-date {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 10px;
        border-radius: 10px;
        font-weight: 600;
        text-align: center;
        min-width: 60px;
    }
    
    .suggestion-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 15px;
    }
    
    .suggestion-btn {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1));
        border: 1px solid #667eea;
        color: #667eea;
        padding: 8px 15px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.9rem;
    }
    
    .suggestion-btn:hover {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transform: translateY(-2px);
    }
`;
document.head.appendChild(style);

// Endorsement functions
async function handleEndorsementSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const endorsementData = {
        endorsee_name: formData.get('endorsee_name'),
        skill: formData.get('skill'),
        message: formData.get('message')
    };

    try {
        // Show loading state
        const submitBtn = e.target.querySelector('.submit-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;

        // Send to API
        const response = await fetch('../api/student/submitEndorsement.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(endorsementData)
        });

        const result = await response.json();
        
        if (result.success) {
            showNotification('Endorsement submitted successfully!', 'success');
            
            // Reset form
            e.target.reset();
            document.getElementById('char-count').textContent = '0';
        } else {
            showNotification(result.message || 'Failed to submit endorsement', 'error');
        }
        
        // Restore button
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
    } catch (error) {
        console.error('Error submitting endorsement:', error);
        showNotification('Failed to submit endorsement. Please try again.', 'error');
        
        // Restore button on error
        const submitBtn = e.target.querySelector('.submit-btn');
        submitBtn.textContent = 'Submit Endorsement';
        submitBtn.disabled = false;
    }
}

async function searchEndorsees(query) {
    if (query.length < 2) {
        clearSearchResults();
        return;
    }

    try {
        // Simulate search API call (replace with actual endpoint)
        const mockResults = [
            { id: 1, name: 'Alex Johnson', title: 'Software Engineer', avatar: 'assets/images/profile_pics/default.jpg' },
            { id: 2, name: 'Sarah Wilson', title: 'UI/UX Designer', avatar: 'assets/images/profile_pics/default.jpg' },
            { id: 3, name: 'Mike Chen', title: 'Data Scientist', avatar: 'assets/images/profile_pics/default.jpg' }
        ].filter(user => user.name.toLowerCase().includes(query.toLowerCase()));

        displaySearchResults(mockResults);
    } catch (error) {
        console.error('Error searching endorsees:', error);
    }
}

function displaySearchResults(results) {
    const searchResults = document.getElementById('search-results');
    if (!searchResults) return;

    if (results.length === 0) {
        searchResults.innerHTML = '<div class="no-results">No users found</div>';
        searchResults.style.display = 'block';
        return;
    }

    const resultsHTML = results.map(user => `
        <div class="search-result-item" onclick="selectEndorsee('${user.name}')">
            <img src="${user.avatar}" alt="${user.name}" class="result-avatar">
            <div class="result-info">
                <div class="result-name">${user.name}</div>
                <div class="result-title">${user.title}</div>
            </div>
        </div>
    `).join('');

    searchResults.innerHTML = resultsHTML;
    searchResults.style.display = 'block';
}

function selectEndorsee(name) {
    const searchInput = document.getElementById('endorsee-search');
    if (searchInput) {
        searchInput.value = name;
        clearSearchResults();
    }
}

function clearSearchResults() {
    const searchResults = document.getElementById('search-results');
    if (searchResults) {
        searchResults.style.display = 'none';
        searchResults.innerHTML = '';
    }
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Enhanced Upload Features
function setupEnhancedUploads() {
    setupMainUploadForm();
    setupQuickUploadForm();
    setupDropZone();
}

function setupMainUploadForm() {
    const mainForm = document.getElementById('upload-form');
    if (!mainForm) return;

    // Enhanced file input handling
    const fileInput = document.getElementById('note-file');
    const dropZone = document.getElementById('file-drop-zone');
    
    if (fileInput && dropZone) {
        // Click to browse
        dropZone.addEventListener('click', () => {
            fileInput.click();
        });

        // File selection
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                displayFilePreview(e.target.files[0]);
            }
        });

        // Drag and drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                displayFilePreview(files[0]);
            }
        });
    }

    // Form submission with progress
    mainForm.addEventListener('submit', handleMainUploadSubmit);
}

function setupQuickUploadForm() {
    const quickForm = document.getElementById('quick-upload-form');
    if (!quickForm) return;

    // Quick file input
    const quickFileInput = document.getElementById('quick-note-file');
    const fileNameDisplay = document.getElementById('quick-file-name');
    
    if (quickFileInput && fileNameDisplay) {
        quickFileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                const fileName = e.target.files[0].name;
                fileNameDisplay.textContent = fileName.length > 20 ? 
                    fileName.substring(0, 20) + '...' : fileName;
            } else {
                fileNameDisplay.textContent = 'No file selected';
            }
        });
    }

    // Form submission
    quickForm.addEventListener('submit', handleQuickUploadSubmit);
}

function setupDropZone() {
    // Add floating animation to upload icon
    const uploadIcons = document.querySelectorAll('.upload-icon');
    uploadIcons.forEach(icon => {
        icon.style.animation = 'float 3s ease-in-out infinite';
    });
}

function displayFilePreview(file) {
    const dropZone = document.getElementById('file-drop-zone');
    const previewEl = document.getElementById('file-preview');
    
    if (!dropZone || !previewEl) return;

    // Hide drop zone content and show preview
    const dropZoneContent = dropZone.querySelector('.drop-zone-content');
    if (dropZoneContent) {
        dropZoneContent.style.display = 'none';
    }

    // Update preview info
    const fileName = previewEl.querySelector('.file-name');
    const fileSize = previewEl.querySelector('.file-size');
    
    if (fileName) fileName.textContent = file.name;
    if (fileSize) fileSize.textContent = formatFileSize(file.size);
    
    previewEl.style.display = 'flex';
}

function removeFile() {
    const fileInput = document.getElementById('note-file');
    const dropZone = document.getElementById('file-drop-zone');
    const previewEl = document.getElementById('file-preview');
    
    if (fileInput) fileInput.value = '';
    
    if (previewEl) previewEl.style.display = 'none';
    
    if (dropZone) {
        const dropZoneContent = dropZone.querySelector('.drop-zone-content');
        if (dropZoneContent) {
            dropZoneContent.style.display = 'flex';
        }
    }
}

async function handleMainUploadSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('.upload-submit-btn');
    const progressEl = document.getElementById('upload-progress');
    
    // Get form data
    const title = document.getElementById('note-title').value.trim();
    const subject = document.getElementById('note-subject').value;
    const description = document.getElementById('note-description').value.trim();
    const fileInput = document.getElementById('note-file');
    
    // Validation
    if (!title || !subject || !fileInput.files[0]) {
        if (typeof showAlert === 'function') {
            showAlert('Please fill all required fields and select a file', 'error');
        }
        return;
    }

    // File size check
    const file = fileInput.files[0];
    if (file.size > 10 * 1024 * 1024) {
        if (typeof showAlert === 'function') {
            showAlert('File size must be less than 10MB', 'error');
        }
        return;
    }

    try {
        // Show loading state
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <span class="btn-content">
                <span class="btn-icon">⏳</span>
                <span class="btn-text">Uploading...</span>
            </span>
        `;
        submitBtn.disabled = true;

        // Show progress bar
        if (progressEl) {
            progressEl.style.display = 'block';
            animateProgress();
        }

        // Prepare form data
        const formData = new FormData();
        formData.append('title', title);
        formData.append('subject', subject);
        formData.append('description', description);
        formData.append('file', file);

        // Upload with progress simulation
        const response = await fetch('../api/student/uploadNotes.php', {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            if (typeof showAlert === 'function') {
                showAlert('🎉 Notes uploaded successfully!', 'success');
            }
            form.reset();
            removeFile();
            
            // Update stats
            updateUploadStats();
            
            // Refresh notes lists
            if (typeof loadNotes === 'function') {
                loadNotes();
            }
        } else {
            if (typeof showAlert === 'function') {
                showAlert(result.error || 'Upload failed', 'error');
            }
        }
    } catch (error) {
        console.error('Upload error:', error);
        if (typeof showAlert === 'function') {
            showAlert('Upload failed. Please try again.', 'error');
        }
    } finally {
        // Restore button
        const originalContent = `
            <span class="btn-content">
                <span class="btn-icon">🚀</span>
                <span class="btn-text">Share with Community</span>
            </span>
            <div class="btn-shine"></div>
        `;
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
        
        // Hide progress
        if (progressEl) {
            setTimeout(() => {
                progressEl.style.display = 'none';
            }, 1000);
        }
    }
}

async function handleQuickUploadSubmit(e) {
    e.preventDefault();
    
    const form = e.target;
    const submitBtn = form.querySelector('.quick-submit-btn');
    
    // Get form data
    const title = document.getElementById('quick-note-title').value.trim();
    const subject = document.getElementById('quick-note-subject').value;
    const fileInput = document.getElementById('quick-note-file');
    
    // Validation
    if (!title || !subject || !fileInput.files[0]) {
        if (typeof showAlert === 'function') {
            showAlert('Please fill all fields and select a file', 'warning');
        }
        return;
    }

    try {
        // Show loading
        const originalContent = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <span class="quick-btn-icon">⏳</span>
            <span>Uploading...</span>
        `;
        submitBtn.disabled = true;

        // Prepare form data
        const formData = new FormData();
        formData.append('title', title);
        formData.append('subject', subject);
        formData.append('description', ''); // Quick upload doesn't have description
        formData.append('file', fileInput.files[0]);

        const response = await fetch('../api/student/uploadNotes.php', {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        });

        const result = await response.json();
        
        if (result.success) {
            if (typeof showAlert === 'function') {
                showAlert('⚡ Quick upload successful!', 'success');
            }
            form.reset();
            document.getElementById('quick-file-name').textContent = 'No file selected';
            
            // Update stats
            updateUploadStats();
        } else {
            if (typeof showAlert === 'function') {
                showAlert(result.error || 'Upload failed', 'error');
            }
        }
    } catch (error) {
        console.error('Quick upload error:', error);
        if (typeof showAlert === 'function') {
            showAlert('Upload failed. Please try again.', 'error');
        }
    } finally {
        // Restore button
        submitBtn.innerHTML = `
            <span class="quick-btn-icon">🚀</span>
            <span>Upload Now</span>
        `;
        submitBtn.disabled = false;
    }
}

function animateProgress() {
    const progressFill = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    
    if (!progressFill || !progressText) return;
    
    let progress = 0;
    const interval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;
        
        progressFill.style.width = progress + '%';
        progressText.textContent = `Uploading... ${Math.round(progress)}%`;
        
        if (progress >= 100) {
            clearInterval(interval);
            progressText.textContent = 'Upload complete! ✅';
        }
    }, 200);
}

function updateUploadStats() {
    // Update quick stats with random increments for demo
    const totalUploads = document.getElementById('total-uploads');
    const totalDownloads = document.getElementById('total-downloads');
    const communityRank = document.getElementById('community-rank');
    
    if (totalUploads) {
        const current = parseInt(totalUploads.textContent) || 0;
        totalUploads.textContent = current + 1;
    }
    
    if (totalDownloads) {
        const current = parseInt(totalDownloads.textContent) || 0;
        totalDownloads.textContent = current + Math.floor(Math.random() * 5);
    }
    
    if (communityRank) {
        const ranks = ['#1', '#2', '#3', '#5', '#8', '#12', '#20'];
        communityRank.textContent = ranks[Math.floor(Math.random() * ranks.length)];
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Initialize enhanced uploads when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', setupEnhancedUploads);
} else {
    setupEnhancedUploads();
}