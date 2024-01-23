<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Jenssegers\Agent\Agent;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        if(Auth::user()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = 'не зарегестрирован';
        }

        $agent = new Agent();

        // Получение информации о браузере
        $browser = $agent->browser();

        // Получение информации о типе устройства (desktop, tablet, phone)
        $deviceType = $agent->device();

        // Логирование ошибки в файл
        Log::channel('custom')->error(
            'User_id: ' . $user_id .
            "\nAgent: " . 'Browser: ' . $browser . '; DeviceType: ' . $deviceType .
            "\nURL: " . URL::current() .
            "\nОписание ошибки: " . $exception->getMessage() .
            "\n"
        );

        parent::report($exception);
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
