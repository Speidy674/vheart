<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

class StagingGateMiddleware
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('staging')) {
            return $next($request);
        }

        $cookiePrefix = 'vheart_staging';
        $cookieSession = $cookiePrefix.'_session';
        $cookieIntended = $cookiePrefix.'_intended';

        $currentUser = $request->cookie($cookieSession, false);

        if ($currentUser) {
            $userId = (int) explode(':', $currentUser)[0];

            $hasRole = DB::table('user_roles')
                ->where('user_id', $userId)
                ->exists();

            abort_unless($hasRole, 403);

            return $next($request);
        }

        if ($request->is('auth/twitch/callback')) {
            try {
                $twitchUser = Socialite::driver('twitch')->stateless()->user();
            } catch (Exception) {
                return Socialite::driver('twitch')->redirect();
            }

            $intendedUrl = $request->cookie($cookieIntended, route('home'));

            return redirect()->intended($intendedUrl)->withCookies([
                Cookie::make($cookieSession, $twitchUser->id.':'.$twitchUser->user['login'], 60 * 24),
                Cookie::forget($cookieIntended),
            ]);
        }

        return Socialite::driver('twitch')
            ->redirect()
            ->withCookie(Cookie::make($cookieIntended, $request->fullUrl(), 10));
    }
}
