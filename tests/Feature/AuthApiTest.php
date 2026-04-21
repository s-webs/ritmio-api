<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_login_and_logout(): void
    {
        $register = $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $register->assertCreated()->assertJsonStructure(['token', 'user']);

        $login = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);
        $login->assertOk()->assertJsonStructure(['token', 'user']);

        $token = $login->json('token');
        $this->withToken($token)->postJson('/api/logout')->assertOk();
    }
}
