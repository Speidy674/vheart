<?php

declare(strict_types=1);

namespace App\Services\Twitch\Data;

use App\Services\Twitch\Contracts\TwitchDtoInterface;
use InvalidArgumentException;

/**
 * A Simple (normalized) user object that twitch returns in specific cases
 *
 * should only be used where twitch returns a simplified user list in this format:
 * - <prefix>_id
 * - <prefix>_login
 * - <prefix>_name
 *
 * Where the prefix can be user, moderator or broadcaster
 *
 * @link https://dev.twitch.tv/docs/api/reference#get-moderators Get Moderators
 * @link https://dev.twitch.tv/docs/api/reference#get-moderated-channels Get Moderated Channels
 * @link https://dev.twitch.tv/docs/api/reference#get-vips Get VIPs
 */
readonly class SimpleUserDto implements TwitchDtoInterface
{
    private const array PREFIXES = [
        'user',
        'moderator',
        'broadcaster',
    ];

    public function __construct(
        public int $id, // user_id / broadcaster_id etc
        public string $login, // user_login / broadcaster_login etc
        public string $name, // user_name / broadcaster_name etc
        public string $type,
    ) {}

    public static function from(array $data): static
    {
        $prefix = self::getPrefix($data);

        return new static(
            id: (int) $data["{$prefix}_id"],
            login: $data["{$prefix}_login"],
            name: $data["{$prefix}_name"],
            type: $prefix,
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
            'name' => $this->name,
        ], $extra);
    }

    private static function getPrefix(array $data): string
    {
        foreach (self::PREFIXES as $prefix) {
            if (array_key_exists("{$prefix}_id", $data)) {
                return $prefix;
            }
        }

        throw new InvalidArgumentException(
            'Cannot detect user prefix from Twitch response. Available keys: '.implode(', ', array_keys($data))
        );
    }
}
