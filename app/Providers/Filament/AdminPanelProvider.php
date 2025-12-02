<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Chats\ChatResource;
use App\Filament\Resources\Roles\RoleResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Filament\Resources\Resource;
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

    private static function shielded(Resource|string $resourceClass): array
    {
        return collect($resourceClass::getNavigationItems())
            ->map(fn($item) =>
            $item->visible(fn() => $resourceClass::canViewAny())
            )
            ->all();
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                    ...Dashboard::getNavigationItems(),

                    ...self::shielded(CollectionResource::class),
                    ...self::shielded(OwnBookResource::class),
                    ...self::shielded(ExtPromotionResource::class),
                    ...self::shielded(ChatResource::class),
                    ...self::shielded(PrintOrderResource::class),
                    ...self::shielded(UserResource::class),
                    ...self::shielded(SurveyCompletedResource::class),
                    ...self::shielded(PromocodeResource::class),
                    ...self::shielded(InnerTaskResource::class),
                    ...self::shielded(RoleResource::class),

                    NavigationItem::make('Log Viewer')
                        ->url('/log-viewer')
                        ->icon('heroicon-o-code-bracket')
                        ->group('Настройки')
                        ->sort(999)
                        ->visible(fn() => auth()->user()->can('view_log_viewer')),
                ])
                    ->groups([
                        NavigationGroup::make('Настройки')
                            ->collapsed()
                            ->items([
                                ...self::shielded(LogisticCompanyResource::class),
                                ...self::shielded(PrintingCompanyResource::class),
                            ])
                    ]);
            })
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([\App\Filament\Pages\Dashboard::class])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
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
            ->plugins([
                FilamentShieldPlugin::make(),
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
