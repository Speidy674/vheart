<?php

declare(strict_types=1);

namespace App\Services\Twitch\Data;

use App\Services\Twitch\Contracts\TwitchDtoInterface;
use App\Services\Twitch\Enums\TwitchBroadcasterLanguage;
use Carbon\CarbonImmutable;

/* @link https://dev.twitch.tv/docs/api/reference#search-channels */
readonly class ChannelDto implements TwitchDtoInterface
{
    public function __construct(
        public int $id,
        public string $login, // broadcaster_login
        public string $displayName, // display_name
        public TwitchBroadcasterLanguage $language, // broadcaster_language
        public int $gameId, // game_id
        public string $gameName, // game_name
        public bool $live, // is_live
        public array $tags,
        public string $thumbnailUrl, // thumbnail_url
        public string $title,
        public ?CarbonImmutable $startedAt, // started_at
    ) {}

    public static function from(array $data): static
    {
        return new static(
            id: (int) $data['id'],
            login: $data['broadcaster_login'],
            displayName: $data['display_name'],
            language: TwitchBroadcasterLanguage::tryFrom($data['broadcaster_language']) ?? TwitchBroadcasterLanguage::Other,
            gameId: (int) $data['game_id'],
            gameName: $data['game_name'],
            live: (bool) $data['is_live'],
            tags: $data['tags'] ?? [],
            thumbnailUrl: $data['thumbnail_url'],
            title: mb_trim($data['title']),
            startedAt: filled($data['started_at'] ?? null)
                ? CarbonImmutable::parse($data['started_at'])
                : null,
        );
    }

    /** @return list<static> */
    public static function fromCollection(array $response): array
    {
        return array_map(static::from(...), $response['data']);
    }

    public function toModel(array $extra = []): array
    {
        return array_merge([
            'id' => $this->id,
            'name' => $this->displayName,
        ], $extra);
    }
}
