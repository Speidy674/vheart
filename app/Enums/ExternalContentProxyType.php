<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Category;
use App\Models\Clip;
use App\Models\Contracts\ExternalProxyable;
use App\Models\User;
use Deprecated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

enum ExternalContentProxyType: string
{
    case TwitchClip = 'clip';
    case TwitchUser = 'user';
    case TwitchCategory = 'category';

    #[Deprecated]
    public static function fromModel(Model $model): ?self
    {
        return $model instanceof ExternalProxyable ? $model->getProxyType() : null;
    }

    #[Deprecated]
    public static function toProxyUrl(?Model $model, ?int $width = null, ?int $height = null): ?string
    {
        if (! $model) {
            return null;
        }

        return $model instanceof ExternalProxyable ? $model->proxiedContentUrl($width, $height) : null;
    }

    /**
     * @return class-string<Model&ExternalProxyable>
     */
    public function modelClass(): string
    {
        return match ($this) {
            self::TwitchCategory => Category::class,
            self::TwitchClip => Clip::class,
            self::TwitchUser => User::class,
        };
    }

    public function getResource(string $identifier): string
    {
        $size = null;
        $dbId = $identifier;
        $modelClass = $this->modelClass();

        if ($modelClass::supportsProxyDynamicSize() && Str::contains($identifier, '-')) {
            $size = Str::afterLast($identifier, '-');
            $dbId = Str::beforeLast($identifier, '-');
        }

        $model = $modelClass::where($modelClass::getProxyIdentifierColumn(), $dbId)->firstOrFail();

        $url = $model->getAttribute($modelClass::getProxyUrlColumn());

        if ($size && $url) {
            return str_replace('{width}x{height}', $size, $url);
        }

        return $url;
    }
}
