document.addEventListener('DOMContentLoaded', () => {
    // ----------------------------------------------------------------------
    // Particles System for Auth Background
    // ----------------------------------------------------------------------
    const particlesContainer = document.getElementById('particles-container');
    if (particlesContainer) {
        for (let i = 0; i < 20; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';

            const left = Math.random() * 100;
            const top = Math.random() * 100;
            const duration = 6 + Math.random() * 8;
            const delay = Math.random() * 8;

            particle.style.left = `${left}%`;
            particle.style.top = `${top}%`;
            particle.style.animationDuration = `${duration}s`;
            particle.style.animationDelay = `${delay}s`;

            particlesContainer.appendChild(particle);
        }
    }

    // ----------------------------------------------------------------------
    // Auth Form Logic
    // ----------------------------------------------------------------------
    const tabLogin = document.getElementById('tab-login');
    const tabSignup = document.getElementById('tab-signup');
    const authTitle = document.getElementById('auth-title');
    const authSubtitle = document.getElementById('auth-subtitle');
    const usernameField = document.getElementById('username-field');
    const forgotPasswordLink = document.getElementById('forgot-password-link');
    const submitText = document.getElementById('submit-text');
    const authForm = document.getElementById('auth-form');

    let isLogin = true;

    function updateAuthUI() {
        if (!tabLogin || !tabSignup) return;

        if (isLogin) {
            tabLogin.className = "tab-btn active";
            tabSignup.className = "tab-btn inactive";

            authTitle.textContent = "Welcome Back, Adventurer";
            authSubtitle.textContent = "Enter the library and continue your quest";

            usernameField.classList.add('hidden');
            forgotPasswordLink.classList.remove('hidden');
            forgotPasswordLink.classList.add('flex');
            submitText.textContent = "Enter the Library";
        } else {
            tabSignup.className = "tab-btn active";
            tabLogin.className = "tab-btn inactive";

            authTitle.textContent = "Begin Your Journey";
            authSubtitle.textContent = "Create your account and explore infinite worlds";

            usernameField.classList.remove('hidden');
            forgotPasswordLink.classList.remove('flex');
            forgotPasswordLink.classList.add('hidden');
            submitText.textContent = "Start Adventure";
        }
    }

    if (tabLogin && tabSignup) {
        tabLogin.addEventListener('click', () => {
            isLogin = true;
            updateAuthUI();
        });

        tabSignup.addEventListener('click', () => {
            isLogin = false;
            updateAuthUI();
        });
    }

    const authMessage = document.getElementById('auth-message');

    function showMessage(msg, isError) {
        if (!authMessage) return;
        authMessage.textContent = msg;
        authMessage.className = `auth-message ${isError ? 'error-text' : 'success-text'}`;
        authMessage.classList.remove('hidden');
    }

    if (authForm) {
        authForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const username = document.getElementById('username').value;
            
            submitText.textContent = "Processing...";
            
            const endpoint = isLogin ? 'backend/login.php' : 'backend/register.php';
            const payload = isLogin ? { email, password } : { username, email, password };

            try {
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    showMessage(data.message, false);
                    setTimeout(() => {
                        window.location.href = 'index.html';
                    }, 1500);
                } else {
                    showMessage(data.message, true);
                    submitText.textContent = isLogin ? "Enter the Library" : "Start Adventure";
                }
            } catch (error) {
                showMessage("An error occurred reaching the server.", true);
                submitText.textContent = isLogin ? "Enter the Library" : "Start Adventure";
            }
        });
    }

    // ----------------------------------------------------------------------
    // Show/Hide Password Logic
    // ----------------------------------------------------------------------
    const togglePasswordBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-icon');

    let showPassword = false;

    if (togglePasswordBtn && passwordInput && eyeIcon) {
        togglePasswordBtn.addEventListener('click', () => {
            showPassword = !showPassword;
            passwordInput.type = showPassword ? "text" : "password";

            if (showPassword) {
                eyeIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                eyeIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons(); // Reactivate the new icon
        });
    }
});
