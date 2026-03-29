<?php

declare(strict_types=1);

namespace App\Services\Twitch\Contracts;

interface TwitchDtoInterface
{
    public static function from(array $data): static;

    /** @return list<static> */
    public static function fromCollection(array $response): array;

    public function toModel(array $extra = []): array;
}
