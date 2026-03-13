<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TwitchAvatarCast implements CastsAttributes
{
    private const string BASE_URL = 'https://static-cdn.jtvnw.net/';

    private const array DIRECTORIES = [
        'u' => 'jtv_user_pictures',
        'd' => 'user-default-pictures-uv',
    ];

    private const array EXTENSIONS = [
        'p' => 'png',
        'j' => 'jpeg',
        'x' => 'jpg',
        'g' => 'gif',
    ];

    public static function decode(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        if (str_starts_with($value, 'http')) {
            return $value;
        }

        $parts = explode(':', $value);

        if (count($parts) < 3) {
            return $value;
        }

        $directory = self::DIRECTORIES[$parts[0]] ?? self::DIRECTORIES['u'];
        $extension = self::EXTENSIONS[$parts[1]] ?? self::EXTENSIONS['p'];
        $identifier = $parts[2].'-profile_image'.(isset($parts[3]) ? "-{$parts[3]}" : '');

        return self::BASE_URL."{$directory}/{$identifier}-300x300.{$extension}";
    }

    public static function encode(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        $directoryPattern = implode('|', self::DIRECTORIES);
        $extensionPattern = implode('|', self::EXTENSIONS);
        $baseUrl = preg_quote(self::BASE_URL, '/');
        $pattern = "/^{$baseUrl}({$directoryPattern})\/(.+?)-profile_image(?:-(.+?))?-300x300\.({$extensionPattern})$/";

        if (preg_match($pattern, $value, $matches)) {
            $directoryFlag = array_search($matches[1], self::DIRECTORIES) ?: 'u';
            $extensionFlag = array_search($matches[4], self::EXTENSIONS) ?: 'p';

            $id = $matches[2];
            $hash = isset($matches[3]) && $matches[3] !== '' ? ":{$matches[3]}" : '';

            return "{$directoryFlag}:{$extensionFlag}:{$id}{$hash}";
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return static::decode($value);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return static::encode($value);
    }
}
