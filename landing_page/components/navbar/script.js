/* ─── Navbar Logic ────────────────────────────────────── */
const mobileToggle = document.getElementById('mobile-toggle');
const mobileMenu = document.getElementById('mobile-menu');
const menuIcon = document.getElementById('menu-icon');
const closeIcon = document.getElementById('close-icon');

if (mobileToggle) {
  mobileToggle.addEventListener('click', () => {
    const isOpen = mobileMenu.classList.toggle('open');
    menuIcon.style.display = isOpen ? 'none' : 'block';
    closeIcon.style.display = isOpen ? 'block' : 'none';
  });
}

function closeMobileMenu() {
  if (mobileMenu) {
    mobileMenu.classList.remove('open');
    menuIcon.style.display = 'block';
    closeIcon.style.display = 'none';
  }
}

// Export for use in other components if needed
window.closeMobileMenu = closeMobileMenu;
