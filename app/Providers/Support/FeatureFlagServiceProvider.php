<?php

declare(strict_types=1);

namespace App\Providers\Support;

use App\Enums\FeatureFlag;
use App\Http\Middleware\FeatureFlagGuard;
use App\Support\FeatureFlag\Feature;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FeatureFlagServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::if('feature', static fn (FeatureFlag $feature): bool => Feature::isActive($feature));
        Route::macro('feature', function (FeatureFlag $feature) {
            /** @var \Illuminate\Routing\Route $this */
            return $this->middleware(FeatureFlagGuard::of($feature));
        });
    }
}
