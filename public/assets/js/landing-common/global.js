/* ─── Global Utilities ──────────────────────────────── */

/**
 * Creates drifting particles in a container.
 * @param {string} containerId - The ID of the container element.
 * @param {number} count - Number of particles to create.
 */
function createParticles(containerId, count = 25) {
  const container = document.getElementById(containerId);
  if (!container) return;
  for (let i = 0; i < count; i++) {
    const p = document.createElement('div');
    const size = Math.random() * 4 + 2;
    const left = Math.random() * 100;
    const dur = Math.random() * 8 + 6;
    const delay = Math.random() * 5;
    const hue = Math.random() > 0.5 ? '42' : '175';
    p.style.cssText = `
      position:absolute;
      width:${size}px;height:${size}px;
      background:hsl(${hue},80%,65%);
      border-radius:50%;
      left:${left}%;bottom:-10px;
      opacity:0;pointer-events:none;
      animation:float-particle ${dur}s ${delay}s ease-in-out infinite;
      box-shadow:0 0 ${size * 2}px hsl(${hue},80%,65%);
    `;
    container.appendChild(p);
  }
}

/**
 * Initializes Lucide icons if the library is loaded.
 */
function initIcons() {
  if (window.lucide) {
    window.lucide.createIcons();
  }
}

/* ─── IntersectionObserver for .reveal / .reveal-x ─── */
function initRevealObserver() {
  const revealEls = document.querySelectorAll('.reveal, .reveal-x');
  if (!revealEls.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.15 });

  revealEls.forEach(el => observer.observe(el));
}

/* ─── Navbar scroll effect ───────────────────────────── */
function initNavbarScroll() {
  const navbar = document.querySelector('.navbar');
  if (!navbar) return;
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      navbar.style.background = 'hsl(24 15% 8% / 0.95)';
    } else {
      navbar.style.background = 'hsl(24 15% 10% / 0.8)';
    }
  }, { passive: true });
}

/* ─── Portal card hover effects ─────────────────────── */
function initPortalCards() {
  document.querySelectorAll('.portal-card').forEach(card => {
    const img = card.querySelector('.portal-img');
    const border = card.querySelector('.portal-border');
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'translateY(-8px)';
      if (img) img.style.transform = 'scale(1.1)';
      if (border) border.style.borderColor = 'hsl(38 75% 55% / 0.6)';
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = '';
      if (img) img.style.transform = '';
      if (border) border.style.borderColor = '';
    });
  });
}

/* ─── Initialize everything on DOMContentLoaded ─────── */
document.addEventListener('DOMContentLoaded', () => {
  initIcons();
  initNavbarScroll();

  // Reveal observer runs immediately (for elements already in view)
  // and also after the splash is dismissed
  initRevealObserver();

  // Re-run reveal after splash transition so newly-visible elements get caught
  window.addEventListener('lexora:entered', () => {
    initRevealObserver();
    initPortalCards();
    createParticles('hero-particles', 25);
    if (window.lucide) window.lucide.createIcons();
  });
});

