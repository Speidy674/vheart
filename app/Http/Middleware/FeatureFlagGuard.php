<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FeatureFlag as FeatureFlagEnum;
use App\Support\FeatureFlag\Feature;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureFlagGuard
{
    public static function of(FeatureFlagEnum $feature): string
    {
        return static::class.':'.$feature->value;
    }

    public function handle(Request $request, Closure $next, string $featureName): Response
    {
        $feature = FeatureFlagEnum::tryFrom($featureName);

        abort_if(! $feature || ! Feature::isActive($feature), 404);

        return $next($request);
    }
}
