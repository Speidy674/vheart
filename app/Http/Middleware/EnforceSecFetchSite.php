<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Using modern browser features, this will ensure CSRF protection by simply looking at the Sec-Fetch-Site header
 * to check if we come from the same origin/site. This method allows us to get rid of the large XSRF token
 * cookie saving us bandwidth, processing and other annoying stuff like handling a XSRF cookie lol
 *
 * Only downside is that browsers that may not support our React version anyway will be blocked entirely.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Sec-Fetch-Site
 */
class EnforceSecFetchSite
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // read only stuff does not need this check
        if (in_array($request->getMethod(), ['GET', 'HEAD', 'OPTIONS'])) {
            return $next($request);
        }

        $secFetchSite = $request->headers->get('sec-fetch-site');
        abort_if($secFetchSite === null, 403, 'Your browser is too old or a privacy extension is stripping the "Sec-Fetch-Site" header. Please disable strict privacy tools or update your browser.');

        if ($secFetchSite === 'same-origin' || $secFetchSite === 'same-site') {
            return $next($request);
        }

        abort(403, 'Cross-site request forbidden.');
    }
}
