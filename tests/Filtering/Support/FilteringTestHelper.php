<?php
declare(strict_types=1);

final class FilteringTestHelper
{
    /**
     * @param array<int, array<string, mixed>> $books
     * @param array<int, array{book_id:int, genre_name:string}> $bookGenres
     */
    public static function createSqliteCatalog(array $books, array $bookGenres = []): PDO
    {
        $pdo = new PDO('sqlite::memory:');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        $pdo->exec(
            'CREATE TABLE books (
                id INTEGER PRIMARY KEY,
                title TEXT NOT NULL,
                author TEXT NOT NULL,
                publication_year INTEGER DEFAULT 0,
                genre TEXT DEFAULT "",
                cover TEXT DEFAULT "",
                trending INTEGER DEFAULT 0,
                description TEXT DEFAULT "",
                audience TEXT DEFAULT "All",
                coinCost INTEGER DEFAULT 0,
                xpReward INTEGER DEFAULT 0,
                coinReward INTEGER DEFAULT 0
            )'
        );
        $pdo->exec(
            'CREATE TABLE book_genres (
                book_id INTEGER NOT NULL,
                genre_name TEXT NOT NULL
            )'
        );

        $bookStmt = $pdo->prepare(
            'INSERT INTO books (
                id, title, author, publication_year, genre, cover, trending, description, audience, coinCost, xpReward, coinReward
            ) VALUES (
                :id, :title, :author, :publication_year, :genre, :cover, :trending, :description, :audience, :coinCost, :xpReward, :coinReward
            )'
        );

        foreach ($books as $book) {
            $bookStmt->execute([
                ':id' => (int)($book['id'] ?? 0),
                ':title' => (string)($book['title'] ?? ''),
                ':author' => (string)($book['author'] ?? ''),
                ':publication_year' => (int)($book['publication_year'] ?? 0),
                ':genre' => (string)($book['genre'] ?? ''),
                ':cover' => (string)($book['cover'] ?? '📖'),
                ':trending' => !empty($book['trending']) ? 1 : 0,
                ':description' => (string)($book['description'] ?? ''),
                ':audience' => (string)($book['audience'] ?? 'All'),
                ':coinCost' => (int)($book['coinCost'] ?? 0),
                ':xpReward' => (int)($book['xpReward'] ?? 0),
                ':coinReward' => (int)($book['coinReward'] ?? 0),
            ]);
        }

        $genreStmt = $pdo->prepare('INSERT INTO book_genres (book_id, genre_name) VALUES (:book_id, :genre_name)');
        foreach ($bookGenres as $bookGenre) {
            $genreStmt->execute([
                ':book_id' => (int)$bookGenre['book_id'],
                ':genre_name' => (string)$bookGenre['genre_name'],
            ]);
        }

        return $pdo;
    }

    public static function setDatabasePdo(?PDO $pdo): void
    {
        $reflection = new ReflectionClass(Database::class);
        $property = $reflection->getProperty('pdo');
        $property->setAccessible(true);
        $property->setValue(null, $pdo);
    }

    public static function resetDatabase(): void
    {
        self::setDatabasePdo(null);
    }

    /**
     * @return array{books: array<int, array<string, mixed>>, book_genres: array<int, array{book_id:int, genre_name:string}>}
     */
    public static function sampleCatalogFixture(): array
    {
        return [
            'books' => [
                [
                    'id' => 3,
                    'title' => 'Zeta Mystery',
                    'author' => 'A. Doyle',
                    'publication_year' => 2019,
                    'genre' => 'Mystery',
                    'cover' => '🕵️',
                    'trending' => 1,
                    'description' => 'Classic whodunit.',
                    'audience' => 'All',
                    'coinCost' => 90,
                    'xpReward' => 20,
                    'coinReward' => 40,
                ],
                [
                    'id' => 1,
                    'title' => 'Alpha Fantasy',
                    'author' => 'F. Author',
                    'publication_year' => 2021,
                    'genre' => 'Fantasy',
                    'cover' => '🐉',
                    'trending' => 1,
                    'description' => 'Epic adventure.',
                    'audience' => 'All',
                    'coinCost' => 120,
                    'xpReward' => 25,
                    'coinReward' => 60,
                ],
                [
                    'id' => 2,
                    'title' => 'Chronicle of Code',
                    'author' => 'Dev Writer',
                    'publication_year' => 2023,
                    'genre' => 'Drama',
                    'cover' => '💻',
                    'trending' => 0,
                    'description' => 'Developer memoir.',
                    'audience' => 'User +18',
                    'coinCost' => 150,
                    'xpReward' => 30,
                    'coinReward' => 70,
                ],
            ],
            'book_genres' => [
                ['book_id' => 1, 'genre_name' => 'Fantasy'],
                ['book_id' => 1, 'genre_name' => 'Adventure'],
                ['book_id' => 3, 'genre_name' => 'Mystery'],
            ],
        ];
    }

    /**
     * @param array{books?: array<int, array<string, mixed>>, book_genres?: array<int, array{book_id:int, genre_name:string}>} $fixture
     * @param array<string, mixed> $session
     * @return array{exit_code:int,status:int,headers:array<int,string>,body:string,stderr:string,json:array<string,mixed>|null}
     */
    public static function runHttpRequest(
        string $method,
        string $uri,
        array $fixture = [],
        array $session = []
    ): array {
        $basePath = dirname(__DIR__, 3);
        $fixturePath = tempnam(sys_get_temp_dir(), 'lx-fixture-');
        $sessionPath = tempnam(sys_get_temp_dir(), 'lx-session-');
        $metaPath = tempnam(sys_get_temp_dir(), 'lx-meta-');

        file_put_contents($fixturePath, json_encode($fixture, JSON_THROW_ON_ERROR));
        file_put_contents($sessionPath, json_encode($session, JSON_THROW_ON_ERROR));

        $command = [
            PHP_BINARY,
            $basePath . '/tests/Filtering/Support/request_harness.php',
            '--method=' . $method,
            '--uri=' . $uri,
            '--fixture=' . $fixturePath,
            '--session=' . $sessionPath,
            '--meta=' . $metaPath,
        ];

        $descriptorSpec = [
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $process = proc_open($command, $descriptorSpec, $pipes, $basePath, null, ['bypass_shell' => true]);
        if (!is_resource($process)) {
            throw new RuntimeException('Could not start request harness.');
        }

        $body = stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $exitCode = proc_close($process);

        $metaRaw = is_file($metaPath) ? file_get_contents($metaPath) : false;
        $meta = is_string($metaRaw) && $metaRaw !== ''
            ? json_decode($metaRaw, true, 512, JSON_THROW_ON_ERROR)
            : [];

        @unlink($fixturePath);
        @unlink($sessionPath);
        @unlink($metaPath);

        $json = null;
        if (is_string($body) && $body !== '') {
            $decoded = json_decode($body, true);
            if (is_array($decoded)) {
                $json = $decoded;
            }
        }

        return [
            'exit_code' => $exitCode,
            'status' => (int)($meta['status'] ?? 200),
            'headers' => array_values(array_map('strval', $meta['headers'] ?? [])),
            'body' => (string)$body,
            'stderr' => (string)$stderr,
            'json' => $json,
        ];
    }
}
