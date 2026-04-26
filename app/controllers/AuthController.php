<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller
{
    private const AUTH_MODE_LOGIN = 'login';
    private const AUTH_MODE_SIGNUP = 'signup';

    public function handle(): void
    {
        $this->ensureSession();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = (string)($_POST['action'] ?? '');

            if ($action === self::AUTH_MODE_SIGNUP) {
                $this->signup();
                return;
            }

            if ($action === self::AUTH_MODE_LOGIN) {
                $this->login();
                return;
            }
        }

        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            $this->logout();
            return;
        }

        $this->redirect('');
    }

    private function signup(): void
    {
        $username = trim((string)($_POST['username'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');
        $birthdate = trim((string)($_POST['birthdate'] ?? ''));

        if ($username === '' || $email === '' || $password === '') {
            $_SESSION['auth_error'] = 'Please fill all fields.';
            $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Please enter a valid email address.';
            $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
        }

        if (mb_strlen($username) < 2 || mb_strlen($username) > 100) {
            $_SESSION['auth_error'] = 'Username must be between 2 and 100 characters.';
            $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
        }

        if (mb_strlen($password) < UserModel::MIN_PASSWORD_LENGTH) {
            $_SESSION['auth_error'] = 'Password must be at least ' . UserModel::MIN_PASSWORD_LENGTH . ' characters.';
            $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
        }

        if (UserModel::emailExists($email)) {
            $_SESSION['auth_error'] = 'Email already exists.';
            $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
        }

        $role = 'user';
        $normalizedBirthdate = null;
        if ($birthdate !== '') {
            try {
                $bday = new DateTimeImmutable($birthdate);
                $today = new DateTimeImmutable('today');
                $age = $bday->diff($today)->y;
                $role = ($age < 18) ? 'User -18' : 'User +18';
                $normalizedBirthdate = $bday->format('Y-m-d');
            } catch (Exception) {
                $_SESSION['auth_error'] = 'Birthdate is invalid.';
                $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
            }
        }

        $userId = UserModel::create(
            $username,
            $email,
            UserModel::hashPassword($password),
            $role,
            $normalizedBirthdate,
            UserModel::STARTING_COINS
        );

        if ($userId === null) {
            $_SESSION['auth_error'] = 'Signup failed.';
            $this->redirectToAuthHome(self::AUTH_MODE_SIGNUP);
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user_name'] = $username;
        $_SESSION['user_role'] = $role;

        $this->redirect('user');
    }

    private function login(): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $_SESSION['auth_error'] = 'Enter email and password.';
            $this->redirectToAuthHome(self::AUTH_MODE_LOGIN);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = 'Please enter a valid email address.';
            $this->redirectToAuthHome(self::AUTH_MODE_LOGIN);
        }

        $user = UserModel::findByEmail($email);
        if (!$user || !UserModel::verifyPassword($password, (string)($user['password'] ?? ''))) {
            $_SESSION['auth_error'] = 'Invalid credentials.';
            $this->redirectToAuthHome(self::AUTH_MODE_LOGIN);
        }

        if (UserModel::passwordNeedsRehash((string)$user['password'])) {
            $newHash = UserModel::hashPassword($password);
            UserModel::updatePasswordHash((int)$user['id'], $newHash);
            $user['password'] = $newHash;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = (string)$user['nom'];
        $_SESSION['user_role'] = (string)$user['role'];

        if (($user['role'] ?? '') === 'admin') {
            $this->redirect('admin');
        }

        $this->redirect('user');
    }

    public function logout(): void
    {
        $this->ensureSession();

        $_SESSION = [];
        session_destroy();

        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header('Location: ' . $this->baseUrl() . '/');
        exit();
    }

    private function redirectToAuthHome(string $mode): void
    {
        $_SESSION['auth_mode'] = $mode;
        $this->redirect('');
    }
}
