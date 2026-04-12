<?php
session_start();
include("../config/database.php");
include("../model/User.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == 'signup') {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $birthdate = isset($_POST['birthdate']) ? trim($_POST['birthdate']) : null;
        
        if (!empty($username) && !empty($email) && !empty($password)) {
            // Check if email already exists
            $stmt = $cnx->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['auth_error'] = "Email already exists.";
                header("Location: ../view/index.php#auth-modal");
                exit;
            }

            $hashed_password = md5($password);
            
            $role = 'user';
            if (!empty($birthdate)) {
                try {
                    $bday = new DateTime($birthdate);
                    $today = new DateTime('today');
                    $age = $bday->diff($today)->y;
                    if ($age < 18) {
                        $role = 'User -18';
                    } else {
                        $role = 'User +18';
                    }
                } catch (Exception $e) {
                    // Ignore date parsing errors
                }
            }
            
            $req = "INSERT INTO users(nom, email, password, role, birthdate) VALUES (?, ?, ?, ?, ?)";
            $stmt = $cnx->prepare($req);
            $res = $stmt->execute([$username, $email, $hashed_password, $role, !empty($birthdate) ? $birthdate : null]);

            if ($res) {
                // Automatically log the user in
                $_SESSION['user_id'] = $cnx->lastInsertId();
                $_SESSION['user_name'] = $username;
                $_SESSION['user_role'] = $role;

                if ($email == 'lexora25@gmail.com' || $role == 'admin') {
                    header("Location: ../view/admin.php");
                } else {
                    header("Location: ../view/user/index.php");
                }
                exit;
            } else {
                $_SESSION['auth_error'] = "Error creating account.";
                header("Location: ../view/index.php#auth-modal");
                exit;
            }
        } else {
            $_SESSION['auth_error'] = "Please fill in all required fields.";
            header("Location: ../view/index.php#auth-modal");
            exit;
        }

    } elseif ($action == 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if (!empty($email) && !empty($password)) {
            $hashed_password = md5($password);
            $stmt = $cnx->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
            $stmt->execute([$email, $hashed_password]);
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['nom'];
                $_SESSION['user_role'] = $user['role'];

                // Temporary logic: if email is admin, go to admin page, else user page
                if ($email == 'lexora25@gmail.com' || $user['role'] == 'admin') {
                    header("Location: ../view/admin.php");
                } else {
                    header("Location: ../view/user/index.php");
                }
                exit;
            } else {
                $_SESSION['auth_error'] = "Invalid email or password.";
                header("Location: ../view/index.php#auth-modal");
                exit;
            }
        } else {
            $_SESSION['auth_error'] = "Please enter email and password.";
            header("Location: ../view/index.php#auth-modal");
            exit;
        }
    }
}
header("Location: ../view/index.php");
exit;
?>
