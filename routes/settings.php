<?php

declare(strict_types=1);

use App\Enums\FeatureFlag;
use App\Http\Controllers\Auth\User\DeleteUserController;
use App\Http\Middleware\FeatureFlagGuard;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', FeatureFlagGuard::of(FeatureFlag::UserSettings)])
    ->prefix('settings')
    ->name('user.settings')
    ->group(function () {
        Route::get('/', function (Request $request, AppAuthentication $mfa) {
            return view('settings', [
                'useTwoFactor' => $mfa->isEnabled($request->user()),
            ]);
        });

        Route::delete('/', DeleteUserController::class)->name('.delete');
    });
