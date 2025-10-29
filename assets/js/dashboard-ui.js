// Dashboard UI micro-interactions: compact mode, theme toggle, small helpers
(function(){
  const body = document.body;
  const compactBtn = document.getElementById('compact-toggle');
  const themeBtn = document.getElementById('theme-toggle');

  // Initialize from localStorage
  const savedCompact = localStorage.getItem('dh_compact');
  const savedTheme = localStorage.getItem('dh_theme');
  if (savedCompact === '1') body.classList.add('compact');
  if (savedTheme === 'neon') body.classList.add('theme-neon');

  if (compactBtn) {
    compactBtn.addEventListener('click', () => {
      const isCompact = body.classList.toggle('compact');
      localStorage.setItem('dh_compact', isCompact ? '1' : '0');
      compactBtn.classList.toggle('active', isCompact);
    });
  }

  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const isNeon = body.classList.toggle('theme-neon');
      localStorage.setItem('dh_theme', isNeon ? 'neon' : 'default');
      themeBtn.classList.toggle('active', isNeon);
    });
  }

  // Simple stat counter animation for visible elements
  function animateCount(el, to) {
    // Prevent multiple animations on the same element
    if (el.dataset.animating === '1') return;
    el.dataset.animating = '1';
    
    const duration = 900;
    let start = null;
    const from = 0;
    function step(timestamp) {
      if (!start) start = timestamp;
      const progress = Math.min((timestamp - start) / duration, 1);
      el.textContent = Math.floor(from + (to - from) * progress);
      if (progress < 1) {
        requestAnimationFrame(step);
      } else {
        el.dataset.animating = '0';
      }
    }
    requestAnimationFrame(step);
  }

  function initCounters() {
    const opportunities = document.getElementById('opportunities-count');
    const notes = document.getElementById('notes-count');
    const ai = document.getElementById('ai-sessions-count');
    
    // Only animate if element exists and hasn't been animated yet
    if (opportunities && !opportunities.dataset.animated && !opportunities.dataset.animating) {
      const targetValue = parseInt(opportunities.textContent || '0', 10) || 0;
      opportunities.dataset.animated = '1';
      // animateCount(opportunities, targetValue);
      opportunities.textContent = targetValue; // Just set directly
    }
    if (notes && !notes.dataset.animated && !notes.dataset.animating) {
      const targetValue = parseInt(notes.textContent || '0', 10) || 0;
      notes.dataset.animated = '1';
      // animateCount(notes, targetValue);
      notes.textContent = targetValue; // Just set directly
    }
    if (ai && !ai.dataset.animated && !ai.dataset.animating) {
      const targetValue = parseInt(ai.textContent || '0', 10) || 0;
      ai.dataset.animated = '1';
      // animateCount(ai, targetValue);
      ai.textContent = targetValue; // Just set directly
    }
  }

  // Run when page loads and when tab becomes visible
  document.addEventListener('DOMContentLoaded', initCounters);
  document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') initCounters();
  });

  // Small keyboard shortcuts for quick demo: T -> theme, C -> compact
  document.addEventListener('keydown', (e) => {
    if (e.key === 'T' || e.key === 't') {
      if (themeBtn) themeBtn.click();
    }
    if (e.key === 'C' || e.key === 'c') {
      if (compactBtn) compactBtn.click();
    }
  });
})();

// Lightweight client-side handlers for the placeholder sections (no-backend friendly)
(function(){
  // helper to show a small inline message
  function flash(el, msg, timeout = 2500) {
    if (!el) return alert(msg);
    const note = document.createElement('div');
    note.className = 'flash-note';
    note.textContent = msg;
    el.prepend(note);
    setTimeout(() => note.remove(), timeout);
  }

  const appForm = document.getElementById('application-form');
  if (appForm) {
    appForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const title = document.getElementById('app-title').value || 'Untitled';
      const type = document.getElementById('app-type').value || 'job';
      const org = document.getElementById('app-org').value || '';
      const list = document.getElementById('my-applications');
      const item = document.createElement('div');
      item.className = 'mini-item';
      item.innerHTML = `<strong>${title}</strong> <span class="muted">(${type}${org? ' • '+org : ''})</span>`;
      list.appendChild(item);
      flash(list, 'Application added (local only)');
      appForm.reset();
    });
  }

  const skillForm = document.getElementById('skill-form');
  if (skillForm) {
    skillForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const name = document.getElementById('skill-name').value || 'New Skill';
      const list = document.getElementById('user-skills');
      const chip = document.createElement('span');
      chip.className = 'skill-chip';
      chip.textContent = name;
      list.appendChild(chip);
      flash(list, 'Skill added (local only)');
      skillForm.reset();
    });
  }

  const quickUpload = document.getElementById('quick-upload-form');
  if (quickUpload) {
    quickUpload.addEventListener('submit', (e) => {
      e.preventDefault();
      const title = document.getElementById('quick-note-title').value || 'Notes';
      const subject = document.getElementById('quick-note-subject').value || '';
      const fileEl = document.getElementById('quick-note-file');
      const fileName = fileEl && fileEl.files && fileEl.files[0] ? fileEl.files[0].name : 'no-file';
      const list = document.getElementById('notes-preview') || document.getElementById('my-applications');
      flash(quickUpload, `Uploaded “${title}” (${fileName}) — local only`);
      quickUpload.reset();
    });
  }

})();

