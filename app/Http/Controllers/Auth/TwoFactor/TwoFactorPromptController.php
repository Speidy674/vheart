<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\TwoFactor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\TwoFactorChallengeRequest;
use App\Models\User;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;

/**
 * Shows the 2FA Verification Prompt
 */
class TwoFactorPromptController extends Controller implements HasMiddleware
{
    public function __invoke(TwoFactorChallengeRequest $request, AppAuthentication $mfa): View|RedirectResponse
    {
        $userId = $request->getChallengedUserId();
        $user = User::query()->find($userId);

        if (! $userId || ! $user || ! $mfa->isEnabled($user)) {
            return to_route('login');
        }

        return view('auth.two-factor');
    }

    public static function middleware(): array
    {
        return ['guest'];
    }
}
