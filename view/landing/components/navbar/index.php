<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Fragment - Lexora</title>
    <link rel="stylesheet" href="../../common/styles/global.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
      <div class="navbar-inner">
        <div style="display:flex;align-items:center;gap:1rem;">
          <a href="#" class="navbar-logo">
            <img
              src="../../assets/img_2.jpeg"
              alt="Lexora" class="navbar-logo-bear-img"
              style="width:36px;height:36px;border-radius:50%;border:2px solid hsl(38 75% 55% / 0.5);object-fit:cover;">
            <span class="navbar-logo-text">LEXORA</span>
          </a>
          <div class="navbar-coins">
            <img
              src="../../assets/img_3.png"
              alt="coins" style="width:20px;height:20px;object-fit:contain;">
            <span>1,250</span>
          </div>
        </div>

        <div class="navbar-links">
          <a href="#explore" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
            </svg>
            Explore
          </a>
          <a href="#stats" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polygon points="3 11 22 2 13 21 11 13 3 11" />
            </svg>
            Map
          </a>
          <a href="#how-it-works" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
              <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
              <path d="M4 22h16" />
              <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
              <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
              <path d="M18 2H6v7a6 6 0 0 0 12 0V2z" />
            </svg>
            Quests
          </a>
          <a href="#stats" class="navbar-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
              <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
            Community
          </a>
        </div>

        <div class="navbar-auth">
          <button class="btn btn-ghost btn-sm" onclick="openAuthModal('login')">Log In</button>
          <button class="btn btn-hero btn-sm" onclick="openAuthModal('signup')">Sign Up ✦</button>
        </div>

        <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle menu">
          <svg id="menu-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="4" y1="6" x2="20" y2="6" />
            <line x1="4" y1="12" x2="20" y2="12" />
            <line x1="4" y1="18" x2="20" y2="18" />
          </svg>
          <svg id="close-icon" style="display:none;" viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2">
            <line x1="18" y1="6" x2="6" y2="18" />
            <line x1="6" y1="6" x2="18" y2="18" />
          </svg>
        </button>
      </div>

      <div class="mobile-menu" id="mobile-menu">
        <div class="mobile-menu-coins">
          <img
            src="../../assets/img_3.png"
            alt="coins" style="width:20px;height:20px;object-fit:contain;"> 1,250 Coins
        </div>
        <a href="#explore" class="navbar-link" onclick="closeMobileMenu()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
          </svg>
          Explore
        </a>
        <a href="#how-it-works" class="navbar-link" onclick="closeMobileMenu()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
            <path d="M4 22h16" />
            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
            <path d="M18 2H6v7a6 6 0 0 0 12 0V2z" />
          </svg>
          Quests
        </a>
        <a href="#stats" class="navbar-link" onclick="closeMobileMenu()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;">
            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
          </svg>
          Community
        </a>
        <div class="mobile-menu-auth">
          <button class="btn btn-ghost btn-sm" onclick="openAuthModal('login')">Log In</button>
          <button class="btn btn-hero btn-sm" onclick="openAuthModal('signup')">Sign Up ✦</button>
        </div>
      </div>
    </nav>

    <script src="../../common/scripts/global.js"></script>
    <script src="script.js"></script>
</body>
</html>
