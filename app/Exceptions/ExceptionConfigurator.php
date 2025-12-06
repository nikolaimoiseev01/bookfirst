<?php

namespace App\Exceptions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ExceptionConfigurator
{
    /**
     * Регистрирует custom render'ы
     */
    public static function register($exceptions): void
    {
        // 404 -----------------------------------------------------
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            Log::warning('test');

            return response()->view('errors.404', [
                'message' => 'Страница не найдена',
                'error_id' => Str::uuid()->toString(),
                'url' => $request->url(),
            ], 404);
        });


        // 500 -----------------------------------------------------
        $exceptions->render(function (\Throwable $e, Request $request) {

            if (app()->isProduction()) {
                return response()->view('errors.500', [
                    'message'   => 'Произошла внутренняя ошибка сервера',
                    'error_id'  => Str::uuid()->toString(),
                    'exception' => $e->getMessage(),
                    'url'       => $request->url(),
                ], 500);
            }

            // В debug режиме — стандартная страница Laravel
            return null;
        });
    }
}
