<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\Categories\BannedCategoriesScope;
use App\Policies\CategoryPolicy;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\URL;

#[ScopedBy(BannedCategoriesScope::class)]
#[UsePolicy(CategoryPolicy::class)]
#[UseResource(CategoryResource::class)]
class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    public const string PLACEHOLDER_BOX_ART = 'https://static-cdn.jtvnw.net/ttv-static/404_boxart-{width}x{height}.jpg';

    public const array Defaults = [
        'title' => 'Pending Category',
        'is_banned' => false,
        'box_art' => self::PLACEHOLDER_BOX_ART,
    ];

    public $incrementing = false;

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class, 'category_id', 'id');
    }

    public function getBoxArt(int $width = 188, int $height = 250): ?string
    {
        $boxArtUrl = $this->box_art ?? self::PLACEHOLDER_BOX_ART;

        return URL::signedRoute('image-proxy', ['url' => str_replace(['{width}', '{height}'], [$width, $height], $boxArtUrl)]);
    }
}
