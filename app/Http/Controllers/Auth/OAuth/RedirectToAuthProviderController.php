<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\OAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

/**
 * Redirects the user to Twitch to initialize the OAuth Flow
 *
 * Name is neutral as we may allow other providers in the future
 */
class RedirectToAuthProviderController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        return Socialite::driver('twitch')
            ->scopes([
                'channel:read:vips', // Required to access VIP list
                'user:read:moderated_channels', // Required to see who a user moderates for
                'channel:manage:clips', // Required to allow the VHeart team to download clips for processing
            ])
            ->redirect();
    }
}