// ===== SETTINGS FUNCTIONS =====
function openPasswordModal() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content settings-modal">
      <div class="modal-header">
        <h3>Change Password</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <form id="password-form">
          <div class="form-group">
            <label>Current Password</label>
            <input type="password" id="current-password" required>
          </div>
          <div class="form-group">
            <label>New Password</label>
            <input type="password" id="new-password" required>
          </div>
          <div class="form-group">
            <label>Confirm New Password</label>
            <input type="password" id="confirm-password" required>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="closeModal(this)">Cancel</button>
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </form>
      </div>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const current = document.getElementById('current-password').value;
    const newPass = document.getElementById('new-password').value;
    const confirm = document.getElementById('confirm-password').value;

    if (newPass !== confirm) {
      alert('New passwords do not match!');
      return;
    }

    // Here you would make an API call to change password
    alert('Password updated successfully!');
    closeModal(modal);
  });
}

function openEmailSettings() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content settings-modal">
      <div class="modal-header">
        <h3>Email Preferences</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <div class="email-settings">
          <div class="setting-item">
            <div class="setting-info">
              <h4>Weekly Digest</h4>
              <p>Get a summary of your activity and new opportunities</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Event Notifications</h4>
              <p>Be notified about upcoming hackathons and events</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
          <div class="setting-item">
            <div class="setting-info">
              <h4>Message Alerts</h4>
              <p>Get notified when you receive new messages</p>
            </div>
            <label class="toggle">
              <input type="checkbox" checked>
              <span class="toggle-slider"></span>
            </label>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn btn-primary" onclick="saveEmailSettings()">Save Preferences</button>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function openProfileSettings() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content settings-modal">
      <div class="modal-header">
        <h3>Profile Information</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <form id="profile-form">
          <div class="form-group">
            <label>Full Name</label>
            <input type="text" id="profile-name" value="John Doe">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" id="profile-email" value="john.doe@example.com">
          </div>
          <div class="form-group">
            <label>College/University</label>
            <input type="text" id="profile-college" value="MIT">
          </div>
          <div class="form-group">
            <label>Bio</label>
            <textarea id="profile-bio" rows="3">Passionate computer science student...</textarea>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="closeModal(this)">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Here you would make an API call to update profile
    alert('Profile updated successfully!');
    closeModal(modal);
  });
}

function openDataSettings() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content settings-modal">
      <div class="modal-header">
        <h3>Data & Privacy</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <div class="data-settings">
          <div class="data-card">
            <h4>Your Data</h4>
            <p>We collect and store the following information:</p>
            <ul>
              <li>Profile information (name, email, college)</li>
              <li>Study notes and uploaded files</li>
              <li>Application history and preferences</li>
              <li>Usage analytics and activity logs</li>
            </ul>
          </div>
          <div class="data-actions">
            <button class="btn btn-outline" onclick="exportUserData()">
              <span>📥</span> Export My Data
            </button>
            <button class="btn btn-outline" onclick="requestDataDeletion()">
              <span>🗑️</span> Request Data Deletion
            </button>
          </div>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function exportUserData() {
  // Simulate data export
  alert('Data export request submitted. You will receive an email with your data download link within 24 hours.');
}

function confirmDeleteAccount() {
  if (confirm('Are you sure you want to delete your account? This action cannot be undone and will permanently remove all your data.')) {
    if (confirm('This is your final warning. All your notes, applications, and profile data will be permanently deleted. Continue?')) {
      // Here you would make an API call to delete account
      alert('Account deletion request submitted. You will receive a confirmation email.');
    }
  }
}

