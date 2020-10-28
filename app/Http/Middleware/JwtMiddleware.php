<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
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
        try {
            if (!$this->auth->parseToken()->authenticate()) {
                return $this->respondUnauthorized('Unable to authenticate user by token');
            }
        } catch (JWTException $e) {
            if ($e instanceof TokenExpiredException || $e instanceof TokenBlacklistedException) {
                try {
                    $refreshedToken = $this->auth->refresh();
                    $response = $next($request);
                    $response->header('Access-Control-Expose-Headers', 'token,token_type,expires_in');
                    $response->header('token', $refreshedToken);
                    $response->header('token_type', 'bearer');
                    $response->header('expires_in', $this->auth->factory()->getTTL() * 60);
                    return $response;
                } catch (JWTException $e) {
                    return $this->respondUnauthorized('Token has expired/blacklisted and can not be refreshed');
                }
            } else if ($e instanceof TokenInvalidException) {
                return $this->respondUnauthorized('Token is invalid');
            } else {
                return $this->respondUnauthorized('Unable to authenticate user by token');
            }
        }
        return $next($request);
    }

    /**
     * Respond with "401 Unauthorized" status.
     *
     * @param  string  $error
     * @return mixed
     */
    private function respondUnauthorized(string $error) {
        return response()->common(Response::HTTP_UNAUTHORIZED, null, [$error]);
    }
}
