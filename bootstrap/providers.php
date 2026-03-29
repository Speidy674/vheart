<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\CookiesServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\DashboardPanelProvider;
use App\Providers\Support\FeatureFlagServiceProvider;
use App\Providers\TwitchServiceProvider;
use SocialiteProviders\Manager\ServiceProvider;

return [
    AppServiceProvider::class,
    FeatureFlagServiceProvider::class,
    CookiesServiceProvider::class,
    AdminPanelProvider::class,
    DashboardPanelProvider::class,
    ServiceProvider::class,
    TwitchServiceProvider::class,
];
