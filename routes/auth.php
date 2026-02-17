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

Route::get('auth/challenge', TwoFactorPromptController::class)
    ->name('auth.challenge');

Route::post('auth/challenge', TwoFactorVerificationController::class)
    ->name('auth.challenge.submit');

Route::get('login', CreateAuthenticatedSessionController::class)
    ->name('login');

Route::get('/auth/twitch', RedirectToAuthProviderController::class)
    ->name('auth.twitch');

Route::get('/auth/twitch/callback', HandleAuthProviderCallbackController::class)
    ->name('auth.callback');

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
