<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;
use App\Support\FeatureFlag\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    Cache::flush();

    $this->defaultFalseFlag = collect(FeatureFlag::cases())
        ->first(fn (FeatureFlag $flag) => $flag->getDefaultState() === false && $flag->getDisabledOnEnvironment() === false);

    $this->defaultTrueFlag = collect(FeatureFlag::cases())
        ->first(fn (FeatureFlag $flag) => $flag->getDefaultState() === true && $flag->getDisabledOnEnvironment() === false);

    $this->anyFlag = $this->defaultFalseFlag ?? $this->defaultTrueFlag;
});

it('prioritizes environment config over database and default state', function () {
    if (! $this->anyFlag) {
        $this->markTestSkipped('No feature flags available to test.');
    }

    Config::set($this->anyFlag->configIdentifier(), true);
    expect(Feature::isActive($this->anyFlag))->toBeTrue();

    Config::set($this->anyFlag->configIdentifier(), false);
    expect(Feature::isActive($this->anyFlag))->toBeFalse();
});

it('retrieves state from the database if config is null', function () {
    if (! $this->anyFlag) {
        $this->markTestSkipped('No feature flags available to test.');
    }

    DB::table('feature_flags')->insert([
        'name' => $this->anyFlag->value,
        'enabled' => true,
    ]);

    expect(Feature::isActive($this->anyFlag))->toBeTrue();

    DB::table('feature_flags')
        ->where('name', $this->anyFlag->value)
        ->update(['enabled' => false]);

    Cache::flush();
    expect(Feature::isActive($this->anyFlag))->toBeFalse();
});

it('falls back to the enum default state if database record is missing', function () {
    if ($this->defaultFalseFlag) {
        expect(Feature::isActive($this->defaultFalseFlag))->toBeFalse();
    }

    if ($this->defaultTrueFlag) {
        expect(Feature::isActive($this->defaultTrueFlag))->toBeTrue();
    }

    if (! $this->defaultFalseFlag && ! $this->defaultTrueFlag) {
        $this->markTestSkipped('No feature flags available to test default states.');
    }
});

it('caches the resolved database state', function () {
    if (! $this->anyFlag) {
        $this->markTestSkipped('No feature flags available to test.');
    }

    DB::table('feature_flags')->insert([
        'name' => $this->anyFlag->value,
        'enabled' => true,
    ]);

    expect(Feature::isActive($this->anyFlag))->toBeTrue();

    DB::table('feature_flags')
        ->where('name', $this->anyFlag->value)
        ->update(['enabled' => false]);

    expect(Feature::isActive($this->anyFlag))->toBeTrue();
});
