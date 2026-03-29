<?php

declare(strict_types=1);

use App\Http\Middleware\AssignRequestId;
use App\Http\Middleware\FeatureFlagGuard;
use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\Localization;
use App\Http\Middleware\StagingGateMiddleware;
use App\Http\Middleware\ValidateSecFetchHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware('stateless')
                ->group(base_path('routes/stateless.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->encryptCookies(except: ['appearance', 'sidebar_state', 'vheart_cookie_consent']);

        $middleware->web(
            append: [
                ValidateSecFetchHeaders::class,
                HandleAppearance::class,
                AddLinkHeadersForPreloadedAssets::class,
            ], prepend: [
                StagingGateMiddleware::class,
                Localization::class,
            ], remove: [
                ValidateCsrfToken::class,
            ]);

        $middleware->appendToPriorityList(
            StartSession::class,
            StagingGateMiddleware::class,
        );

        $middleware->prependToPriorityList(
            SubstituteBindings::class,
            FeatureFlagGuard::class
        );

        $middleware->prependToPriorityList(
            StartSession::class,
            FeatureFlagGuard::class
        );

        $middleware->group('stateless', [
            SubstituteBindings::class,
        ]);

        $middleware->prepend(AssignRequestId::class);
        $middleware->trustProxies('*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        Integration::handles($exceptions);

        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;

            if (! $request->expectsJson()
                && $request->header('Referer')
                && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])
            ) {
                return back()
                    ->with('error', "Too many requests. Try again in $retryAfter seconds.")
                    ->withInput();
            }

            return response('Too many requests.', 429, $e->getHeaders());
        });
    })->create();
