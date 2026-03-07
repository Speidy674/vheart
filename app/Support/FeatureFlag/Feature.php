<?php

declare(strict_types=1);

namespace App\Support\FeatureFlag;

use App\Enums\FeatureFlag;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Feature
{
    /**
     * Check if a feature flag is active.
     * Environment variables take priority over the database.
     */
    public static function isActive(FeatureFlag $feature): bool
    {
        $envValue = config($feature->configIdentifier());

        if (! is_null($envValue)) {
            return (bool) $envValue;
        }

        return Cache::rememberForever($feature->cacheIdentifier(), static function () use ($feature): bool {
            $flag = DB::table('feature_flags')->where('name', $feature->value)->first();

            if (! $flag && $feature->getDefaultState()) {
                return true;
            }

            return (bool) ($flag?->enabled ?? false);
        });
    }
}
