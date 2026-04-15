<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class UserActivityLog
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $cacheKey = "user:last_seen:{$userId}";

            if (Cache::add($cacheKey, true, now()->addMinutes(3))) {
                DB::table('users')
                    ->where('id', $userId)
                    ->update([
                        'last_seen' => now(),
                    ]);
            }
        }

        return $next($request);
    }
}
