<?php

namespace App\Service;

use App\Repository\UserRepository;

class AuthService
{
    private $userRepository;

    public function __construct($config)
    {
        $this->userRepository = new UserRepository($config);
    }

    // =========================
    // 🔐 LOGIN
    // =========================
    public function login($username, $password)
    {
        $user = $this->userRepository->findByUsername($username);

        if (!$user) {
            return false;
        }

        // Vérification password
        if ($user['password'] == $password) {
            return $user;
        }

        return false;
    }

    // =========================
    // 📝 REGISTER
    // =========================
    public function register($data)
    {
        // Vérification simple
        if (empty($data['username']) || empty($data['password'])) {
            return false;
        }

        // Vérifier si user existe
        $existing = $this->userRepository->findByUsername($data['username']);

        if ($existing) {
            return false;
        }

        // Création user
        return $this->userRepository->create($data);
    }
}