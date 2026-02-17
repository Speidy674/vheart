<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorChallengeRequest;
use App\Http\Requests\Auth\TwoFactorSubmitRequest;
use App\Models\User;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
class TwoFactorController extends Controller
{
    public function index(TwoFactorChallengeRequest $request, AppAuthentication $mfa): InertiaResponse|RedirectResponse
    {
        $userId = $request->getChallengedUserId();
        $user = User::query()->find($userId);

        if (! $userId || ! $user || ! $mfa->isEnabled($user)) {
            return to_route('login');
        }

        return Inertia::render('auth/challenge');
    }

    public function store(TwoFactorSubmitRequest $request, AppAuthentication $mfa): SymfonyResponse
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
}
