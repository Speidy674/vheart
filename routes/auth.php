<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\Email\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\Email\EmailVerificationPromptController;
use App\Http\Controllers\Auth\Email\EmailVerificationController;
use App\Http\Controllers\Auth\TwoFactorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('auth/challenge', [TwoFactorController::class, 'index'])
        ->name('auth.challenge');

    Route::post('auth/challenge', [TwoFactorController::class, 'store'])
        ->middleware(['throttle:two-factor'])
        ->name('auth.challenge.submit');

    Route::get('login', [AuthController::class, 'login'])
        ->name('login');

    Route::get('/auth/twitch', [AuthController::class, 'redirect'])
        ->name('auth.twitch');

    Route::get('/auth/twitch/callback', [AuthController::class, 'callback'])
        ->middleware(['throttle:login'])
        ->name('auth.callback');
});

Route::post('logout', [AuthController::class, 'logout'])
    ->middleware(['auth:web'])
    ->name('logout');

Route::middleware(['auth:web'])
    ->prefix('email')
    ->name('verification.')
    ->group(function () {
        Route::get('verify', EmailVerificationPromptController::class)
            ->name('notice');

        Route::get('verify/{id}/{hash}', EmailVerificationController::class)
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verify');

        Route::post('verification-notification', EmailVerificationNotificationController::class)
            ->middleware(['throttle:6,1'])
            ->name('send');
    });
