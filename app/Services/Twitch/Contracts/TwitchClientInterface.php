<?php

declare(strict_types=1);

namespace App\Services\Twitch\Contracts;

use App\Services\Twitch\Exceptions\TwitchApiException;
use App\Services\Twitch\TwitchUserContext;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Promises\LazyPromise;
use Illuminate\Http\Client\Response;

interface TwitchClientInterface
{
    /** @throws TwitchApiException|ConnectionException */
    public function get(string $endpoint, array $params = []): PromiseInterface|LazyPromise|Response;

    /** @throws TwitchApiException|ConnectionException */
    public function post(string $endpoint, array $data = []): PromiseInterface|LazyPromise|Response;

    public function userContext(): ?TwitchUserContext;
}
