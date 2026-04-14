<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../model/BookModel.php';

class AdminController extends Controller
{
    private UserModel $userModel;
    private BookModel $bookModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->bookModel = new BookModel();
    }

    public function index(): void
    {
        $this->render('admin', [
            'usersCount' => $this->userModel->countUsers(),
            'booksCount' => $this->bookModel->countBooks(),
            'message' => $this->getFlash('admin_message'),
            'error' => $this->getFlash('admin_error'),
        ]);
    }

    public function users(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'add') {
                $this->handleUserAdd();
                return;
            }
            if ($action === 'delete') {
                $this->handleUserDelete();
                return;
            }
        }

        $search = trim($_GET['search'] ?? '');
        $role = trim($_GET['role'] ?? 'All');
        $users = $this->userModel->getUsers($search, $role);
        $this->render('admin_users', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'message' => $this->getFlash('admin_message'),
            'error' => $this->getFlash('admin_error'),
        ]);
    }

    public function books(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'add') {
                $this->handleBookAdd();
                return;
            }
            if ($action === 'delete') {
                $this->handleBookDelete();
                return;
            }
        }

        $search = trim($_GET['search'] ?? '');
        $genre = trim($_GET['genre'] ?? 'All');
        $audience = trim($_GET['audience'] ?? 'All');
        $books = $this->bookModel->getBooks($search, $genre, $audience);
        $this->render('admin_books', [
            'books' => $books,
            'search' => $search,
            'genre' => $genre,
            'audience' => $audience,
            'message' => $this->getFlash('admin_message'),
            'error' => $this->getFlash('admin_error'),
        ]);
    }

    private function handleUserAdd(): void
    {
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? 'user');
        $coins = (int)($_POST['coins'] ?? 0);
        $level = (int)($_POST['level'] ?? 1);
        $birthdate = trim($_POST['birthdate'] ?? '') ?: null;

        if ($nom === '' || $email === '' || $password === '') {
            $this->setFlash('admin_error', 'Name, email, and password are required to add a user.');
            $this->redirect('/admin/users');
        }

        $addedId = $this->userModel->addUser($nom, $email, $password, $role, $coins, $level, $birthdate);
        if ($addedId === null) {
            $this->setFlash('admin_error', 'Unable to add user. Email may already exist.');
        } else {
            $this->setFlash('admin_message', 'User added successfully.');
        }

        $this->redirect('/admin/users');
    }

    private function handleUserDelete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0 || !$this->userModel->deleteUser($id)) {
            $this->setFlash('admin_error', 'User could not be deleted.');
        } else {
            $this->setFlash('admin_message', 'User deleted successfully.');
        }

        $this->redirect('/admin/users');
    }

    private function handleBookAdd(): void
    {
        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $genre = trim($_POST['genre'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $audience = trim($_POST['audience'] ?? 'All');
        $coinCost = max(0, (int)($_POST['coinCost'] ?? 100));
        $xpReward = max(0, (int)($_POST['xpReward'] ?? 150));
        $coinReward = max(0, (int)($_POST['coinReward'] ?? 40));
        $trending = isset($_POST['trending']) && $_POST['trending'] === '1';
        $coverEmoji = trim($_POST['coverEmoji'] ?? '?') ?: '?';

        if ($title === '' || $author === '' || $genre === '') {
            $this->setFlash('admin_error', 'Title, author, and genre are required to add a book.');
            $this->redirect('/admin/books');
        }

        $addedId = $this->bookModel->addBook($title, $author, $genre, $description, $audience, $coinCost, $xpReward, $coinReward, $trending, $coverEmoji);
        if ($addedId === null) {
            $this->setFlash('admin_error', 'Unable to add book.');
        } else {
            $this->setFlash('admin_message', 'Book added successfully.');
        }

        $this->redirect('/admin/books');
    }

    private function handleBookDelete(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0 || !$this->bookModel->deleteBook($id)) {
            $this->setFlash('admin_error', 'Book could not be deleted.');
        } else {
            $this->setFlash('admin_message', 'Book deleted successfully.');
        }

        $this->redirect('/admin/books');
    }

    private function setFlash(string $key, string $value): void
    {
        $_SESSION[$key] = $value;
    }

    private function getFlash(string $key): ?string
    {
        if (!isset($_SESSION[$key])) {
            return null;
        }

        $value = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $value;
    }
}