function saveEmailSettings() {
  // Here you would save email preferences
  alert('Email preferences saved successfully!');
  closeModal(document.querySelector('.modal-overlay'));
}

function closeModal(button) {
  const modal = button.closest('.modal-overlay');
  if (modal) {
    modal.remove();
  }
}

// Theme switching functionality
document.addEventListener('DOMContentLoaded', function() {
  const themeInputs = document.querySelectorAll('input[name="theme"]');
  themeInputs.forEach(input => {
    input.addEventListener('change', function() {
      const theme = this.value;
      document.documentElement.setAttribute('data-theme', theme);

      // Update active theme option
      document.querySelectorAll('.theme-option').forEach(option => {
        option.classList.remove('active');
      });
      this.closest('.theme-option').classList.add('active');

      // Save theme preference
      localStorage.setItem('theme', theme);
    });
  });

  // Load saved theme
  const savedTheme = localStorage.getItem('theme') || 'light';
  document.querySelector(`input[name="theme"][value="${savedTheme}"]`).checked = true;
  document.documentElement.setAttribute('data-theme', savedTheme);
});

// ===== HELP CENTER FUNCTIONS =====
function searchHelp() {
  const query = document.getElementById('help-search').value.toLowerCase();
  if (!query.trim()) {
    alert('Please enter a search term');
    return;
  }

  // Simulate search results
  const results = [
    { title: 'Getting Started Guide', url: '#getting-started', type: 'Guide' },
    { title: 'How to Upload Notes', url: '#notes-sharing', type: 'Tutorial' },
    { title: 'Finding Internships', url: '#opportunities', type: 'Guide' },
    { title: 'AI Coach Features', url: '#ai-coach', type: 'FAQ' }
  ];

  showSearchResults(results.filter(result =>
    result.title.toLowerCase().includes(query) ||
    result.type.toLowerCase().includes(query)
  ));
}

