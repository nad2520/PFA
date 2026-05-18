<?php

namespace App\Controller;

use App\Service\AuthService;

class AuthController
{
    private $authService;

    public function __construct($config)
    {
        $this->authService = new AuthService($config);
    }

    // =========================
    // 🔐 LOGIN
    // =========================
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->authService->login($username, $password);

            if ($user) {
                $_SESSION['user'] = $user;

                header("Location: /dashboard.php");
                exit;
            }

            $error = "Invalid credentials";
        }

        require __DIR__ . '/../../views/login.view.php';
    }

    // =========================
    // 📝 REGISTER
    // =========================
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'username' => $_POST['username'] ?? '',
                'email' => $_POST['email'] ?? '',
                'password' => $_POST['password'] ?? ''
            ];

            $user = $this->authService->register($data);

            if ($user) {
                header("Location: /login.php");
                exit;
            }

            $error = "Registration failed";
        }

        require __DIR__ . '/../../views/register.view.php';
    }

    // =========================
    // 🚪 LOGOUT
    // =========================
    public function logout()
    {
        session_destroy();

        header("Location: /login.php");
        exit;
    }
}