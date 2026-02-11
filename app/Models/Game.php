<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Resources\CategoryResource;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UseResource(CategoryResource::class)]
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory;

    public const string PLACEHOLDER_BOX_ART = 'https://static-cdn.jtvnw.net/ttv-static/404_boxart-{width}x{height}.jpg';

    public $incrementing = false;

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class, 'game_id', 'id');
    }

    public function getBoxArt(int $width = 188, int $height = 250): ?string
    {
        $boxArtUrl = $this->box_art ?? self::PLACEHOLDER_BOX_ART;

        return route('image-proxy', ['url' => str_replace(['{width}', '{height}'], [$width, $height], $boxArtUrl)]);
    }
}
