<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UnableToAuthenticateUserByTokenException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    private function formAuthDataObject(string $token)
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    public function signIn(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }
        return $this->formAuthDataObject($token);
    }

    public function signUp(string $name, string $email, string $password)
    {
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);
        $token = JWTAuth::fromUser($user);
        return [
            'user' => $user,
            'auth_data' => $this->formAuthDataObject($token),
        ];
    }

    public function whoami()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            throw new UnableToAuthenticateUserByTokenException();
        }
        return $user;
    }
}
