<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AssessmentTest extends TestCase
{
    public function testUserTestRetrievalForNomineeFailNoTokenNoEmail() {
        $this->json('POST', 'api/assessments')
            ->assertStatus(401)
            ->assertJson([
                "error" => "Unauthenticated."
            ]);
    }

    public function testUserTestRetrievalForNomineeFail() {
        $this->json('POST', 'http://mipad.my-gpi.org/api/assessments')
            ->assertStatus(401)
            ->assertJson([
                "error" => "Unauthenticated."
            ]);
    }

    public function testUserTestRetrievalSuccess() {
        $payload = [
            'email' => 'emmanuel.c.benson@gmail.com',
            'api_token' => 'RUZDTHlVcDdqZnQwckdoc1NHSVg2WG04cnFCcWtLS2szRWhTODFadw=='
        ];

        $this->json('POST', 'http://api.gpi.dev/api/assessments', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'found',
                'assessments'
            ]);
    }

    public function testRetrieveUserAssessmentsForAdminFail() {

        $payload = [
            'email' => 'emmanuel.c.benson@gmail.com',
            'api_token' => 'RUZDTHlVcDdqZnQwckdoc1NHSVg2WG04cnFCcWtLS2szRWhTODFadw=='
        ];

        $this->json('POST', 'http://api.gpi.dev/api/nominee/assessments', $payload)
            ->assertStatus(200)
            ->assertJson([
                'found' => false,
                'message' => 'Unauthorized.'
            ]);
    }

    public function testRetrieveUserAssessmentsForAdminSuccess() {
        $payload = [
            'email' => 'emmanuel.c.benson@gmail.com',
            'api_token' => 'OHVSMW51Y21JZ3Y0SDNvRjF2aGp4dEgxczYzVEdtMm1ucWpzOVNFRA=='
        ];

        $this->json('POST', 'http://api.gpi.dev/api/nominee/assessments', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'found',
                'tests'
            ]);
    }
}
