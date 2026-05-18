<?php

namespace App\Repository;

use App\Database\Database;
use App\Database\FakeDatabase;
use PDO;

class UserRepository
{
    private $db;
    private $useFake;

    public function __construct($config)
    {
        $this->useFake = $config['use_fake_db'];

        if ($this->useFake) {
            $this->db = new FakeDatabase();
        } else {
            $this->db = Database::getInstance($config['db'])->getConnection();
        }
    }

    // =========================
    // 🔐 FIND USER
    // =========================
    public function findByUsername($username)
    {
        if ($this->useFake) {
            return $this->db->findUserByUsername($username);
        }

        $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
        $stmt = $this->db->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // 📝 CREATE USER
    // =========================
    public function create($data)
    {
        if ($this->useFake) {
            return $this->db->createUser($data);
        }

        $sql = "INSERT INTO users (username, email, password)
                VALUES ('{$data['username']}', '{$data['email']}', '{$data['password']}')";

        $this->db->exec($sql);

        return $data;
    }
}