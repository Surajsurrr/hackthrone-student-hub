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

// ============ NOTES SECTION FUNCTIONALITY ============

// Initialize notes functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeNotesUpload();
    initializeViewToggle();
    loadMyNotes();
    loadPopularNotes();
});

// File upload functionality
function initializeNotesUpload() {
    const fileDropZone = document.getElementById('file-drop-zone');
    const fileInput = document.getElementById('note-file');
    const filePreview = document.getElementById('file-preview');
    const uploadForm = document.getElementById('upload-form');

    if (!fileDropZone || !fileInput) return;

    // Click to browse files
    fileDropZone.addEventListener('click', () => {
        fileInput.click();
    });

    // Drag and drop functionality
    fileDropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        fileDropZone.classList.add('dragover');
    });

    fileDropZone.addEventListener('dragleave', (e) => {
        e.preventDefault();
        fileDropZone.classList.remove('dragover');
    });

    fileDropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        fileDropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect(files[0]);
        }
    });

    // File input change
    fileInput.addEventListener('change', (e) => {
        if (e.target.files.length > 0) {
            handleFileSelect(e.target.files[0]);
        }
    });

    // Form submission
    if (uploadForm) {
        uploadForm.addEventListener('submit', handleFormSubmit);
    }
}

// Handle file selection
function handleFileSelect(file) {
    const filePreview = document.getElementById('file-preview');
    const fileDropZone = document.getElementById('file-drop-zone');
    
    if (!filePreview) return;

    // Validate file type
    const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
    
    if (!allowedTypes.includes(file.type)) {
        showNotification('Please select a valid file type (PDF, DOC, DOCX, TXT, PPT, PPTX)', 'error');
        return;
    }

    // Validate file size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        showNotification('File size must be less than 10MB', 'error');
        return;
    }

    // Show file preview
    const fileName = file.name;
    const fileSize = formatFileSize(file.size);
    
    filePreview.querySelector('.file-name').textContent = fileName;
    filePreview.querySelector('.file-size').textContent = fileSize;
    filePreview.style.display = 'flex';
    
    // Hide drop zone content
    fileDropZone.querySelector('.drop-zone-content').style.display = 'none';
}

// Remove selected file
function removeFile() {
    const fileInput = document.getElementById('note-file');
    const filePreview = document.getElementById('file-preview');
    const fileDropZone = document.getElementById('file-drop-zone');
    
    if (fileInput) fileInput.value = '';
    if (filePreview) filePreview.style.display = 'none';
    if (fileDropZone) fileDropZone.querySelector('.drop-zone-content').style.display = 'flex';
}

// Handle form submission
async function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData();
    const title = document.getElementById('note-title').value;
    const subject = document.getElementById('note-subject').value;
    const description = document.getElementById('note-description').value;
    const file = document.getElementById('note-file').files[0];
    
    // Validation
    if (!title || !subject || !file) {
        showNotification('Please fill in all required fields', 'error');
        return;
    }
    
    formData.append('title', title);
    formData.append('subject', subject);
    formData.append('description', description);
    formData.append('file', file);
    
    const progressElement = document.getElementById('upload-progress');
    const progressFill = progressElement.querySelector('.progress-fill');
    const progressText = progressElement.querySelector('.progress-text');
    
    try {
        progressElement.style.display = 'block';
        
        const response = await fetch('api/student/uploadNotes.php', {
            method: 'POST',
            body: formData
        });
        
        // Simulate progress
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 10;
            progressFill.style.width = progress + '%';
            progressText.textContent = Uploading... %;
            
            if (progress >= 100) {
                clearInterval(progressInterval);
            }
        }, 100);
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Note uploaded successfully!', 'success');
            resetForm();
            loadMyNotes(); // Reload notes
        } else {
            showNotification(result.message || 'Upload failed', 'error');
        }
    } catch (error) {
        console.error('Upload error:', error);
        showNotification('Upload failed. Please try again.', 'error');
    } finally {
        setTimeout(() => {
            progressElement.style.display = 'none';
            progressFill.style.width = '0%';
            progressText.textContent = 'Uploading... 0%';
        }, 1000);
    }
}

// Reset form after successful upload
function resetForm() {
    document.getElementById('upload-form').reset();
    removeFile();
}

// View toggle functionality
function initializeViewToggle() {
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            toggleBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            
            const view = btn.getAttribute('data-view');
            toggleNotesView(view);
        });
    });
}

// Toggle between grid and list view
function toggleNotesView(view) {
    const notesContainers = document.querySelectorAll('.notes-container');
    
    notesContainers.forEach(container => {
        if (view === 'list') {
            container.classList.add('list-view');
        } else {
            container.classList.remove('list-view');
        }
    });
}

