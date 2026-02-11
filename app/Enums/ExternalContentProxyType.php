<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Clip;
use App\Models\Game;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;

enum ExternalContentProxyType: string
{
    case TwitchClip = 'clip';
    case TwitchUser = 'user';
    case TwitchCategory = 'category';

    public static function fromModel(Model $model): ?self
    {
        return match (true) {
            $model instanceof Game => self::TwitchCategory,
            $model instanceof Clip => self::TwitchClip,
            $model instanceof User => self::TwitchUser,
            default => null,
        };
    }

    public static function toProxyUrl(?Model $model, ?int $width = null, ?int $height = null): ?string
    {
        if (! $model) {
            return null;
        }

        if ($model instanceof User && $model->id === 0) {
            return Vite::asset('resources/images/png/cat.png');
        }

        $type = self::fromModel($model);

        if (! $type) {
            return null;
        }

        $id = $model->getAttribute($type->identifierColumn());

        if ($width && $height && $type->supportsDynamicSize()) {
            $id = "{$id}-{$width}x{$height}";
        }

        return route('static-external', [
            'type' => $type->value,
            'identifier' => $id,
            'extension' => $type->extension(),
        ]);
    }

    public function getResource(string $identifier): string
    {
        $size = null;
        $dbId = $identifier;

        if ($this->supportsDynamicSize() && Str::contains($identifier, '-')) {
            $size = Str::afterLast($identifier, '-');
            $dbId = Str::beforeLast($identifier, '-');
        }

        $model = $this->modelClass()::where($this->identifierColumn(), $dbId)->firstOrFail();
        $url = $model->getAttribute($this->urlColumn());

        if ($size && $url) {
            return str_replace('{width}x{height}', $size, $url);
        }

        return $url;
    }

    public function extension(): string
    {
        return match ($this) {
            self::TwitchCategory, self::TwitchClip => 'jpg',
            self::TwitchUser => 'png',
        };
    }

    private function modelClass(): string
    {
        return match ($this) {
            self::TwitchCategory => Game::class,
            self::TwitchClip => Clip::class,
            self::TwitchUser => User::class,
        };
    }

    private function identifierColumn(): string
    {
        return match ($this) {
            self::TwitchClip => 'twitch_id',
            default => 'id',
        };
    }

    private function urlColumn(): string
    {
        return match ($this) {
            self::TwitchCategory => 'box_art',
            self::TwitchClip => 'thumbnail_url',
            self::TwitchUser => 'avatar_url',
        };
    }

    private function supportsDynamicSize(): bool
    {
        return $this === self::TwitchCategory;
    }
}
