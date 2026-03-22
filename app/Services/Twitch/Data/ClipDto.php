<?php

declare(strict_types=1);

namespace App\Services\Twitch\Data;

use App\Services\Twitch\Contracts\TwitchDtoInterface;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/* @link https://dev.twitch.tv/docs/api/reference#get-clips */
readonly class ClipDto implements TwitchDtoInterface
{
    public function __construct(
        public string $id,
        public string $url,
        public string $embedUrl, // embed_url
        public int $broadcasterId, // broadcaster_id
        public string $broadcasterName, // broadcaster_name
        public int $creatorId, // creator_id
        public string $creatorName, // creator_name
        public ?int $videoId, // video_id
        public int $gameId, // game_id
        public string $language,
        public string $title,
        public int $viewCount, // view_count
        public CarbonInterface $createdAt, // created_at
        public string $thumbnailUrl, // thumbnail_url
        public float $duration,
        public ?int $vodOffset, // vod_offset
        public bool $isFeatured, // is_featured
    ) {}

    public static function from(array $data): static
    {
        // Twitch returns an empty string "" for video_id if unavailable
        return new static(
            id: (string) $data['id'],
            url: $data['url'],
            embedUrl: $data['embed_url'],
            broadcasterId: (int) $data['broadcaster_id'],
            broadcasterName: $data['broadcaster_name'],
            creatorId: (int) $data['creator_id'],
            creatorName: $data['creator_name'],
            videoId: empty($data['video_id']) ? null : (int) $data['video_id'],
            gameId: (int) $data['game_id'],
            language: $data['language'],
            title: $data['title'],
            viewCount: (int) $data['view_count'],
            createdAt: Carbon::parse($data['created_at']),
            thumbnailUrl: $data['thumbnail_url'],
            duration: (float) $data['duration'],
            vodOffset: $data['vod_offset'] ?? null,
            isFeatured: (bool) $data['is_featured'],
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
            'twitch_id' => $this->id,
            'title' => $this->title,
            'thumbnail_url' => $this->thumbnailUrl,
            'broadcaster_id' => $this->broadcasterId,
            'creator_id' => $this->creatorId,
            'category_id' => $this->gameId,
            'vod_id' => $this->videoId,
            'vod_offset' => $this->vodOffset,
            'duration' => $this->duration,
            'language' => $this->language,
            'date' => $this->createdAt,
        ], $extra);
    }
}
