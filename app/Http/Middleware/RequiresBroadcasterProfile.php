<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\FeatureFlag;
use App\Models\Broadcaster\Broadcaster;
use App\Support\FeatureFlag\Feature;
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
        if (! Feature::isActive(FeatureFlag::UserDashboard)) {
            return abort(404);
        }
        $user = $request->user();
        $tenantId = $request->route('tenant');
        $isSelfTenant = ((int) $tenantId) === $user?->id;

        if ((! $tenantId || $isSelfTenant) && ! Broadcaster::where('id', $user?->id)->exists()) {
            return redirect()->guest(route('dashboard.onboarding'));
        }

        return $next($request);
    }
}
