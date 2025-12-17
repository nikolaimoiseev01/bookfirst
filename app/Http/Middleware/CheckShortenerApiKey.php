<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckShortenerApiKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-KEY');

        if (!$key || !hash_equals(config('services.shortener.api_key'), $key)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return $next($request);
    }
}
