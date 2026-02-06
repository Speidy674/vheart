<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

/**
 * Strict Security Isolation & CSRF Protection
 *
 * CSRF: Requests to unsafe methods are blocked if Sec-Fetch-Site is not trusted.
 * Isolation: Cross-site resource access is blocked unless it is a standard navigation.
 *
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Sec-Fetch-Site
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Sec-Fetch-Dest
 * @see https://datatracker.ietf.org/doc/html/rfc7231#section-4.2.1
 */
class ValidateSecFetchHeaders
{
    /**
     * Trusted CSRF Origins
     * Only `same-origin` and `same-site` should be used as any other option would make it unsafe.
     *
     * - `same-origin`: Only allow requests from the same origin/domain.
     * - `same-site`: Only allow requests from the same origin/domain and subdomains.
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Sec-Fetch-Site
     *
     * @var string[]
     */
    protected static array $TrustedOrigins = ['same-origin'];

    /**
     * Blocked Destinations for Resource Isolation
     *
     * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/Sec-Fetch-Dest
     *
     * @var string[]
     */
    protected static array $BlockedDestinations = ['frame', 'iframe', 'object', 'embed'];

    /**
     * @param  Closure(Request): (SymfonyResponse)  $next
     */
    public function handle(Request $request, Closure $next): SymfonyResponse
    {
        if (app()->runningUnitTests()) {
            return $this->setVary($next($request));
        }

        $headers = $request->headers;
        $site = $headers->get('Sec-Fetch-Site');

        if ($site === null) {
            return $this->fail('Your browser is too old or a privacy extension is stripping the "Sec-Fetch-Site" header. Please disable strict privacy tools or update your browser.');
        }

        $isTrusted = in_array($site, self::$TrustedOrigins, true);

        // CSRF Protection
        if (! $request->isMethodSafe()) {
            if (! $isTrusted) {
                return $this->fail('Cross-site request blocked.');
            }

            return $this->setVary($next($request));
        }

        // Resource Isolation
        if ($isTrusted || in_array($site, ['same-site', 'none'], true)) {
            return $this->setVary($next($request));
        }

        $mode = $headers->get('Sec-Fetch-Mode');
        $dest = $headers->get('Sec-Fetch-Dest');

        if (
            $mode === 'navigate' &&
            ! in_array($dest, self::$BlockedDestinations, true)
        ) {
            return $this->setVary($next($request));
        }

        return $this->fail('Cross-site resource access forbidden.');
    }

    private function setVary(SymfonyResponse $response): SymfonyResponse
    {
        $response->setVary('Sec-Fetch-Site, Sec-Fetch-Mode, Sec-Fetch-Dest', false);

        return $response;
    }

    private function fail(string $message = 'Access forbidden.'): SymfonyResponse
    {
        return new SymfonyResponse($message, 403, [
            'Vary' => 'Sec-Fetch-Site, Sec-Fetch-Mode, Sec-Fetch-Dest',
        ]);
    }
}
