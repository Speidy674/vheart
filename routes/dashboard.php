<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;
use App\Http\Controllers\Broadcaster\OnboardingController;
use App\Http\Controllers\Broadcaster\OnboardingSubmitController;
use App\Http\Middleware\FeatureFlagGuard;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', FeatureFlagGuard::of(FeatureFlag::BroadcasterOnboarding)])->group(function () {
    Route::get('/onboarding', OnboardingController::class)->name('dashboard.onboarding');
    Route::post('/onboarding', OnboardingSubmitController::class)->name('dashboard.onboarding.store');
});
