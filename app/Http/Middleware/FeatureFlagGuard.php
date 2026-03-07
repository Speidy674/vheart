<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FeatureFlag as FeatureFlagEnum;
use App\Support\FeatureFlag\Feature;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class FeatureFlagGuard
{
    public static function of(FeatureFlagEnum $feature): string
    {
        return static::class.':one,'.$feature->value;
    }

    public static function any(array|Collection $features): string
    {
        $values = collect($features)->map(fn (FeatureFlagEnum $f) => $f->value)->implode(',');

        return static::class.':any,'.$values;
    }

    public static function all(array|Collection $features): string
    {
        $values = collect($features)->map(fn (FeatureFlagEnum $f) => $f->value)->implode(',');

        return static::class.':all,'.$values;
    }

    public function handle(Request $request, Closure $next, string $modeOrFeature, string ...$featureNames): Response
    {
        if ($featureNames === []) {
            $feature = FeatureFlagEnum::tryFrom($modeOrFeature);

            abort_if(! $feature || ! Feature::isActive($feature), 404);

            return $next($request);
        }

        $isActive = match ($modeOrFeature) {
            'any' => collect($featureNames)->contains(function (string $name): bool {
                $feature = FeatureFlagEnum::tryFrom($name);

                return $feature && Feature::isActive($feature);
            }),
            'all' => collect($featureNames)->every(function (string $name): bool {
                $feature = FeatureFlagEnum::tryFrom($name);

                return $feature && Feature::isActive($feature);
            }),
            default => ($feature = FeatureFlagEnum::tryFrom($featureNames[0])) && Feature::isActive($feature),
        };

        abort_if(! $isActive, 404);

        return $next($request);
    }
}
