<?php

declare(strict_types=1);

namespace App\Services\Twitch\Data;

use App\Services\Twitch\Contracts\TwitchDtoInterface;

/* @link https://dev.twitch.tv/docs/api/reference#get-clips-download */
readonly class ClipDownloadDto implements TwitchDtoInterface
{
    public function __construct(
        public string $clipId, // clip_id
        public ?string $landscapeDownloadUrl, // landscape_download_url
        public ?string $portraitDownloadUrl, // portrait_download_url
    ) {}

    public static function from(array $data): static
    {
        return new static(
            clipId: $data['clip_id'],
            landscapeDownloadUrl: $data['landscape_download_url'] ?? null,
            portraitDownloadUrl: $data['portrait_download_url'] ?? null,
        );
    }

    /** @return list<static> */
    public static function fromCollection(array $response): array
    {
        return array_map(static::from(...), $response['data']);
    }

    public function toModel(array $extra = []): array
    {
        return $extra;
    }
}
