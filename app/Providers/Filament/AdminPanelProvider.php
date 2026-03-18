<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Enums\Filament\LucideIcon;
use App\Filament\Pages\Auth\EditProfile;
use App\Http\Middleware\StagingGateMiddleware;
use Filament\Actions\Action;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugin(SpatieTranslatablePlugin::make()->defaultLocales(['de', 'en'])->useFallbackLocale(false))
            ->emailChangeVerification()
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->regenerableRecoveryCodes(false)
                    ->recoverable()
                    ->brandName('VHeart'),
            ], isRequired: config('auth.admin.require_2fa'))
            ->profile(EditProfile::class, isSimple: false)
            ->id('admin')
            ->path('admin')
            ->viteTheme('resources/css/filament/admin.css')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->maxContentWidth(Width::ScreenTwoExtraLarge)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->databaseNotifications()
            ->userMenuItems([
                Action::make('back-home')
                    ->label('dashboard/navigation.back-home')
                    ->translateLabel()
                    ->url(fn (): string => route('home'))
                    ->icon(LucideIcon::Home)
                    ->sort(100),
                Action::make('to-admin')
                    ->label('navigation.dashboard')
                    ->translateLabel()
                    ->url(fn (): string => Filament::getPanel('dashboard')->getUrl())
                    ->icon(LucideIcon::LayoutDashboard)
                    ->sort(100),
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
                StagingGateMiddleware::class,
            ]);
    }
}
