<?php
require_once dirname(__DIR__, 3) . '/public/_lx_public_urls.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
if (empty($_SESSION['user_id'])) {
    header('Location: ' . lx_app_href('/'));
    exit();
}
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = (string)$_SESSION['csrf_token'];
$lxUserName = isset($_SESSION['user_name']) ? (string)$_SESSION['user_name'] : 'Reader';
$lxUserCoins = isset($userCoins) ? (int)$userCoins : 0;
$lxUserLevel = isset($userLevel) ? max(1, (int)$userLevel) : 1;
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
$libraryRows = is_array($library ?? null) ? $library : [];
$libraryOnlyRows = array_values(array_filter($libraryRows, static function ($row): bool {
    $status = strtolower((string)($row['status'] ?? ''));
    return in_array($status, ['reading', 'completed'], true);
}));
$planToReadRows = array_values(array_filter($libraryRows, static function ($row): bool {
    $status = strtolower((string)($row['status'] ?? ''));
    return in_array($status, ['plan_to_read', 'plan-to-read'], true);
}));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile  Lexora</title>
    <meta name="description"
        content="Your Lexora reading profile  track progress, view your library, and explore the Scholar's Map.">
    <link rel="stylesheet" href="<?= htmlspecialchars(lx_main_css_href(), ENT_QUOTES, 'UTF-8') ?>">
    <script>
      window.LX_REQUIRED_ROLE = 'user';
      document.documentElement.style.visibility = 'hidden';
    </script>
    <script src="<?= htmlspecialchars(lx_public_js_href('assets/js/session_guard.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script>
      window.LX_SESSION = {
        csrfToken: "<?= htmlspecialchars($csrfToken, ENT_QUOTES) ?>",
        userCoins: <?= $lxUserCoins ?>,
        userLevel: <?= $lxUserLevel ?>
      };
      window.__lxBootstrapUser = <?= json_encode(['name' => $lxUserName, 'initials' => $lxInitials], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
      window.__lxLibrary = <?= json_encode($library ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
    </script>
</head>

<body>
    <div class="min-h-screen">

        <!-- --- Global Header -------------------------------------------------------- -->
  <nav class="global-header">
    <div class="header-inner">
      <a href="<?= htmlspecialchars(lx_app_href('/user'), ENT_QUOTES, 'UTF-8') ?>" class="logo"> LEXORA</a>
      <div class="header-spacer" aria-hidden="true"></div>
      <div class="nav-right">
        <a id="navBackLecture" class="header-link-primary" href="<?= htmlspecialchars(lx_app_href('/read-book'), ENT_QUOTES, 'UTF-8') ?>" style="display:none"> Back to
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
                LVL <?= $lxUserLevel ?></p>
            </div>
            <div class="coins-badge">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="8" />
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                <path d="M12 17h.01" />
              </svg>
              <span id="coinCount"><?= number_format($lxUserCoins) ?></span> COINS
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>

        <!-- --- Profile Content ----------------------------------------------------- -->
        <div class="profile-wrap">

            <!-- Stats Card -->
            <section class="profile-stats">
                <div class="stats-inner">
                    <div class="avatar-block">
                        <div id="avatarInitials" class="avatar-circle avatar-initials" aria-hidden="true"><?= $lxInitialsEsc ?></div>
                        <h1 id="profileDisplayName" class="font-display"><?= $lxUserNameEsc ?></h1>
                    </div>
                    <div class="stats-row">
                        <!-- Reading hours circular -->
                        <div class="stat-item">
                            <div class="circular-progress" style="position:relative;width:6rem;height:6rem">
                                <svg id="circularSvg" width="96" height="96" viewBox="0 0 100 100"
                                    style="transform:rotate(-90deg)"></svg>
                                <div
                                    style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center">
                                    <span id="circularLabel" class="font-display"
                                        style="font-size:1.1rem;font-weight:700">0h</span>
                                </div>
                            </div>
                            <span id="readingGoalCaption" style="font-size:.875rem;color:var(--muted-foreground)">of 4h goal</span>
                        </div>
                        <!-- Coins -->
                        <div class="stat-item">
                            <div class="stat-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" viewBox="0 0 24 24" style="color:var(--primary)">
                                    <circle cx="12" cy="12" r="8" />
                                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                    <path d="M12 17h.01" />
                                </svg>
                            </div>
                            <span id="profileCoins" class="font-display"
                                style="font-size:1.1rem;font-weight:700"><?= number_format($lxUserCoins) ?></span>
                            <span style="font-size:.875rem;color:var(--muted-foreground)">Coins</span>
                        </div>
                        <!-- Books Read -->
                        <div class="stat-item">
                            <div class="stat-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" viewBox="0 0 24 24" style="color:var(--destructive)">
                                    <path
                                        d="M8.5 14.5A2.5 2.5 0 0 0 11 12c0-1.38-.5-2-1-3-1.072-2.143-.224-4.054 2-6 .5 2.5 2 4.9 4 6.5 2 1.6 3 3.5 3 5.5a7 7 0 1 1-14 0c0-1.153.433-2.294 1-3a2.5 2.5 0 0 0 2.5 3z" />
                                </svg>
                            </div>
                            <span id="booksReadCount" class="font-display"
                                style="font-size:1.1rem;font-weight:700">0</span>
                            <span style="font-size:.875rem;color:var(--muted-foreground)">Books Read</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="lamp-of-knowledge" aria-labelledby="coin-system-heading">
                <div class="lamp-card-head">
                    <h2 id="coin-system-heading" class="font-display lamp-card-title">The Coin System</h2>
                </div>
                <div class="lamp-card-body" style="display:block">
                    <p style="color:var(--muted-foreground);margin-bottom:1rem">
                        Coins are the currency of the Reading Kingdom. Earn them by reading pages, completing quests,
                        and maintaining your daily streak. Spend them on premium books, avatar cosmetics, and
                        exclusive features.
                    </p>
                    <ul style="display:grid;gap:.5rem;list-style:none;padding:0;margin:0 0 1rem 0">
                        <li>Read 10 pages → <strong>+20 coins</strong></li>
                        <li>Daily streak → <strong>+50 coins</strong></li>
                        <li>Quest complete → <strong>XP + Coins reward</strong></li>
                    </ul>
                    <div style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap">
                        <span id="coinSystemMsg" style="color:var(--muted-foreground);font-size:.9rem"></span>
                    </div>
                </div>
            </section>

            <!-- Lamp of Knowledge -->
            <section class="lamp-of-knowledge" aria-labelledby="lamp-heading">
                <div class="lamp-card-head">
                    <h2 id="lamp-heading" class="font-display lamp-card-title"> Lamp of Knowledge</h2>
                    <span class="lamp-demo-badge">DEMO CONTROL</span>
                </div>
                <div class="lamp-card-body">
                    <div class="lumo-thumb lamp-lumo-wrap">
                        <img id="lumoThumbLamp" src="<?= htmlspecialchars(lx_public_asset('assets/images/lumo-happy.png'), ENT_QUOTES, 'UTF-8') ?>" alt="Lumo">
                        <div id="lampDot" class="lamp-dot animate-lamp-glow"></div>
                    </div>
                    <div class="lamp-card-controls">
                        <div id="lampStatus" class="lamp-status-block"></div>
                        <div class="slider-row lamp-slider-row">
                            <span class="lamp-slider-label">Hours since last read</span>
                            <span id="lampHours" class="lamp-hours-val">0h</span>
                        </div>
                        <input id="lampSlider" type="range" min="0" max="24" step="1" value="0" class="lamp-range">
                        <div class="lamp-range-ticks">
                            <span>0h </span><span>18h </span><span>24h </span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Scholar's Map -->
            <div class="scholars-map" id="scholarsMapSection">
                <div class="scholars-map__head">
                    <h2 class="font-display"> Scholar's Map</h2>
                    <p>Level up to unlock new regions scroll to explore your journey</p>
                </div>
                <div class="scholars-map__scroll" id="scholarsMapScroll">
                    <div class="scholars-map__canvas" id="scholarsMapCanvas"></div>
                </div>
            </div>

            <section class="lamp-of-knowledge" aria-labelledby="my-library-heading">
                <div class="lamp-card-head">
                    <h2 id="my-library-heading" class="font-display lamp-card-title">My Library</h2>
                </div>
                <div class="lamp-card-body" style="display:block">
                    <div id="libraryGrid" class="book-grid" aria-live="polite">
                        <?php if (count($libraryOnlyRows) > 0): ?>
                            <?php foreach ($libraryOnlyRows as $entry): ?>
                                <?php
                                $book = is_array($entry['book'] ?? null) ? $entry['book'] : [];
                                $bookId = (int)($book['id'] ?? $entry['book_id'] ?? 0);
                                $bookTitle = htmlspecialchars((string)($book['title'] ?? 'Untitled Book'), ENT_QUOTES, 'UTF-8');
                                $bookAuthor = htmlspecialchars((string)($book['author'] ?? 'Unknown Author'), ENT_QUOTES, 'UTF-8');
                                $bookGenre = htmlspecialchars((string)($book['genre'] ?? 'General'), ENT_QUOTES, 'UTF-8');
                                $bookPublicationYear = (int)($book['publicationYear'] ?? $book['publication_year'] ?? 0);
                                $bookGenresRaw = $book['genres'] ?? [];
                                $bookGenres = is_array($bookGenresRaw)
                                    ? array_values(array_filter(array_map(static fn($g) => trim((string)$g), $bookGenresRaw)))
                                    : [];
                                if (count($bookGenres) === 0) {
                                    $fallbackGenre = trim((string)($book['genre'] ?? ''));
                                    $bookGenres = $fallbackGenre !== '' ? [$fallbackGenre] : ['General'];
                                }
                                $status = strtolower((string)($entry['status'] ?? 'reading'));
                                $badgeClass = $status === 'completed' ? 'completed' : 'reading';
                                $badgeText = $status === 'completed' ? '✓ DONE' : 'READING';
                                $detailHref = lx_app_href('/book-detail?id=' . $bookId);
                                ?>
                                <div class="book-card-static" role="link" data-book-id="<?= $bookId ?>" style="cursor:pointer" onclick="window.location.href='<?= htmlspecialchars($detailHref, ENT_QUOTES, 'UTF-8') ?>'">
                                    <div class="card-body">
                                        <span class="status-badge <?= htmlspecialchars($badgeClass, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($badgeText, ENT_QUOTES, 'UTF-8') ?></span>
                                        <h3 class="line-clamp-1"><?= $bookTitle ?></h3>
                                        <p class="line-clamp-1"><?= $bookAuthor ?></p>
                                        <?php if ($bookPublicationYear > 0): ?>
                                            <p class="book-pub-year">Published: <?= (int)$bookPublicationYear ?></p>
                                        <?php endif; ?>
                                        <div class="genre-tags-wrap">
                                            <?php foreach ($bookGenres as $genreLabel): ?>
                                                <span class="genre-tag"><?= htmlspecialchars($genreLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-library-card">
                                <p class="empty-library-msg">No books yet. Start exploring and add some!</p>
                                <p class="empty-library-hint" style="margin:.5rem 0 1rem;font-size:.9rem;color:var(--muted-foreground)">
                                    Books you add from Book Detail will appear here.
                                </p>
                                <a href="<?= htmlspecialchars(lx_app_href('/user#catalog'), ENT_QUOTES, 'UTF-8') ?>" class="btn-primary empty-library-cta">Browse the catalog</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="lamp-of-knowledge" aria-labelledby="my-list-heading">
                <div class="lamp-card-head">
                    <h2 id="my-list-heading" class="font-display lamp-card-title">My List</h2>
                </div>
                <div class="lamp-card-body" style="display:block">
                    <div id="planGrid" class="book-grid" aria-live="polite">
                        <?php if (count($planToReadRows) > 0): ?>
                            <?php foreach ($planToReadRows as $entry): ?>
                                <?php
                                $book = is_array($entry['book'] ?? null) ? $entry['book'] : [];
                                $bookId = (int)($book['id'] ?? $entry['book_id'] ?? 0);
                                $bookTitle = htmlspecialchars((string)($book['title'] ?? 'Untitled Book'), ENT_QUOTES, 'UTF-8');
                                $bookAuthor = htmlspecialchars((string)($book['author'] ?? 'Unknown Author'), ENT_QUOTES, 'UTF-8');
                                $bookGenre = htmlspecialchars((string)($book['genre'] ?? 'General'), ENT_QUOTES, 'UTF-8');
                                $bookPublicationYear = (int)($book['publicationYear'] ?? $book['publication_year'] ?? 0);
                                $bookGenresRaw = $book['genres'] ?? [];
                                $bookGenres = is_array($bookGenresRaw)
                                    ? array_values(array_filter(array_map(static fn($g) => trim((string)$g), $bookGenresRaw)))
                                    : [];
                                if (count($bookGenres) === 0) {
                                    $fallbackGenre = trim((string)($book['genre'] ?? ''));
                                    $bookGenres = $fallbackGenre !== '' ? [$fallbackGenre] : ['General'];
                                }
                                $detailHref = lx_app_href('/book-detail?id=' . $bookId);
                                ?>
                                <div class="book-card-static" role="link" data-book-id="<?= $bookId ?>" style="cursor:pointer" onclick="window.location.href='<?= htmlspecialchars($detailHref, ENT_QUOTES, 'UTF-8') ?>'">
                                    <div class="card-body">
                                        <span class="status-badge plan">PLAN</span>
                                        <h3 class="line-clamp-1"><?= $bookTitle ?></h3>
                                        <p class="line-clamp-1"><?= $bookAuthor ?></p>
                                        <?php if ($bookPublicationYear > 0): ?>
                                            <p class="book-pub-year">Published: <?= (int)$bookPublicationYear ?></p>
                                        <?php endif; ?>
                                        <div class="genre-tags-wrap">
                                            <?php foreach ($bookGenres as $genreLabel): ?>
                                                <span class="genre-tag"><?= htmlspecialchars($genreLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-library-card">
                                <p class="empty-library-msg">Your list is empty for now.</p>
                                <p class="empty-library-hint" style="margin:.5rem 0 1rem;font-size:.9rem;color:var(--muted-foreground)">
                                    Use “Add to list” on a book to save it here.
                                </p>
                                <a href="<?= htmlspecialchars(lx_app_href('/user#catalog'), ENT_QUOTES, 'UTF-8') ?>" class="btn-primary empty-library-cta">Browse the catalog</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section class="profile-leaderboard profile-leaderboard--featured" aria-labelledby="profile-lb-heading">
                <div class="profile-leaderboard__head">
                    <h2 id="profile-lb-heading" class="font-display profile-leaderboard__title">
                        <svg class="profile-leaderboard__title-icon" xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path d="M6 9H4.5a2.5 2.5 0 0 1 0-5H6" />
                            <path d="M18 9h1.5a2.5 2.5 0 0 0 0-5H18" />
                            <path d="M4 22h16" />
                            <path d="M10 14.66V17c0 .55-.47.98-.97 1.21C7.85 18.75 7 20.24 7 22" />
                            <path d="M14 14.66V17c0 .55.47.98.97 1.21C16.15 18.75 17 20.24 17 22" />
                            <path d="M18 2H6v7a6 6 0 0 0 12 0V2Z" />
                        </svg>
                        Your Leaderboard
                    </h2>
                    <p class="profile-leaderboard__rankline">
                        Your rank
                        <span class="profile-leaderboard__rank-badge" id="profileLeaderboardRank">—</span>
                    </p>
                    <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;margin-top:.75rem">
                        <input id="leaderboardSearchInput" type="text" placeholder="Search reader name..." aria-label="Search reader ranking"
                            style="flex:1;min-width:220px;padding:.55rem .7rem;border-radius:.6rem;border:1px solid var(--border);background:var(--background);color:var(--foreground)">
                        <button id="leaderboardSearchBtn" type="button" class="btn-primary" style="padding:.55rem .9rem">Search</button>
                        <button id="leaderboardSearchResetBtn" type="button" class="btn-outline" style="padding:.55rem .9rem">Reset</button>
                    </div>
                    <p id="leaderboardSearchMsg" style="margin:.5rem 0 0;color:var(--muted-foreground);font-size:.85rem"></p>
                </div>
                <div class="profile-leaderboard__tablewrap">
                    <table class="profile-lb-table" role="table" aria-label="Reading leaderboard window">
                        <thead>
                            <tr>
                                <th scope="col">Rank</th>
                                <th scope="col">Reader</th>
                                <th scope="col" class="profile-lb-table__th-num">Score</th>
                                <th scope="col" class="profile-lb-table__th-num">Books</th>
                                <th scope="col" class="profile-lb-table__th-num">Level</th>
                            </tr>
                        </thead>
                        <tbody id="profileLeaderboardRows"></tbody>
                    </table>
                </div>
            </section>

        </div>

        <!-- Footer -->
        <footer class="site-footer">
            <p> LEXORA A cozy corner for readers </p>
        </footer>
    </div>

    <div id="lumo-chatbot-root" data-asset-base="<?= htmlspecialchars(lx_public_asset('assets/images/'), ENT_QUOTES, 'UTF-8') ?>"
        data-lumo-greeting="Hi there! I'm Lumo Ask me about your progress or book recommendations!"></div>

    <script src="<?= htmlspecialchars(lx_public_js_href('assets/js/models/user_data.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_js_href('assets/js/models/lexora-state.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_js_href('assets/js/lumo-chatbot.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
    <script src="<?= htmlspecialchars(lx_public_js_href('assets/js/user_app.js'), ENT_QUOTES, 'UTF-8') ?>"></script>
</body>

</html>

