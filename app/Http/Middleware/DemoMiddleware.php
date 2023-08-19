<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DemoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//         Before request
        Log::info("receive request '" . $request->path() . "' with '" . $request->method() . "' method");
        $response = $next($request);
//        After request
        Log::info("receive response '" . $response . "' with '" . $request->method() . "' method");
        return $response;
    }
}
