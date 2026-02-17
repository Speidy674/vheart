<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorController extends Controller
{
    public function index(Request $request): Response|RedirectResponse
    {
        $userId = $request->session()->get('auth.2fa.id', false);
        $user = User::query()->find($userId);
        $mfa = app(AppAuthentication::class);

        if (! $userId || ! $user || ! $mfa->isEnabled($user)) {
            return to_route('login');
        }

        return Inertia::render('auth/challenge');
    }

    public function store(Request $request)
    {
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

            $url = $request->session()->pull('url.intended', route('home'));

            return Inertia::location($url);
        }

        throw ValidationException::withMessages([
            'code' => 'Incorrect code',
            'recovery_code' => 'Incorrect code',
        ]);
    }
}
