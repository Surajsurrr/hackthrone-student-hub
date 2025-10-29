// Dashboard JavaScript for dynamic content loading

document.addEventListener('DOMContentLoaded', function() {
    // Sidebar navigation
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href').substring(1);
            toggleSection(sectionId);
        });
    });

    // Load dashboard data
    if (document.getElementById('opportunities-count')) {
        loadDashboardStats();
    }

    // Load events for students
    if (document.getElementById('hackathons-list')) {
        loadEvents('hackathon');
    }

    // Load jobs for students
    if (document.getElementById('internships-list')) {
        loadJobs('internship');
    }

    // Load notes
    if (document.getElementById('notes-list')) {
        loadNotes();
    }

    // Load events for colleges
    if (document.querySelector('.events-list')) {
        loadCollegeEvents();
    }

    // Load jobs for companies
    if (document.querySelector('.jobs-list')) {
        loadCompanyJobs();
    }

    // Load users for admin
    if (document.getElementById('users-list')) {
        loadUsers();
    }

    // Load events for admin
    if (document.getElementById('events-list') && !document.querySelector('.events-list')) {
        loadAllEvents();
    }

    // Load jobs for admin
    if (document.getElementById('jobs-list') && !document.querySelector('.jobs-list')) {
        loadAllJobs();
    }

    // Form submissions
    setupFormSubmissions();
});

function toggleSection(sectionId) {
    const sections = document.querySelectorAll('.dashboard-section');
    sections.forEach(section => {
        section.classList.remove('active');
    });

    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }

    // Update sidebar active state
    const sidebarLinks = document.querySelectorAll('.sidebar a');
    sidebarLinks.forEach(link => {
        link.classList.remove('active');
    });

    const activeLink = document.querySelector(`.sidebar a[href="#${sectionId}"]`);
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

function loadDashboardStats() {
    fetch('api/student/getDashboard.php')
        .then(response => response.json())
        .then(data => {
            // Safely update student dashboard elements if they exist
            const opportunitiesEl = document.getElementById('opportunities-count');
            const notesEl = document.getElementById('notes-count');
            const aiSessionsEl = document.getElementById('ai-sessions-count');
            
            if (opportunitiesEl) opportunitiesEl.textContent = data.opportunities_count;
            if (notesEl) notesEl.textContent = data.notes_count;
            if (aiSessionsEl) aiSessionsEl.textContent = data.ai_sessions_count;
        })
        .catch(error => console.error('Error loading dashboard stats:', error));
}

function loadEvents(type = null) {
    fetch('api/student/getOpportunities.php')
        .then(response => response.json())
        .then(data => {
            const events = type ? data.events.filter(e => e.type === type) : data.events;
            displayEvents(events, 'hackathons-list');
        })
        .catch(error => console.error('Error loading events:', error));
}

function loadJobs(type = null) {
    fetch('api/student/getOpportunities.php')
        .then(response => response.json())
        .then(data => {
            const jobs = type ? data.jobs.filter(j => j.type === type) : data.jobs;
            displayJobs(jobs, 'internships-list');
        })
        .catch(error => console.error('Error loading jobs:', error));
}

function loadNotes() {
    fetch('api/student/fetchNotes.php')
        .then(response => response.json())
        .then(data => {
            displayNotes(data.notes);
        })
        .catch(error => console.error('Error loading notes:', error));
}

function loadCollegeEvents() {
    fetch('../api/college/getEvents.php')
        .then(response => response.json())
        .then(data => {
            displayEvents(data.events, 'events-list');
        })
        .catch(error => console.error('Error loading college events:', error));
}

function loadCompanyJobs() {
    fetch('../api/company/getJobs.php')
        .then(response => response.json())
        .then(data => {
            displayJobs(data.jobs, 'jobs-list');
        })
        .catch(error => console.error('Error loading company jobs:', error));
}

function loadUsers() {
    // This would need an admin API endpoint
    // For now, placeholder
    document.getElementById('users-list').innerHTML = '<p>Users management coming soon...</p>';
}

