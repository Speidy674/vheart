<?php

declare(strict_types=1);

namespace App\Models\Clip;

use App\Models\Clip;
use Database\Factories\Clip\TagFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    /** @use HasFactory<TagFactory> */
    use HasFactory;

    public function clips(): BelongsToMany
    {
        return $this->belongsToMany(Clip::class, 'clip_tags');
    }
}
