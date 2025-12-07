<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceNewSite
{
    public function handle(Request $request, Closure $next): Response
    {
        // ✅ Если запрашивается реальный файл из public — пропускаем
        if (file_exists(public_path($request->path()))) {
            return $next($request);
        }

        // ✅ Разрешаем сам new-site
        if ($request->is('new-site')) {
            return $next($request);
        }

        // 🔒 Всё остальное — редирект
        return redirect('/new-site');
    }
}
