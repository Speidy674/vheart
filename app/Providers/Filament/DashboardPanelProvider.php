<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Dashboard\Pages\Dashboard;
use App\Http\Middleware\Localization;
use App\Http\Middleware\StagingGateMiddleware;
use App\Models\Broadcaster\Broadcaster;
use Filament\Actions\Action;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->plugin(SpatieTranslatablePlugin::make()->defaultLocales(['de', 'en'])->useFallbackLocale(false))
            ->emailChangeVerification()
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->regenerableRecoveryCodes(false)
                    ->recoverable()
                    ->brandName('VHeart'),
            ])
            ->id('dashboard')
            ->path('dashboard')
            ->viteTheme('resources/css/filament/dashboard/theme.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                // 'profile' => fn (Action $action) => $action->label('Edit profile'),
            ])
            ->maxContentWidth(Width::ScreenTwoExtraLarge)
            ->darkModeBrandLogo(fn () => Vite::asset('resources/images/svg/logo-full-dark.svg'))
            ->brandLogo(fn () => Vite::asset('resources/images/svg/logo-full-title.svg'))
            ->brandLogoHeight('2rem')
            ->homeUrl('/')
            ->tenant(Broadcaster::class)
            ->searchableTenantMenu()
            ->discoverResources(in: app_path('Filament/Dashboard/Resources'), for: 'App\Filament\Dashboard\Resources')
            ->discoverPages(in: app_path('Filament/Dashboard/Pages'), for: 'App\Filament\Dashboard\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Dashboard/Widgets'), for: 'App\Filament\Dashboard\Widgets')
            ->databaseNotifications()
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
                StagingGateMiddleware::class,
                Localization::class,
            ])->spa();
    }
}
