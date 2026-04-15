<?php
namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use NotificationChannels\Telegram\Telegram;
use NotificationChannels\Telegram\TelegramChannel;
use GuzzleHttp\Client as HttpClient;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register(): void
    {
        $this->app->bind(Telegram::class, function () {
            $client = new HttpClient([
                'proxy' => config('services.telegram-proxy'),
                'timeout' => 30,
                'connect_timeout' => 10,
            ]);

            return new Telegram(
                config('services.telegram-bot-api.token'),
                $client,
                config('services.telegram-bot-api.base_uri')
            );
        });
    }
}
