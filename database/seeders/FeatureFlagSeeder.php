<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\FeatureFlag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FeatureFlagSeeder extends Seeder
{
    public function run(): void
    {
        $features = array_map(static fn (FeatureFlag $case) => $case->value, FeatureFlag::cases());

        $unusedFlags = DB::table('feature_flags')
            ->whereNotIn('name', $features)
            ->pluck('name');

        $uselessFlags = collect();
        $flags = DB::table('feature_flags')->whereIn('name', $features)->get();

        foreach ($flags as $flag) {
            $featureFlag = FeatureFlag::tryFrom($flag->name);

            if ($featureFlag && (bool) $flag->enabled === $featureFlag->getDefaultState()) {
                $uselessFlags->push($flag->name);
            }
        }

        $cleanupFlags = $unusedFlags->merge($uselessFlags)->unique();

        if ($cleanupFlags->isEmpty()) {
            return;
        }

        foreach ($cleanupFlags as $name) {
            Cache::forget(FeatureFlag::makeCacheIdentifier($name));

            DB::table('feature_flags')->where('name', $name)->delete();
        }
    }
}
