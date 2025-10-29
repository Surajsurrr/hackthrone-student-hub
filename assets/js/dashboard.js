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
        container.innerHTML = '<p>No notes shared yet.</p>';
        return;
    }

    notes.forEach(note => {
        const noteCard = document.createElement('div');
        noteCard.className = 'note-card';
        noteCard.innerHTML = `
            <h3>${note.title}</h3>
            <p>${truncateText(note.description, 100)}</p>
            <p class="uploader">Uploaded by: ${note.uploader_name}</p>
            <a href="uploads/${note.file_path}" target="_blank" class="btn">Download</a>
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
            const formData = new FormData(this);

            fetch('api/student/uploadNotes.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Notes uploaded successfully!', 'success');
                    this.reset();
                    loadNotes();
                } else {
                    showAlert(data.error || 'Failed to upload notes', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred', 'error');
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