// Load user's notes
async function loadMyNotes() {
    const container = document.getElementById('my-notes-list');
    if (!container) return;
    
    try {
        const response = await fetch('api/student/fetchNotes.php');
        const data = await response.json();
        
        if (data.success && data.notes) {
            renderNotes(container, data.notes, true);
        } else {
            container.innerHTML = '<div class="empty-state"><p> No notes uploaded yet. Share your knowledge!</p></div>';
        }
    } catch (error) {
        console.error('Error loading notes:', error);
        container.innerHTML = '<div class="error-state"><p> Failed to load notes</p></div>';
    }
}

// Load popular notes
async function loadPopularNotes() {
    const container = document.getElementById('popular-notes-list');
    if (!container) return;
    
    try {
        const response = await fetch('api/student/getPopularNotes.php');
        const data = await response.json();
        
        if (data.success && data.notes) {
            renderNotes(container, data.notes, false);
        } else {
            container.innerHTML = '<div class="empty-state"><p> No popular notes available</p></div>';
        }
    } catch (error) {
        console.error('Error loading popular notes:', error);
        container.innerHTML = '<div class="error-state"><p> Failed to load popular notes</p></div>';
    }
}

// Render notes in container
function renderNotes(container, notes, isOwner = false) {
    container.innerHTML = '';
    
    notes.forEach(note => {
        const noteCard = createNoteCard(note, isOwner);
        container.appendChild(noteCard);
    });
}

// Create note card element
function createNoteCard(note, isOwner = false) {
    const card = document.createElement('div');
    card.className = 'note-card';
    
    const actions = isOwner ? 
        <div class="note-actions">
            <button class="note-action-btn" onclick="editNote()"> Edit</button>
            <button class="note-action-btn" onclick="deleteNote()"> Delete</button>
        </div> :
        <div class="note-actions">
            <button class="note-action-btn" onclick="downloadNote()"> Download</button>
            <button class="note-action-btn" onclick="likeNote()"> Like</button>
        </div>;
    
    card.innerHTML = 
        <div class="note-header">
            <h4 class="note-title"></h4>
            <span class="note-subject"></span>
        </div>
        <p class="note-description"></p>
        <div class="note-footer">
            <div class="note-meta">
                <span> </span>
                <span> </span>
                <span> </span>
            </div>
            
        </div>
    ;
    
    // Add click event to view note details
    card.addEventListener('click', (e) => {
        if (!e.target.closest('.note-actions')) {
            viewNoteDetails(note);
        }
    });
    
    return card;
}

// Note actions
function editNote(noteId) {
    // Implement edit functionality
    console.log('Edit note:', noteId);
}

function deleteNote(noteId) {
    if (confirm('Are you sure you want to delete this note?')) {
        // Implement delete functionality
        console.log('Delete note:', noteId);
    }
}

function downloadNote(noteId) {
    window.open(pi/student/downloadNote.php?id=, '_blank');
}

function likeNote(noteId) {
    // Implement like functionality
    console.log('Like note:', noteId);
}

function viewNoteDetails(note) {
    // Implement note details modal
    console.log('View note details:', note);
}

// Utility functions
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

function showNotification(message, type = 'info') {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = 
otification notification-;
    notification.innerHTML = 
        <span></span>
        <button onclick="this.parentElement.remove()"></button>
    ;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.remove();
        }
    }, 5000);
}

// ============ ENHANCED UPLOAD FUNCTIONALITY ============

// Enhanced Quick Upload Functionality
document.addEventListener('DOMContentLoaded', function() {
    initializeQuickUpload();
    loadUploadStats();
});

function initializeQuickUpload() {
    const quickForm = document.getElementById('quick-upload-form');
    const fileInput = document.getElementById('quick-note-file');
    const fileNameDisplay = document.getElementById('quick-file-name');
    const uploadBtn = document.querySelector('.file-upload-btn');
    
    if (!quickForm || !fileInput) return;

    // Enhanced file selection with visual feedback
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            updateFileDisplay(file);
            addFileValidation(file);
        }
    });

    // Drag and drop functionality for file upload button
    uploadBtn.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadBtn.classList.add('dragover');
    });

    uploadBtn.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadBtn.classList.remove('dragover');
    });

    uploadBtn.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadBtn.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            updateFileDisplay(files[0]);
            addFileValidation(files[0]);
        }
    });

    // Enhanced form submission
    quickForm.addEventListener('submit', function(e) {
        e.preventDefault();
        handleQuickUpload();
    });

    // Real-time validation
    addRealTimeValidation();
}

