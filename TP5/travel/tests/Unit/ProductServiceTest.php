<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\ProductService;

class ProductServiceTest extends TestCase
{
    private $config;

    protected function setUp(): void
    {
        $this->config = [
            'use_fake_db' => true
        ];
    }

    public function testGetAllProducts()
    {
        $service = new ProductService($this->config);

        $products = $service->getAllProducts();

        $this->assertNotEmpty($products);
    }

    public function testGetProduct()
    {
        $service = new ProductService($this->config);

        $product = $service->getProduct(1);

        $this->assertEquals(1, $product['id']);
    }

    public function testGetAvailableProducts()
    {
        $service = new ProductService($this->config);

        $products = $service->getAvailableProducts();

        foreach ($products as $p) {
            $this->assertGreaterThan(0, $p['stock']);
        }
    }
}