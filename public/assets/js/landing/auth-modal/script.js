/* Auth Modal Logic */
function openAuthModal(mode = 'login') {
  const modal = document.getElementById('auth-modal');
  if (!modal) return;
  modal.classList.add('active');
  document.body.style.overflow = 'hidden'; // Prevent scrolling

  if (mode === 'signup') {
    setAuthMode(false);
  } else {
    setAuthMode(true);
  }

  // Initialize particles if not already done
  initAuthParticles();

  // Create icons for the modal
  if (window.lucide) lucide.createIcons();
}

function closeAuthModal() {
  const modal = document.getElementById('auth-modal');
  if (!modal) return;
  modal.classList.remove('active');
  document.body.style.overflow = ''; // Restore scrolling
}

// Close modal when clicking outside
document.addEventListener('click', (e) => {
  const modal = document.getElementById('auth-modal');
  if (e.target === modal) {
    closeAuthModal();
  }
});

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape') {
    closeAuthModal();
  }
});

let isLoginMode = true;

function setAuthMode(isLogin) {
  isLoginMode = isLogin;
  const tabLogin = document.getElementById('tab-login');
  const tabSignup = document.getElementById('tab-signup');
  const authTitle = document.getElementById('auth-title');
  const authSubtitle = document.getElementById('auth-subtitle');
  const usernameField = document.getElementById('username-field');
  const ageField = document.getElementById('birthdate-field');
  const submitText = document.getElementById('submit-text');
  const authMessage = document.getElementById('auth-message');
  const usernameInput = document.getElementById('username');
  const birthdateInput = document.getElementById('birthdate');
  const emailInput = document.getElementById('email');
  const passwordInput = document.getElementById('password');

  if (!tabLogin || !tabSignup) return;

  if (authMessage) authMessage.classList.add('hidden');

  if (isLoginMode) {
    document.getElementById('auth-action').value = 'login';
    tabLogin.className = "tab-btn active";
    tabSignup.className = "tab-btn inactive";
    authTitle.textContent = "Welcome Back, Adventurer";
    authSubtitle.textContent = "Enter the library and continue your quest";
    usernameField.classList.add('hidden');
    if (ageField) ageField.classList.add('hidden');
    if (usernameInput) {
      usernameInput.required = false;
      usernameInput.disabled = true;
      usernameInput.value = '';
    }
    if (birthdateInput) {
      birthdateInput.disabled = true;
      birthdateInput.required = false;
      birthdateInput.value = '';
    }
    if (emailInput) emailInput.required = true;
    if (passwordInput) {
      passwordInput.required = true;
      passwordInput.minLength = 0;
    }
    submitText.textContent = "Enter the Library";
  } else {
    document.getElementById('auth-action').value = 'signup';
    tabSignup.className = "tab-btn active";
    tabLogin.className = "tab-btn inactive";
    authTitle.textContent = "Begin Your Journey";
    authSubtitle.textContent = "Create your account and explore infinite worlds";
    usernameField.classList.remove('hidden');
    if (ageField) ageField.classList.remove('hidden');
    if (usernameInput) {
      usernameInput.disabled = false;
      usernameInput.required = true;
    }
    if (birthdateInput) {
      birthdateInput.disabled = false;
      birthdateInput.required = true;
    }
    if (emailInput) emailInput.required = true;
    if (passwordInput) {
      passwordInput.required = true;
      passwordInput.minLength = 8;
    }
    submitText.textContent = "Start Adventure";
  }
}

// Tab listeners initialization
(function () {
  document.addEventListener('DOMContentLoaded', () => {
    const tabLogin = document.getElementById('tab-login');
    const tabSignup = document.getElementById('tab-signup');
    const authForm = document.getElementById('auth-form');
    const togglePasswordBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    if (tabLogin) tabLogin.onclick = () => setAuthMode(true);
    if (tabSignup) tabSignup.onclick = () => setAuthMode(false);

    if (togglePasswordBtn) {
      let showPassword = false;
      togglePasswordBtn.onclick = () => {
        showPassword = !showPassword;
        passwordInput.type = showPassword ? "text" : "password";
        eyeIcon.setAttribute('data-lucide', showPassword ? 'eye-off' : 'eye');
        if (window.lucide) lucide.createIcons();
      };
    }

    if (authForm) {
      authForm.addEventListener('submit', (event) => {
        const actionInput = document.getElementById('auth-action');
        const currentAction = actionInput ? actionInput.value : 'login';
        const currentPassword = passwordInput ? passwordInput.value : '';
        const birthdate = document.getElementById('birthdate');

        if (currentAction === 'signup') {
          if (!birthdate || birthdate.value.trim() === '') {
            event.preventDefault();
            showAuthMessage('Birthday is required.', true);
            return;
          }

          if (currentPassword.length < 8) {
            event.preventDefault();
            showAuthMessage('Password must be at least 8 characters long.', true);
            return;
          }
        }
      });
    }

    if (window.lucide) lucide.createIcons();
    setAuthMode(true);

    if (window.location.hash === '#auth-modal') {
      openAuthModal(isLoginMode ? 'login' : 'signup');
    }
  });
})();

function showAuthMessage(msg, isError) {
  const authMessage = document.getElementById('auth-message');
  if (!authMessage) return;
  authMessage.textContent = msg;
  authMessage.className = `auth-message ${isError ? 'error-text' : 'success-text'}`;
  authMessage.classList.remove('hidden');
}

let particlesInitialized = false;
function initAuthParticles() {
  if (particlesInitialized) return;
  const container = document.getElementById('auth-particles');
  if (!container) return;

  for (let i = 0; i < 20; i++) {
    const p = document.createElement('div');
    p.className = 'particle';
    const left = Math.random() * 100;
    const top = Math.random() * 100;
    const dur = (6 + Math.random() * 8);
    const delay = (Math.random() * 8);
    p.style.left = `${left}%`;
    p.style.top = `${top}%`;
    p.style.animationDuration = `${dur}s`;
    p.style.animationDelay = `${delay}s`;
    container.appendChild(p);
  }
  particlesInitialized = true;
}
