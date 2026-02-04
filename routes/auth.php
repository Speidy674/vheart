<?php

declare(strict_types=1);

use App\Http\Requests\Auth\VerifyEmailRequest;
use App\Models\User;
use Carbon\CarbonInterval;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

Route::middleware(['guest'])->group(function () {
    Route::get('auth/challenge', static function (Request $request) {
        $userId = $request->session()->get('auth.2fa.id', false);
        $user = User::query()->find($userId);
        $mfa = app(AppAuthentication::class);

        if (! $userId || ! $user || ! $mfa->isEnabled($user)) {
            return to_route('login');
        }

        return Inertia::render('auth/challenge');
    })->name('auth.challenge');

    Route::post('auth/challenge', static function (Request $request) {
        $request->validate([
            'code' => 'sometimes|numeric|max_digits:6|min_digits:6',
            'recovery_code' => 'sometimes|string',
        ]);

        $userId = $request->session()->get('auth.2fa.id', false);
        $user = User::query()->find($userId);

        $mfa = app(AppAuthentication::class);

        if (! $userId || ! $user || ! $mfa->isEnabled($user)) {
            return to_route('login');
        }

        if (
            $mfa->verifyCode($request->input('code', ''), $user->app_authentication_secret)
            || $mfa->verifyRecoveryCode($request->input('recovery_code', ''), $user)
        ) {
            $request->session()->forget(['auth.2fa.id']);
            $request->session()->regenerate();
            Auth::login($user);

            $url = $request->session()->pull('url.intended', route('start'));

            return Inertia::location($url);
        }

        throw ValidationException::withMessages([
            'code' => 'Incorrect code',
            'recovery_code' => 'Incorrect code',
        ]);
    })
        ->middleware(['throttle:two-factor'])
        ->name('auth.challenge.submit');

    Route::get('login', static function (Request $request) {
        return Inertia::render('auth/login', [
            'status' => $request->session()->get('status'),
        ]);
    })
        ->name('login');

    Route::get('/auth/twitch', static function () {
        return Socialite::driver('twitch')->scopes([
            'channel:read:vips', // Required to access VIP list
            'user:read:moderated_channels', // Required to see who a user moderates for
            'channel:manage:clips', // Required to allow the VHeart team to download clips for processing
        ])->redirect();
    })
        ->name('auth.twitch');

    Route::get('/auth/twitch/callback', function (Request $request) {
        try {
            $twitchUser = Socialite::driver('twitch')->user();
        } catch (Exception $e) {
            return to_route('login')->with('error', __('auth.oauth_error_try_again'));
        }

        $userCreatedAt = Date::parse($twitchUser->user['created_at']);
        $userAgeMinimum = CarbonInterval::fromString(config('auth.required_account_age'));

        if ($userCreatedAt->add($userAgeMinimum)->isFuture()) {
            return to_route('login')->withErrors(['login' => __('auth.account_created_too_early')]);
        }

        $user = User::updateOrCreate([
            'id' => $twitchUser->getId(),
        ],
            [
                'name' => $twitchUser->getName(),
                'avatar_url' => $twitchUser->getAvatar(),
                'twitch_refresh_token' => $twitchUser->refreshToken,
            ]);

        if ($user->deleted_at) {
            return to_route('login')->withErrors(['login' => __('user.disabled')]);
        }

        $mfa = app(AppAuthentication::class);

        $request->session()->regenerate();
        $request->session()->put('twitch_access_token', $twitchUser->token);

        if ($mfa->isEnabled($user)) {
            $request->session()->put('auth.2fa.id', $user->id);

            return to_route('auth.challenge');
        }

        Auth::login($user);

        if ($user->wasRecentlyCreated) {
            Inertia::flash('showTwitchPermissionsPrompt', true);

            if (User::count() === 1) {
                $user->syncRoles([1]);
            }
        }

        return redirect()->intended(route('dashboard'));
    })
        ->middleware(['throttle:login'])
        ->name('auth.callback');
});

Route::post('logout', static function (Request $request) {
    auth()->logout();

    if ($request->hasSession()) {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }

    return to_route('home');
})
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
