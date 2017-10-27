<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LoginTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginFailure()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(422)
            ->assertJson([
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.']
            ]);
    }

    public function testLoginSuccess() {
        $payload = [
            'email' => 'email2@example.com',
            'password' => 'password'
        ];

        $this->json('post', '/api/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'authenticated',
                'email',
                'api_token',
                'admin'
            ]);
    }

    public function testLogout() {
        $payload = [
            'api_token' => 'NGx4NllabUFnRUpxYWxISzNyaDNDMXdoRWw5Qzd1RlVxVTZ5eUpGcA=='
        ];
        $this->json('post', '/api/logout', $payload)
            ->assertStatus(200)
            ->assertJson([
                'logged_out' => 'true'
            ]);
    }
}
