<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Routing\Middleware\ThrottleRequests;
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
        $this->withoutMiddleware(ThrottleRequests::class);
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $this->token = json_decode($response->baseResponse->getContent())
            ->data
            ->auth_data
            ->token;
    }

    public function test_get_all_with_default_params_succeeds(): void
    {
        $response = $this->get('/api/public_transport', [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                '_meta' => [
                    'total' => 25,
                    'total_pages' => 1,
                    'rows' => 25,
                    'page' => 1,
                    'first' =>
                    "/public_transport?page=1&rows=25&sort_by=id&order=asc",
                    'last' =>
                    "/public_transport?page=1&rows=25&sort_by=id&order=asc"
                ],
                'paginated_data' => [
                    ['id' => 1],
                    ['id' => 2],
                    ['id' => 3],
                    ['id' => 4],
                    ['id' => 5],
                    ['id' => 6],
                    ['id' => 7],
                    ['id' => 8],
                    ['id' => 9],
                    ['id' => 10],
                    ['id' => 11],
                    ['id' => 12],
                    ['id' => 13],
                    ['id' => 14],
                    ['id' => 15],
                    ['id' => 16],
                    ['id' => 17],
                    ['id' => 18],
                    ['id' => 19],
                    ['id' => 20],
                    ['id' => 21],
                    ['id' => 22],
                    ['id' => 23],
                    ['id' => 24],
                    ['id' => 25]
                ]
            ]
        ]);
        $response->assertJsonStructure([
            'errors',
            'statusCode',
            'data' => [
                '_meta' => [
                    'total',
                    'total_pages',
                    'rows',
                    'page',
                    'first',
                    'last'
                ],
                'paginated_data' => [[
                    'id',
                    'type',
                    'route_number',
                    'capacity',
                    'organization_name',
                    'created_at',
                    'updated_at'
                ]]
            ]
        ]);
        $this->assertEquals(25, count($response['data']['paginated_data']));
        foreach ($response['data']['paginated_data'] as $item) {
            $this->assertGreaterThan(0, $item['id']);
            $this->assertTrue(
                in_array(
                    $item['type'],
                    config('constants.public_transport_types')
                )
            );
            $this->assertNotEmpty($item['route_number']);
            $this->assertGreaterThan(0, $item['capacity']);
            $this->assertNotEmpty($item['organization_name']);
            $this->assertNotEmpty($item['created_at']);
            $this->assertNotEmpty($item['updated_at']);
        }
    }

    public function test_get_all_with_custom_params_succeeds(): void
    {
        $response = $this->get(
            '/api/public_transport?page=2&rows=10&sort_by=id&order=desc' .
                '&type[]=route_taxi&organization_name[]=Company%20%231',
            ['Authorization' => "Bearer {$this->token}"]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK
        ]);
        $response->assertJsonStructure([
            'errors',
            'statusCode',
            'data' => [
                '_meta' => [
                    'total',
                    'total_pages',
                    'rows'
                ],
                'paginated_data' => [[
                    'id',
                    'type',
                    'route_number',
                    'capacity',
                    'organization_name',
                    'created_at',
                    'updated_at'
                ]]
            ]
        ]);
        foreach ($response['data']['paginated_data'] as $item) {
            $this->assertGreaterThan(0, $item['id']);
            $this->assertEquals('route_taxi', $item['type']);
            $this->assertNotEmpty($item['route_number']);
            $this->assertGreaterThan(0, $item['capacity']);
            $this->assertEquals('Company #1', $item['organization_name']);
            $this->assertNotEmpty($item['created_at']);
            $this->assertNotEmpty($item['updated_at']);
        }
    }

    public function test_get_all_with_custom_params_fails_because_of_invalid_values(): void
    {
        $response = $this->get(
            '/api/public_transport?page=zxc&rows=qwe&sort_by=nickname' .
                '&order=from_up_to_down&type[]=airplane',
            ['Authorization' => "Bearer {$this->token}"]
        );
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The selected sort by is invalid.',
                'The selected order is invalid.',
                'The page must be an integer.',
                'The page must be greater than or equal 1.',
                'The rows must be an integer.',
                'The rows must be greater than or equal 1.',
                'The rows must be less than or equal 1000.',
                'The selected type.0 is invalid.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
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
        $this->assertGreaterThan(0, $response['data']['id']);
        $this->assertTrue(
            in_array(
                $response['data']['type'],
                config('constants.public_transport_types')
            )
        );
        $this->assertNotEmpty($response['data']['route_number']);
        $this->assertGreaterThan(0, $response['data']['capacity']);
        $this->assertNotEmpty($response['data']['organization_name']);
        $this->assertNotEmpty($response['data']['created_at']);
        $this->assertNotEmpty($response['data']['updated_at']);
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

    public function test_add_succeeds(): void
    {
        $response = $this->post('/api/public_transport', [
            'type' => 'route_taxi',
            'route_number' => 'test_route_number',
            'capacity' => 32767,
            'organization_name' => 'test_organization_name'
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
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
            'statusCode' => Response::HTTP_CREATED,
            'data' => [
                'type' => 'route_taxi',
                'route_number' => 'test_route_number',
                'capacity' => 32767,
                'organization_name' => 'test_organization_name'
            ]
        ]);
        $this->assertGreaterThan(0, $response['data']['id']);
        $this->assertNotEmpty($response['data']['created_at']);
        $this->assertNotEmpty($response['data']['updated_at']);
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

    public function test_add_fails_because_of_invalid_values(): void
    {
        $response = $this->post('/api/public_transport', [
            'type' => 'airplane',
            'route_number' => [
                'route_number_nested_property' => 13
            ],
            'capacity' => 'capacity',
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

    public function test_add_fails_because_of_null_or_empty_values(): void
    {
        $response = $this->post('/api/public_transport', [
            'type' => '',
            'route_number' => null,
            'capacity' => null,
            'organization_name' => ''
        ], [
            'Authorization' => "Bearer {$this->token}"
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The type field is required.',
                'The route number field is required.',
                'The capacity field is required.',
                'The organization name field is required.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
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
        $this->assertGreaterThan(0, $response['data']['id']);
        $this->assertNotEmpty($response['data']['created_at']);
        $this->assertNotEmpty($response['data']['updated_at']);
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
