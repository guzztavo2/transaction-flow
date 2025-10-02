<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Redis;

class CheckUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();
        if(!$user)
            return response()->json(['error' => 'Unauthorized'], 401);

        $currentToken = $request->header('authorization');
        $validToken = Redis::get("user:{$user->id}:session");

        if(!$validToken || $currentToken !== $validToken){
            auth('api')->logout(true);
            return response()->json(['error' => 'Session expired or invalid. Please log in again.'], 401);
        }

        return $next($request);
    }
}
