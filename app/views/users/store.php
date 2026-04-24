<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = (string)$_SESSION['csrf_token'];
$coinError = isset($_GET['coin_error']) ? trim((string)$_GET['coin_error']) : '';
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
    <title>Imperial Treasury ? Lexora</title>
    <meta name="description" content="Purchase coin bundles for the Lexora Reading Kingdom and unlock premium books.">
    <link rel="stylesheet" href="<?= htmlspecialchars(lx_main_css_href(), ENT_QUOTES, 'UTF-8') ?>">
    <script>
      window.LX_SESSION = { csrfToken: "<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>" };
      window.__lxBootstrapUser = <?= json_encode(['name' => $lxUserName, 'initials' => $lxInitials], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
    </script>
</head>

<body>
    <div class="min-h-screen">

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

        <!-- --- Hero ---------------------------------------------------------------- -->
        <div class="store-hero">
            <h1 class="text-golden font-display">Imperial Treasury</h1>
            <p>Acquire premium book volumes or test your luck.</p>
            <?php if ($coinError !== ''): ?>
                <p style="margin-top:.75rem;color:hsl(0,62%,60%);font-weight:600;">
                    <?= htmlspecialchars($coinError, ENT_QUOTES) ?>
                </p>
            <?php endif; ?>
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
                <span>Secure Checkout  Your payment is encrypted and safe</span>
            </div>
            <p> LEXORA A cozy corner for readers </p>
        </footer>

    </div>

    <div id="lumo-chatbot-root" data-asset-base="<?= htmlspecialchars(lx_public_asset('assets/images/'), ENT_QUOTES, 'UTF-8') ?>"
        data-lumo-greeting="Hi there! I'm Lumo ?? Ask me anything about the store or coins!"></div>

    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/models/user_data.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/models/lexora-state.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/lumo-chatbot.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_asset('assets/js/user_app.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>

