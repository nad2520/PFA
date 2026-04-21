<?php
declare(strict_types=1);

require_once CORE_PATH . '/Database.php';
require_once APP_PATH . '/models/BookModel.php';

session_start();

if (empty($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$bookId = (int)($_GET['id'] ?? 0);
$book    = $bookId > 0 ? BookModel::findById($bookId) : null;

if (!$book) {
    header('Location: index.php?view=store');
    exit;
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = (string)$_SESSION['csrf_token'];

$userId = (int)$_SESSION['user_id'];
$alreadyCompleted = false;
try {
    $pdo = Database::pdo();
    $chk = $pdo->prepare('SELECT status FROM user_books WHERE user_id = ? AND book_id = ? LIMIT 1');
    $chk->execute([$userId, (int)$book['id']]);
    $ubRow = $chk->fetch(PDO::FETCH_ASSOC);
    $alreadyCompleted = ($ubRow['status'] ?? '') === 'completed';

    // Paid-read gate: read access requires ownership when coinCost > 0.
    // plan_to_read is wishlist only and does not grant read access.
    if (!$ubRow || (($ubRow['status'] ?? '') === 'plan_to_read')) {
        $coinCost = max(0, (int)($book['coinCost'] ?? 0));
        if ($coinCost > 0) {
            $pdo->beginTransaction();
            $userStmt = $pdo->prepare('SELECT coins FROM users WHERE id = ? FOR UPDATE');
            $userStmt->execute([$userId]);
            $coins = (int)($userStmt->fetchColumn() ?: 0);
            if ($coins < $coinCost) {
                $pdo->rollBack();
                header('Location: index.php?view=store&coin_error=' . rawurlencode('You don’t have enough coins to read this book.'));
                exit;
            }

            $ded = $pdo->prepare('UPDATE users SET coins = coins - ? WHERE id = ? AND coins >= ?');
            $ded->execute([$coinCost, $userId, $coinCost]);
            if ($ded->rowCount() === 0) {
                $pdo->rollBack();
                header('Location: index.php?view=store&coin_error=' . rawurlencode('You don’t have enough coins to read this book.'));
                exit;
            }

            $pdo->prepare(
                'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
                 VALUES (?, ?, "reading", 0, NOW())
                 ON DUPLICATE KEY UPDATE
                   status = "reading",
                   started_at = IFNULL(started_at, NOW())'
            )->execute([$userId, (int)$book['id']]);
            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_spent, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $coinCost, 'book_access']);
            $pdo->commit();
        } else {
            $pdo->prepare(
                'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
                 VALUES (?, ?, "reading", 0, NOW())
                 ON DUPLICATE KEY UPDATE
                   status = "reading",
                   started_at = IFNULL(started_at, NOW())'
            )->execute([$userId, (int)$book['id']]);
        }
    }
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    $alreadyCompleted = false;
}

$totalPages = 24;
$lxBook = [
    'id'               => (int)$book['id'],
    'title'            => (string)$book['title'],
    'xpReward'         => (int)($book['xpReward'] ?? 0),
    'coinReward'       => (int)($book['coinReward'] ?? 0),
    'totalPages'       => $totalPages,
    'alreadyCompleted' => $alreadyCompleted,
];
$lxBookJson = json_encode($lxBook, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
$lxSessionJson = json_encode(['csrfToken' => $csrfToken], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading — <?= htmlspecialchars($book['title'], ENT_QUOTES) ?> — Lexora</title>
    <meta name="description" content="Continue reading your book on Lexora.">
    <link rel="stylesheet" href="public/assets/css/user/main.css">
    <script>
        window.LX_SESSION = <?= $lxSessionJson ?>;
        window.LX_CURRENT_BOOK = <?= $lxBookJson ?>;
    </script>
</head>

<body class="read-book-page" data-lexora-read-app="1">

    <div class="read-layout">
        <header class="read-topbar">
            <div class="read-topbar-inner">
                <button type="button" class="read-back" id="readBack" aria-label="Back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    <span class="read-back-text">Back</span>
                </button>
                <div class="read-title-block">
                    <p class="read-book-title font-display" id="readBookTitle">?</p>
                    <p class="read-page-meta font-body" id="readPageMeta">Page 1 of 1</p>
                </div>
                <div class="read-progress-label">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z" />
                        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z" />
                    </svg>
                    <span id="readPct" class="read-pct-pixel">0%</span>
                </div>
            </div>
            <div class="read-progress-track">
                <div class="read-progress-fill" id="readProgressFill"></div>
            </div>
        </header>

        <main class="read-main">
            <div class="read-page-card">
                <p class="read-page-kicker" id="readPageKicker"> PAGE 1 </p>
                <div class="read-page-body font-body" id="readPageBody"></div>
            </div>
        </main>

        <footer class="read-footer">
            <div class="read-footer-inner">
                <button type="button" class="btn-read-nav" id="readPrev">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    PREV
                </button>
                <div class="read-page-pills" id="readPagePills"></div>
                <button type="button" class="btn-read-nav" id="readNext">
                    <span id="readNextLabel">NEXT</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2" viewBox="0 0 24 24">
                        <path d="m9 18 6-6-6-6" />
                    </svg>
                </button>
            </div>
        </footer>
    </div>

    <!-- Finish flow: confirm → rating → success -->
    <div id="finishModal" class="modal-overlay" style="display:none" role="dialog" aria-modal="true"
        aria-labelledby="finishStep1Title">
        <div class="modal-box read-finish-modal" onclick="event.stopPropagation()">
            <div class="read-finish-modal-inner">
                <div id="finishStep1">
                    <h2 id="finishStep1Title" class="font-display read-finish-title">Did you finish this book?</h2>
                    <p class="read-finish-sub font-body" id="finishBookName"></p>
                    <div class="read-finish-actions">
                        <button type="button" class="btn-primary" id="btnFinishYes" aria-label="Completed">✅
                            Completed</button>
                        <button type="button" class="btn-outline" id="btnFinishNo" aria-label="Not finished">❌ Not
                            finished</button>
                    </div>
                </div>
                <div id="finishStep2" style="display:none">
                    <h2 class="font-display read-finish-title">Rate this book</h2>
                    <p class="read-finish-sub font-body">Tap 1–5 stars, then confirm.</p>
                    <div id="finishStarRating" class="read-finish-stars" role="group" aria-label="Star rating">
                        <button type="button" class="fstar" data-v="1" aria-label="1 star">☆</button>
                        <button type="button" class="fstar" data-v="2" aria-label="2 stars">☆</button>
                        <button type="button" class="fstar" data-v="3" aria-label="3 stars">☆</button>
                        <button type="button" class="fstar" data-v="4" aria-label="4 stars">☆</button>
                        <button type="button" class="fstar" data-v="5" aria-label="5 stars">☆</button>
                    </div>
                    <p id="finishRatingError" class="read-finish-error" style="display:none" role="alert">Please
                        select 1 to 5 stars.</p>
                    <div class="read-finish-actions">
                        <button type="button" class="btn-primary" id="btnFinishSave" disabled>Confirm rating</button>
                    </div>
                </div>
                <div id="finishStep3" style="display:none" aria-labelledby="finishSuccessTitle">
                    <h2 id="finishSuccessTitle" class="font-display read-finish-title">You did it!</h2>
                    <p id="finishRewardMsg" class="read-finish-reward font-body"></p>
                    <p id="finishRedirectHint" class="read-finish-hint font-body" style="display:none;text-align:center;color:var(--muted-foreground);font-size:.8rem;margin:0 0 1rem">Taking you to your profile…</p>
                    <button type="button" class="btn-primary" id="btnFinishClose">Continue to profile</button>
                </div>
            </div>
        </div>
    </div>

    <div id="lumo-chatbot-root" data-asset-base="public/assets/images/" data-lumo-greeting="Hi there! I'm Lumo — enjoy your reading session!"></div>

    <style>
        .read-finish-modal-inner {
            padding: 1.5rem;
        }

        .read-finish-title {
            font-size: 1.1rem;
            margin: 0 0 .75rem;
            text-align: center;
        }

        .read-finish-sub {
            text-align: center;
            color: var(--muted-foreground);
            margin: 0 0 1.25rem;
        }

        .read-finish-actions {
            display: flex;
            flex-direction: column;
            gap: .75rem;
        }

        .read-finish-stars {
            display: flex;
            justify-content: center;
            gap: .35rem;
            margin-bottom: 1rem;
        }

        .read-finish-stars .fstar {
            font-size: 1.75rem;
            line-height: 1;
            background: none;
            border: none;
            cursor: pointer;
            padding: .25rem;
            color: var(--primary);
        }

        .read-finish-error {
            color: hsl(0, 62%, 55%);
            font-size: .875rem;
            text-align: center;
            margin: 0 0 .75rem;
        }

        .read-finish-reward {
            text-align: center;
            margin: 0 0 1.25rem;
            font-size: 1rem;
        }
    </style>

    <script src="public/assets/js/models/user_data.js"></script>
    <script src="public/assets/js/models/lexora-state.js"></script>
    <script src="public/assets/js/lumo-chatbot.js"></script>
    <script src="public/assets/js/user_app.js"></script>
    <script src="public/assets/js/read_book_app.js"></script>
</body>

</html>
