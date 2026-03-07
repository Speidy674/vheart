<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Support\FeatureFlagServiceProvider::class,
    App\Providers\CookiesServiceProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    SocialiteProviders\Manager\ServiceProvider::class,
];
