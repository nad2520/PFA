<?php

namespace App\Database;

class FakeDatabase
{
    private $users = [
        [
            'id' => 1,
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => '1234',
            'role' => 'ADMIN'
        ],
        [
            'id' => 2,
            'username' => 'user1',
            'email' => 'user1@test.com',
            'password' => '1234',
            'role' => 'USER'
        ]
    ];

    private $products = [
        [
            'id' => 1,
            'name' => 'Laptop',
            'price' => 2500,
            'stock' => 10
        ],
        [
            'id' => 2,
            'name' => 'Phone',
            'price' => 1200,
            'stock' => 0
        ]
    ];

    // =========================
    // 🔐 USERS
    // =========================

    public function findUserByUsername($username)
    {
        foreach ($this->users as $user) {
            if ($user['username'] === $username) {
                return $user;
            }
        }

        return null;
    }

    public function createUser($data)
    {
        $data['id'] = count($this->users) + 1;
        $this->users[] = $data;

        return $data;
    }

    // =========================
    // 📦 PRODUCTS
    // =========================

    public function getAllProducts()
    {
        return $this->products;
    }

    public function findProductById($id)
    {
        foreach ($this->products as $product) {
            if ($product['id'] == $id) {
                return $product;
            }
        }

        return null;
    }
}