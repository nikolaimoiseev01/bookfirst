<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
//use Sentry\Laravel\Integration;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'payments/callback',
        ]);
        $middleware->alias([
            'userActivityLog' => \App\Http\Middleware\UserActivityLog::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'accountOwner' => \App\Http\Middleware\EnsureOwner::class,
            'view-logs' => \App\Http\Middleware\ViewLogs::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ОТКЛЮЧАЕМ стандартное логирование Laravel
        $exceptions->reportable(function (\Throwable $e) {
            return false;
        });
        \App\Exceptions\ExceptionConfigurator::register($exceptions);
    })->create();
return $app;
