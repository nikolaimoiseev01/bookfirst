<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Illuminate\Validation\ValidationException;

class ExceptionConfigurator
{

    public static function register($exceptions): void
    {
        /*
        |--------------------------------------------------------------------------
        | 404 — Страница не найдена
        |--------------------------------------------------------------------------
        */
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {

            // ✅ В DEBUG показываем стандартную Laravel 404
            if (config('app.debug')) {
                dd(123);
                return null;
            }

            $errorId = Str::uuid()->toString();

            Log::info(
                "🔵 404 Not Found | {$request->fullUrl()}",
                array_merge(
                    self::context($e, $request, 404, $errorId),
                    ['exception' => $e] // 🔥 Для LogViewer
                )
            );

        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {

            // ✅ В DEBUG — стандартная ларавелевская логика
            if (config('app.debug')) {
                return null;
            }

            // ✅ ДЛЯ WEB — redirect на login + запоминание intended URL 🔥
            return redirect()->guest(route('login'));
        });

        /*
        |--------------------------------------------------------------------------
        | Ошибки валидации (422)
        |--------------------------------------------------------------------------
        */
        $exceptions->render(function (ValidationException $e, Request $request) {

            // ✅ В DEBUG — стандартное поведение Laravel
            if (config('app.debug')) {
                return null;
            }

            $errorId = Str::uuid()->toString();

            Log::info(
                "🔵 Validation error",
                array_merge(
                    self::context($e, $request, 422, $errorId),
                    ['exception' => $e]
                )
            );

            return null;
        });

        /*
        |--------------------------------------------------------------------------
        | Все остальные ошибки (500, 401, 403 и т.д.)
        |--------------------------------------------------------------------------
        */
        $exceptions->render(function (\Throwable $e, Request $request) {

            // ✅ Если это HTTP-исключение — НЕ ТРОГАЕМ
            if ($e instanceof HttpExceptionInterface) {
                return null;
            }

            $errorId = Str::uuid()->toString();

            $statusCode = 500;

            Log::error(
                "🔴 Exception 500 | {$e->getMessage()}",
                [
                    ...self::context($e, $request, 500, $errorId),
                    'exception' => $e,
                ]
            );

            if (config('app.debug')) {
                return null;
            }

            return response()->view('errors.500', [
                'message'  => 'Произошла внутренняя ошибка сервера',
                'error_id' => $errorId,
                'url'      => $request->fullUrl(),
            ], 500);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Общий контекст для логов
    |--------------------------------------------------------------------------
    */
    private static function context(\Throwable $e, Request $request, int $statusCode, string $errorId): array
    {
        $user = Auth::user();

        return [
            'error_id' => $errorId,
            'status'   => $statusCode,
            'message'  => $e->getMessage(),
            'file'     => $e->getFile() . ':' . $e->getLine(),
            'url'      => $request->fullUrl(),
            'method'   => $request->method(),
            'ip'       => $request->ip(),

            // ✅ Пользователь в логах всегда
            'user' => $user ? [
                'id'            => $user->id,
                'email'         => $user->email ?? null,
                'userFullName'  => function_exists('getUserName')
                    ? getUserName($user)
                    : null,
            ] : [
                'guest' => true,
            ],

            // вторичный trace (основной строится LogViewer через exception)
            'trace' => collect($e->getTrace())
                ->take(15)
                ->toArray(),
        ];
    }
}
