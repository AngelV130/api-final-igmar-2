<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareOrigin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $allwedOrigin = explode(',',env('CORS_ALLOWED_ORIGINS'));
        if(!in_array($request->headers->get('Origin'),$allwedOrigin) || !($request->headers->get('Origin') == null))
            return response([
                'all'=> $allwedOrigin,
                'origin'=> $request->headers->get('Origin')
            ], 404);
        return $next($request);
    }
}
