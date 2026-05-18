<?php

namespace App\Repository;

use App\Database\Database;
use App\Database\FakeDatabase;
use PDO;

class ProductRepository
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
    // 📦 GET ALL PRODUCTS
    // =========================
    public function findAll()
    {
        if ($this->useFake) {
            return $this->db->getAllProducts();
        }

        $sql = "SELECT * FROM products";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // 🔍 FIND BY ID
    // =========================
    public function findById($id)
    {
        if ($this->useFake) {
            return $this->db->findProductById($id);
        }

        $sql = "SELECT * FROM products WHERE id = $id LIMIT 1";
        $stmt = $this->db->query($sql);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}