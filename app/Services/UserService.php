<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UnableToAuthenticateUserByTokenException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    /**
     * Form auth data object.
     *
     * @param  string  $token
     * @return array
     */
    private function formAuthDataObject(string $token)
    {
        return [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ];
    }

    /**
     * Sign in.
     *
     * @param  array  $credentials
     * @return array
     *
     * @throws \App\Exceptions\InvalidCredentialsException
     */
    public function signIn(array $credentials)
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }
        return [
            'user' => $this->getUser(),
            'auth_data' => $this->formAuthDataObject($token),
        ];
    }

    /**
     * Sign up.
     *
     * @param  string  $name
     * @param  string  $email
     * @param  string  $password
     * @return array
     */
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

    /**
     * Whoami.
     *
     * @return \Tymon\JWTAuth\Contracts\JWTSubject
     */
    public function whoami()
    {
        return $this->getUser();
    }

    /**
     * Logout.
     */
    public function logout()
    {
        JWTAuth::parseToken()->invalidate();
    }

    /**
     * Get user.
     *
     * @return \Tymon\JWTAuth\Contracts\JWTSubject
     */
    private function getUser()
    {
        $user = JWTAuth::user();
        unset($user->email_verified_at);
        return $user;
    }
}
