<?php
require_once __DIR__ . '/../../core/Database.php';

class BookModel
{
    /** @var array<string,bool> */
    private static array $columnExistsCache = [];

    private static function tableExists(string $tableName): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM information_schema.TABLES
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
            );
            $stmt->execute([$tableName]);
            return ((int)$stmt->fetchColumn() > 0);
        } catch (Throwable $e) {
            return false;
        }
    }

    private static function columnExists(string $tableName, string $columnName): bool
    {
        $cacheKey = $tableName . '.' . $columnName;
        if (array_key_exists($cacheKey, self::$columnExistsCache)) {
            return self::$columnExistsCache[$cacheKey];
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT COUNT(*) FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = ?
                   AND COLUMN_NAME = ?'
            );
            $stmt->execute([$tableName, $columnName]);
            self::$columnExistsCache[$cacheKey] = ((int)$stmt->fetchColumn() > 0);
        } catch (\Throwable) {
            self::$columnExistsCache[$cacheKey] = false;
        }
        return self::$columnExistsCache[$cacheKey];
    }

    private static function addEdgeDfs(array &$graph, int $fromId, int $toId, string $reason): void
    {
        if (!isset($graph[$fromId])) {
            $graph[$fromId] = [];
        }
        if (!isset($graph[$fromId][$toId])) {
            $graph[$fromId][$toId] = [];
        }
        if (!in_array($reason, $graph[$fromId][$toId], true)) {
            $graph[$fromId][$toId][] = $reason;
        }
    }

    private static function buildGenreGraphDfs(array $booksById): array
    {
        $graph = [];
        $booksByGenre = [];

        foreach ($booksById as $bookId => $book) {
            $genres = [];
            $legacyGenre = trim((string)($book['genre'] ?? ''));
            if ($legacyGenre !== '') {
                $genres[$legacyGenre] = true;
            }

            $bookGenres = $book['genres'] ?? [];
            if (is_array($bookGenres)) {
                foreach ($bookGenres as $g) {
                    $genre = trim((string)$g);
                    if ($genre !== '') {
                        $genres[$genre] = true;
                    }
                }
            }

            foreach (array_keys($genres) as $genre) {
                if (!isset($booksByGenre[$genre])) {
                    $booksByGenre[$genre] = [];
                }
                $booksByGenre[$genre][] = (int)$bookId;
            }
        }

        foreach ($booksByGenre as $genre => $bookIds) {
            $uniqueIds = array_values(array_unique(array_map('intval', $bookIds)));
            $count = count($uniqueIds);
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $reason = 'Same genre: ' . $genre;
                    self::addEdgeDfs($graph, $uniqueIds[$i], $uniqueIds[$j], $reason);
                    self::addEdgeDfs($graph, $uniqueIds[$j], $uniqueIds[$i], $reason);
                }
            }
        }

        return $graph;
    }

    private static function dfsRecommendBooks(
        array $startIds,
        array $booksById,
        array $graph,
        int $limit,
        array $excludeBookIds
    ): array {
        $stack = [];
        $visited = [];
        $results = [];
        $exclude = array_fill_keys(array_map('intval', $excludeBookIds), true);

        foreach ($startIds as $id) {
            $startId = (int)$id;
            if ($startId <= 0 || !isset($booksById[$startId])) {
                continue;
            }
            if (isset($visited[$startId])) {
                continue;
            }
            $visited[$startId] = true;
            $stack[] = [$startId, 0];
        }

        while ($stack !== [] && count($results) < $limit) {
            $entry = array_pop($stack);
            $currentId = (int)($entry[0] ?? 0);
            $distance = (int)($entry[1] ?? 0);
            $neighbors = array_keys($graph[$currentId] ?? []);
            rsort($neighbors);

            foreach ($neighbors as $neighborIdRaw) {
                $neighborId = (int)$neighborIdRaw;
                if (isset($visited[$neighborId]) || !isset($booksById[$neighborId])) {
                    continue;
                }

                $visited[$neighborId] = true;
                $stack[] = [$neighborId, $distance + 1];

                if (isset($exclude[$neighborId])) {
                    continue;
                }

                $book = $booksById[$neighborId];
                $book['distance'] = $distance + 1;
                $book['linked_from'] = (string)($booksById[$currentId]['title'] ?? '');
                $book['match_reasons'] = array_slice($graph[$currentId][$neighborId] ?? [], 0, 3);
                $results[] = $book;

                if (count($results) >= $limit) {
                    break;
                }
            }
        }

        return $results;
    }

    private static function getUserProfileSignals(int $userId): array
    {
        $signals = [
            'readBookIds' => [],
            'completedBookIds' => [],
            'likedBookIds' => [],
            'preferredGenres' => [],
        ];
        if ($userId <= 0) {
            return $signals;
        }

        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT book_id, status, rating
                 FROM user_books
                 WHERE user_id = ?'
            );
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

            $read = [];
            $completed = [];
            $liked = [];
            foreach ($rows as $row) {
                $bookId = (int)($row['book_id'] ?? 0);
                if ($bookId <= 0) {
                    continue;
                }
                $status = (string)($row['status'] ?? '');
                $rating = $row['rating'] !== null ? (int)$row['rating'] : null;

                $read[$bookId] = true;
                if ($status === 'completed') {
                    $completed[$bookId] = true;
                }
                if ($rating !== null && $rating >= 4) {
                    $liked[$bookId] = true;
                }
            }

            $signals['readBookIds'] = array_map('intval', array_keys($read));
            $signals['completedBookIds'] = array_map('intval', array_keys($completed));
            $signals['likedBookIds'] = array_map('intval', array_keys($liked));

            if (self::tableExists('user_category_preferences')) {
                $prefStmt = $pdo->prepare(
                    'SELECT genre FROM user_category_preferences WHERE user_id = ? ORDER BY genre ASC'
                );
                $prefStmt->execute([$userId]);
                $prefs = $prefStmt->fetchAll(PDO::FETCH_COLUMN) ?: [];
                $signals['preferredGenres'] = array_values(array_filter(array_map(static function ($g): string {
                    return trim((string)$g);
                }, $prefs)));
            }
        } catch (Throwable $e) {
            return $signals;
        }

        return $signals;
    }

    private static function getRecentReadingWeights(int $userId, int $days = 30): array
    {
        if ($userId <= 0) {
            return [];
        }

        $days = max(1, min(365, $days));
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT book_id, COALESCE(SUM(pages_read), 0) AS pages_total, COALESCE(SUM(minutes_read), 0) AS minutes_total
                 FROM reading_sessions
                 WHERE user_id = ?
                   AND session_date >= DATE_SUB(CURDATE(), INTERVAL ' . (int)$days . ' DAY)
                 GROUP BY book_id'
            );
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
            $weights = [];
            foreach ($rows as $row) {
                $bookId = (int)($row['book_id'] ?? 0);
                if ($bookId <= 0) {
                    continue;
                }
                $pages = (int)($row['pages_total'] ?? 0);
                $minutes = (int)($row['minutes_total'] ?? 0);
                // Favor recent pages read, with minutes as a lighter signal.
                $weights[$bookId] = ($pages * 2) + (int)round($minutes / 5);
            }
            return $weights;
        } catch (Throwable $e) {
            return [];
        }
    }

    public static function personalizedRecommendationsDfs(
        int $userId,
        int $limit = 3,
        ?string $preferredGenreHint = null,
        array $excludeBookIds = []
    ): array {
        if ($userId <= 0) {
            return [];
        }
        $limit = max(1, min(20, $limit));

        $rows = self::all();
        if (!$rows) {
            return [];
        }

        $bookIds = array_values(array_filter(array_map(static function (array $r): int {
            return (int)($r['id'] ?? 0);
        }, $rows), static function (int $id): bool {
            return $id > 0;
        }));
        $genresMap = self::genresMapByBookIds($bookIds);

        $booksById = [];
        foreach ($rows as $row) {
            $id = (int)($row['id'] ?? 0);
            if ($id <= 0) {
                continue;
            }
            $legacyGenre = trim((string)($row['genre'] ?? ''));
            $genres = $genresMap[$id] ?? [];
            if (!$genres && $legacyGenre !== '') {
                $genres = [$legacyGenre];
            }
            $booksById[$id] = [
                'id' => $id,
                'title' => (string)($row['title'] ?? ''),
                'author' => (string)($row['author'] ?? ''),
                'publicationYear' => (int)($row['publication_year'] ?? 0),
                'genre' => $legacyGenre,
                'genres' => array_values($genres),
                'cover' => (string)($row['cover'] ?? '📖'),
                'coinCost' => (int)($row['coinCost'] ?? 0),
                'xpReward' => (int)($row['xpReward'] ?? 0),
                'coinReward' => (int)($row['coinReward'] ?? 0),
                'audience' => (string)($row['audience'] ?? 'All'),
                'trending' => !empty($row['trending']),
                'description' => (string)($row['description'] ?? ''),
            ];
        }
        if (!$booksById) {
            return [];
        }

        $signals = self::getUserProfileSignals($userId);
        $preferredGenreHint = trim((string)$preferredGenreHint);
        $preferredGenres = $signals['preferredGenres'] ?? [];
        if ($preferredGenreHint !== '') {
            array_unshift($preferredGenres, $preferredGenreHint);
        }
        $preferredGenres = array_values(array_unique(array_map(static function ($g): string {
            return strtolower(trim((string)$g));
        }, $preferredGenres)));

        $candidateStartIds = [];
        $likedIds = $signals['likedBookIds'] ?? [];
        $completedIds = $signals['completedBookIds'] ?? [];
        $readIds = $signals['readBookIds'] ?? [];
        $excludeBookIds = array_values(array_unique(array_filter(array_map('intval', $excludeBookIds), static function (int $id): bool {
            return $id > 0;
        })));
        $readAndExcluded = array_values(array_unique(array_merge($readIds, $excludeBookIds)));

        foreach ([$likedIds, $completedIds, $readIds] as $group) {
            foreach ($group as $bookId) {
                $id = (int)$bookId;
                if ($id > 0 && isset($booksById[$id])) {
                    $candidateStartIds[$id] = true;
                }
            }
        }

        if (count($candidateStartIds) > 0 && count($preferredGenres) > 0) {
            $filtered = [];
            foreach (array_keys($candidateStartIds) as $bookId) {
                $book = $booksById[(int)$bookId] ?? null;
                if (!$book) {
                    continue;
                }
                $bookGenres = array_map(static function ($g): string {
                    return strtolower(trim((string)$g));
                }, is_array($book['genres'] ?? null) ? $book['genres'] : []);
                $legacyGenre = strtolower(trim((string)($book['genre'] ?? '')));
                if ($legacyGenre !== '') {
                    $bookGenres[] = $legacyGenre;
                }

                $matches = false;
                foreach ($bookGenres as $bg) {
                    if ($bg === '') {
                        continue;
                    }
                    foreach ($preferredGenres as $pg) {
                        if ($pg !== '' && ($bg === $pg || str_contains($bg, $pg) || str_contains($pg, $bg))) {
                            $matches = true;
                            break 2;
                        }
                    }
                }
                if ($matches) {
                    $filtered[(int)$bookId] = true;
                }
            }
            if (count($filtered) > 0) {
                $candidateStartIds = $filtered;
            }
        }

        if (count($candidateStartIds) === 0) {
            foreach ($booksById as $id => $book) {
                $bookGenres = array_map(static function ($g): string {
                    return strtolower(trim((string)$g));
                }, is_array($book['genres'] ?? null) ? $book['genres'] : []);
                $legacyGenre = strtolower(trim((string)($book['genre'] ?? '')));
                if ($legacyGenre !== '') {
                    $bookGenres[] = $legacyGenre;
                }

                $matches = count($preferredGenres) === 0;
                foreach ($bookGenres as $bg) {
                    foreach ($preferredGenres as $pg) {
                        if ($pg !== '' && ($bg === $pg || str_contains($bg, $pg) || str_contains($pg, $bg))) {
                            $matches = true;
                            break 2;
                        }
                    }
                }
                if ($matches) {
                    $candidateStartIds[(int)$id] = true;
                    break;
                }
            }
        }

        if (count($candidateStartIds) === 0) {
            $firstId = (int)array_key_first($booksById);
            if ($firstId > 0) {
                $candidateStartIds[$firstId] = true;
            }
        }

        $recentWeights = self::getRecentReadingWeights($userId, 30);
        $likedLookup = array_fill_keys(array_map('intval', $likedIds), true);
        $completedLookup = array_fill_keys(array_map('intval', $completedIds), true);
        $startIds = array_map('intval', array_keys($candidateStartIds));

        usort($startIds, static function (int $a, int $b) use ($recentWeights, $likedLookup, $completedLookup): int {
            $wa = (int)($recentWeights[$a] ?? 0);
            $wb = (int)($recentWeights[$b] ?? 0);
            if ($wa !== $wb) {
                return $wb <=> $wa;
            }

            $sa = (isset($likedLookup[$a]) ? 20 : 0) + (isset($completedLookup[$a]) ? 5 : 0);
            $sb = (isset($likedLookup[$b]) ? 20 : 0) + (isset($completedLookup[$b]) ? 5 : 0);
            if ($sa !== $sb) {
                return $sb <=> $sa;
            }

            return $a <=> $b;
        });

        $graph = self::buildGenreGraphDfs($booksById);
        return self::dfsRecommendBooks(
            $startIds,
            $booksById,
            $graph,
            $limit,
            $readAndExcluded
        );
    }

    public static function personalizedRecommendations(
        int $userId,
        int $limit = 3,
        ?string $preferredGenreHint = null,
        array $excludeBookIds = []
    ): array {
        $limit = max(3, min(20, $limit));
        $base = self::personalizedRecommendationsDfs($userId, $limit, $preferredGenreHint, $excludeBookIds);
        $picked = [];
        foreach ($base as $row) {
            $id = (int)($row['id'] ?? 0);
            if ($id > 0) {
                $picked[$id] = $row;
            }
        }
        if (count($picked) >= $limit) {
            return array_values(array_slice($picked, 0, $limit, true));
        }

        $signals = self::getUserProfileSignals($userId);
        $readIds = array_values(array_unique(array_map('intval', $signals['readBookIds'] ?? [])));
        $likedIds = array_values(array_unique(array_map('intval', $signals['likedBookIds'] ?? [])));
        $preferredGenresRaw = array_values(array_unique(array_filter(array_map(static function ($g): string {
            return strtolower(trim((string)$g));
        }, $signals['preferredGenres'] ?? []))));
        if ($preferredGenreHint !== null && trim($preferredGenreHint) !== '') {
            array_unshift($preferredGenresRaw, strtolower(trim($preferredGenreHint)));
            $preferredGenresRaw = array_values(array_unique($preferredGenresRaw));
        }

        $exclude = array_fill_keys(array_map('intval', array_merge($readIds, $excludeBookIds, array_keys($picked))), true);
        $rows = self::all();
        if (!$rows) {
            return array_values($picked);
        }
        $bookIds = array_values(array_filter(array_map(static function (array $r): int {
            return (int)($r['id'] ?? 0);
        }, $rows), static function (int $id): bool {
            return $id > 0;
        }));
        $genresMap = self::genresMapByBookIds($bookIds);

        $likedAudiences = [];
        foreach ($rows as $row) {
            $id = (int)($row['id'] ?? 0);
            if ($id > 0 && in_array($id, $likedIds, true)) {
                $aud = strtolower(trim((string)($row['audience'] ?? '')));
                if ($aud !== '') {
                    $likedAudiences[$aud] = true;
                }
            }
        }

        $scored = [];
        foreach ($rows as $row) {
            $id = (int)($row['id'] ?? 0);
            if ($id <= 0 || isset($exclude[$id])) {
                continue;
            }
            $legacyGenre = strtolower(trim((string)($row['genre'] ?? '')));
            $genres = array_map(static function ($g): string {
                return strtolower(trim((string)$g));
            }, $genresMap[$id] ?? []);
            if ($legacyGenre !== '') {
                $genres[] = $legacyGenre;
            }
            $genres = array_values(array_unique(array_filter($genres)));
            $audience = strtolower(trim((string)($row['audience'] ?? '')));
            $trending = !empty($row['trending']);

            $score = 0;
            foreach ($genres as $g) {
                foreach ($preferredGenresRaw as $pg) {
                    if ($pg !== '' && ($g === $pg || str_contains($g, $pg) || str_contains($pg, $g))) {
                        $score += 30;
                        break;
                    }
                }
            }
            if ($audience !== '' && isset($likedAudiences[$audience])) {
                $score += 15;
            }
            if ($trending) {
                $score += 5;
            }

            if ($score <= 0 && count($preferredGenresRaw) > 0) {
                continue;
            }
            $scored[] = ['score' => $score, 'id' => $id, 'row' => [
                'id' => $id,
                'title' => (string)($row['title'] ?? ''),
                'author' => (string)($row['author'] ?? ''),
                'publicationYear' => (int)($row['publication_year'] ?? 0),
                'genre' => (string)($row['genre'] ?? ''),
                'genres' => $genresMap[$id] ?? (($row['genre'] ?? '') !== '' ? [(string)$row['genre']] : []),
                'cover' => (string)($row['cover'] ?? '📖'),
                'coinCost' => (int)($row['coinCost'] ?? 0),
                'xpReward' => (int)($row['xpReward'] ?? 0),
                'coinReward' => (int)($row['coinReward'] ?? 0),
                'audience' => (string)($row['audience'] ?? 'All'),
                'trending' => $trending,
                'description' => (string)($row['description'] ?? ''),
                'match_reasons' => ['Matched your reading preferences'],
            ]];
        }

        usort($scored, static function (array $a, array $b): int {
            if ($a['score'] !== $b['score']) {
                return $b['score'] <=> $a['score'];
            }
            return $b['id'] <=> $a['id'];
        });

        foreach ($scored as $entry) {
            $id = (int)$entry['id'];
            if (isset($picked[$id])) {
                continue;
            }
            $picked[$id] = $entry['row'];
            if (count($picked) >= $limit) {
                break;
            }
        }

        // Final safety fill: if still short, append any unseen unread books
        // so chatbot can return a multi-book list instead of a generic fallback.
        if (count($picked) < $limit) {
            foreach ($rows as $row) {
                $id = (int)($row['id'] ?? 0);
                if ($id <= 0 || isset($exclude[$id]) || isset($picked[$id])) {
                    continue;
                }
                $picked[$id] = [
                    'id' => $id,
                    'title' => (string)($row['title'] ?? ''),
                    'author' => (string)($row['author'] ?? ''),
                    'publicationYear' => (int)($row['publication_year'] ?? 0),
                    'genre' => (string)($row['genre'] ?? ''),
                    'genres' => $genresMap[$id] ?? (($row['genre'] ?? '') !== '' ? [(string)$row['genre']] : []),
                    'cover' => (string)($row['cover'] ?? '📖'),
                    'coinCost' => (int)($row['coinCost'] ?? 0),
                    'xpReward' => (int)($row['xpReward'] ?? 0),
                    'coinReward' => (int)($row['coinReward'] ?? 0),
                    'audience' => (string)($row['audience'] ?? 'All'),
                    'trending' => !empty($row['trending']),
                    'description' => (string)($row['description'] ?? ''),
                    'match_reasons' => ['Picked from your unread library context'],
                ];
                if (count($picked) >= $limit) {
                    break;
                }
            }
        }

        return array_values(array_slice($picked, 0, $limit, true));
    }

    public static function genresMapByBookIds(array $bookIds): array
    {
        $bookIds = array_values(array_filter(array_map('intval', $bookIds), static function ($id): bool {
            return $id > 0;
        }));
        if (!$bookIds) {
            return [];
        }

        try {
            $pdo = Database::pdo();
            $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
            $stmt = $pdo->prepare(
                "SELECT book_id, genre_name
                 FROM book_genres
                 WHERE book_id IN ($placeholders)
                 ORDER BY book_id ASC, genre_name ASC"
            );
            $stmt->execute($bookIds);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $map = [];
            foreach ($rows as $row) {
                $bookId = (int)($row['book_id'] ?? 0);
                $genre = trim((string)($row['genre_name'] ?? ''));
                if ($bookId <= 0 || $genre === '') {
                    continue;
                }
                if (!isset($map[$bookId])) {
                    $map[$bookId] = [];
                }
                $map[$bookId][] = $genre;
            }
            return $map;
        } catch (PDOException $e) {
            // If book_genres doesn't exist yet, fall back gracefully.
            return [];
        }
    }

    public static function findById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function all(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("SELECT * FROM books ORDER BY id DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // If the table doesn't exist (or DB not initialized yet), don't crash the app.
            return [];
        }
    }

    public static function create(array $data): bool
    {
        try {
            $pdo = Database::pdo();
            $columns = ['title', 'author', 'genre'];
            $values = [
                (string)($data['title'] ?? ''),
                (string)($data['author'] ?? ''),
                (string)($data['genre'] ?? ''),
            ];

            if (self::columnExists('books', 'publication_year')) {
                $columns[] = 'publication_year';
                $values[] = (int)($data['publication_year'] ?? 0);
            }
            if (self::columnExists('books', 'cover')) {
                $columns[] = 'cover';
                $values[] = (string)($data['cover'] ?? '📖');
            }
            if (self::columnExists('books', 'coinCost')) {
                $columns[] = 'coinCost';
                $values[] = (int)($data['coinCost'] ?? 0);
            }
            if (self::columnExists('books', 'xpReward')) {
                $columns[] = 'xpReward';
                $values[] = (int)($data['xpReward'] ?? 0);
            }
            if (self::columnExists('books', 'coinReward')) {
                $columns[] = 'coinReward';
                $values[] = (int)($data['coinReward'] ?? 0);
            }
            if (self::columnExists('books', 'audience')) {
                $columns[] = 'audience';
                $values[] = (string)($data['audience'] ?? 'All');
            }
            if (self::columnExists('books', 'trending')) {
                $columns[] = 'trending';
                $values[] = (int)($data['trending'] ?? 0);
            }
            if (self::columnExists('books', 'description') && array_key_exists('description', $data)) {
                $columns[] = 'description';
                $values[] = (string)$data['description'];
            }

            $placeholders = implode(', ', array_fill(0, count($columns), '?'));
            $columnsSql = implode(', ', $columns);
            $stmt = $pdo->prepare("INSERT INTO books ({$columnsSql}) VALUES ({$placeholders})");
            return (bool)$stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(int $id, array $data): bool
    {
        try {
            $pdo = Database::pdo();
            $setParts = [];
            $values = [];

            $setParts[] = 'title = ?';
            $values[] = (string)($data['title'] ?? '');
            $setParts[] = 'author = ?';
            $values[] = (string)($data['author'] ?? '');
            $setParts[] = 'genre = ?';
            $values[] = (string)($data['genre'] ?? '');

            if (self::columnExists('books', 'publication_year')) {
                $setParts[] = 'publication_year = ?';
                $values[] = (int)($data['publication_year'] ?? 0);
            }
            if (self::columnExists('books', 'cover')) {
                $setParts[] = 'cover = ?';
                $values[] = (string)($data['cover'] ?? '📖');
            }
            if (self::columnExists('books', 'coinCost')) {
                $setParts[] = 'coinCost = ?';
                $values[] = (int)($data['coinCost'] ?? 0);
            }
            if (self::columnExists('books', 'xpReward')) {
                $setParts[] = 'xpReward = ?';
                $values[] = (int)($data['xpReward'] ?? 0);
            }
            if (self::columnExists('books', 'coinReward')) {
                $setParts[] = 'coinReward = ?';
                $values[] = (int)($data['coinReward'] ?? 0);
            }
            if (self::columnExists('books', 'audience')) {
                $setParts[] = 'audience = ?';
                $values[] = (string)($data['audience'] ?? 'All');
            }
            if (self::columnExists('books', 'trending')) {
                $setParts[] = 'trending = ?';
                $values[] = (int)($data['trending'] ?? 0);
            }
            if (self::columnExists('books', 'description') && array_key_exists('description', $data)) {
                $setParts[] = 'description = ?';
                $values[] = (string)$data['description'];
            }

            $values[] = $id;
            $setSql = implode(', ', $setParts);
            $stmt = $pdo->prepare("UPDATE books SET {$setSql} WHERE id = ?");
            return (bool)$stmt->execute($values);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $pdo = Database::pdo();
            $pdo->beginTransaction();

            $tablesWithBookFk = [
                'user_books',
                'reading_sessions',
                'reading_progress',
                'book_genres',
                'posts',
                'comments',
            ];
            foreach ($tablesWithBookFk as $tableName) {
                if (!self::tableExists($tableName) || !self::columnExists($tableName, 'book_id')) {
                    continue;
                }
                $pdo->prepare("DELETE FROM {$tableName} WHERE book_id = ?")->execute([$id]);
            }

            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
            $ok = (bool)$stmt->execute([$id]);
            if ($ok) {
                $pdo->commit();
                return true;
            }
            $pdo->rollBack();
            return false;
        } catch (PDOException $e) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            return false;
        }
    }
    public static function searchByTitle(string $query): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM books WHERE title LIKE ? ORDER BY title ASC');
            $stmt->execute(['%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}