function loadAllEvents() {
    // This would need an admin API endpoint
    // For now, placeholder
    document.getElementById('events-list').innerHTML = '<p>Events management coming soon...</p>';
}

function loadAllJobs() {
    // This would need an admin API endpoint
    // For now, placeholder
    document.getElementById('jobs-list').innerHTML = '<p>Jobs management coming soon...</p>';
}

function displayEvents(events, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '';

    if (events.length === 0) {
        container.innerHTML = '<p>No events found.</p>';
        return;
    }

    events.forEach(event => {
        const eventCard = document.createElement('div');
        eventCard.className = 'event-card';
        eventCard.innerHTML = `
            <h3>${event.title}</h3>
            <p>${truncateText(event.description, 100)}</p>
            <p class="date">Date: ${formatDate(event.date)}</p>
            <p>Type: ${event.type}</p>
            ${event.college_name ? `<p>College: ${event.college_name}</p>` : ''}
        `;
        container.appendChild(eventCard);
    });
}

function displayJobs(jobs, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '';

    if (jobs.length === 0) {
        container.innerHTML = '<p>No jobs found.</p>';
        return;
    }

    jobs.forEach(job => {
        const jobCard = document.createElement('div');
        jobCard.className = 'job-card';
        jobCard.innerHTML = `
            <h3>${job.title}</h3>
            <p>${truncateText(job.description, 100)}</p>
            <p class="company">Company: ${job.company_name}</p>
            <p>Type: ${job.type}</p>
            ${job.salary ? `<p>Salary: ${job.salary}</p>` : ''}
        `;
        container.appendChild(jobCard);
    });
}

function displayNotes(notes) {
    const container = document.getElementById('notes-list');
    if (!container) return;

    container.innerHTML = '';

    if (notes.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 60px 20px; color: #64748b;">
                <div style="font-size: 3rem; margin-bottom: 20px;">📚</div>
                <h3 style="color: #374151; margin-bottom: 10px;">No notes found</h3>
                <p>Be the first to share your knowledge with others!</p>
            </div>
        `;
        return;
    }

    notes.forEach(note => {
        const noteCard = document.createElement('div');
        noteCard.className = 'note-card';
        
        // Get first letter of uploader name for avatar
        const avatarLetter = note.uploader_name ? note.uploader_name.charAt(0).toUpperCase() : 'U';
        
        // Format date
        const uploadDate = new Date(note.created_at || Date.now()).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        });

        // Determine file type icon
        const fileExt = note.file_path ? note.file_path.split('.').pop().toLowerCase() : 'file';
        const fileIcons = {
            'pdf': '📄',
            'doc': '📝',
            'docx': '📝',
            'txt': '📄',
            'ppt': '📊',
            'pptx': '📊'
        };
        const fileIcon = fileIcons[fileExt] || '📎';

        noteCard.innerHTML = `
            <div class="note-header">
                <div>
                    <div class="note-title">${note.title || 'Untitled Note'}</div>
                    <div class="note-subject">${note.subject || 'General'}</div>
                </div>
                <div style="font-size: 1.5rem;">${fileIcon}</div>
            </div>
            
            <div class="note-description">
                ${truncateText(note.description || 'No description provided', 120)}
            </div>
            
            <div class="note-meta">
                <div class="note-author">
                    <div class="author-avatar">${avatarLetter}</div>
                    <div class="author-info">
                        <div class="author-name">${note.uploader_name || 'Anonymous'}</div>
                        <div class="upload-date">${uploadDate}</div>
                    </div>
                </div>
                
                <div class="note-actions">
                    <button class="action-btn like-btn" onclick="toggleLike(${note.id || Math.random()})">
                        ❤️ <span class="like-count">${note.likes || Math.floor(Math.random() * 50) + 1}</span>
                    </button>
                    <button class="action-btn download-btn" onclick="downloadNote('${note.file_path || '#'}')">
                        📥 Download
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(noteCard);
    });
}

