<?php
class HomeController extends Controller
{
    private Book $bookModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->bookModel = new Book($config);
    }

    public function index(): void
    {
        $search = $_GET['q'] ?? '';
        $books = $search ? $this->bookModel->search($search) : $this->bookModel->getAll();
        $trending = $this->bookModel->getTrending();
        $this->render('home', [
            'pageTitle' => 'Lexora - Votre coin lecture',
            'books' => $books,
            'trending' => $trending,
            'search' => $search,
        ]);
    }

    public function auth(): void
    {
        $message = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $profileModel = new Profile($this->config);
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $mode = $_POST['mode'] ?? 'signin';

            if ($mode === 'register') {
                if ($username === '' || $email === '' || $password === '') {
                    $message = 'Tous les champs sont requis.';
                } else {
                    $profile = $profileModel->create($email, $username, $password);
                    if ($profile) {
                        $_SESSION['profile'] = $profile;
                        $this->redirect($this->config['base_url'] . '?page=user&view=profile');
                        return;
                    }
                    $message = 'Impossible de créer le compte. Nom d\'utilisateur déjà utilisé.';
                }
            } else {
                $profile = $profileModel->authenticate($username, $password);
                if ($profile) {
                    $_SESSION['profile'] = $profile;
                    $this->redirect($this->config['base_url'] . '?page=user&view=profile');
                    return;
                }
                $message = 'Identifiants invalides.';
            }
        }

        $this->render('auth', [
            'pageTitle' => 'Connexion Lexora',
            'message' => $message,
        ]);
    }

    public function bookDetail(): void
    {
        $bookId = $_GET['id'] ?? null;
        if (!$bookId) {
            $this->redirect($this->config['base_url']);
            return;
        }

        $book = $this->bookModel->getById($bookId);
        if (!$book) {
            header('HTTP/1.0 404 Not Found');
            echo 'Livre introuvable.';
            return;
        }

        $this->render('book_detail', [
            'pageTitle' => $book['title'] . ' - Lexora',
            'book' => $book,
        ]);
    }

    public function readBook(): void
    {
        $bookId = $_GET['id'] ?? null;
        if (!$bookId) {
            $this->redirect($this->config['base_url']);
            return;
        }

        $book = $this->bookModel->getById($bookId);
        if (!$book) {
            header('HTTP/1.0 404 Not Found');
            echo 'Livre introuvable.';
            return;
        }

        $pages = $this->generateBookPages($bookId);
        $pageIndex = max(0, min(count($pages) - 1, (int)($_GET['page'] ?? 0)));

        $this->render('read_book', [
            'pageTitle' => 'Lecture - ' . $book['title'],
            'book' => $book,
            'pages' => $pages,
            'pageIndex' => $pageIndex,
        ]);
    }

    private function generateBookPages(string $bookId, int $totalPages = 24): array
    {
        $seed = crc32($bookId);
        $paragraphs = [
            'La lumière du matin glissait sur les pages comme une caresse.',
            'Un souffle ancien traversait le vieux manoir, portant des souvenirs oubliés.',
            'Chaque chapitre révélait une nouvelle promesse, plus sombre et plus belle que la précédente.',
            'Le silence de la bibliothèque était brisé par le papier qui tournait lentement.',
            'La brise du soir apportait avec elle des échos de royaumes lointains.',
            'Elle sentit la magie de l histoire se réveiller dans son cœur.',
        ];
        $pages = [];

        for ($i = 0; $i < $totalPages; $i++) {
            $lines = [];
            for ($j = 0; $j < 3; $j++) {
                $index = ($seed + $i * 3 + $j) % count($paragraphs);
                $lines[] = $paragraphs[$index];
            }
            $pages[] = implode('\n\n', $lines);
        }
        return $pages;
    }
}
