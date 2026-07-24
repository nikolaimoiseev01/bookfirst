<?php

namespace App\Providers\Filament;

use App\Filament\Pages\BulkCreateEmailCampaign;
use App\Filament\Resources\EmailMarketing\EmailCampaigns\EmailCampaignResource;
use App\Filament\Resources\EmailMarketing\EmailTemplates\EmailTemplateResource;
use App\Filament\Resources\EmailMarketing\EmailRecipientLists\EmailRecipientListResource;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class EmailMarketingPanelProvider extends PanelProvider
{
    private static function shielded(Resource|string $resourceClass): array
    {
        return collect($resourceClass::getNavigationItems())
            ->map(fn($item) => $item->visible(fn() => $resourceClass::canViewAny())
            )
            ->all();
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->sidebarCollapsibleOnDesktop()
            ->id('email-marketing')
            ->path('email-marketing')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->items([
                    ...Dashboard::getNavigationItems(),
                ])
                    ->groups([
                        NavigationGroup::make('Email маркетинг')
                            ->items([
                                ...self::shielded(EmailCampaignResource::class),
                                ...self::shielded(EmailTemplateResource::class),
                                ...self::shielded(EmailRecipientListResource::class),

                                NavigationItem::make('bulk_create')
                                    ->label('Массовое создание кампаний')
                                    ->url('/email-marketing/bulk-create-email-campaign')
                                    ->icon('heroicon-o-envelope-open')
                                    ->visible(fn() => auth()->user()->hasAnyRole('admin|super_admin'))
                                    ->sort(10),

                                NavigationItem::make('Админка')
                                    ->url('/admin')
                                    ->icon('heroicon-o-user')
                                    ->visible(fn() => auth()->user()->hasAnyRole('admin|super_admin'))
                                    ->sort(50),
                            ]),
                    ]);
            })
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
                BulkCreateEmailCampaign::class,
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
