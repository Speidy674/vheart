<?php

declare(strict_types=1);

namespace App\Models;

use App\Policies\CategoryPolicy;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[UsePolicy(CategoryPolicy::class)]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
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

        return str_replace(['{width}', '{height}'], [$width, $height], $boxArtUrl);
    }
}
