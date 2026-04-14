<?php

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../model/AuthModel.php';
require_once __DIR__ . '/../model/UserModel.php';

class AuthController extends Controller
{
    private AuthModel $authModel;
    private UserModel $userModel;

    public function __construct()
    {
        $this->authModel = new AuthModel();
        $this->userModel = new UserModel();
    }

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleSubmit();
            return;
        }

        $this->render('auth');
    }

    private function handleSubmit(): void
    {
        $mode = trim($_POST['authMode'] ?? 'login');
        $identity = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirm = trim($_POST['confirmPassword'] ?? '');

        if ($mode === 'signup') {
            $this->register($email, $password, $identity, $confirm);
            return;
        }

        $this->login($identity, $password);
    }

    private function login(string $email, string $password): void
    {
        $account = $this->authModel->login($email, $password);
        if (!$account) {
            $_SESSION['auth_error'] = 'Invalid email or password.';
            $this->redirect($_SERVER['REQUEST_URI']);
        }

        $_SESSION['accountId'] = $account['id'];
        $_SESSION['isAuthenticated'] = true;
        $this->redirect('/user_page/index.html');
    }

    private function register(string $email, string $password, string $username, string $confirm): void
    {
        if ($password === '' || $email === '' || $username === '' || $confirm === '') {
            $_SESSION['auth_error'] = 'All registration fields are required.';
            $this->redirect($_SERVER['REQUEST_URI']);
        }

        if ($password !== $confirm) {
            $_SESSION['auth_error'] = 'Passwords do not match.';
            $this->redirect($_SERVER['REQUEST_URI']);
        }

        $result = $this->authModel->register($email, $password, $username);
        if (!$result) {
            $_SESSION['auth_error'] = 'Unable to create account. This email may already be registered.';
            $this->redirect($_SERVER['REQUEST_URI']);
        }

        $_SESSION['accountId'] = $result['accountId'];
        $_SESSION['isAuthenticated'] = true;
        $this->redirect('/user_page/index.html');
    }
}
