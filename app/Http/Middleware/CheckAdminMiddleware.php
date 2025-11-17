<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return response()->json([
                'status'    => 401,
                'error'     => 'Unauthorized. Please Login First.'
            ],401);
        }
        if(Auth::user()->role !== 'ADM'){
            return response()->json([
                'status'    => 403,
                'error'     => 'You are not authorized to access this resource.'
            ],403);
        }
        return $next($request);
    }
}