function updateFileDisplay(file) {
    const fileNameDisplay = document.getElementById('quick-file-name');
    const uploadText = document.querySelector('.upload-text');
    
    if (file) {
        fileNameDisplay.textContent = file.name;
        fileNameDisplay.classList.add('has-file');
        uploadText.textContent = 'Change File';
        
        // Add file size and type info
        const fileSize = formatFileSize(file.size);
        const fileInfo = document.createElement('div');
        fileInfo.className = 'file-info';
        fileInfo.innerHTML = <small>   </small>;
        
        const existingInfo = fileNameDisplay.parentNode.querySelector('.file-info');
        if (existingInfo) {
            existingInfo.remove();
        }
        fileNameDisplay.parentNode.appendChild(fileInfo);
    }
}

function addFileValidation(file) {
    const maxSize = 10 * 1024 * 1024; // 10MB
    const allowedTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'text/plain',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
    ];

    let isValid = true;
    let errorMessage = '';

    if (file.size > maxSize) {
        isValid = false;
        errorMessage = 'File size must be less than 10MB';
    }

    if (!allowedTypes.includes(file.type)) {
        isValid = false;
        errorMessage = 'File type not supported. Please use PDF, DOC, DOCX, TXT, PPT, or PPTX';
    }

    displayValidationFeedback(isValid, errorMessage);
    return isValid;
}

function displayValidationFeedback(isValid, message) {
    const fileUploadSection = document.querySelector('.quick-file-upload');
    const existingFeedback = fileUploadSection.querySelector('.validation-feedback');
    
    if (existingFeedback) {
        existingFeedback.remove();
    }

    if (!isValid && message) {
        const feedback = document.createElement('div');
        feedback.className = 'validation-feedback error';
        feedback.innerHTML = <span class="error-icon"></span> ;
        fileUploadSection.appendChild(feedback);
    } else if (isValid) {
        const feedback = document.createElement('div');
        feedback.className = 'validation-feedback success';
        feedback.innerHTML = <span class="success-icon"></span> File ready to upload;
        fileUploadSection.appendChild(feedback);
    }
}

function addRealTimeValidation() {
    const titleInput = document.getElementById('quick-note-title');
    const subjectSelect = document.getElementById('quick-note-subject');

    if (titleInput) {
        titleInput.addEventListener('input', function() {
            validateField(this, this.value.length >= 3, 'Title must be at least 3 characters');
        });
    }

    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            validateField(this, this.value !== '', 'Please select a subject');
        });
    }
}

function validateField(field, isValid, errorMessage) {
    const inputGroup = field.closest('.quick-input-group');
    const existingError = inputGroup.querySelector('.field-error');
    
    if (existingError) {
        existingError.remove();
    }

    if (isValid) {
        inputGroup.classList.remove('error');
        inputGroup.classList.add('success');
    } else {
        inputGroup.classList.remove('success');
        inputGroup.classList.add('error');
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'field-error';
        errorDiv.textContent = errorMessage;
        inputGroup.appendChild(errorDiv);
    }
}

