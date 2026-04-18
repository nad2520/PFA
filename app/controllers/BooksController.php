<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/BookModel.php';

class BooksController extends Controller
{
    public function create(): void
    {
        if (!isset($_POST['title'])) {
            $this->redirectBack('index.php?view=admin&addbook=error');
        }

        $data = [
            'title' => trim((string)$_POST['title']),
            'author' => trim((string)($_POST['author'] ?? '')),
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

