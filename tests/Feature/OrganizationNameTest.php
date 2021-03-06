<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;
use Illuminate\Http\Response;

class OrganizationNameTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $this->token = $response['data']['auth_data']['token'];
    }

    public function test_get_all_succeeds(): void
    {
        $response = $this->get(
            '/api/organization_name',
            ['Authorization' => "Bearer {$this->token}"]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                'Company #1',
                'Company #2',
                'Company #3'
            ]
        ]);
        foreach ($response['data'] as $item) {
            $this->assertDatabaseHas(
                'public_transport',
                ['organization_name' => $item]
            );
        }
    }
}
