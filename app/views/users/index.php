<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
// 🔐 BLOCK ACCESS if not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: index.php");
  exit();
}

// 🚫 PREVENT BACK BUTTON (disable cache)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = (string)$_SESSION['csrf_token'];
$lxUserName = isset($_SESSION['user_name']) ? (string)$_SESSION['user_name'] : 'Reader';
$lxUserNameEsc = htmlspecialchars($lxUserName, ENT_QUOTES, 'UTF-8');
$parts = preg_split('/\s+/', trim($lxUserName));
$lxInitials = '';
foreach (array_slice($parts, 0, 2) as $p) {
    if ($p !== '') {
        $lxInitials .= strtoupper(substr($p, 0, 1));
    }
}
if ($lxInitials === '') {
    $lxInitials = 'R';
}
$lxInitialsEsc = htmlspecialchars($lxInitials, ENT_QUOTES, 'UTF-8');
require_once __DIR__ . '/_lx_public_urls.php';
?>
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
  <link rel="stylesheet" href="<?= htmlspecialchars(lx_main_css_href(), ENT_QUOTES, 'UTF-8') ?>">
  <script>
    window.LX_USER_ROLE = "<?php echo isset($_SESSION['user_role']) ? $_SESSION['user_role'] : 'user'; ?>";
    window.LX_SESSION = { csrfToken: "<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>" };
    window.__lxBootstrapUser = <?= json_encode(['name' => $lxUserName, 'initials' => $lxInitials], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
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
        <button type="button" class="btn-disconnect" onclick="window.location.href='/PFA/logout'">
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
            <img id="avatarImg" src="<?= htmlspecialchars(lx_public_asset('assets/images/lumo-happy.png'), ENT_QUOTES, 'UTF-8') ?>" alt="User avatar">
          </button>
          <div class="hover-card-content">
            <img src="<?= htmlspecialchars(lx_public_asset('assets/images/lumo-happy.png'), ENT_QUOTES, 'UTF-8') ?>" alt="Lumo">
            <div style="text-align:center">
              <p id="hoverCardUserName" style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700"><?= $lxUserNameEsc ?></p>
              <p id="hoverLevelBadge"
                style="font-family:'Press Start 2P';font-size:.5rem;color:var(--primary);letter-spacing:.05em;margin-top:.25rem">
                LVL 1</p>
            </div>
            <div class="coins-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="8" />
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                <path d="M12 17h.01" />
              </svg>
              <span id="coinCount">0</span> COINS
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

  <!-- --- Hero Section -------------------------------------------------------- -->
  <section class="hero-section">
    <div class="hero-media">
      <video autoplay loop muted playsinline poster="<?= htmlspecialchars(lx_public_asset('assets/images/hero-library.png'), ENT_QUOTES, 'UTF-8') ?>">
        <source src="<?= htmlspecialchars(lx_public_asset('assets/videos/hero-library.mp4'), ENT_QUOTES, 'UTF-8') ?>" type="video/mp4">
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
        <div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;margin-bottom:1rem">
          <h3 class="font-display" style="font-size:1.5rem;font-weight:700;margin:0"> For You</h3>
          <button type="button" id="editForYouPrefsBtn" class="btn-outline" style="padding:.5rem .9rem;font-size:.75rem">
            Edit categories
          </button>
        </div>
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
        <img src="<?= htmlspecialchars(lx_public_asset('assets/images/reading-kingdom-map.png'), ENT_QUOTES, 'UTF-8') ?>" alt="Reading Kingdom Map" class="reading-kingdom-map-img"
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
        <img src="<?= htmlspecialchars(lx_public_asset('assets/images/lumo-happy.png'), ENT_QUOTES, 'UTF-8') ?>" alt="Lumo the bear" class="animate-breathe">
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
  <div id="lumo-chatbot-root" data-asset-base="<?= htmlspecialchars(lx_public_asset('assets/images/'), ENT_QUOTES, 'UTF-8') ?>"></div>

  <!-- First-login For You category onboarding -->
  <div id="forYouPrefsOverlay" class="modal-overlay hidden" aria-hidden="true">
    <div class="modal-box" style="max-width:42rem;width:100%;position:relative">
      <button type="button" id="forYouPrefsClose" class="modal-close-btn" aria-label="Close category selection">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor"
          stroke-width="2" viewBox="0 0 24 24">
          <line x1="18" y1="6" x2="6" y2="18" />
          <line x1="6" y1="6" x2="18" y2="18" />
        </svg>
      </button>
      <div style="padding:1.5rem">
        <h2 class="font-display" style="margin:0 0 .5rem 0">Choose your favorite categories</h2>
        <p style="margin:0 0 1rem 0;color:var(--muted-foreground)">
          Pick at least one genre so Lexora can personalize your For You shelf.
        </p>
        <div id="forYouPrefsChoices" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(140px,1fr));gap:.6rem"></div>
        <p id="forYouPrefsMsg" style="min-height:1.2rem;color:var(--muted-foreground);font-size:.9rem;margin:.8rem 0 0"></p>
        <div style="display:flex;justify-content:flex-end;gap:.6rem;margin-top:1rem">
          <button type="button" id="forYouPrefsSkip" class="btn-outline">Skip for now</button>
          <button type="button" id="forYouPrefsSave" class="btn-primary">Save preferences</button>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= htmlspecialchars(lx_public_asset('assets/js/models/user_data.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
  <script src="<?= htmlspecialchars(lx_public_asset('assets/js/models/lexora-state.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
  <script src="<?= htmlspecialchars(lx_public_asset('assets/js/lumo-chatbot.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
  <script src="<?= htmlspecialchars(lx_public_asset('assets/js/user_app.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>

