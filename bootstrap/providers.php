<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\VoltServiceProvider::class,
    \SocialiteProviders\Manager\ServiceProvider::class,
    Illuminate\Mail\MailServiceProvider::class,
];
