<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_sign_in_succeeds()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'errors',
            'statusCode',
            'data' => [
                'user' => [
                    'name',
                    'email'
                ],
                'auth_data' => [
                    'token_type',
                    'expires_in',
                    'token'
                ]
            ]
        ]);
        $response->assertJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                'user' => [
                    'name' => 'admin',
                    'email' => 'admin@gmail.com'
                ],
                'auth_data' => [
                    'token_type' => 'bearer',
                    'expires_in' => 3600
                ]
            ]
        ]);
        $token = json_decode($response->baseResponse->getContent())
            ->data
            ->auth_data
            ->token;
        $this->assertNotEmpty($token);
        $response = $this->get('/api/whoami', [
            'Authorization' => "Bearer {$token}"
        ]);
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

    public function test_sign_in_fails_because_of_invalid_credentials()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@hotmail.com',
            'password' => 'zxcqwe123'
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => ['Invalid credentials'],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_sign_in_fails_because_of_invalid_values()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin',
            'password' => 'zxc'
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The email must be a valid email address.',
                'The password must be at least 6 characters.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_sign_in_fails_because_of_null_or_empty_values()
    {
        $response = $this->post('/api/sign_in', [
            'email' => null,
            'password' => ''
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The email field is required.',
                'The password field is required.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_sign_up_succeeds()
    {
        $response = $this->post('/api/sign_up', [
            'name' => 'test_user',
            'email' => 'test_user@gmail.com',
            'password' => 'test_user_password',
            'password_confirmation' => 'test_user_password',
        ]);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonStructure([
            'errors',
            'statusCode',
            'data' => [
                'user' => [
                    'name',
                    'email'
                ],
                'auth_data' => [
                    'token_type',
                    'expires_in',
                    'token'
                ]
            ]
        ]);
        $response->assertJson([
            'errors' => [],
            'statusCode' => Response::HTTP_CREATED,
            'data' => [
                'user' => [
                    'name' => 'test_user',
                    'email' => 'test_user@gmail.com'
                ],
                'auth_data' => [
                    'token_type' => 'bearer',
                    'expires_in' => 3600
                ]
            ]
        ]);
        $token = json_decode($response->baseResponse->getContent())
            ->data
            ->auth_data
            ->token;
        $this->assertNotEmpty($token);
        $response = $this->get('/api/whoami', [
            'Authorization' => "Bearer {$token}"
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            'errors' => [],
            'statusCode' => Response::HTTP_OK,
            'data' => [
                'name' => 'test_user',
                'email' => 'test_user@gmail.com'
            ]
        ]);
        $this->assertDatabaseHas('users', [
            'name' => 'test_user',
            'email' => 'test_user@gmail.com'
        ]);
        $this->assertTrue(Hash::check(
            'test_user_password',
            DB::table('users')
                ->where('email', 'test_user@gmail.com')
                ->pluck('password')
                ->pop()
        ));
    }

    public function test_sign_up_fails_because_of_invalid_values()
    {
        $response = $this->post('/api/sign_up', [
            'name' => 'test_user',
            'email' => 'test_user',
            'password' => 'zxc',
            'password_confirmation' => 'zxc'
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The email must be a valid email address.',
                'The password must be at least 6 characters.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_sign_up_fails_because_of_null_or_empty_values()
    {
        $response = $this->post('/api/sign_up', [
            'name' => null,
            'email' => '',
            'password' => null
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => [
                'The name field is required.',
                'The email field is required.',
                'The password field is required.'
            ],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_sign_up_fails_because_of_non_matching_password_confirmation()
    {
        $response = $this->post('/api/sign_up', [
            'name' => 'test_user',
            'email' => 'test_user@gmail.com',
            'password' => 'zxcqwe123',
            'password_confirmation' => 'qwe123zxc'
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => ['The password confirmation does not match.'],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_sign_up_fails_because_of_email_collision()
    {
        $response = $this->post('/api/sign_up', [
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin_password',
            'password_confirmation' => 'admin_password'
        ]);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson([
            'errors' => ['The email has already been taken.'],
            'statusCode' => Response::HTTP_BAD_REQUEST,
            'data' => null
        ]);
    }

    public function test_whoami_succeeds()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $token = json_decode($response->baseResponse->getContent())
            ->data
            ->auth_data
            ->token;
        $response = $this->get('/api/whoami', [
            'Authorization' => "Bearer {$token}"
        ]);
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

    public function test_logout_succeeds()
    {
        $response = $this->post('/api/sign_in', [
            'email' => 'admin@gmail.com',
            'password' => 'admin_password'
        ]);
        $token = json_decode($response->baseResponse->getContent())
            ->data
            ->auth_data
            ->token;
        $response = $this->get('/api/whoami', [
            'Authorization' => "Bearer {$token}"
        ]);
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
        $response = $this->get('/api/whoami', [
            'Authorization' => "Bearer {$token}"
        ]);
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
        $response->assertExactJson([
            'errors' => ["Token has expired/blacklisted and can not be refreshed"],
            'statusCode' => Response::HTTP_UNAUTHORIZED,
            'data' => null
        ]);
    }
}
