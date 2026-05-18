<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\AuthService;

class AuthServiceTest extends TestCase
{
    private $config;

    protected function setUp(): void
    {
        $this->config = [
            'use_fake_db' => true
        ];
    }

    public function testLoginSuccess()
    {
        $service = new AuthService($this->config);

        $user = $service->login('admin', '1234');

        $this->assertNotFalse($user);
    }

    public function testLoginFailWrongPassword()
    {
        $service = new AuthService($this->config);

        $user = $service->login('admin', 'wrong');

        $this->assertFalse($user);
    }

    public function testLoginUserNotFound()
    {
        $service = new AuthService($this->config);

        $user = $service->login('unknown', '1234');

        $this->assertFalse($user);
    }

    public function testRegisterSuccess()
    {
        $service = new AuthService($this->config);

        $user = $service->register([
            'username' => 'newuser',
            'email' => 'test@test.com',
            'password' => '1234'
        ]);

        $this->assertNotFalse($user);
    }
}