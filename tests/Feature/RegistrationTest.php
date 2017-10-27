<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;

class RegistrationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRequiresEmailFullnameMobilePassword() {
        $this->json('POST', 'api/register')
            ->assertStatus(422)
            ->assertJson([
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
                'mobile' => ['The mobile field is required.']
            ]);
    }

    public function testRegisteredSuccessful() {
        $payload = [
            'email' => 'email1@example.com',
            'password' => 'secretepassword',
            'password_confirmation' => 'secretepassword',
            'mobile' => '0979866969869',
            'fullname' => 'Emmanuel Benson',
            'api_token' => 'OHVSMW51Y21JZ3Y0SDNvRjF2aGp4dEgxczYzVEdtMm1ucWpzOVNFRA=='
        ];

        $this->json('post', '/api/register', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'registered',
                'email',
                'activation'
            ]);
    }

    public function testEmailNotValid() {
        $payload = [
            'email' => 'email1@example',
            'password' => 'secretepassword',
            'password_confirmation' => 'secretepassword',
            'mobile' => '0979866969869',
            'fullname' => 'Emmanuel Benson',
            'api_token' => 'OHVSMW51Y21JZ3Y0SDNvRjF2aGp4dEgxczYzVEdtMm1ucWpzOVNFRA=='
        ];

        $this->json('post', '/api/register', $payload)
            ->assertJson([
                "email" => ["The email must be a valid email address."]
            ]);
    }

}
