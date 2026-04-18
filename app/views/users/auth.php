<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Lexora</title>
    <meta name="description" content="Sign in to your Lexora account and continue your reading journey.">
    <link rel="stylesheet" href="public/assets/css/user/main.css">
</head>

<body>
    <div class="auth-page">
        <!-- Nav -->
        <!-- --- Global Header -------------------------------------------------------- -->
  <nav class="global-header">
    <div class="header-inner">
      <a href="?view=user" class="logo"> LEXORA</a>
      <div class="header-spacer" aria-hidden="true"></div>
      <div class="nav-right">
        <a id="navBackLecture" class="header-link-primary" href="?view=read-book" style="display:none"> Back to
          lecture</a>
        <a href="?view=user" class="header-nav-link header-nav-active">My Home</a>
        <a href="?view=store" class="header-nav-link">My Store</a>
        <button type="button" id="mapBtn" class="header-nav-btn">My Map</button>
        <button type="button" class="btn-disconnect" onclick="window.location.href='index.php'">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
          DISCONNECT
        </button>
        <div class="hover-card">
          <button class="avatar-btn" onclick="nav('?view=profile')">
            <img id="avatarImg" src="public/assets/images/lumo-happy.png" alt="User avatar">
          </button>
          <div class="hover-card-content">
            <img src="public/assets/images/lumo-happy.png" alt="Lumo">
            <div style="text-align:center">
              <p style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700">Eleanor Vance</p>
              <p
                style="font-family:'Press Start 2P';font-size:.5rem;color:var(--primary);letter-spacing:.05em;margin-top:.25rem">
                LVL 12</p>
            </div>
            <div class="coins-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="8" />
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                <path d="M12 17h.01" />
              </svg>
              <!-- <span id="coinCount">1,350</span> COINS -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

        <div class="auth-body">
            <div class="auth-card">
                <div class="auth-title">
                    <h1 class="text-golden">Lexora</h1>
                    <p>YOUR COZY READING SANCTUARY </p>
                </div>

                <div class="auth-box">
                    <h2 id="authFormTitle">Sign In</h2>
                    <form id="authForm">
                        <!-- Username -->
                        <div class="field">
                            <label>
                                <span style="font-size:1.1rem">??</span> Username
                            </label>
                            <input type="text" id="username" placeholder="Username" required>
                        </div>
                        <!-- Password -->
                        <div class="field">
                            <label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" viewBox="0 0 24 24" style="color:var(--primary)">
                                    <path
                                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
                                </svg>
                                Password
                            </label>
                            <input type="password" id="password" placeholder="??????" required>
                        </div>
                        <!-- Confirm Password (sign-up only) -->
                        <div class="field" id="confirmWrap" style="display:none">
                            <label>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" viewBox="0 0 24 24" style="color:var(--primary)">
                                    <path
                                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
                                </svg>
                                Confirm Password
                            </label>
                            <input type="password" placeholder="??????">
                        </div>

                        <button type="submit" class="btn-submit" id="authSubmit">
                            <span id="authSubmitIcon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" viewBox="0 0 24 24">
                                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                                    <polyline points="10 17 15 12 10 7" />
                                    <line x1="15" y1="12" x2="3" y2="12" />
                                </svg>
                            </span>
                            <span id="authSubmitLabel">Sign In</span>
                        </button>
                    </form>

                    <div class="auth-toggle">
                        <button id="authToggle"> Register</button>
                        <span id="forgotLink"> Forgot Password</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="lumo-chatbot-root" data-asset-base="public/assets/images/"
        data-lumo-greeting="Hi there! I'm Lumo ?? your cozy reading companion. Ask me anything!"></div>

    <script src="public/assets/js/models/user_data.js"></script>
    <script src="public/assets/js/models/lexora-state.js"></script>
    <script src="public/assets/js/lumo-chatbot.js"></script>
    <script src="public/assets/js/user_app.js"></script>
</body>

</html>

