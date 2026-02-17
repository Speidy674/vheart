<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Requests\Auth\VerifyEmailRequest;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::get('auth/challenge', [TwoFactorController::class, 'index'])
        ->name('auth.challenge');

    Route::post('auth/challenge', [TwoFactorController::class, 'store'])
        ->middleware(['throttle:two-factor'])
        ->name('auth.challenge.submit');

    Route::get('login', [AuthController::class, 'index'])
        ->name('login');

    Route::get('/auth/twitch', [AuthController::class, 'create'])
        ->name('auth.twitch');

    Route::get('/auth/twitch/callback', [AuthController::class, 'store'])
        ->middleware(['throttle:login'])
        ->name('auth.callback');
});

Route::post('logout', [AuthController::class, 'destroy'])
    ->middleware(['auth:web'])
    ->name('logout');

Route::middleware(['auth:web'])
    ->prefix('email')
    ->name('verification.')
    ->group(function () {
        Route::get('verify', static function (Request $request) {
            if ($request->user()->email === null || $request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard'));
            }

            return Inertia::render('auth/verify-email');
        })
            ->name('notice');

        Route::get('verify/{id}/{hash}', static function (VerifyEmailRequest $request) {
            if ($request->user()->hasVerifiedEmail()) {
                return redirect()->intended(route('dashboard', ['verified' => true]));
            }

            if ($request->user()->markEmailAsVerified()) {
                event(new Verified($request->user()));
            }

            return redirect()->intended(route('dashboard', ['verified' => true]));
        })
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verify');

        Route::post('verification-notification', static function (Request $request) {
            if ($request->user()->hasVerifiedEmail()) {
                return $request->wantsJson()
                    ? new JsonResponse(status: 204)
                    : redirect()->intended(route('dashboard'));
            }

            $request->user()->sendEmailVerificationNotification();

            return $request->wantsJson()
                ? new JsonResponse(status: 202)
                : back()->with('status', __('auth.verification.sent'));

        })
            ->middleware(['throttle:6,1'])
            ->name('send');
    });
