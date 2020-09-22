<?php

namespace App\Http\Middleware;

use Closure;
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
        try {
            if (!JWTAuth::parseToken()->authenticate()) {
                return response()->common(401, null, ["Unable to authenticate user by token"]);
            }
        } catch (TokenExpiredException $e) {
            return response()->common(401, null, ["Token has expired"]);
        } catch (TokenInvalidException $e) {
            return response()->common(401, null, ["Token is invalid"]);
        } catch (JWTException $e) {
            return response()->common(401, null, ["Unable to authenticate user by token"]);
        }
        return $next($request);
    }
}
