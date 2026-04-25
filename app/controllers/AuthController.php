<?php
require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../core/Database.php';

class AuthController extends Controller
{
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
        try {
            $pdo = Database::pdo();
        } catch (Throwable $e) {
            $_SESSION['auth_error'] = "Database connection error.";
            $this->redirect('index.php#auth-modal');
        }

        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $birthdate = trim($_POST['birthdate'] ?? '');

        if ($username === '' || $email === '' || $password === '') {
            $_SESSION['auth_error'] = "Please fill all fields.";
            $this->redirect('index.php#auth-modal');
        }

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $_SESSION['auth_error'] = "Email already exists.";
            $this->redirect('index.php#auth-modal');
        }

        // Role calculation
        $role = 'user';
        if (!empty($birthdate)) {
            try {
                $bday = new DateTime($birthdate);
                $today = new DateTime();
                $age = $bday->diff($today)->y;
                $role = ($age < 18) ? 'User -18' : 'User +18';
            } catch (Exception $e) {}
        }

        // ✅ SECURE HASH
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO users(nom, email, password, role, birthdate, coins)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $ok = $stmt->execute([
            $username,
            $email,
            $hashedPassword,
            $role,
            $birthdate ?: null,
            1000
        ]);

        if (!$ok) {
            $_SESSION['auth_error'] = "Signup failed.";
            $this->redirect('index.php#auth-modal');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_name'] = $username;
        $_SESSION['user_role'] = $role;

        $this->redirect('index.php?view=user');
    }

    private function login(): void
    {
        try {
            $pdo = Database::pdo();
        } catch (Throwable $e) {
            $_SESSION['auth_error'] = "Database connection error.";
            $this->redirect('index.php#auth-modal');
        }

        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $_SESSION['auth_error'] = "Enter email and password.";
            $this->redirect('index.php#auth-modal');
        }

        // Get user by email ONLY
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $_SESSION['auth_error'] = "Invalid credentials.";
            $this->redirect('index.php#auth-modal');
        }

        // ✅ VERIFY PASSWORD
        $isValid = password_verify($password, $user['password']);

        // 🔥 OPTIONAL: support old md5 passwords (remove later)
        if (!$isValid && md5($password) === $user['password']) {
            // Upgrade old password to new hash
            $newHash = password_hash($password, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update->execute([$newHash, $user['id']]);

            $isValid = true;
        }

        if (!$isValid) {
            $_SESSION['auth_error'] = "Invalid credentials.";
            $this->redirect('index.php#auth-modal');
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['nom'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            $this->redirect('index.php?view=admin');
        }

        $this->redirect('index.php?view=user');
    }

    public function logout(): void
    {
        session_start();

        $_SESSION = [];
        session_destroy();

        // ✅ Prevent back button
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Location: /PFA"); // not index.php?action=logout
        exit();
    }
}