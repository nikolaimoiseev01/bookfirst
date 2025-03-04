<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Jenssegers\Agent\Agent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list.blade.php of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list.blade.php of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function report(Exception|Throwable $exception)
    {
        $errorId = Str::uuid()->toString(); // Генерируем один раз

        // Добавляем error_id в исключение
        if (method_exists($exception, 'setData')) {
            $exception->setData(['error_id' => $errorId]);
        } else {
            $exception->error_id = $errorId;
        }

        $user_id = Auth::check() ? Auth::id() : 'Not Registered';

        $agent = new Agent();
        $browser = $agent->browser();
        $deviceType = $agent->device();

        // Определяем код ошибки (по умолчанию 500)
        $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        // Определяем уровень логирования
        if ($statusCode >= 500 && $statusCode < 600) {
            $logLevel = 'error';
            $icon = '🔴';
        } elseif ($statusCode >= 400 && $statusCode < 500) {
            $logLevel = 'warning';
            $icon = '🟡';
        }

        // Логируем ошибку с нужным уровнем
        Log::$logLevel(
            "$icon $statusCode. {$exception->getMessage()} $icon" .
            "\nID: " . $errorId .
            "\nUser ID: " . $user_id .
            "\nBrowser: " . $browser . " | Device: " . $deviceType .
            "\nURL: " . URL::current() .
            "\nError: " . $exception .
            "\n"
        );

        if ($this->shouldReport($exception)) {
            return;
        }

        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {


        // Извлекаем error_id, если он есть
        $errorId = $exception->error_id ?? Str::uuid()->toString();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Произошла ошибка! Сообщите код поддержки: ' . $errorId,
                'error_id' => $errorId,
            ], 500);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->view('errors.404', ['error_id' => $errorId], 404);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->view('errors.404', ['error_id' => $errorId], 404);
        }

        return response()->view('errors.500', ['error_id' => $errorId], 500);
    }

    /**
     * Register the exception handling callbacks for the participation.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

}