async function handleQuickUpload() {
    const submitBtn = document.querySelector('.quick-submit-btn');
    const titleInput = document.getElementById('quick-note-title');
    const subjectSelect = document.getElementById('quick-note-subject');
    const fileInput = document.getElementById('quick-note-file');

    // Validate all fields
    const title = titleInput.value.trim();
    const subject = subjectSelect.value;
    const file = fileInput.files[0];

    if (!title || !subject || !file) {
        showNotification('Please fill in all fields and select a file', 'error');
        return;
    }

    // Show loading state
    setUploadingState(submitBtn, true);

    try {
        const formData = new FormData();
        formData.append('title', title);
        formData.append('subject', subject);
        formData.append('file', file);
        formData.append('action', 'quick_upload');

        const response = await fetch('../api/student/uploadNotes.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            showNotification('File uploaded successfully! ', 'success');
            resetQuickUploadForm();
            updateUploadStats();
            
            // Add success animation
            submitBtn.classList.add('success-animation');
            setTimeout(() => {
                submitBtn.classList.remove('success-animation');
            }, 2000);
        } else {
            throw new Error(result.message || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload error:', error);
        showNotification(Upload failed: , 'error');
    } finally {
        setUploadingState(submitBtn, false);
    }
}

function setUploadingState(button, isUploading) {
    const iconSpan = button.querySelector('.quick-btn-icon');
    const textSpan = button.querySelector('span:last-child');
    
    if (isUploading) {
        button.disabled = true;
        button.classList.add('uploading');
        iconSpan.textContent = '';
        textSpan.textContent = 'Uploading...';
        
        // Add progress animation
        button.style.background = 'linear-gradient(90deg, var(--accent1) 0%, var(--accent2) 50%, var(--accent1) 100%)';
        button.style.backgroundSize = '200% 100%';
        button.style.animation = 'uploadProgress 2s ease-in-out infinite';
    } else {
        button.disabled = false;
        button.classList.remove('uploading');
        iconSpan.textContent = '';
        textSpan.textContent = 'Upload Now';
        button.style.background = '';
        button.style.animation = '';
    }
}

function resetQuickUploadForm() {
    const form = document.getElementById('quick-upload-form');
    if (form) {
        form.reset();
        
        const fileNameDisplay = document.getElementById('quick-file-name');
        const uploadText = document.querySelector('.upload-text');
        
        if (fileNameDisplay) {
            fileNameDisplay.textContent = 'No file selected';
            fileNameDisplay.classList.remove('has-file');
        }
        
        if (uploadText) {
            uploadText.textContent = 'Choose File';
        }
        
        // Clear validation feedback
        document.querySelectorAll('.validation-feedback, .field-error').forEach(el => el.remove());
        document.querySelectorAll('.quick-input-group').forEach(group => {
            group.classList.remove('error', 'success');
        });
    }
}

async function loadUploadStats() {
    try {
        const response = await fetch('../api/student/getDashboard.php?section=upload_stats');
        const data = await response.json();
        
        if (data.success && data.stats) {
            updateStatsDisplay(data.stats);
        }
    } catch (error) {
        console.error('Error loading upload stats:', error);
    }
}

function updateUploadStats() {
    // Increment local stats optimistically
    const uploadsElement = document.getElementById('total-uploads');
    if (uploadsElement) {
        const currentCount = parseInt(uploadsElement.textContent) || 0;
        animateNumber(uploadsElement, currentCount, currentCount + 1);
    }
    
    // Reload actual stats from server
    setTimeout(loadUploadStats, 1000);
}

function updateStatsDisplay(stats) {
    if (stats.uploads !== undefined) {
        const uploadsElement = document.getElementById('total-uploads');
        if (uploadsElement) {
            animateNumber(uploadsElement, parseInt(uploadsElement.textContent) || 0, stats.uploads);
        }
    }
    
    if (stats.downloads !== undefined) {
        const downloadsElement = document.getElementById('total-downloads');
        if (downloadsElement) {
            animateNumber(downloadsElement, parseInt(downloadsElement.textContent) || 0, stats.downloads);
        }
    }
    
    if (stats.rank !== undefined) {
        const rankElement = document.getElementById('community-rank');
        if (rankElement) {
            rankElement.textContent = #;
        }
    }
}

function animateNumber(element, start, end) {
    const duration = 1000;
    const startTime = performance.now();
    
    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        const current = Math.floor(start + (end - start) * progress);
        element.textContent = current;
        
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }
    
    requestAnimationFrame(update);
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Enhanced notification system for uploads
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = upload-notification ;
    
    const icon = type === 'success' ? '' : type === 'error' ? '' : '?';
    notification.innerHTML = 
        <div class="notification-content">
            <span class="notification-icon"></span>
            <span class="notification-message"></span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()"></button>
    ;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// CSS animations for upload progress
const uploadStyles = 
@keyframes uploadProgress {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.success-animation {
    animation: successPulse 0.6s ease-in-out;
}

@keyframes successPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

.upload-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    border-radius: 12px;
    padding: 1rem 1.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    border-left: 4px solid var(--accent1);
    transform: translateX(100%);
    transition: transform 0.3s ease;
    z-index: 10000;
    max-width: 400px;
}

.upload-notification.show {
    transform: translateX(0);
}

.upload-notification.success {
    border-left-color: #10b981;
}

.upload-notification.error {
    border-left-color: #ef4444;
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-close {
    position: absolute;
    top: 0.5rem;
    right: 0.75rem;
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: #6b7280;
}

.validation-feedback {
    margin-top: 0.5rem;
    padding: 0.5rem;
    border-radius: 6px;
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.validation-feedback.error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.validation-feedback.success {
    background: #f0fdf4;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.field-error {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

.quick-input-group.error {
    border-color: #dc2626 !important;
}

.quick-input-group.success {
    border-color: #16a34a !important;
}

.file-info {
    margin-top: 0.5rem;
    color: #6b7280;
}
;

// Inject styles
const styleSheet = document.createElement('style');
styleSheet.textContent = uploadStyles;
document.head.appendChild(styleSheet);
