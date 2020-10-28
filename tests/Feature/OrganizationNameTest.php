<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\TestCase;
use Illuminate\Http\Response;

class OrganizationNameTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_get_all_succeeds(): void
    {
        $response = $this->withoutMiddleware()->get('/api/organization_name');
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
    }
}
