<?php

namespace App\Http\Middleware;

use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class UserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            User::where('id', $userId)->update(['last_seen' => now()]);
        }

        return $next($request);
    }
}
