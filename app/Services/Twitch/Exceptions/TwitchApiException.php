<?php

declare(strict_types=1);

namespace App\Services\Twitch\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class TwitchApiException extends Exception implements HttpExceptionInterface
{
    private function __construct(
        string $message,
        int $code = 500,
    ) {
        parent::__construct($message, $code);
    }

    public static function authenticationFailed(Response $response): self
    {
        return new self(
            "Could not authenticate application with Twitch: {$response->status()} {$response->reason()}, {$response->json()['message']}",
            401,
        );
    }

    public static function notConfigured(): self
    {
        return new self('Twitch client_id or client_secret is not configured.', 500);
    }

    public static function requestFailed(Response $response): self
    {
        return new self(
            "Twitch API request failed: {$response->status()} {$response->reason()}, {$response->json()['message']}",
            $response->status(),
        );
    }

    public static function userTokenRefreshFailed(Response $response): self
    {
        return new self(
            "Failed to refresh Twitch user access token: {$response->status()} {$response->reason()}, {$response->json()['message']}",
            401,
        );
    }

    public function getStatusCode(): int
    {
        return $this->getCode();
    }

    public function getHeaders(): array
    {
        return [];
    }
}
