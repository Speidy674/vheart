<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\Twitch\TwitchService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class BroadcasterDashboardAcces
{
    private TwitchService $twitchService;

    public function __construct()
    {
        $this->twitchService = new TwitchService;
        $this->twitchService->onUserTokenRefresh(function ($token) {
            session()->put('twitch_access_token', $token);
        });
    }

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authUser = $request->user();
        $broadcaster = $request->route('user');
        if (empty($broadcaster)) {
            return Redirect::route('home');
        }

        if ($broadcaster->id === $authUser->id) {
            return $next($request);
        }

        if ($this->twitchService->asUser($authUser, session()?->get('twitch_access_token'))->isModeratorFor($broadcaster)) {
            return $next($request);
        }

        return Redirect::route('home');
    }
}
