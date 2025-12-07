<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceNewSite
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = trim($request->path(), '/');

        // ✅ Пропускаем только если это РЕАЛЬНЫЙ ФАЙЛ, а не папка
        if ($path !== '' && file_exists(public_path($path))) {
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
