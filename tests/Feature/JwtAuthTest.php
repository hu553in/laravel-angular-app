<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Routing\Middleware\ThrottleRequests;

class JwtAuthTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(ThrottleRequests::class);
    }

    public function test_jwt_auth_succeeds()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $token = $response['data']['auth_data']['token'];
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                'name' => 'admin',
                'email' => 'admin@gmail.com'
            ]
        ]);
    }

    public function test_jwt_auth_fails_because_of_invalid_token()
    {
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => 'Bearer 123']
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ['Token is invalid'],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }

    public function test_jwt_auth_fails_because_of_missing_request_header()
    {
        $response = $this->get('/api/whoami');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ['Unable to authenticate user by token'],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }

    public function test_jwt_auth_fails_because_of_wrong_token_type()
    {
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => 'SuperToken 123']
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ['Unable to authenticate user by token'],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }

    public function test_jwt_auth_fails_because_of_missing_token_type()
    {
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => '123']
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ['Unable to authenticate user by token'],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }

    public function test_jwt_auth_fails_because_of_missing_token()
    {
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => 'Bearer']
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ['Unable to authenticate user by token'],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }

    public function test_jwt_auth_fails_because_of_blacklisted_token()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $token = $response['data']['auth_data']['token'];
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                'name' => 'admin',
                'email' => 'admin@gmail.com'
            ]
        ]);
        $response = $this->post('/api/logout', [], [
            'Authorization' => "Bearer {$token}"
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => null
        ]);
        $response = $this->get(
            '/api/whoami',
            ['Authorization' => "Bearer {$token}"]
        );
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ['Token has expired/blacklisted and can not be refreshed'],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }
}
