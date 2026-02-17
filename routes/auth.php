<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\CreateAuthenticatedSessionController;
use App\Http\Controllers\Auth\DestroyAuthenticatedSessionController;
use App\Http\Controllers\Auth\Email\EmailVerificationController;
use App\Http\Controllers\Auth\Email\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\Email\EmailVerificationPromptController;
use App\Http\Controllers\Auth\OAuth\HandleAuthProviderCallbackController;
use App\Http\Controllers\Auth\OAuth\RedirectToAuthProviderController;
use App\Http\Controllers\Auth\TwoFactor\TwoFactorPromptController;
use App\Http\Controllers\Auth\TwoFactor\TwoFactorVerificationController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->name('auth.')
    ->group(function () {
        Route::get('2fa/challenge', TwoFactorPromptController::class)
            ->name('challenge');

        Route::post('2fa/challenge', TwoFactorVerificationController::class)
            ->name('challenge.submit');

        Route::get('/twitch', RedirectToAuthProviderController::class)
            ->name('twitch');

        Route::get('/twitch/callback', HandleAuthProviderCallbackController::class)
            ->name('callback');
    });

Route::get('login', CreateAuthenticatedSessionController::class)
    ->name('login');

Route::post('logout', DestroyAuthenticatedSessionController::class)
    ->name('logout');

Route::prefix('email')
    ->name('verification.')
    ->group(function () {
        Route::get('verify', EmailVerificationPromptController::class)
            ->name('notice');

        Route::get('verify/{id}/{hash}', EmailVerificationController::class)
            ->name('verify');

        Route::post('verification-notification', EmailVerificationNotificationController::class)
            ->name('send');
    });
