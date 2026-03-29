<?php

declare(strict_types=1);

namespace App\Providers;

use App\Services\Twitch\Contracts\TwitchClientInterface;
use App\Services\Twitch\Exceptions\TwitchApiException;
use App\Services\Twitch\TwitchClient;
use App\Services\Twitch\TwitchService;
use App\Services\Twitch\TwitchTokenManager;
use Illuminate\Support\ServiceProvider;

class TwitchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(function (): TwitchTokenManager {
            $clientId = config('services.twitch.client_id', '');
            $clientSecret = config('services.twitch.client_secret', '');

            if ($clientId === '' || $clientSecret === '') {
                throw TwitchApiException::notConfigured();
            }

            return new TwitchTokenManager(
                clientId: $clientId,
                clientSecret: $clientSecret,
            );
        });

        $this->app->scoped(TwitchClient::class, fn (): TwitchClient => new TwitchClient(
            clientId: config('services.twitch.client_id'),
            tokens: $this->app->make(TwitchTokenManager::class),
        ));

        $this->app->bind(TwitchClientInterface::class, TwitchClient::class);

        $this->app->scoped(TwitchService::class, fn (): TwitchService => new TwitchService(
            client: $this->app->make(TwitchClient::class),
        ));
    }
}
