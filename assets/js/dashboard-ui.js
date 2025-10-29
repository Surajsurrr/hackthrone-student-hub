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
