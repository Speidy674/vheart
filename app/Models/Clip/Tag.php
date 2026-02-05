<?php

declare(strict_types=1);

namespace App\Models\Clip;

use App\Http\Resources\Clip\TagResource;
use App\Models\Clip;
use Database\Factories\Clip\TagFactory;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UseResource(TagResource::class)]
class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class, 'clip_tags');
    }
}
