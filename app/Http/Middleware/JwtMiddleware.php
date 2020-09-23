<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $respondUnauthorized = function (string $error) {
            return response()->common(Response::HTTP_UNAUTHORIZED, null, [$error]);
        };
        $response = $next($request);
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return $respondUnauthorized("Unable to authenticate user by token");
            }
        } catch (TokenExpiredException $e) {
            try {
                $refreshedToken = JWTAuth::refresh(JWTAuth::getToken());
                JWTAuth::setToken($refreshedToken)->toUser();
                $response->header('token', $refreshedToken);
                $response->header('token_type', 'bearer');
                $response->header('expires_in', JWTAuth::factory()->getTTL() * 60);
            } catch (JWTException $e) {
                return $respondUnauthorized("Token has expired and can not be refreshed");
            }
        } catch (TokenInvalidException $e) {
            return $respondUnauthorized("Token is invalid");
        } catch (JWTException $e) {
            return $respondUnauthorized("Unable to authenticate user by token");
        }
        return $response;
    }
}
