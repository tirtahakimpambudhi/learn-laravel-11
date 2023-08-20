<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoMiddlewareParameter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $token = $request->bearerToken();
        $data = unserialize($token);
        if (!$data || empty($token) || $data['role'] !== $role) {
            return response('Unauthorized.', Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}