function setupFormSubmissions() {
    // Profile update
    const profileForm = document.getElementById('profile-form');
    if (profileForm) {
        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('api/student/createProfile.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Profile updated successfully!', 'success');
                } else {
                    showAlert(data.error || 'Failed to update profile', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred', 'error');
            });
        });
    }

    // Note upload
    const uploadForm = document.getElementById('upload-form');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const title = document.getElementById('note-title').value.trim();
            const subject = document.getElementById('note-subject').value;
            const description = document.getElementById('note-description').value.trim();
            const fileInput = document.getElementById('note-file');
            
            // Validate form
            if (!title) {
                showAlert('Please enter a title for your notes', 'error');
                return;
            }
            
            if (!subject) {
                showAlert('Please select a subject', 'error');
                return;
            }
            
            if (!fileInput.files[0]) {
                showAlert('Please select a file to upload', 'error');
                return;
            }
            
            // Check file size (10MB limit)
            const fileSize = fileInput.files[0].size / 1024 / 1024; // Convert to MB
            if (fileSize > 10) {
                showAlert('File size must be less than 10MB', 'error');
                return;
            }
            
            const formData = new FormData();
            formData.append('title', title);
            formData.append('subject', subject);
            formData.append('description', description);
            formData.append('file', fileInput.files[0]);
            
            // Show loading state
            const submitBtn = this.querySelector('.upload-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="btn-icon">⏳</span> Uploading...';
            submitBtn.disabled = true;

            fetch('api/student/uploadNotes.php', {
                method: 'POST',
                credentials: 'same-origin',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Notes uploaded successfully! 🎉', 'success');
                    this.reset();
                    
                    // Reset file upload display
                    const uploadContent = document.querySelector('.file-upload-content');
                    if (uploadContent) {
                        uploadContent.innerHTML = `
                            <div class="file-upload-icon">📎</div>
                            <div class="file-upload-text">
                                <span class="primary-text">Click to upload or drag & drop</span>
                                <span class="secondary-text">PDF, DOC, DOCX, TXT, PPT files (Max 10MB)</span>
                            </div>
                        `;
                    }
                    
                    loadNotes();
                    updateNotesStats();
                } else {
                    showAlert(data.error || 'Failed to upload notes', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred while uploading', 'error');
            })
            .finally(() => {
                // Restore button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    // Event creation
    const createEventForm = document.getElementById('create-event-form');
    if (createEventForm) {
        createEventForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../api/college/createEvent.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Event created successfully!', 'success');
                    this.reset();
                    loadCollegeEvents();
                } else {
                    showAlert(data.error || 'Failed to create event', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred', 'error');
            });
        });
    }

    // Job creation
    const createJobForm = document.getElementById('create-job-form');
    if (createJobForm) {
        createJobForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('../api/company/createJob.php', {
                method: 'POST',
                body: JSON.stringify(Object.fromEntries(formData)),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Job posted successfully!', 'success');
                    this.reset();
                    loadCompanyJobs();
                } else {
                    showAlert(data.error || 'Failed to post job', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred', 'error');
            });
        });
    }
}

// Utility functions
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.textContent = message;

    const container = document.querySelector('.main-content') || document.querySelector('.container') || document.body;
    container.insertBefore(alertDiv, container.firstChild);

    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength) + '...';
}

// Modern Notes Functions
function toggleLike(noteId) {
    const likeBtn = event.target.closest('.like-btn');
    const likeCount = likeBtn.querySelector('.like-count');
    const currentCount = parseInt(likeCount.textContent);
    
    if (likeBtn.classList.contains('liked')) {
        likeBtn.classList.remove('liked');
        likeCount.textContent = currentCount - 1;
        likeBtn.innerHTML = `❤️ <span class="like-count">${currentCount - 1}</span>`;
    } else {
        likeBtn.classList.add('liked');
        likeCount.textContent = currentCount + 1;
        likeBtn.innerHTML = `💖 <span class="like-count">${currentCount + 1}</span>`;
        
        // Add some animation
        likeBtn.style.transform = 'scale(1.2)';
        setTimeout(() => {
            likeBtn.style.transform = 'scale(1)';
        }, 200);
    }
}

