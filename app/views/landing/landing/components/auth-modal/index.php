<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Authentication - Lexora</title>
  <link rel="stylesheet" href="../../common/styles/global.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Authentication Modal -->
  <div id="auth-modal" class="auth-overlay">
      <!-- Particles -->
      <div class="particles-container" id="auth-particles"></div>
      
      <div class="auth-modal-content">
          <!-- Close Button -->
          <button class="modal-close-btn" onclick="closeAuthModal()" aria-label="Close Modal">
              <i data-lucide="x"></i>
          </button>

          <!-- Auth Card -->
          <div class="auth-card">
              <!-- Tab Switcher -->
              <div class="tab-switcher">
                  <button id="tab-login" class="tab-btn active">Log In</button>
                  <button id="tab-signup" class="tab-btn inactive">Sign Up</button>
              </div>

              <!-- Title -->
              <div class="auth-header">
                  <h1 id="auth-title" class="auth-title text-gold-gradient">Welcome Back, Adventurer</h1>
                  <p id="auth-subtitle" class="auth-subtitle">Enter the library and continue your quest</p>
              </div>

              <!-- Form -->
              <form id="auth-form" class="auth-form">
                  <div id="username-field" class="form-group hidden">
                      <i data-lucide="user" class="input-icon"></i>
                      <input type="text" id="username" placeholder="Choose your adventurer name" class="form-input" />
                  </div>

                  <div class="form-group">
                      <i data-lucide="mail" class="input-icon"></i>
                      <input type="email" id="email" placeholder="Your email address" class="form-input" required />
                  </div>

                  <div id="birthdate-field" class="form-group hidden">
                      <i data-lucide="calendar" class="input-icon"></i>
                      <input type="date" id="birthdate" class="form-input" />
                  </div>

                  <div class="form-group">
                      <i data-lucide="lock" class="input-icon"></i>
                      <input type="password" id="password" placeholder="Your secret passphrase" class="form-input password-input" required />
                      <button type="button" id="toggle-password" class="toggle-password-btn">
                          <i data-lucide="eye" id="eye-icon" class="icon-sm"></i>
                      </button>
                  </div>

                  <div id="forgot-password-link" class="forgot-password-wrapper flex">
                      <button type="button" class="forgot-password-link">Forgot your passphrase?</button>
                  </div>

                  <div id="auth-message" class="auth-message hidden"></div>

                  <!-- Submit Button -->
                  <button type="submit" class="btn-auth btn-auth-primary btn-auth-full">
                      <i data-lucide="sparkles" class="icon-sm"></i>
                      <span id="submit-text">Enter the Library</span>
                  </button>
              </form>

              <!-- Divider -->
              <div class="divider">
                  <div class="divider-line"></div>
                  <span class="divider-text">OR</span>
                  <div class="divider-line"></div>
              </div>

              <!-- Social Auth -->
              <button class="btn-auth btn-auth-secondary btn-auth-full">
                  <i data-lucide="book-open" class="icon-sm"></i>
                  Continue with Google
              </button>
          </div>

          <!-- Footer -->
          <p class="auth-footer">
              By continuing, you agree to our 
              <span class="footer-link">Terms of Quest</span>
              and 
              <span class="footer-link">Privacy Scroll</span>
          </p>
      </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="../../common/scripts/global.js"></script>
  <script src="script.js"></script>
</body>
</html>
