<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UnableToAuthenticateUserByTokenException;
use App\Http\Requests\User\SignInRequest;
use App\Http\Requests\User\SignUpRequest;
use App\Services\UserService;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function signIn(SignInRequest $request, UserService $service)
    {
        try {
            return response()->common(
                Response::HTTP_OK,
                $service->signIn($request->only("email", "password"))
            );
        } catch (InvalidCredentialsException $e) {
            return response()->common(
                Response::HTTP_BAD_REQUEST,
                null,
                ["Invalid credentials"]
            );
        }
    }

    public function signUp(SignUpRequest $request, UserService $service)
    {
        return response()->common(
            Response::HTTP_CREATED,
            $service->signUp(
                $request->get('name'),
                $request->get('email'),
                $request->get('password')
            )
        );
    }

    public function whoami(UserService $service)
    {
        try {
            return response()->common(Response::HTTP_OK, $service->whoami());
        } catch (UnableToAuthenticateUserByTokenException $e) {
            return response()->common(Response::HTTP_UNAUTHORIZED, null, ["Unable to authenticate user by token"]);
        }
    }
}
