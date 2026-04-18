<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lexora  Your Cozy Reading Sanctuary</title>
  <meta name="description"
    content="Lexora is a cozy corner for readers. Discover books, track your reading, earn coins, and explore the Reading Kingdom.">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,500&family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Press+Start+2P&display=swap');
  </style>
  <link rel="stylesheet" href="public/assets/css/user/main.css">
  <script>
    window.LX_USER_ROLE = "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user'; ?>";
  </script>
</head>

<body>

  <!-- --- Global Header -------------------------------------------------------- -->
  <nav class="global-header">
    <div class="header-inner">
      <a href="index.php" class="logo"> LEXORA</a>
      <div class="header-spacer" aria-hidden="true"></div>
      <div class="nav-right">
        <a id="navBackLecture" class="header-link-primary" href="?view=read-book" style="display:none">Back to lecture</a>
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

  <!-- --- Hero Section -------------------------------------------------------- -->
  <section class="hero-section">
    <div class="hero-media">
      <video autoplay loop muted playsinline poster="public/assets/images/hero-library.png">
        <source src="public/assets/videos/hero-library.mp4" type="video/mp4">
      </video>
    </div>
    <div class="hero-vignette"></div>
    <div class="hero-fade"></div>
    <div class="hero-content">
      <h1 class="text-golden animate-float-up">Lexora</h1>
      <div class="animate-float-up" style="animation-delay:.15s">
        <button id="getStartedBtn" class="btn-primary">Get Started </button>
      </div>
    </div>
  </section>

  <!-- --- Book Catalog -------------------------------------------------------- -->
  <div id="catalog">
    <section class="catalog-section">
      <div class="catalog-header">
        <h2 class="font-display"> Book Catalog</h2>
        <div class="search-wrap" style="width:100%;max-width:18rem">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <path d="m21 21-4.35-4.35" />
          </svg>
          <input id="bookSearch" type="text" placeholder="Search by title or author...">
        </div>
      </div>
      <div class="genre-filter">
        <div id="filterRow" class="filter-row"></div>
      </div>

      <!-- For You -->
      <div id="forYouSection" style="margin-top:2rem;margin-bottom:1.5rem">
        <h3 class="font-display" style="font-size:1.5rem;font-weight:700;margin-bottom:1rem"> For You</h3>
        <div id="forYouGrid"></div>
      </div>
      <div id="catalogDivider" style="border-top:1px solid var(--border);margin:1.5rem 0"></div>

      <div class="book-grid" id="bookGrid"></div>
      <div id="noResults" style="display:none;text-align:center;padding:4rem 1rem">
        <p style="font-family:'Press Start 2P';font-size:.75rem;color:var(--muted-foreground)">No books found</p>
      </div>
      <div style="display:flex;justify-content:center;margin-top:2.5rem">
        <button id="exploreMore" class="btn-primary" style="display:none">Explore More </button>
      </div>
    </section>
  </div>



  <!-- --- Footer -------------------------------------------------------------- -->
  <footer class="site-footer">
    <p> LEXORA A cozy corner for readers </p>
  </footer>

  <!-- --- Reading Kingdom Map Modal ------------------------------------------ -->
  <div id="mapOverlay" class="modal-overlay hidden">
    <div class="modal-box map-dialog">
      <h2 class="sr-only">Reading Kingdom Map</h2>
      <button type="button" id="mapClose" class="map-dialog-close" aria-label="Close map">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
          stroke-width="2" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>
      <div class="reading-kingdom-map-wrap">
        <div class="reading-kingdom-badge">YOUR READING KINGDOM </div>
        <img src="public/assets/images/reading-kingdom-map.png" alt="Reading Kingdom Map" class="reading-kingdom-map-img"
          draggable="false">
        <div id="genreOverlay" class="reading-kingdom-regions"></div>
        <div class="castle-shimmer" aria-hidden="true"></div>
      </div>
    </div>
  </div>

  <!-- --- Lumo Welcome (Bounty Board) Modal ---------------------------------- -->
  <div id="lumoOverlay" class="modal-overlay hidden">
    <div class="modal-box" style="position:relative">
      <button id="lumoClose" class="modal-close-btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
          stroke-width="2" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>
      <div class="lumo-welcome">
        <img src="public/assets/images/lumo-happy.png" alt="Lumo the bear" class="animate-breathe">
        <h2> LUMO'S BOUNTY BOARD </h2>
        <p class="sub">Complete these quests to level up and unlock new map regions!</p>
        <div class="bounty-list">
          <div class="bounty-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z" />
              <polyline points="14 2 14 8 20 8" />
            </svg>
            <div>
              <h3>The Midnight Scholar</h3>
              <p>Read 20 pages after 11 PM.</p><span class="reward">+200 XP, +50 Coins</span>
            </div>
          </div>
          <div class="bounty-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
              <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
            </svg>
            <div>
              <h3>Genre Explorer</h3>
              <p>Add a Historical Fiction book to your list.</p><span class="reward">+150 XP, +30 Coins</span>
            </div>
          </div>
          <div class="bounty-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
              stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
              <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
            </svg>
            <div>
              <h3>Speed Reader</h3>
              <p>Finish a book in under 3 days.</p><span class="reward">+500 XP, +100 Coins</span>
            </div>
          </div>
        </div>
        <button id="acceptBounties" class="btn-pixel" style="width:100%;padding:.75rem">ACCEPT BOUNTIES </button>
      </div>
    </div>
  </div>

  <!-- Lumo Chatbot (shared markup: controller/lumo-chatbot.js) -->
  <div id="lumo-chatbot-root" data-asset-base="public/assets/images/"></div>

  <script src="public/assets/js/models/user_data.js"></script>
  <script src="public/assets/js/models/lexora-state.js"></script>
  <script src="public/assets/js/lumo-chatbot.js"></script>
  <script src="public/assets/js/user_app.js"></script>
</body>

</html>

