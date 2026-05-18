<?php

namespace App\Utils;

class Validator
{
    // =========================
    // 🔐 USERNAME
    // =========================
    public static function validateUsername($username)
    {
        if (empty($username)) {
            return false;
        }

        if (strlen($username) < 3) {
            return false;
        }

        return true;
    }

    // =========================
    // 📧 EMAIL
    // =========================
    public static function validateEmail($email)
    {
        if (empty($email)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    // =========================
    // 🔑 PASSWORD
    // =========================
    public static function validatePassword($password)
    {
        if (empty($password)) {
            return false;
        }

        if (strlen($password) < 4) {
            return false;
        }

        return true;
    }
}