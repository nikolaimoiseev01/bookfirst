<?php

namespace App\Providers\Filament;

use App\Filament\Ai\Pages\AiDashboard;
use App\Filament\Ai\Resources\AiKnowledgeDocumentsResource;
use App\Filament\Ai\Resources\AiPromptsResource;
use App\Filament\Ai\Resources\AiReplyExamplesResource;
use App\Filament\Ai\Resources\AiAssistantConversationsResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
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

class AiPanelProvider extends PanelProvider
{
    private static function resourceItems(Resource|string $resourceClass): array
    {
        return collect($resourceClass::getNavigationItems())
            ->map(fn ($item) => $item->visible(fn () => $resourceClass::canViewAny()))
            ->all();
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('ai')
            ->path('ai-admin')
            ->brandName('Панель ИИ')
            ->sidebarCollapsibleOnDesktop()
            ->login()
            ->colors(['primary' => Color::Amber])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        ...AiDashboard::getNavigationItems(),
                        NavigationItem::make('Основная админка')
                            ->url('/admin')
                            ->icon('heroicon-o-arrow-left')
                            ->visible(fn () => auth()->user()?->hasAnyRole('admin|super_admin')),
                    ])
                    ->groups([
                        NavigationGroup::make('Для пользователя')->items([
                            ...self::resourceItems(AiPromptsResource::class),
                            ...self::resourceItems(AiAssistantConversationsResource::class),
                        ]),
                        NavigationGroup::make('Для админа')->items([
                            ...self::resourceItems(AiKnowledgeDocumentsResource::class),
                            ...self::resourceItems(AiReplyExamplesResource::class),
                        ]),
                    ]);
            })
            ->discoverResources(in: app_path('Filament/Ai/Resources'), for: 'App\\Filament\\Ai\\Resources')
            ->resources([AiAssistantConversationsResource::class])
            ->pages([AiDashboard::class])
            ->viteTheme('resources/css/filament/admin/theme.css')
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
            ->authMiddleware([Authenticate::class]);
    }
}
