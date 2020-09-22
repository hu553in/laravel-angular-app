<?php

namespace App\Http\Middleware;

use Closure;
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
    public function handle($request, Closure $next)
    {
        $respond = function (string $error) {
            return response()->common(Response::HTTP_UNAUTHORIZED, null, [$error]);
        };
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return $respond("Unable to authenticate user by token");
            }
        } catch (TokenExpiredException $e) {
            return $respond("Token has expired");
        } catch (TokenInvalidException $e) {
            return $respond("Token is invalid");
        } catch (JWTException $e) {
            return $respond("Unable to authenticate user by token");
        }
        return $next($request);
    }
}
