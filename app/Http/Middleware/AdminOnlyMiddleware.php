<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnlyMiddleware
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
        $user = auth('api')->user();
        if($user->role_id != 1) {
            return response()->json(['status_code' => Response::HTTP_FORBIDDEN,'message' => 'Unauthorized.']);
        }
        
        return $next($request);
    }
}
