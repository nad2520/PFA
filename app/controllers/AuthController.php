<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Database.php';

class AuthController extends Controller
{
    private function hashPassword(string $plain): string
    {
        // Backward-compatible with current DB contents.
        return md5($plain);
    }

    public function handle(): void
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            if ($action === 'signup') {
                $this->signup();
                return;
            }
            if ($action === 'login') {
                $this->login();
                return;
            }
        }

        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            $this->logout();
            return;
        }

        $this->redirect('index.php');
    }

    private function signup(): void
    {
        $pdo = Database::pdo();

        $username = trim((string)($_POST['username'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));
        $birthdate = isset($_POST['birthdate']) ? trim((string)$_POST['birthdate']) : null;

        if ($username === '' || $email === '' || $password === '') {
            $_SESSION['auth_error'] = "Please fill in all required fields.";
            $this->redirect('index.php#auth-modal');
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['auth_error'] = "Email already exists.";
            $this->redirect('index.php#auth-modal');
        }

        $role = 'user';
        if (!empty($birthdate)) {
            try {
                $bday = new DateTime($birthdate);
                $today = new DateTime('today');
                $age = $bday->diff($today)->y;
                $role = ($age < 18) ? 'User -18' : 'User +18';
            } catch (Exception $e) {
                // Ignore parsing errors; keep default role.
            }
        }

        $hashed = $this->hashPassword($password);
        $req = "INSERT INTO users(nom, email, password, role, birthdate) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($req);
        $ok = $stmt->execute([$username, $email, $hashed, $role, $birthdate ?: null]);

        if (!$ok) {
            $_SESSION['auth_error'] = "Error creating account.";
            $this->redirect('index.php#auth-modal');
        }

        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        $_SESSION['user_name'] = $username;
        $_SESSION['user_role'] = $role;

        if ($email === 'lexora25@gmail.com' || $role === 'admin') {
            $this->redirect('index.php?view=admin');
        }
        $this->redirect('index.php?view=user');
    }

    private function login(): void
    {
        $pdo = Database::pdo();

        $email = trim((string)($_POST['email'] ?? ''));
        $password = trim((string)($_POST['password'] ?? ''));

        if ($email === '' || $password === '') {
            $_SESSION['auth_error'] = "Please enter email and password.";
            $this->redirect('index.php#auth-modal');
        }

        $hashed = $this->hashPassword($password);
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->execute([$email, $hashed]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['auth_error'] = "Invalid email or password.";
            $this->redirect('index.php#auth-modal');
        }

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['user_name'] = (string)$user['nom'];
        $_SESSION['user_role'] = (string)$user['role'];

        if ($email === 'lexora25@gmail.com' || $user['role'] === 'admin') {
            $this->redirect('index.php?view=admin');
        }
        $this->redirect('index.php?view=user');
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        $this->redirect('index.php');
    }
}

