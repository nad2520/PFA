<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$lxUserName = isset($_SESSION['user_name']) ? (string)$_SESSION['user_name'] : 'Reader';
$lxUserNameEsc = htmlspecialchars($lxUserName, ENT_QUOTES, 'UTF-8');
require_once dirname(__DIR__, 3) . '/public/_lx_public_urls.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Page Not Found Lexora</title>
    <link rel="stylesheet" href="<?= htmlspecialchars(lx_main_css_href(), ENT_QUOTES, 'UTF-8') ?>">
</head>

<body>
    <div class="min-h-screen" style="display:flex;flex-direction:column">

        <!-- --- Global Header -------------------------------------------------------- -->
  <nav class="global-header">
    <div class="header-inner">
      <a href="<?= htmlspecialchars(lx_app_href('/user'), ENT_QUOTES, 'UTF-8') ?>" class="logo"> LEXORA</a>
      <div class="header-spacer" aria-hidden="true"></div>
      <div class="nav-right">
        <a id="navBackLecture" class="header-link-primary" href="<?= htmlspecialchars(lx_app_href('/read-book'), ENT_QUOTES, 'UTF-8') ?>" style="display:none">Back to
          lecture</a>
        <a href="<?= htmlspecialchars(lx_app_href('/user'), ENT_QUOTES, 'UTF-8') ?>" class="header-nav-link header-nav-active">My Home</a>
        <a href="<?= htmlspecialchars(lx_app_href('/store'), ENT_QUOTES, 'UTF-8') ?>" class="header-nav-link">My Store</a>
        <button type="button" id="mapBtn" class="header-nav-btn">My Map</button>
        <button type="button" class="btn-disconnect" onclick="window.location.href='<?= htmlspecialchars(lx_app_href('/logout'), ENT_QUOTES, 'UTF-8') ?>'">
          <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" y1="12" x2="9" y2="12" />
          </svg>
          DISCONNECT
        </button>
        <div class="hover-card">
          <button class="avatar-btn" onclick="nav('<?= htmlspecialchars(lx_app_href('/profile'), ENT_QUOTES, 'UTF-8') ?>')">
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
              <!-- <span id="coinCount">1,350</span> COINS -->
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

        <div
            style="flex:1;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 1rem;text-align:center;gap:2rem">
            <p style="font-family:'Press Start 2P';font-size:4rem;color:var(--primary);line-height:1">404</p>
            <h1 class="font-display" style="font-size:2rem;color:var(--foreground)">Page Not Found</h1>
            <p style="font-size:1.1rem;color:var(--muted-foreground);max-width:28rem">
                Looks like this page wandered off into the Reading Kingdom. Let's get you back to familiar territory.
            </p>
            <a href="<?= htmlspecialchars(lx_app_href('/'), ENT_QUOTES, 'UTF-8') ?>" class="btn-primary">Return Home </a>
        </div>

        <footer class="site-footer">
            <p>LEXORA A cozy corner for readers </p>
        </footer>
    </div>

    <div id="lumo-chatbot-root" data-asset-base="<?= htmlspecialchars(lx_public_asset('assets/images/'), ENT_QUOTES, 'UTF-8') ?>"></div>

    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/models/user_data.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/models/lexora-state.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/lumo-chatbot.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/user_app.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>

