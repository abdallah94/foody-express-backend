<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Illuminate\Support\Facades\Request;

class authJWT
{
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::toUser($request->header('Authorization'));
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['error' => 'Token is Invalid'],401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['error' => 'Token is Expired'],401);
            } else {
                return response()->json(['error' => 'Please include token'],401);
            }
        }
        return $next($request);
    }
}
