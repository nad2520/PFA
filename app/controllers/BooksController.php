<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/BookModel.php';

class BooksController extends Controller
{
    /**
     * GET /api/catalog/books — public catalog for the user SPA (titles, prices, meta).
     */
    public function catalogBooks(): void
    {
        $rows = BookModel::all();
        $bookIds = array_map(static function (array $r): int {
            return (int)($r['id'] ?? 0);
        }, $rows);
        $genresMap = BookModel::genresMapByBookIds($bookIds);

        usort($rows, static function (array $a, array $b): int {
            return ((int)($a['id'] ?? 0)) <=> ((int)($b['id'] ?? 0));
        });
        $out = [];
        foreach ($rows as $r) {
            $id = (int)($r['id'] ?? 0);
            $legacyGenre = (string)($r['genre'] ?? '');
            $genres = $genresMap[$id] ?? [];
            if (!$genres && $legacyGenre !== '') {
                $genres = [$legacyGenre];
            }
            $out[] = [
                'id'          => $id,
                'title'       => (string)($r['title'] ?? ''),
                'author'      => (string)($r['author'] ?? ''),
                'publicationYear' => (int)($r['publication_year'] ?? 0),
                'genre'       => $legacyGenre,
                'genres'      => array_values($genres),
                'cover'       => (string)($r['cover'] ?? '📖'),
                'trending'    => !empty($r['trending']),
                'description' => (string)($r['description'] ?? ''),
                'audience'    => (string)($r['audience'] ?? 'All'),
                'coinCost'    => (int)($r['coinCost'] ?? 0),
                'xpReward'    => (int)($r['xpReward'] ?? 0),
                'coinReward'  => (int)($r['coinReward'] ?? 0),
            ];
        }
        $this->json(['success' => true, 'data' => $out]);
    }

    public function create(): void
    {
        if (!isset($_POST['title'])) {
            $this->redirectBack('index.php?view=admin&addbook=error');
        }

        $data = [
            'title' => trim((string)$_POST['title']),
            'author' => trim((string)($_POST['author'] ?? '')),
            'publication_year' => (int)($_POST['publication_year'] ?? 0),
            'genre' => trim((string)($_POST['genre'] ?? '')),
            'cover' => trim((string)($_POST['cover'] ?? '')),
            'coinCost' => (int)($_POST['coinCost'] ?? 0),
            'xpReward' => (int)($_POST['xpReward'] ?? 0),
            'coinReward' => (int)($_POST['coinReward'] ?? 0),
            'audience' => (string)($_POST['audience'] ?? 'All'),
            'trending' => isset($_POST['trending']) ? 1 : 0,
        ];
        if ($data['cover'] === '') $data['cover'] = '📖';

        $ok = BookModel::create($data);
        $this->redirectBack('index.php?view=admin&addbook=' . ($ok ? 'ok' : 'error'));
    }

    public function update(): void
    {
        if (!isset($_POST['idb'])) {
            $this->redirectBack('index.php?view=admin&editbook=error');
        }
        $id = (int)$_POST['idb'];

        $data = [
            'title' => trim((string)$_POST['title']),
            'author' => trim((string)($_POST['author'] ?? '')),
            'publication_year' => (int)($_POST['publication_year'] ?? 0),
            'genre' => trim((string)($_POST['genre'] ?? '')),
            'cover' => trim((string)($_POST['cover'] ?? '')),
            'coinCost' => (int)($_POST['coinCost'] ?? 0),
            'xpReward' => (int)($_POST['xpReward'] ?? 0),
            'coinReward' => (int)($_POST['coinReward'] ?? 0),
            'audience' => (string)($_POST['audience'] ?? 'All'),
            'trending' => isset($_POST['trending']) ? 1 : 0,
        ];
        if ($data['cover'] === '') $data['cover'] = '📖';

        $ok = BookModel::update($id, $data);
        $this->redirectBack('index.php?view=admin&editbook=' . ($ok ? 'ok' : 'error'));
    }

    public function delete(): void
    {
        $id = isset($_GET['idb']) ? (int)$_GET['idb'] : 0;
        $ok = $id > 0 ? BookModel::delete($id) : false;
        $this->redirectBack('index.php?view=admin&deletebook=' . ($ok ? 'ok' : 'error'));
    }
public function search(): void
{
    $query = trim((string)($_GET['q'] ?? ''));

    if ($query === '') {
        $this->json(['success' => false, 'message' => 'Query is required'], 400);
    }

    $books = BookModel::searchByTitle($query);
    $this->json(['success' => true, 'data' => $books, 'count' => count($books)]);
}
}

