<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TwitchClipThumbnailCast implements CastsAttributes
{
    private const string BASE_URL = 'https://static-cdn.jtvnw.net/';

    private const array DIRECTORIES = [
        'p' => 'twitch-clips-thumbnails-prod',
        'c' => 'twitch-clips',
        'v' => 'twitch-video-assets/twitch-vap-video-assets-prod-us-west-2',
    ];

    private const array EXTENSIONS = [
        'x' => 'jpg',
        'p' => 'png',
        'j' => 'jpeg',
        'g' => 'gif',
    ];

    public static function decode(?string $value, ?string $clipId = null): ?string
    {
        if (! $value) {
            return null;
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        $parts = explode(':', $value, 4);
        $partsCount = count($parts);

        if ($partsCount !== 3 && $partsCount !== 4) {
            return $value;
        }

        $directoryFlag = $parts[0];
        $directory = self::DIRECTORIES[$directoryFlag] ?? self::DIRECTORIES['p'];
        $extension = self::EXTENSIONS[$parts[1]] ?? self::EXTENSIONS['x'];

        if ($partsCount === 3) {
            $size = '480x272';
            $id = $parts[2];
        } else {
            $size = $parts[2];
            $id = $parts[3];
        }

        if ($clipId) {
            $id = str_replace('{}', $clipId, $id);
        }

        if ($directoryFlag === 'c') {
            $suffix = '-preview-';
        } elseif ($directoryFlag === 'v') {
            $suffix = '/landscape/thumb/thumb-0000000000-';
        } else {
            $suffix = '/preview-';
        }

        return self::BASE_URL."{$directory}/{$id}{$suffix}{$size}.{$extension}";
    }

    public static function encode(?string $value, ?string $clipId = null): ?string
    {
        if (! $value) {
            return null;
        }

        $directoryPattern = implode('|', self::DIRECTORIES);
        $extensionPattern = implode('|', self::EXTENSIONS);
        $baseUrl = preg_quote(self::BASE_URL, '/');

        $pattern = "~^{$baseUrl}({$directoryPattern})/(.+?)(?:[/-]preview-|/landscape/thumb/thumb-0000000000-)(\d+x\d+)\.({$extensionPattern})$~";

        if (preg_match($pattern, $value, $matches)) {
            $directoryFlag = array_search($matches[1], self::DIRECTORIES) ?: 'p';
            $extensionFlag = array_search($matches[4], self::EXTENSIONS) ?: 'x';

            $id = $matches[2];
            $size = $matches[3];

            if ($clipId) {
                $id = str_replace($clipId, '{}', $id);
            }

            return "{$directoryFlag}:{$extensionFlag}:{$size}:{$id}";
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return static::decode($value, $model->twitch_id ?? null);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return static::encode($value, $model->twitch_id ?? null);
    }
}