function downloadNote(filePath) {
    if (filePath && filePath !== '#') {
        // Create download link
        const link = document.createElement('a');
        link.href = `uploads/${filePath}`;
        link.download = filePath;
        link.target = '_blank';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        
        // Show notification
        if (typeof showNotification === 'function') {
            showNotification('Download started!', 'success');
        }
    } else {
        if (typeof showNotification === 'function') {
            showNotification('File not available for download', 'error');
        }
    }
}

function setupNotesSearch() {
    const searchInput = document.getElementById('search-notes');
    const subjectFilter = document.getElementById('filter-subject');
    const sortSelect = document.getElementById('sort-notes');
    
    if (searchInput) {
        searchInput.addEventListener('input', debounceSearch(filterAndDisplayNotes, 300));
    }
    
    if (subjectFilter) {
        subjectFilter.addEventListener('change', filterAndDisplayNotes);
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', filterAndDisplayNotes);
    }
}

function filterAndDisplayNotes() {
    // This would typically fetch filtered results from the server
    // For now, we'll just reload the notes
    if (typeof loadNotes === 'function') {
        loadNotes();
    }
}

function setupFileUpload() {
    const fileInput = document.getElementById('note-file');
    const uploadArea = document.querySelector('.file-upload-content');
    
    if (fileInput && uploadArea) {
        // Drag and drop functionality
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#667eea';
            uploadArea.style.background = 'rgba(102, 126, 234, 0.1)';
        });
        
        uploadArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#cbd5e1';
            uploadArea.style.background = '#f8fafc';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#cbd5e1';
            uploadArea.style.background = '#f8fafc';
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateFileUploadDisplay(files[0]);
            }
        });
        
        fileInput.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                updateFileUploadDisplay(e.target.files[0]);
            }
        });
    }
}

function updateFileUploadDisplay(file) {
    const uploadContent = document.querySelector('.file-upload-content');
    if (uploadContent && file) {
        const fileSize = (file.size / 1024 / 1024).toFixed(2); // Convert to MB
        uploadContent.innerHTML = `
            <div class="file-upload-icon">✅</div>
            <div class="file-upload-text">
                <span class="primary-text">File Selected: ${file.name}</span>
                <span class="secondary-text">Size: ${fileSize} MB</span>
            </div>
        `;
    }
}

function updateNotesStats() {
    // Update stats with sample data for demo
    const totalNotes = document.getElementById('total-notes');
    const contributors = document.getElementById('contributors');
    const myNotes = document.getElementById('my-notes');
    
    if (totalNotes) totalNotes.textContent = Math.floor(Math.random() * 200) + 100;
    if (contributors) contributors.textContent = Math.floor(Math.random() * 100) + 50;
    if (myNotes) myNotes.textContent = Math.floor(Math.random() * 20) + 5;
}

// Utility function for debouncing
function debounceSearch(func, wait) {
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

// Initialize modern notes features when page loads
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeNotesFeatures);
} else {
    initializeNotesFeatures();
}

function initializeNotesFeatures() {
    setupNotesSearch();
    setupFileUpload();
    updateNotesStats();
    addNotificationStyles();
}

// Add notification styles and showAlert function
function addNotificationStyles() {
    const style = document.createElement('style');
    style.textContent = `
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
            z-index: 1000;
            transform: translateX(400px);
            transition: all 0.3s ease;
            font-weight: 600;
            min-width: 300px;
            max-width: 400px;
        }
        .notification.show {
            transform: translateX(0);
        }
        .notification.hidden {
            transform: translateX(400px);
        }
        .notification.error {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            box-shadow: 0 10px 25px rgba(239, 68, 68, 0.3);
        }
        .notification.warning {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
        }
    `;
    document.head.appendChild(style);
}

// Enhanced showAlert function for notifications
function showAlert(message, type = 'success') {
    const notification = document.getElementById('notification');
    const messageEl = document.getElementById('notification-message');
    
    if (!notification || !messageEl) {
        // Fallback to browser alert if notification elements don't exist
        alert(message);
        return;
    }
    
    messageEl.textContent = message;
    notification.className = `notification ${type}`;
    
    // Show notification
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Hide notification after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        notification.classList.add('hidden');
    }, 4000);
}
