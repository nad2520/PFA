<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Service\AuthService;

class AuthFlowTest extends TestCase
{
    public function testFullLoginFlow()
    {
        $config = [
            'use_fake_db' => true
        ];

        $service = new AuthService($config);

        $user = $service->login('admin', '1234');

        $this->assertNotFalse($user);
    }
}