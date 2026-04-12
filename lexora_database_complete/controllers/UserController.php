<?php
class UserController extends Controller
{
    private Profile $profileModel;
    private UserBook $userBookModel;
    private Book $bookModel;

    public function __construct(array $config)
    {
        parent::__construct($config);
        $this->profileModel = new Profile($config);
        $this->userBookModel = new UserBook($config);
        $this->bookModel = new Book($config);
    }

    public function index(): void
    {
        $view = $_GET['view'] ?? 'profile';
        switch ($view) {
            case 'store':
                $this->store();
                break;
            case 'book-detail':
                $this->bookDetail();
                break;
            case 'read-book':
                $this->readBook();
                break;
            case 'profile':
            default:
                $this->profile();
                break;
        }
    }

    public function profile(): void
    {
        $profile = $this->getSessionProfile();
        if (!$profile) {
            $this->redirect($this->config['base_url'] . '?page=home&action=auth');
            return;
        }

        $library = $this->userBookModel->getByProfile($profile['id']);
        $wishlist = $this->userBookModel->getWishlist($profile['id']);

        $this->render('profile', [
            'pageTitle' => 'Mon profil - Lexora',
            'profile' => $profile,
            'library' => $library,
            'wishlist' => $wishlist,
        ]);
    }

    public function store(): void
    {
        $profile = $this->getSessionProfile();
        if (!$profile) {
            $this->redirect($this->config['base_url'] . '?page=home&action=auth');
            return;
        }

        $books = $this->bookModel->getAll();
        $this->render('store', [
            'pageTitle' => 'Boutique - Lexora',
            'profile' => $profile,
            'books' => $books,
        ]);
    }

    public function bookDetail(): void
    {
        $bookId = $_GET['id'] ?? null;
        if (!$bookId) {
            $this->redirect($this->config['base_url'] . '?page=user&view=profile');
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
            'profile' => $this->getSessionProfile(),
        ]);
    }

    public function readBook(): void
    {
        $bookId = $_GET['id'] ?? null;
        if (!$bookId) {
            $this->redirect($this->config['base_url'] . '?page=user&view=profile');
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
            'profile' => $this->getSessionProfile(),
        ]);
    }

    private function getSessionProfile(): ?array
    {
        return $_SESSION['profile'] ?? null;
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
            for ($j = 0; $j < 4; $j++) {
                $index = ($seed + $i * 4 + $j) % count($paragraphs);
                $lines[] = $paragraphs[$index];
            }
            $pages[] = implode('\n\n', $lines);
        }
        return $pages;
    }

    public function buy(): void
    {
        $profile = $this->getSessionProfile();
        $bookId = $_GET['id'] ?? null;
        if (!$profile || !$bookId) {
            $this->redirect($this->config['base_url'] . '?page=user&view=store');
            return;
        }

        $this->userBookModel->addToLibrary($profile['id'], $bookId, 'READING');
        $this->redirect($this->config['base_url'] . '?page=user&view=store');
    }

    public function wishlist(): void
    {
        $profile = $this->getSessionProfile();
        $bookId = $_GET['id'] ?? null;
        if (!$profile || !$bookId) {
            $this->redirect($this->config['base_url'] . '?page=user&view=store');
            return;
        }

        $this->userBookModel->addToWishlist($profile['id'], $bookId);
        $this->redirect($this->config['base_url'] . '?page=user&view=store');
    }

    public function logout(): void
    {
        unset($_SESSION['profile']);
        $this->redirect($this->config['base_url'] . '?page=home');
    }
}
