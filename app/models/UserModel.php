<?php
require_once __DIR__ . '/../../core/Database.php';

class UserModel
{
    /** Shown when userCoins < book coin cost (purchase / unlock). */
    public const MSG_INSUFFICIENT_COINS = 'You cannot buy this book. Try to fulfill your quests or buy coins.';
    /** @var array<string,bool> */
    private static array $tableExistsCache = [];

    private static function tableExists(string $tableName): bool
    {
        if (array_key_exists($tableName, self::$tableExistsCache)) {
            return self::$tableExistsCache[$tableName];
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT COUNT(*)
                 FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
            );
            $stmt->execute([$tableName]);
            self::$tableExistsCache[$tableName] = ((int)$stmt->fetchColumn() > 0);
        } catch (\Throwable) {
            self::$tableExistsCache[$tableName] = false;
        }
        return self::$tableExistsCache[$tableName];
    }

    public static function all(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query('SELECT * FROM users ORDER BY id DESC');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function findById(int $id): ?array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            $row['xp'] = (int)($row['xp'] ?? 0);
            $row['streak_days'] = (int)($row['streak_days'] ?? 0);
            $row['coins'] = (int)($row['coins'] ?? 0);
            $row['level'] = (int)($row['level'] ?? 1);
            return $row;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function findPasswordById(int $id): ?string
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT password FROM users WHERE id = ?');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['password'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function update(int $id, string $name, string $email, string $passwordHash): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('UPDATE users SET nom = ?, email = ?, password = ? WHERE id = ?');
            return (bool) $stmt->execute([$name, $email, $passwordHash, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
            return (bool) $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * True if the user may open the reader: owns the book as reading or completed (not plan_to_read only).
     */
    public static function userHasReadableAccess(int $userId, int $bookId): bool
    {
        if ($userId <= 0 || $bookId <= 0) {
            return false;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT 1 FROM user_books
                 WHERE user_id = ? AND book_id = ? AND status IN ("reading", "completed")
                 LIMIT 1'
            );
            $stmt->execute([$userId, $bookId]);
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Aggregates from reading_sessions for profile UI.
     *
     * @return array{
     *   totalReadingMinutes:int,
     *   totalReadingHours:float,
     *   averageReadingMinutes:int,
     *   averageReadingHours:float,
     *   dailyReadingGoalHours:float
     * }
     */
    public static function getReadingTimeAggregates(int $userId): array
    {
        $defaults = [
            'totalReadingMinutes' => 0,
            'totalReadingHours' => 0.0,
            'averageReadingMinutes' => 0,
            'averageReadingHours' => 0.0,
            'dailyReadingGoalHours' => 4.0,
        ];
        if ($userId <= 0) {
            return $defaults;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT
                    COALESCE(SUM(minutes_read), 0) AS total_minutes,
                    NULLIF(COUNT(DISTINCT CASE WHEN minutes_read > 0 OR pages_read > 0 THEN session_date END), 0) AS active_days
                 FROM reading_sessions
                 WHERE user_id = ?'
            );
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return $defaults;
            }
            $total = (int)($row['total_minutes'] ?? 0);
            $activeDays = (int)($row['active_days'] ?? 0);
            $avg = ($activeDays > 0) ? (int)round($total / $activeDays) : 0;

            return [
                'totalReadingMinutes' => $total,
                'totalReadingHours' => round($total / 60, 2),
                'averageReadingMinutes' => $avg,
                'averageReadingHours' => round($avg / 60, 2),
                'dailyReadingGoalHours' => 4.0,
            ];
        } catch (Throwable $e) {
            return $defaults;
        }
    }

    public static function countBooksRead(int $userId): int
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM user_books WHERE user_id = ? AND status = "completed"'
            );
            $stmt->execute([$userId]);
            return (int)$stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Library rows for API / profile: each item has book, status (hyphenated for frontend), progress_page.
     *
     * @return list<array<string, mixed>>
     */
    public static function getUserBooks(int $userId): array
    {
        try {
            $pdo = Database::pdo();
            $hasReadingProgress = self::tableExists('reading_progress');
            if ($hasReadingProgress) {
                $stmt = $pdo->prepare(
                    'SELECT ub.book_id, ub.status,
                            GREATEST(COALESCE(ub.progress_page, 0), COALESCE(rp.last_page, 0)) AS progress_page,
                            ub.rating, ub.completed_at,
                            b.id AS bid, b.title, b.author, b.genre, b.cover, b.coinCost, b.xpReward, b.coinReward,
                            b.audience, b.trending, b.description
                     FROM user_books ub
                     INNER JOIN books b ON b.id = ub.book_id
                     LEFT JOIN reading_progress rp ON rp.user_id = ub.user_id AND rp.book_id = ub.book_id
                     WHERE ub.user_id = ?
                     ORDER BY ub.id DESC'
                );
            } else {
                $stmt = $pdo->prepare(
                    'SELECT ub.book_id, ub.status,
                            COALESCE(ub.progress_page, 0) AS progress_page,
                            ub.rating, ub.completed_at,
                            b.id AS bid, b.title, b.author, b.genre, b.cover, b.coinCost, b.xpReward, b.coinReward,
                            b.audience, b.trending, b.description
                     FROM user_books ub
                     INNER JOIN books b ON b.id = ub.book_id
                     WHERE ub.user_id = ?
                     ORDER BY ub.id DESC'
                );
            }
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $out = [];
            foreach ($rows as $row) {
                $out[] = [
                    'book_id'       => (int)$row['book_id'],
                    'status'        => self::statusToFrontend((string)$row['status']),
                    'progress_page' => (int)$row['progress_page'],
                    'rating'        => $row['rating'] !== null ? (int)$row['rating'] : null,
                    'completed_at'  => $row['completed_at'],
                    'book'          => [
                        'id'         => (int)$row['bid'],
                        'title'      => $row['title'],
                        'author'     => $row['author'],
                        'genre'      => $row['genre'],
                        'cover'      => $row['cover'],
                        'coinCost'   => (int)$row['coinCost'],
                        'xpReward'   => (int)$row['xpReward'],
                        'coinReward' => (int)$row['coinReward'],
                        'audience'   => $row['audience'],
                        'trending'   => (int)$row['trending'],
                        'description'=> $row['description'] ?? '',
                    ],
                ];
            }
            return $out;
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function statusToFrontend(string $dbStatus): string
    {
        return match ($dbStatus) {
            'plan_to_read' => 'plan-to-read',
            default        => $dbStatus,
        };
    }

    public static function statusFromFrontend(string $status): string
    {
        return match ($status) {
            'plan-to-read' => 'plan_to_read',
            default          => $status,
        };
    }

    public static function touchLastRead(int $userId): void
    {
        try {
            $pdo = Database::pdo();
            $pdo->prepare('UPDATE users SET last_read_at = NOW() WHERE id = ?')->execute([$userId]);
        } catch (PDOException $e) {
            // ignore
        }
    }

    /** @return 'happy'|'dim'|'worried' */
    public static function computeLumoState(array $userRow): string
    {
        $raw = $userRow['last_read_at'] ?? null;
        if ($raw === null || $raw === '') {
            return 'worried';
        }
        try {
            $last = new DateTimeImmutable((string)$raw);
        } catch (Exception $e) {
            return 'worried';
        }
        $now = new DateTimeImmutable('now');
        $hours = ($now->getTimestamp() - $last->getTimestamp()) / 3600;
        if ($hours > 72) {
            return 'worried';
        }
        if ($hours > 24) {
            return 'dim';
        }
        return 'happy';
    }

    public static function updateAfterReading(int $userId, int $minutesRead): void
    {
        $minutesRead = max(0, $minutesRead);
        $xpGain = max(1, min($minutesRead, 50));

        try {
            $pdo = Database::pdo();
            $row = self::findById($userId);
            if (!$row) {
                return;
            }

            $today = (new DateTimeImmutable('today'))->format('Y-m-d');
            $yesterday = (new DateTimeImmutable('today'))->modify('-1 day')->format('Y-m-d');
            $lastRead = $row['last_read_at'] ?? null;
            $streak = (int)($row['streak_days'] ?? 0);

            $newStreak = 1;
            $firstReadToday = true;
            if ($lastRead) {
                try {
                    $lastDay = (new DateTimeImmutable((string)$lastRead))->format('Y-m-d');
                    if ($lastDay === $today) {
                        $newStreak = $streak > 0 ? $streak : 1;
                        $firstReadToday = false;
                    } elseif ($lastDay === $yesterday) {
                        $newStreak = max(1, $streak + 1);
                    }
                } catch (Exception $e) {
                    $newStreak = 1;
                }
            }

            $pdo->prepare(
                'UPDATE users SET last_read_at = NOW(), xp = xp + ?, streak_days = ? WHERE id = ?'
            )->execute([$xpGain, $newStreak, $userId]);

            $pdo->prepare(
                'UPDATE users SET level = GREATEST(1, FLOOR(xp / 500) + 1) WHERE id = ?'
            )->execute([$userId]);

            // Daily streak coin reward: first read session of the day only.
            if ($firstReadToday) {
                $pdo->prepare('UPDATE users SET coins = coins + 50 WHERE id = ?')->execute([$userId]);
                $pdo->prepare(
                    'INSERT INTO economy_logs (user_id, log_date, coins_earned, event_type) VALUES (?, CURDATE(), ?, ?)'
                )->execute([$userId, 50, 'daily_streak']);
            }
        } catch (PDOException $e) {
            // ignore
        }
    }

    /**
     * Reward +20 coins for each new 10-page milestone reached on a book.
     */
    public static function applyPageMilestoneRewards(int $userId, int $bookId): int
    {
        if ($userId <= 0 || $bookId <= 0) {
            return 0;
        }
        try {
            $pdo = Database::pdo();
            $pdo->beginTransaction();

            $ub = $pdo->prepare(
                'SELECT id, page_reward_steps FROM user_books WHERE user_id = ? AND book_id = ? FOR UPDATE'
            );
            $ub->execute([$userId, $bookId]);
            $row = $ub->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $pdo->commit();
                return 0;
            }

            $sum = $pdo->prepare(
                'SELECT COALESCE(SUM(pages_read), 0) AS total_pages FROM reading_sessions WHERE user_id = ? AND book_id = ?'
            );
            $sum->execute([$userId, $bookId]);
            $totalPages = (int)($sum->fetchColumn() ?: 0);

            $earnedSteps = intdiv(max(0, $totalPages), 10);
            $alreadySteps = (int)($row['page_reward_steps'] ?? 0);
            $newSteps = max(0, $earnedSteps - $alreadySteps);

            if ($newSteps <= 0) {
                $pdo->commit();
                return 0;
            }

            $coinReward = $newSteps * 20;
            $pdo->prepare(
                'UPDATE user_books SET page_reward_steps = ? WHERE id = ?'
            )->execute([$alreadySteps + $newSteps, (int)$row['id']]);
            $pdo->prepare(
                'UPDATE users SET coins = coins + ? WHERE id = ?'
            )->execute([$coinReward, $userId]);
            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_earned, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $coinReward, 'reading_pages_reward']);

            $pdo->commit();
            return $coinReward;
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return 0;
        }
    }

    public static function addToMyList(int $userId, int $bookId): bool
    {
        if ($userId <= 0 || $bookId <= 0) {
            return false;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at, completed_at)
                 VALUES (?, ?, "plan_to_read", 0, NULL, NULL)
                 ON DUPLICATE KEY UPDATE
                   status = CASE
                     WHEN status = "completed" THEN status
                     WHEN status = "reading" THEN status
                     ELSE "plan_to_read"
                   END'
            );
            return (bool)$stmt->execute([$userId, $bookId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function removeFromMyList(int $userId, int $bookId): bool
    {
        if ($userId <= 0 || $bookId <= 0) {
            return false;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'DELETE FROM user_books
                 WHERE user_id = ? AND book_id = ? AND status = "plan_to_read"'
            );
            $stmt->execute([$userId, $bookId]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function ensureBookInLibrary(int $userId, int $bookId): bool
    {
        if ($userId <= 0 || $bookId <= 0) {
            return false;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
                 VALUES (?, ?, "reading", 0, NOW())
                 ON DUPLICATE KEY UPDATE
                   status = CASE
                     WHEN status = "completed" THEN status
                     ELSE "reading"
                   END,
                   started_at = IFNULL(started_at, NOW())'
            );
            return (bool)$stmt->execute([$userId, $bookId]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function saveReadingProgress(int $userId, int $bookId, int $page): bool
    {
        if ($userId <= 0 || $bookId <= 0) {
            return false;
        }
        $page = max(0, $page);
        try {
            $pdo = Database::pdo();
            $pdo->beginTransaction();

            $chk = $pdo->prepare(
                'SELECT status FROM user_books WHERE user_id = ? AND book_id = ? LIMIT 1 FOR UPDATE'
            );
            $chk->execute([$userId, $bookId]);
            $row = $chk->fetch(PDO::FETCH_ASSOC);
            $st = (string)($row['status'] ?? '');
            if (!in_array($st, ['reading', 'completed'], true)) {
                $pdo->rollBack();
                return false;
            }

            if (self::tableExists('reading_progress')) {
                $stmtProgress = $pdo->prepare(
                    'INSERT INTO reading_progress (user_id, book_id, last_page, updated_at)
                     VALUES (?, ?, ?, NOW())
                     ON DUPLICATE KEY UPDATE
                       last_page = GREATEST(last_page, VALUES(last_page)),
                       updated_at = NOW()'
                );
                $stmtProgress->execute([$userId, $bookId, $page]);
            }

            $stmtBook = $pdo->prepare(
                'UPDATE user_books
                 SET progress_page = GREATEST(progress_page, ?)
                 WHERE user_id = ? AND book_id = ? AND status IN ("reading", "completed")'
            );
            $stmtBook->execute([$page, $userId, $bookId]);

            $pdo->commit();
            return true;
        } catch (\Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }

    public static function getBackToLecture(int $userId): ?array
    {
        if ($userId <= 0) {
            return null;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT rp.book_id, rp.last_page, rp.updated_at,
                        b.title, b.author, b.genre, b.cover, b.coinCost, b.xpReward, b.coinReward, b.audience, b.trending, b.description
                 FROM reading_progress rp
                 INNER JOIN books b ON b.id = rp.book_id
                 WHERE rp.user_id = ?
                 ORDER BY rp.updated_at DESC
                 LIMIT 1'
            );
            $stmt->execute([$userId]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                return null;
            }
            return [
                'book_id' => (int)$row['book_id'],
                'last_page' => (int)$row['last_page'],
                'updated_at' => $row['updated_at'],
                'book' => [
                    'id' => (int)$row['book_id'],
                    'title' => (string)$row['title'],
                    'author' => (string)$row['author'],
                    'genre' => (string)$row['genre'],
                    'cover' => (string)$row['cover'],
                    'coinCost' => (int)$row['coinCost'],
                    'xpReward' => (int)$row['xpReward'],
                    'coinReward' => (int)$row['coinReward'],
                    'audience' => (string)$row['audience'],
                    'trending' => (int)$row['trending'],
                    'description' => (string)($row['description'] ?? ''),
                ],
            ];
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function leaderboard(int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query(
                'SELECT
                    u.id,
                    u.nom,
                    u.level,
                    u.xp,
                    u.coins,
                    COALESCE((
                      SELECT COUNT(*)
                      FROM user_books ub
                      WHERE ub.user_id = u.id AND ub.status = "completed"
                    ), 0) AS books_read
                 FROM users u
                 ORDER BY u.xp DESC, u.coins DESC, u.id ASC
                 LIMIT ' . (int)$limit
            );
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $idx => &$row) {
                $row['rank'] = $idx + 1;
            }
            unset($row);
            return $rows;
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * @return array{rank:int,window:list<array<string,mixed>>}|null
     */
    public static function relativeLeaderboard(int $userId, int $above = 4, int $below = 2): ?array
    {
        if ($userId <= 0) {
            return null;
        }
        $above = max(0, $above);
        $below = max(0, $below);
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query(
                'SELECT
                    u.id,
                    u.nom,
                    u.level,
                    u.xp,
                    u.coins,
                    COALESCE((
                      SELECT COUNT(*)
                      FROM user_books ub
                      WHERE ub.user_id = u.id AND ub.status = "completed"
                    ), 0) AS books_read
                 FROM users u
                 ORDER BY u.xp DESC, u.coins DESC, u.id ASC'
            );
            $allRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $rank = 0;
            foreach ($allRows as $idx => $row) {
                if ((int)$row['id'] === $userId) {
                    $rank = $idx + 1;
                    break;
                }
            }
            if ($rank <= 0) {
                return null;
            }

            $startRank = max(1, $rank - $above);
            $endRank = $rank + $below;
            $rows = [];
            for ($i = $startRank; $i <= $endRank; $i++) {
                if (!isset($allRows[$i - 1])) {
                    continue;
                }
                $row = $allRows[$i - 1];
                $row['rank'] = $i;
                $row['isCurrentUser'] = ((int)$row['id'] === $userId);
                $rows[] = $row;
            }
            foreach ($rows as &$row) {
                $row['isCurrentUser'] = ((int)$row['id'] === $userId);
            }
            unset($row);

            return [
                'rank' => $rank,
                'window' => $rows,
            ];
        } catch (PDOException $e) {
            return null;
        }
    }

    /** @return list<string> */
    public static function getAvailableGenres(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query(
                'SELECT DISTINCT genre FROM books
                 WHERE genre IS NOT NULL AND TRIM(genre) <> ""
                 ORDER BY genre ASC'
            );
            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
            return array_values(array_filter(array_map(static fn($g) => trim((string)$g), $rows)));
        } catch (PDOException $e) {
            return [];
        }
    }

    /** @return list<string> */
    public static function getUserCategoryPreferences(int $userId): array
    {
        if ($userId <= 0 || !self::tableExists('user_category_preferences')) {
            return [];
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT genre
                 FROM user_category_preferences
                 WHERE user_id = ?
                 ORDER BY genre ASC'
            );
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
            return array_values(array_filter(array_map(static fn($g) => trim((string)$g), $rows)));
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function hasUserCategoryPreferencesSchema(): bool
    {
        return self::tableExists('user_category_preferences');
    }

    /** @param list<string> $genres */
    public static function saveUserCategoryPreferences(int $userId, array $genres): bool
    {
        if ($userId <= 0 || !self::tableExists('user_category_preferences')) {
            return false;
        }
        $available = self::getAvailableGenres();
        $allowed = array_fill_keys($available, true);
        $sanitized = [];
        foreach ($genres as $g) {
            $genre = trim((string)$g);
            if ($genre !== '' && isset($allowed[$genre])) {
                $sanitized[$genre] = true;
            }
        }
        $selected = array_keys($sanitized);
        if (count($selected) === 0) {
            return false;
        }
        try {
            $pdo = Database::pdo();
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM user_category_preferences WHERE user_id = ?')->execute([$userId]);
            $ins = $pdo->prepare(
                'INSERT INTO user_category_preferences (user_id, genre, created_at, updated_at)
                 VALUES (?, ?, NOW(), NOW())'
            );
            foreach ($selected as $genre) {
                $ins->execute([$userId, $genre]);
            }
            $pdo->commit();
            return true;
        } catch (\Throwable $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }

    /** @return list<array<string,mixed>> */
    public static function getForYouBooks(int $userId, int $limit = 12): array
    {
        if ($userId <= 0) {
            return [];
        }
        $limit = max(1, min(30, $limit));
        $genres = self::getUserCategoryPreferences($userId);
        if (count($genres) === 0) {
            return [];
        }
        try {
            $pdo = Database::pdo();
            $ph = implode(',', array_fill(0, count($genres), '?'));
            $sql = "SELECT b.id, b.title, b.author, b.genre, b.cover, b.coinCost, b.xpReward, b.coinReward, b.audience, b.trending, b.description
                    FROM books b
                    WHERE b.genre IN ($ph)
                      AND NOT EXISTS (
                        SELECT 1 FROM user_books ub
                        WHERE ub.user_id = ? AND ub.book_id = b.id
                      )
                    ORDER BY b.trending DESC, b.id DESC
                    LIMIT $limit";
            $stmt = $pdo->prepare($sql);
            $params = array_merge($genres, [$userId]);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            return array_map(static function (array $r): array {
                return [
                    'id' => (int)($r['id'] ?? 0),
                    'title' => (string)($r['title'] ?? ''),
                    'author' => (string)($r['author'] ?? ''),
                    'genre' => (string)($r['genre'] ?? ''),
                    'cover' => (string)($r['cover'] ?? '📖'),
                    'coinCost' => (int)($r['coinCost'] ?? 0),
                    'xpReward' => (int)($r['xpReward'] ?? 0),
                    'coinReward' => (int)($r['coinReward'] ?? 0),
                    'audience' => (string)($r['audience'] ?? 'All'),
                    'trending' => !empty($r['trending']),
                    'description' => (string)($r['description'] ?? ''),
                ];
            }, $rows);
        } catch (PDOException $e) {
            return [];
        }
    }
}
