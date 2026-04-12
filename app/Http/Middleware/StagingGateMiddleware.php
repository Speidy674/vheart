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
        $cookieAccess = $cookiePrefix.'_access';
        $cookieIntended = $cookiePrefix.'_intended';

        if ($request->cookie($cookieAccess, false)) {
            return $next($request);
        }

        $userId = $request->cookie($cookieSession, false);

        if ($userId && filter_var($userId, FILTER_VALIDATE_INT)) {
            abort_unless($this->userHasAnyRole((int) $userId), 403);
            Cookie::queue(Cookie::make($cookieAccess, '1', 60));

            return $next($request);
        }

        if ($userId !== false) {
            Cookie::queue(Cookie::forget($cookieSession));
        }

        if ($request->is('auth/twitch/callback')) {
            try {
                $twitchUser = Socialite::driver('twitch')->stateless()->user();
            } catch (Exception) {
                return Socialite::driver('twitch')->redirect();
            }

            abort_unless($this->userHasAnyRole($twitchUser->id), 403);

            $intendedUrl = $request->cookie($cookieIntended, route('home'));

            return redirect()->intended($intendedUrl)->withCookies([
                Cookie::make($cookieSession, $twitchUser->id, 60 * 24),
                Cookie::make($cookieAccess, '1', 60),
                Cookie::forget($cookieIntended),
            ]);
        }

        return Socialite::driver('twitch')
            ->redirect()
            ->withCookie(Cookie::make($cookieIntended, $request->fullUrl(), 10));
    }

    private function userHasAnyRole(int|string $userId): bool
    {
        return DB::table('user_roles')
            ->where('user_id', $userId)
            ->exists();
    }
}
