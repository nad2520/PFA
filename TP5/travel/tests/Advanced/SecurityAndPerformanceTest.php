<?php

namespace Tests\Advanced;

use PHPUnit\Framework\TestCase;
use App\Service\AuthService;
use App\Service\ProductService;

class SecurityAndPerformanceTest extends TestCase
{
    private $config;

    protected function setUp(): void
    {
        $this->config = [
            'use_fake_db' => true
        ];
    }

    // =========================
    // 🔐 SQL INJECTION TEST
    // =========================
    public function testSqlInjectionLogin()
    {
        $service = new AuthService($this->config);

        $user = $service->login("' OR 1=1 --", "test");

        $this->assertFalse($user, "SQL Injection possible!");
    }

    // =========================
    // 🔐 EMPTY INPUT TEST
    // =========================
    public function testEmptyLogin()
    {
        $service = new AuthService($this->config);

        $user = $service->login("", "");

        $this->assertFalse($user);
    }

    // =========================
    // 🔐 LONG INPUT TEST (FUZZ)
    // =========================
    public function testVeryLongUsername()
    {
        $service = new AuthService($this->config);

        $long = str_repeat("A", 1000);

        $user = $service->login($long, "1234");

        $this->assertFalse($user);
    }

    // =========================
    // 🔐 SPECIAL CHARACTERS TEST
    // =========================
    public function testSpecialCharacters()
    {
        $service = new AuthService($this->config);

        $user = $service->login("%%%$$$", "1234");

        $this->assertFalse($user);
    }

    // =========================
    // 🔐 REGISTER DUPLICATE USER
    // =========================
    public function testRegisterDuplicateUser()
    {
        $service = new AuthService($this->config);

        $service->register([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => '1234'
        ]);

        $user = $service->register([
            'username' => 'admin',
            'email' => 'admin@test.com',
            'password' => '1234'
        ]);

        $this->assertFalse($user, "Duplicate user allowed!");
    }

    // =========================
    // 📦 PRODUCT NOT FOUND
    // =========================
    public function testProductNotFound()
    {
        $service = new ProductService($this->config);

        $product = $service->getProduct(999);

        $this->assertNull($product);
    }

    // =========================
    // 📦 AVAILABLE PRODUCTS ONLY
    // =========================
    public function testAvailableProductsOnly()
    {
        $service = new ProductService($this->config);

        $products = $service->getAvailableProducts();

        foreach ($products as $product) {
            $this->assertGreaterThan(0, $product['stock']);
        }
    }

    // =========================
    // ⚡ PERFORMANCE TEST
    // =========================
    public function testPerformance()
    {
        $service = new ProductService($this->config);

        $start = microtime(true);

        for ($i = 0; $i < 500; $i++) {
            $service->getAllProducts();
        }

        $end = microtime(true);

        $this->assertLessThan(1, $end - $start, "Performance issue detected!");
    }

    // =========================
    // 💣 STRESS TEST (SIMULATED)
    // =========================
    public function testStressSimulation()
    {
        $service = new ProductService($this->config);

        for ($i = 0; $i < 1000; $i++) {
            $products = $service->getAllProducts();

            $this->assertNotEmpty($products);
        }
    }

    // =========================
    // 🔐 PASSWORD SECURITY TEST
    // =========================
    public function testPasswordNotHashed()
    {
        $service = new AuthService($this->config);

        $user = $service->login('admin', '1234');

        // Ce test doit FAIL dans ton TP (exprès 😈)
        $this->assertNotEquals('1234', $user['password'], "Password is not hashed!");
    }
}