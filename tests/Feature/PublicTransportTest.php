<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;

class PublicTransportTest extends TestCase
{
    use RefreshDatabase;

    private $token;
    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
        $signInResponse = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $this->token = json_decode($signInResponse->baseResponse->getContent())
            ->data
            ->auth_data
            ->token;
    }

    public function test_get_by_id_succeeds(): void
    {
        $response = $this->get('/api/public_transport/1', [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => ['id' => 1]
        ]);
        $response->assertJsonStructure([
            'errors',
            'statusCode',
            'data' => [
                'id',
                'type',
                'route_number',
                'capacity',
                'organization_name',
                'created_at',
                'updated_at'
            ]
        ]);
        $this->assertGreaterThan(0, strlen($response['data']['type']));
        $this->assertTrue(in_array($response['data']['type'], config('constants.public_transport_types')));
        $this->assertGreaterThan(0, strlen($response['data']['route_number']));
        $this->assertGreaterThan(0, $response['data']['capacity']);
        $this->assertGreaterThan(0, strlen($response['data']['organization_name']));
        $this->assertGreaterThan(0, strlen($response['data']['created_at']));
        $this->assertGreaterThan(0, strlen($response['data']['updated_at']));
    }

    public function test_get_by_id_fails_because_of_non_existent_id(): void
    {
        $response = $this->get('/api/public_transport/65536', [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson([
            'errors' => ["Requested resource is not found"],
            'statusCode' => Response::HTTP_NOT_FOUND,
            'data' => null
        ]);
    }

    public function test_update_by_id_succeeds(): void
    {
        $response = $this->put('/api/public_transport/1', [
            'type' => 'route_taxi',
            'route_number' => 'test_route_number',
            'capacity' => 32767,
            'organization_name' => 'test_organization_name'
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'errors',
            'statusCode',
            'data' => [
                'id',
                'type',
                'route_number',
                'capacity',
                'organization_name',
                'created_at',
                'updated_at'
            ]
        ]);
        $response->assertJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                'type' => 'route_taxi',
                'route_number' => 'test_route_number',
                'capacity' => 32767,
                'organization_name' => 'test_organization_name'
            ]
        ]);
        $this->assertDatabaseHas('public_transport', [
            'id' => $response['data']['id'],
            'type' => 'route_taxi',
            'route_number' => 'test_route_number',
            'capacity' => 32767,
            'organization_name' => 'test_organization_name',
            'created_at' => $response['data']['created_at'],
            'updated_at' => $response['data']['updated_at']
        ]);
    }

    public function test_update_by_id_fails_because_of_non_existent_id(): void
    {
        $response = $this->put('/api/public_transport/65536', [
            'type' => 'route_taxi',
            'route_number' => '5',
            'capacity' => 25,
            'organization_name' => 'StrongestOrganizationEver'
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson([
            'errors' => ["Requested resource is not found"],
            'statusCode' => Response::HTTP_NOT_FOUND,
            'data' => null
        ]);
    }

    public function test_update_by_id_fails_because_of_invalid_values(): void
    {
        $response = $this->put('/api/public_transport/1', [
            'type' => 'airplane',
            'route_number' => [
                'route_number_nested_property' => 13
            ],
            'capacity' => '',
            'organization_name' => 3.14
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The selected type is invalid.',
                'The route number must be a string.',
                'The capacity must be an integer.',
                'The capacity must be greater than or equal 1.',
                'The capacity must be less than or equal 32767.',
                'The organization name must be a string.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_delete_by_id_succeeds(): void
    {
        $this->assertDatabaseHas('public_transport', ['id' => 1]);
        $response = $this->delete('/api/public_transport/1', [], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('public_transport', ['id' => 1]);
    }

    public function test_delete_by_id_fails_because_of_non_existent_id(): void
    {
        $response = $this->delete('/api/public_transport/65536', [], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson([
            'errors' => ['Requested resource is not found'],
            'statusCode' => Response::HTTP_NOT_FOUND,
            'data' => null
        ]);
    }
}
