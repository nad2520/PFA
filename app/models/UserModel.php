<?php
require_once __DIR__ . '/../../core/Database.php';

class UserModel
{
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
            $stmt = $pdo->prepare(
                'SELECT ub.book_id, ub.status, ub.progress_page, ub.rating, ub.completed_at,
                        b.id AS bid, b.title, b.author, b.genre, b.cover, b.coinCost, b.xpReward, b.coinReward,
                        b.audience, b.trending, b.description
                 FROM user_books ub
                 INNER JOIN books b ON b.id = ub.book_id
                 WHERE ub.user_id = ?
                 ORDER BY ub.id DESC'
            );
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

    /**
     * @return list<array<string, mixed>>
     */
    public static function leaderboard(int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query(
                'SELECT id, nom, level, xp, coins FROM users ORDER BY xp DESC, level DESC LIMIT ' . (int)$limit
            );
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}
