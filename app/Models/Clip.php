<?php

declare(strict_types=1);

namespace App\Models;

use App\Http\Resources\PublicClipResource;
use App\Models\Clip\Compilation;
use App\Models\Clip\CompilationClip;
use App\Models\Clip\Tag;
use App\Models\Scopes\ClipPermissionScope;
use App\Models\Traits\Reportable;
use App\Policies\ClipPolicy;
use Database\Factories\ClipFactory;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Vite;

#[ScopedBy(ClipPermissionScope::class)]
#[UseResource(PublicClipResource::class)]
#[UsePolicy(ClipPolicy::class)]
class Clip extends Model
{
    /** @use HasFactory<ClipFactory> */
    use HasFactory, Reportable;

    public function broadcaster(): BelongsTo
    {
        return $this->BelongsTo(User::class)
            ->withDefault(['name' => 'N/A', 'avatar_url' => Vite::asset('resources/images/png/cat.png')]);
    }

    public function creator(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function submitter(): BelongsTo
    {
        return $this->BelongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class)
            ->withDefault(['title' => 'Pending']);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'clip_tags');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function compilations(): BelongsToMany
    {
        return $this->belongsToMany(Compilation::class)
            ->using(CompilationClip::class)
            ->withPivot(CompilationClip::getPivotColumns())
            ->withTimestamps();
    }

    public function claimer(): BelongsTo
    {
        return $this->BelongsTo(User::class, 'claimed_by');
    }

    public function getReportableTitleAttribute(): string
    {
        return 'title';
    }
}
