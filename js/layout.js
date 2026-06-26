(function () {
  const root = document.documentElement;
  const btn = document.querySelector('[data-theme-toggle]');
  let theme = matchMedia('(prefers-color-scheme:dark)').matches ? 'dark' : 'light';
  root.setAttribute('data-theme', theme);

  const sunSVG = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>';
  const moonSVG = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>';

  function applyTheme() {
    root.setAttribute('data-theme', theme);
    if (btn) {
      btn.innerHTML = theme === 'dark' ? sunSVG : moonSVG;
      btn.setAttribute('aria-label', 'Mudar para tema ' + (theme === 'dark' ? 'claro' : 'escuro'));
    }
  }

  applyTheme();
  if (btn) {
    btn.addEventListener('click', () => {
      theme = theme === 'dark' ? 'light' : 'dark';
      applyTheme();
    });
  }
})();

const hamburger = document.getElementById('hamburger');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebar-overlay');
const closeBtn = document.getElementById('sidebar-close');

function openSidebar() {
  if (sidebar && overlay && hamburger) {
    sidebar.classList.add('is-open');
    overlay.classList.add('is-visible');
    hamburger.setAttribute('aria-expanded', 'true');
  }
}

function closeSidebar() {
  if (sidebar && overlay && hamburger) {
    sidebar.classList.remove('is-open');
    overlay.classList.remove('is-visible');
    hamburger.setAttribute('aria-expanded', 'false');
  }
}

if (hamburger) hamburger.addEventListener('click', openSidebar);
if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
if (overlay) overlay.addEventListener('click', closeSidebar);

const chip = document.getElementById('user-chip');
if (chip) {
  chip.addEventListener('click', e => {
    e.stopPropagation();
    const open = chip.classList.toggle('is-open');
    chip.setAttribute('aria-expanded', String(open));
  });

  chip.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') {
      e.preventDefault();
      chip.click();
    }
    if (e.key === 'Escape') {
      chip.classList.remove('is-open');
      chip.setAttribute('aria-expanded', 'false');
    }
  });

  document.addEventListener('click', () => {
    chip.classList.remove('is-open');
    chip.setAttribute('aria-expanded', 'false');
  });
}

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;

    const el = entry.target;
    const target = parseInt(el.dataset.target, 10);
    const start = performance.now();
    const dur = 1000;

    const step = now => {
      const p = Math.min((now - start) / dur, 1);
      const ease = 1 - Math.pow(1 - p, 3);
      el.textContent = Math.round(ease * target);
      if (p < 1) requestAnimationFrame(step);
      else el.textContent = target;
    };

    requestAnimationFrame(step);
    observer.unobserve(el);
  });
}, { threshold: 0.4 });

document.querySelectorAll('.kpi-value[data-target]').forEach(el => observer.observe(el));

const liveDate = document.getElementById('live-date');
if (liveDate) {
  const s = new Date().toLocaleDateString('pt-PT', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
  liveDate.textContent = s.charAt(0).toUpperCase() + s.slice(1);
}

