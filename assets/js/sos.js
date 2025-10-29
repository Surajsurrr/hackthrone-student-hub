// Floating SOS wellness button (student scope)
(function(){
  const style = document.createElement('style');
  style.textContent = `
    .sos-fab {
      position: fixed; right: 22px; bottom: 22px; z-index: 10000;
      width: 58px; height: 58px; border-radius: 50%;
      background: linear-gradient(135deg, #ef4444, #dc2626);
      color: #fff; display: flex; align-items: center; justify-content: center;
      font-size: 22px; box-shadow: 0 10px 20px rgba(239,68,68,.4);
      cursor: pointer; transition: transform .2s ease, box-shadow .2s ease;
    }
    .sos-fab:hover { transform: translateY(-2px); box-shadow: 0 14px 26px rgba(239,68,68,.5); }
    .sos-modal-backdrop {
      position: fixed; inset: 0; background: rgba(0,0,0,.5); backdrop-filter: blur(3px);
      display: none; align-items: center; justify-content: center; z-index: 10001;
    }
    .sos-modal { width: min(560px, 92vw); background: #fff; border-radius: 16px; padding: 22px; 
      box-shadow: 0 30px 70px rgba(0,0,0,.25); }
    .sos-header { display:flex; align-items:center; justify-content: space-between; margin-bottom: 8px; }
    .sos-title { font-weight: 700; font-size: 1.1rem; color: #111827; }
    .sos-close { background: none; border: 0; font-size: 22px; cursor: pointer; color: #6b7280; }
    .sos-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px,1fr)); gap: 12px; margin-top: 12px; }
    .sos-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px; background: #f9fafb; }
    .sos-card h4 { margin: 0 0 6px; font-size: .95rem; color: #111827; }
    .sos-actions { display:flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
    .sos-btn { background: linear-gradient(135deg, #667eea, #764ba2); color:#fff; border:0; border-radius: 10px; padding: 8px 12px; cursor:pointer; }
    .sos-muted { color: #6b7280; font-size: .9rem; }
  `;
  document.head.appendChild(style);

  const fab = document.createElement('button');
  fab.className = 'sos-fab';
  fab.title = 'Emergency SOS';
  fab.setAttribute('aria-label', 'Open SOS');
  fab.textContent = 'SOS';

  const backdrop = document.createElement('div');
  backdrop.className = 'sos-modal-backdrop';
  backdrop.innerHTML = `
    <div class="sos-modal" role="dialog" aria-modal="true" aria-labelledby="sos-title">
      <div class="sos-header">
        <div id="sos-title" class="sos-title">Feeling overwhelmed? We’ve got you.</div>
        <button class="sos-close" aria-label="Close">×</button>
      </div>
      <div class="sos-muted">Quick relief tools. Your data stays private.</div>
      <div class="sos-grid">
        <div class="sos-card">
          <h4>Breathing • 4-7-8</h4>
          <div class="sos-actions">
            <button class="sos-btn" data-action="breath">Start 60s</button>
          </div>
          <div class="sos-muted">Inhale 4 • Hold 7 • Exhale 8</div>
        </div>
        <div class="sos-card">
          <h4>Grounding • 5-4-3-2-1</h4>
          <div class="sos-actions">
            <button class="sos-btn" data-action="ground">Guide</button>
          </div>
          <div class="sos-muted">Name 5 things you see…</div>
        </div>
        <div class="sos-card">
          <h4>Instant Calm • 2 min</h4>
          <div class="sos-actions">
            <button class="sos-btn" data-action="calm">Play</button>
          </div>
          <div class="sos-muted">Short guided reset</div>
        </div>
        <div class="sos-card">
          <h4>Help & Helplines</h4>
          <div class="sos-actions">
            <a class="sos-btn" href="https://www.mohfw.gov.in/" target="_blank" rel="noopener">Resources</a>
          </div>
          <div class="sos-muted">Professional help directory</div>
        </div>
      </div>
    </div>`;

  function openModal(){ backdrop.style.display = 'flex'; }
  function closeModal(){ backdrop.style.display = 'none'; stopBreath(); stopCalm(); }

  function rhythm(ms){ return new Promise(r => setTimeout(r, ms)); }
  let breathTimer = null; let calmAudio = null;

  async function startBreath(){
    stopBreath();
    const btn = backdrop.querySelector('[data-action="breath"]');
    const original = btn.textContent; btn.disabled = true;
    const start = Date.now();
    const cycle = async () => {
      btn.textContent = 'Inhale 4'; await rhythm(4000);
      btn.textContent = 'Hold 7'; await rhythm(7000);
      btn.textContent = 'Exhale 8'; await rhythm(8000);
    };
    const loop = async () => { while(Date.now()-start < 60000){ await cycle(); } btn.textContent = original; btn.disabled = false; };
    breathTimer = loop();
  }
  function stopBreath(){ breathTimer = null; const btn = backdrop.querySelector('[data-action="breath"]'); if(btn){ btn.disabled=false; btn.textContent='Start 60s'; } }

  function startGround(){ alert('5 things you can see\n4 things you can feel\n3 things you can hear\n2 things you can smell\n1 thing you can taste'); }

  function startCalm(){
    stopCalm();
    calmAudio = new Audio('https://cdn.pixabay.com/download/audio/2022/03/14/audio_c1b0f3f5a0.mp3?filename=calm-meditation-11157.mp3');
    calmAudio.volume = 0.5; calmAudio.play().catch(()=>{});
    setTimeout(stopCalm, 120000);
  }
  function stopCalm(){ if(calmAudio){ calmAudio.pause(); calmAudio=null; } }

  document.addEventListener('click', (e)=>{
    if(e.target === fab){ openModal(); }
    if(e.target.matches('.sos-close')){ closeModal(); }
    if(e.target === backdrop){ closeModal(); }
    if(e.target.matches('[data-action="breath"]')){ startBreath(); }
    if(e.target.matches('[data-action="ground"]')){ startGround(); }
    if(e.target.matches('[data-action="calm"]')){ startCalm(); }
  });

  document.body.appendChild(fab);
  document.body.appendChild(backdrop);
})();