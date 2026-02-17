<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\TwoFactor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorSubmitRequest;
use App\Models\User;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Verifies the 2FA input is valid and authenticates the user if so
 */
class TwoFactorVerificationController extends Controller implements HasMiddleware
{
    public function __invoke(TwoFactorSubmitRequest $request, AppAuthentication $mfa): SymfonyResponse
    {
        $userId = $request->getChallengedUserId();
        $user = User::query()->find($userId);

        if (! $user || ! $mfa->isEnabled($user)) {
            return to_route('login');
        }

        $request->ensureCodeIsValid($user);
        $request->session()->regenerate();
        Auth::login($user);

        $url = $request->session()->pull('url.intended', route('home'));

        return Inertia::location($url);
    }

    public static function middleware(): array
    {
        return [
            'guest',
            'throttle:two-factor',
        ];
    }
}
