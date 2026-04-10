<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imperial Treasury ? Lexora</title>
    <meta name="description" content="Purchase coin bundles for the Lexora Reading Kingdom and unlock premium books.">
    <link rel="stylesheet" href="view/user_page/scss/main.css">
</head>

<body>
    <div class="min-h-screen">

        <!-- --- Global Header -------------------------------------------------------- -->
  <nav class="global-header">
    <div class="header-inner">
      <a href="index.php?page=user" class="logo">?? LEXORA</a>
      <div class="header-spacer" aria-hidden="true"></div>
      <div class="nav-right">
        <a id="navBackLecture" class="header-link-primary" href="index.php?page=user&view=read-book" style="display:none">? Back to
          lecture</a>
        <a href="index.php?page=user" class="header-nav-link header-nav-active">My Home</a>
        <a href="index.php?page=user&view=store" class="header-nav-link">My Store</a>
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
          <button class="avatar-btn" onclick="nav('profile.html')">
            <img id="avatarImg" src="view/user_page/assets/lumo-happy.png" alt="User avatar">
          </button>
          <div class="hover-card-content">
            <img src="view/user_page/assets/lumo-happy.png" alt="Lumo">
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
              <span id="coinCount">1,350</span> COINS
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

        <!-- --- Hero ---------------------------------------------------------------- -->
        <div class="store-hero">
            <h1 class="text-golden font-display">Imperial Treasury</h1>
            <p>Acquire premium book volumes or test your luck.</p>
        </div>

        <!-- --- Tier Cards ---------------------------------------------------------- -->
        <div class="tier-grid" id="tierGrid"></div>

        <!-- --- Footer ------------------------------------------------------------- -->
        <footer class="site-footer">
            <div class="footer-secure">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                    <polyline points="9 12 11 14 15 10" />
                </svg>
                <span>Secure Checkout ? Your payment is encrypted and safe</span>
            </div>
            <p>? LEXORA ? A cozy corner for readers ?</p>
        </footer>

    </div>

    <div id="lumo-chatbot-root"
        data-lumo-greeting="Hi there! I'm Lumo ?? Ask me anything about the store or coins!"></div>

    <script src="script/user_page/data.js"></script>
    <script src="script/user_page/lexora-state.js"></script>
    <script src="script/user_page/lumo-chatbot.js"></script>
    <script src="script/user_page/app.js"></script>
</body>

</html>

