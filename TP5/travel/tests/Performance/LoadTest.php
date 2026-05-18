<?php

namespace Tests\Performance;

use PHPUnit\Framework\TestCase;
use App\Service\ProductService;

class LoadTest extends TestCase
{
    public function testPerformanceProducts()
    {
        $config = [
            'use_fake_db' => true
        ];

        $service = new ProductService($config);

        $start = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            $service->getAllProducts();
        }

        $end = microtime(true);

        $this->assertLessThan(1, $end - $start);
    }
}