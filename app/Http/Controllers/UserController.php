<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function signIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        if ($validator->fails()) {
            return response()->common(400, null, $validator->errors()->all());
        }
        $credentials = $request->only("email", "password");
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->common(400, null, ["Invalid credentials"]);
        }
        return response()->common(200, [
            'token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
        ]);
    }

    public function signUp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        if ($validator->fails()) {
            return response()->common(400, null, $validator->errors()->all());
        }
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        $token = JWTAuth::fromUser($user);
        return response()->common(201, [
            'user' => $user,
            'auth_data' => [
                'token' => $token,
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
            ]
        ]);
    }

    public function whoami()
    {
        if (!$user = JWTAuth::parseToken()->authenticate()) {
            return response()->common(401, null, ["Unable to authenticate user by token"]);
        }
        return response()->common(200, $user);
    }
}
