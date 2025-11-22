<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Chats\ChatResource;
use App\Filament\Resources\Collection\Collections\CollectionResource;
use App\Filament\Resources\ExtPromotions\ExtPromotionResource;
use App\Filament\Resources\InnerTasks\InnerTaskResource;
use App\Filament\Resources\OwnBook\OwnBooks\OwnBookResource;
use App\Filament\Resources\PrintOrder\LogisticCompanies\LogisticCompanyResource;
use App\Filament\Resources\PrintOrder\PrintingCompanies\PrintingCompanyResource;
use App\Filament\Resources\PrintOrder\PrintOrders\PrintOrderResource;
use App\Filament\Resources\Promocodes\PromocodeResource;
use App\Filament\Resources\SurveyCompleteds\SurveyCompletedResource;
use App\Filament\Resources\User\Users\UserResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
//            ->topNavigation()
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                    ...Dashboard::getNavigationItems(),
                    ...CollectionResource::getNavigationItems(),
                    ...OwnBookResource::getNavigationItems(),
                    ...ExtPromotionResource::getNavigationItems(),
                    ...ChatResource::getNavigationItems(),
                    ...PrintOrderResource::getNavigationItems(),
                    ...UserResource::getNavigationItems(),
                    ...SurveyCompletedResource::getNavigationItems(),
                    ...PromocodeResource::getNavigationItems(),
                    ...InnerTaskResource::getNavigationItems(),
                ])->groups([
                    NavigationGroup::make('Настройки')
                        ->collapsed()
                        ->items([
                            ...LogisticCompanyResource::getNavigationItems(),
                            ...PrintingCompanyResource::getNavigationItems(),
                        ])
                ]);
            })
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
