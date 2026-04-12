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

        if ($userId = $request->cookie($cookieSession, false)) {
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
                Cookie::make($cookieSession, $twitchUser->id, 60 * 24),
                Cookie::forget($cookieIntended),
            ]);
        }

        return Socialite::driver('twitch')
            ->redirect()
            ->withCookie(Cookie::make($cookieIntended, $request->fullUrl(), 10));
    }
}
