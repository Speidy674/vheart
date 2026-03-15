<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Broadcaster\Broadcaster;
use Closure;
use Filament\Facades\Filament;
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
        $tenantId = $request->route('tenant');

        if (! $tenantId && ! Broadcaster::where('id', $request->user()?->id)->exists()) {
            return redirect()->guest(route('dashboard.onboarding'));
        }

        if (! is_numeric($tenantId)) {
            return redirect()->guest(Filament::getPanel('dashboard')->getUrl());
        }

        if (! Broadcaster::where('id', $tenantId)->exists()) {
            return redirect()->guest(Filament::getPanel('dashboard')->getUrl());
        }

        return $next($request);
    }
}
