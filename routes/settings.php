<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;
use App\Http\Controllers\Settings\PermissionsController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Middleware\FeatureFlagGuard;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', FeatureFlagGuard::of(FeatureFlag::UserSettings)])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/appearance', function () {
        return Inertia::render('settings/appearance');
    })->name('appearance.edit');

    Route::get('settings/permissions', [PermissionsController::class, 'edit'])->name('permissions.edit');
    Route::patch('settings/permissions', [PermissionsController::class, 'update'])->name('permissions.update');
});
