<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

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
     * @param  Closure(Request): (SymfonyResponse)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (! $request->headers->has('X-Force-Sec-Fetch') && app()->runningUnitTests()) {
            return $next($request);
        }

        $headers = $request->headers;
        $site = $headers->get('Sec-Fetch-Site');
        abort_if($site === null, 403, 'Your browser is too old or a privacy extension is stripping the "Sec-Fetch-Site" header. Please disable strict privacy tools or update your browser.');

        if (in_array($site, ['same-origin', 'same-site', 'none'], true)) {
            return $this->setVary($next($request));
        }

        $mode = $headers->get('Sec-Fetch-Mode');
        $dest = $headers->get('Sec-Fetch-Dest');
        if (
            $mode === 'navigate' &&
            $request->isMethodSafe() &&
            ! in_array($dest, ['iframe', 'object', 'embed'], true)
        ) {
            return $this->setVary($next($request));
        }

        abort(403, 'Cross-site resource access forbidden.');
    }

    private function setVary(SymfonyResponse $response): SymfonyResponse
    {
        $response->setVary('Sec-Fetch-Site, Sec-Fetch-Mode, Sec-Fetch-Dest', false);

        return $response;
    }
}