function showSearchResults(results) {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h3>Search Results</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        ${results.length > 0 ?
          results.map(result => `
            <div class="search-result" onclick="window.location.hash='${result.url}'">
              <div class="result-icon">${getResultIcon(result.type)}</div>
              <div class="result-content">
                <h4>${result.title}</h4>
                <span class="result-type">${result.type}</span>
              </div>
              <div class="result-arrow">→</div>
            </div>
          `).join('') :
          '<p>No results found. Try different keywords.</p>'
        }
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function getResultIcon(type) {
  const icons = {
    'Guide': '📖',
    'Tutorial': '🎥',
    'FAQ': '❓'
  };
  return icons[type] || '📄';
}

function showTopic(topicId) {
  const topics = {
    'getting-started': {
      title: 'Getting Started with LearnX',
      content: `
        <h4>Welcome to LearnX! 🎉</h4>
        <p>Here's how to get started:</p>
        <ol>
          <li><strong>Complete your profile:</strong> Add your personal information, college details, and upload a profile picture.</li>
          <li><strong>Explore opportunities:</strong> Browse internships, jobs, and hackathons in the Opportunities section.</li>
          <li><strong>Share knowledge:</strong> Upload study notes to help your peers in the Notes section.</li>
          <li><strong>Get career guidance:</strong> Chat with our AI Coach for personalized advice.</li>
          <li><strong>Earn achievements:</strong> Complete activities to unlock badges and track your progress.</li>
        </ol>
        <p>Need help? Check out our video tutorials or contact support!</p>
      `
    },
    'notes-sharing': {
      title: 'Sharing Study Notes',
      content: `
        <h4>How to Share Your Notes 📝</h4>
        <p>Help your fellow students by sharing your study materials:</p>
        <ol>
          <li>Go to the <strong>Notes</strong> section from the sidebar.</li>
          <li>Click <strong>"Upload Notes"</strong> button.</li>
          <li>Fill in the details:
            <ul>
              <li><strong>Title:</strong> Give your notes a clear, descriptive name</li>
              <li><strong>Subject:</strong> Choose the relevant subject category</li>
              <li><strong>Description:</strong> Briefly describe what the notes cover</li>
              <li><strong>Tags:</strong> Add relevant tags to help others find your notes</li>
            </ul>
          </li>
          <li>Select your file (PDF, DOC, PPT, etc.)</li>
          <li>Click <strong>"Upload"</strong> to share with the community</li>
        </ol>
        <p><strong>Pro tip:</strong> Well-organized notes with good descriptions get more downloads and help more students!</p>
      `
    },
    'opportunities': {
      title: 'Finding Opportunities',
      content: `
        <h4>Discover Your Next Opportunity 💼</h4>
        <p>LearnX connects you with internships, jobs, and hackathons:</p>
        <h5>Internships & Jobs:</h5>
        <ol>
          <li>Navigate to <strong>Opportunities</strong> section</li>
          <li>Browse available positions by filters</li>
          <li>Click <strong>"Apply"</strong> on interesting opportunities</li>
          <li>Track your applications in the <strong>Applications</strong> section</li>
        </ol>
        <h5>Hackathons:</h5>
        <ol>
          <li>Visit the <strong>Hackathons</strong> page</li>
          <li>Explore upcoming events</li>
          <li>Register for events that match your skills</li>
          <li>Collaborate with other participants</li>
        </ol>
        <p><strong>Tip:</strong> Complete your profile to increase your chances of getting selected!</p>
      `
    },
    'ai-coach': {
      title: 'Using the AI Career Coach',
      content: `
        <h4>Your Personal Career Advisor 🤖</h4>
        <p>The AI Coach provides personalized career guidance:</p>
        <h5>What can the AI Coach help with?</h5>
        <ul>
          <li><strong>Resume building:</strong> Get tips to improve your resume</li>
          <li><strong>Interview preparation:</strong> Practice common interview questions</li>
          <li><strong>Skill development:</strong> Learn which skills to focus on</li>
          <li><strong>Career planning:</strong> Get advice on career paths</li>
          <li><strong>Networking:</strong> Tips for building professional connections</li>
        </ul>
        <h5>How to use it:</h5>
        <ol>
          <li>Go to <strong>AI Coach</strong> section</li>
          <li>Type your question or topic</li>
          <li>Get instant, personalized advice</li>
          <li>Save important conversations for later reference</li>
        </ol>
        <p><strong>Available 24/7:</strong> Get help whenever you need it!</p>
      `
    },
    'achievements': {
      title: 'Understanding Achievements',
      content: `
        <h4>Earn Badges & Track Progress 🏆</h4>
        <p>Achievements gamify your learning journey:</p>
        <h5>How to earn achievements:</h5>
        <ul>
          <li><strong>Upload Notes:</strong> Share study materials with peers</li>
          <li><strong>Apply to Opportunities:</strong> Show initiative in career search</li>
          <li><strong>Use AI Coach:</strong> Seek career guidance actively</li>
          <li><strong>Complete Profile:</strong> Build a comprehensive profile</li>
          <li><strong>Participate in Events:</strong> Join hackathons and networking events</li>
        </ul>
        <h5>Achievement Levels:</h5>
        <ul>
          <li><strong>Bronze:</strong> Getting started</li>
          <li><strong>Silver:</strong> Active participation</li>
          <li><strong>Gold:</strong> Community contributor</li>
          <li><strong>Platinum:</strong> Platform expert</li>
        </ul>
        <p>Check your <strong>Achievements</strong> section to see your progress and unlock new badges!</p>
      `
    },
    'profile': {
      title: 'Setting Up Your Profile',
      content: `
        <h4>Complete Your Student Profile 👤</h4>
        <p>A complete profile increases your chances of getting selected:</p>
        <h5>Essential Information:</h5>
        <ul>
          <li><strong>Personal Details:</strong> Name, email, phone number</li>
          <li><strong>Education:</strong> College, major, graduation year</li>
          <li><strong>Skills:</strong> Technical skills, programming languages</li>
          <li><strong>Experience:</strong> Previous internships, projects</li>
          <li><strong>Profile Picture:</strong> Professional headshot</li>
        </ul>
        <h5>How to update:</h5>
        <ol>
          <li>Click on your name/avatar in the sidebar</li>
          <li>Go to <strong>Profile</strong> section</li>
          <li>Fill in all required fields</li>
          <li>Upload a professional profile picture</li>
          <li>Save your changes</li>
        </ol>
        <p><strong>Pro tip:</strong> A complete profile can increase your visibility to employers by up to 300%!</p>
      `
    }
  };

  const topic = topics[topicId];
  if (!topic) return;

  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h3>${topic.title}</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        ${topic.content}
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function toggleFAQ(element) {
  const faqItem = element.closest('.faq-item');
  faqItem.classList.toggle('active');
}

function openVideoTutorials() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h3>Video Tutorials 🎥</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <div class="video-grid">
          <div class="video-item">
            <div class="video-thumbnail">🎬</div>
            <h4>Getting Started</h4>
            <p>Complete guide for new users</p>
            <button class="btn btn-primary">Watch Now</button>
          </div>
          <div class="video-item">
            <div class="video-thumbnail">📝</div>
            <h4>Uploading Notes</h4>
            <p>How to share study materials</p>
            <button class="btn btn-primary">Watch Now</button>
          </div>
          <div class="video-item">
            <div class="video-thumbnail">💼</div>
            <h4>Finding Opportunities</h4>
            <p>Navigate internships and jobs</p>
            <button class="btn btn-primary">Watch Now</button>
          </div>
          <div class="video-item">
            <div class="video-thumbnail">🤖</div>
            <h4>Using AI Coach</h4>
            <p>Get the most from AI guidance</p>
            <button class="btn btn-primary">Watch Now</button>
          </div>
        </div>
        <p style="text-align: center; margin-top: 2rem; color: var(--muted, #64748b);">
          More videos coming soon! Check back regularly for updates.
        </p>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function openUserGuides() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h3>User Guides 📋</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <div class="guides-list">
          <div class="guide-item">
            <div class="guide-icon">📖</div>
            <div class="guide-content">
              <h4>Complete User Manual</h4>
              <p>Comprehensive guide to all features</p>
              <a href="#" class="btn btn-secondary">Download PDF</a>
            </div>
          </div>
          <div class="guide-item">
            <div class="guide-icon">🎯</div>
            <div class="guide-content">
              <h4>Quick Start Guide</h4>
              <p>Get up and running in 5 minutes</p>
              <a href="#" class="btn btn-secondary">Download PDF</a>
            </div>
          </div>
          <div class="guide-item">
            <div class="guide-icon">💡</div>
            <div class="guide-content">
              <h4>Best Practices</h4>
              <p>Tips for getting the most out of LearnX</p>
              <a href="#" class="btn btn-secondary">Download PDF</a>
            </div>
          </div>
          <div class="guide-item">
            <div class="guide-icon">🔒</div>
            <div class="guide-content">
              <h4>Privacy & Security</h4>
              <p>How we protect your data</p>
              <a href="#" class="btn btn-secondary">Download PDF</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function openLiveSupport() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h3>Live Support 💬</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <div class="support-status">
          <div class="status-indicator online">
            <div class="status-dot"></div>
            <span>Support Team Online</span>
          </div>
          <p>Our support team is available 24/7 to help you with any questions or issues.</p>
        </div>
        <div class="support-options">
          <button class="btn btn-primary" onclick="startLiveChat()" style="width: 100%; margin-bottom: 1rem;">
            Start Live Chat
          </button>
          <p style="text-align: center; color: var(--muted, #64748b);">
            Average response time: <strong>2 minutes</strong>
          </p>
        </div>
      </div>
    </div>
  `;
  document.body.appendChild(modal);
}

function openEmailSupport() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content help-modal">
      <div class="modal-header">
        <h3>Email Support 📧</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <div class="modal-body">
        <form id="email-support-form">
          <div class="form-group">
            <label>Subject</label>
            <input type="text" id="support-subject" placeholder="Brief description of your issue" required>
          </div>
          <div class="form-group">
            <label>Category</label>
            <select id="support-category" required>
              <option value="">Select a category</option>
              <option value="technical">Technical Issue</option>
              <option value="account">Account Problem</option>
              <option value="feature">Feature Request</option>
              <option value="billing">Billing Question</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label>Message</label>
            <textarea id="support-message" rows="5" placeholder="Describe your issue in detail..." required></textarea>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="closeModal(this)">Cancel</button>
            <button type="submit" class="btn btn-primary">Send Email</button>
          </div>
        </form>
      </div>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('email-support-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Support email sent successfully! We\'ll respond within 24 hours.');
    closeModal(modal);
  });
}

function startLiveChat() {
  alert('Live chat feature coming soon! Please use email support for now.');
}

function sendSupportEmail() {
  openEmailSupport();
}

function callSupport() {
  alert('Phone support: +1 (555) 123-4567\nAvailable Mon-Fri, 9AM-6PM EST');
}

// ========================================
// REMINDERS FUNCTIONALITY
// ========================================

// Add reminder modal
function openAddReminderModal() {
  const modal = document.createElement('div');
  modal.className = 'modal-overlay';
  modal.innerHTML = `
    <div class="modal-content">
      <div class="modal-header">
        <h3>Add New Reminder</h3>
        <button class="modal-close" onclick="closeModal(this)">×</button>
      </div>
      <form id="add-reminder-form">
        <div class="form-group">
          <label for="reminder-text">Reminder Text</label>
          <input type="text" id="reminder-text" placeholder="What do you need to remember?" required>
        </div>
        <div class="form-group">
          <label for="reminder-due">Due Date (Optional)</label>
          <input type="datetime-local" id="reminder-due">
        </div>
        <div class="form-actions">
          <button type="button" class="btn btn-secondary" onclick="closeModal(this)">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Reminder</button>
        </div>
      </form>
    </div>
  `;
  document.body.appendChild(modal);

  document.getElementById('add-reminder-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const text = document.getElementById('reminder-text').value;
    const dueDate = document.getElementById('reminder-due').value;

    // Add reminder to the list (frontend only for now)
    addReminderToList(text, dueDate);
    closeModal(modal);
  });
}

function addReminderToList(text, dueDate) {
  const remindersList = document.getElementById('reminders-list');
  const reminderItem = document.createElement('div');
  reminderItem.className = 'reminder-item';

  const dueText = dueDate ? new Date(dueDate).toLocaleDateString() : 'No due date';
  const isOverdue = dueDate && new Date(dueDate) < new Date();

  reminderItem.innerHTML = `
    <div class="reminder-content">
      <input type="checkbox" class="reminder-checkbox" onchange="toggleReminder(this)">
      <span class="reminder-text">${text}</span>
    </div>
    <div class="reminder-meta">
      <span class="reminder-due ${isOverdue ? 'overdue' : ''}">Due: ${dueText}</span>
    </div>
  `;

  remindersList.insertBefore(reminderItem, remindersList.firstChild);
}

function toggleReminder(checkbox) {
  const reminderItem = checkbox.closest('.reminder-item');
  const reminderText = reminderItem.querySelector('.reminder-text');
  const reminderDue = reminderItem.querySelector('.reminder-due');

  if (checkbox.checked) {
    reminderItem.classList.add('completed');
    reminderText.style.textDecoration = 'line-through';
    reminderDue.textContent = 'Completed';
    reminderDue.classList.add('completed');
  } else {
    reminderItem.classList.remove('completed');
    reminderText.style.textDecoration = 'none';
    reminderDue.classList.remove('completed');
    // Restore original due date (this would need to be stored properly)
    reminderDue.textContent = 'Due: ' + (reminderItem.dataset.originalDue || 'No due date');
  }
}

// ========================================
// MESSAGES FUNCTIONALITY
// ========================================

function showInbox() {
  // Update navigation
  document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelector('.nav-btn[onclick="showInbox()"]').classList.add('active');

  // Show inbox content (placeholder for now)
  updateMessageView('inbox');
}

function showSent() {
  document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelector('.nav-btn[onclick="showSent()"]').classList.add('active');

  updateMessageView('sent');
}

function showDrafts() {
  document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelector('.nav-btn[onclick="showDrafts()"]').classList.add('active');

  updateMessageView('drafts');
}

function showArchived() {
  document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
  document.querySelector('.nav-btn[onclick="showArchived()"]').classList.add('active');

  updateMessageView('archived');
}

function updateMessageView(type) {
  const messageView = document.querySelector('.message-view');
  const messageInfo = messageView.querySelector('.message-info');

  switch(type) {
    case 'inbox':
      messageInfo.innerHTML = `
        <h3>Google Recruitment</h3>
        <p>Software Engineer Intern Application Update</p>
      `;
      break;
    case 'sent':
      messageInfo.innerHTML = `
        <h3>Sent Messages</h3>
        <p>Your sent communications</p>
      `;
      break;
    case 'drafts':
      messageInfo.innerHTML = `
        <h3>Draft Messages</h3>
        <p>Unfinished messages</p>
      `;
      break;
    case 'archived':
      messageInfo.innerHTML = `
        <h3>Archived Messages</h3>
        <p>Previously archived conversations</p>
      `;
      break;
  }
}

// Initialize messages functionality
document.addEventListener('DOMContentLoaded', function() {
  // Set inbox as default active
  const inboxBtn = document.querySelector('.nav-btn[onclick="showInbox()"]');
  if (inboxBtn) {
    inboxBtn.classList.add('active');
  }
});
