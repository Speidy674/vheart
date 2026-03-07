<?php

declare(strict_types=1);

namespace App\Providers\Support;

use App\Enums\FeatureFlag;
use App\Http\Middleware\FeatureFlagGuard;
use App\Support\FeatureFlag\Feature;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class FeatureFlagServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::if('feature', static fn (FeatureFlag $feature): bool => Feature::isActive($feature));
        Blade::if('featureAny', static fn (array $features): bool => collect($features)->contains(static fn (FeatureFlag $feature): bool => Feature::isActive($feature)));
        Blade::if('featureAll', static fn (array $features): bool => collect($features)->every(static fn (FeatureFlag $feature): bool => Feature::isActive($feature)));

        Route::macro('feature', function (FeatureFlag $feature) {
            /** @var \Illuminate\Routing\Route $this */
            return $this->middleware(FeatureFlagGuard::of($feature));
        });

        Route::macro('featureAny', function (array|Collection $features) {
            /** @var \Illuminate\Routing\Route $this */
            return $this->middleware(FeatureFlagGuard::any($features));
        });

        Route::macro('featureAll', function (array|Collection $features) {
            /** @var \Illuminate\Routing\Route $this */
            return $this->middleware(FeatureFlagGuard::all($features));
        });
    }
}
