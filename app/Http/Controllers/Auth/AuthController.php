<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\CarbonInterval;
use Exception;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function login(Request $request): Response
    {
        return Inertia::render('auth/login', [
            'status' => $request->session()->get('status'),
        ]);
    }

    public function redirect(): RedirectResponse
    {
        return Socialite::driver('twitch')
            ->scopes([
                'channel:read:vips', // Required to access VIP list
                'user:read:moderated_channels', // Required to see who a user moderates for
                'channel:manage:clips', // Required to allow the VHeart team to download clips for processing
            ])
            ->redirect();
    }

    public function callback(Request $request, AppAuthentication $mfa): RedirectResponse
    {
        try {
            $twitchUser = Socialite::driver('twitch')->user();
        } catch (Exception) {
            return to_route('login')
                ->with('error', __('auth.oauth_error_try_again'));
        }

        $userCreatedAt = Date::parse($twitchUser->user['created_at']);
        $userAgeMinimum = CarbonInterval::fromString(config('auth.required_account_age'));

        if ($userCreatedAt->add($userAgeMinimum)->isFuture()) {
            return to_route('login')
                ->withErrors(['login' => __('auth.account_created_too_early')]);
        }

        /** @var User $user */
        $user = User::withTrashed()->updateOrCreate([
            'id' => $twitchUser->getId(),
        ],
            [
                'name' => $twitchUser->getName(),
                'avatar_url' => $twitchUser->getAvatar(),
                'twitch_refresh_token' => $twitchUser->refreshToken,
            ]);

        if ($user->trashed()) {
            return to_route('login')
                ->withErrors(['login' => __('user.disabled')]);
        }

        $request->session()->regenerate();
        $request->session()->put('twitch_access_token', $twitchUser->token);

        if ($mfa->isEnabled($user)) {
            $request->session()->put('auth.2fa.id', $user->id);

            return to_route('auth.challenge');
        }

        Auth::login($user);

        if ($user->wasRecentlyCreated) {
            Inertia::flash('showTwitchPermissionsPrompt', true);

            if (User::query()->whereNot('id', 0)->count() === 1) {
                $user->syncRoles([0]);
            }
        }

        return redirect()->intended(route('dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return to_route('home');
    }
}
