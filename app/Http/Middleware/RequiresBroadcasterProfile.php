<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Broadcaster\Broadcaster;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * If the user has no broadcaster profile setup this will redirect them to the onboarding instead (remembering the intended route)
 */
class RequiresBroadcasterProfile
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Broadcaster::where('id', $request->user()?->id)->exists()) {
            // we don't want infinite redirect loops with this economy (just in case it happens to be on that route too)
            if ($request->routeIs('dashboard.onboarding')) {
                return $next($request);
            }

            return redirect()->guest(route('dashboard.onboarding'));
        }

        return $next($request);
    }
}
