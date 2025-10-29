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

  // Global helper to add a reminder quickly
  window.addReminder = function() {
    const text = prompt('Reminder text');
    if (!text) return;
    const list = document.getElementById('reminders-list');
    const row = document.createElement('div');
    row.className = 'reminder-item';
    const when = new Date();
    row.innerHTML = `<strong>${text}</strong><div class="muted">added ${when.toLocaleString()}</div>`;
    if (list) list.appendChild(row);
    flash(list || document.body, 'Reminder added (local only)');
  };

})();
