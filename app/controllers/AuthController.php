<?php
include_once __DIR__ . '/../models/UserModel.php';
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../models/User.php';

function handleAuth(): void
{
    session_start();

    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        if ($action == 'signup') {
            signup();
            return;
        }
        if ($action == 'login') {
            login();
            return;
        }
    }

    if (isset($_GET['action']) && $_GET['action'] == 'logout') {
        logout();
        return;
    }

    header("Location: index.php");
}

function signup(): void
{
    require __DIR__ . '/../../config/database.php';
    $username = trim((string)($_POST['username'] ?? ""));
    $email = trim((string)($_POST['email'] ?? ""));
    $password = trim((string)($_POST['password'] ?? ""));
    $birthdate = isset($_POST['birthdate']) ? trim((string)$_POST['birthdate']) : null;

    if ($username == '' || $email == '' || $password == '') {
        $_SESSION['auth_error'] = "Please fill in all required fields.";
        header("Location: index.php#auth-modal");
        return;
    }

    if (UserModel::emailExists($cnx, $email)) {
        $_SESSION['auth_error'] = "Email already exists.";
        header("Location: index.php#auth-modal");
        return;
    }

    $role = 'user';
    if (!empty($birthdate)) {
        try {
            $bday = new DateTime($birthdate);
            $today = new DateTime('today');
            $age = $bday->diff($today)->y;
            $role = ($age < 18) ? 'User -18' : 'User +18';
        } catch (Exception $e) {
        }
    }

    $hashed = md5($password);
    $ok = UserModel::createUser($cnx, $username, $email, $hashed, $role, $birthdate ?: null);

    if (!$ok) {
        $_SESSION['auth_error'] = "Error creating account.";
        header("Location: index.php#auth-modal");
        return;
    }

    $_SESSION['user_id'] = (int)$cnx->lastInsertId();
    $_SESSION['user_name'] = $username;
    $_SESSION['user_role'] = $role;

    if ($email === 'lexora25@gmail.com' || $role === 'admin') {
        header("Location: index.php?view=admin");
        return;
    }
    header("Location: index.php?view=user");
}

function login(): void
{
    require __DIR__ . '/../../config/database.php';
    $email = trim((string)($_POST['email'] ?? ""));
    $password = trim((string)($_POST['password'] ?? ""));

    if ($email == '' || $password == '') {
        $_SESSION['auth_error'] = "Please enter email and password.";
        header("Location: index.php#auth-modal");
        return;
    }

    $hashed = md5($password);
    $user = UserModel::findByEmailAndPassword($cnx, $email, $hashed);

    if (!$user) {
        $_SESSION['auth_error'] = "Invalid email or password.";
        header("Location: index.php#auth-modal");
        return;
    }

    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = (string)$user['nom'];
    $_SESSION['user_role'] = (string)$user['role'];

    if ($email === 'lexora25@gmail.com' || $user['role'] === 'admin') {
        header("Location: index.php?view=admin");
        return;
    }
    header("Location: index.php?view=user");
}

function logout(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    session_destroy();
    header("Location: index.php");
}

