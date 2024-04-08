<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidHostUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->user()->rol == 1){
            if($request->header('Origin') != env("IP_VPN_NETWORK", "https://192.0.2.6")){
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }else if($request->user()->rol == 2){
            if($request->header('Origin') == env("IP_VPN_NETWORK", "https://192.0.2.6")){
                return response()->json(['message' => 'Unauthorized'], 401);
            }
        }
        return $next($request);
    }
}
