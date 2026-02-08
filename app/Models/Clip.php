<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Clips\CompilationStatus;
use App\Enums\ClipVoteType;
use App\Http\Resources\PublicClipResource;
use App\Models\Clip\Compilation;
use App\Models\Clip\CompilationClip;
use App\Models\Clip\Tag;
use App\Models\Scopes\ClipPermissionScope;
use App\Models\Traits\Reportable;
use App\Policies\ClipPolicy;
use Database\Factories\ClipFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Builder;
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
            ->withTrashed()
            ->withDefault(['name' => 'N/A', 'avatar_url' => Vite::asset('resources/images/png/cat.png')]);
    }

    public function creator(): BelongsTo
    {
        return $this->BelongsTo(User::class)->withTrashed();
    }

    public function submitter(): BelongsTo
    {
        return $this->BelongsTo(User::class)->withTrashed();
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

    /**
     * Exclude Clips that are attached to a published or scheduled Compilation
     */
    #[Scope]
    protected function whereNotPublished(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query->whereDoesntHave('compilations', function (Builder $q) {
                $q->whereIn('compilations.status', array_merge(
                    CompilationStatus::getPublicCases(),
                    [CompilationStatus::Scheduled]
                ));
            });
        });
    }

    /**
     * Exclude Clips that user has voted on
     */
    #[Scope]
    protected function whereNoVotesFrom(Builder $query, User|int $userOrId): Builder
    {
        $userId = $userOrId instanceof User ? $userOrId->id : $userOrId;

        return $query->whereDoesntHave('votes', fn (Builder $q) => $q->where('user_id', $userId));
    }

    /**
     * Include only Clips that user has voted on
     */
    #[Scope]
    protected function whereVotesFrom(Builder $query, User|int $userOrId): Builder
    {
        $userId = $userOrId instanceof User ? $userOrId->id : $userOrId;

        return $query->whereHas('votes', fn (Builder $q) => $q->where('user_id', $userId));
    }

    /**
     * Exclude Clips the user has Broadcasted
     */
    #[Scope]
    protected function whereNotBroadcastBy(Builder $query, User|int $userOrId): Builder
    {
        $userId = $userOrId instanceof User ? $userOrId->id : $userOrId;

        return $query->whereNot('broadcaster_id', $userId);
    }

    /**
     * Include only Clips the user has Broadcasted
     */
    #[Scope]
    protected function whereBroadcastBy(Builder $query, User|int $userOrId): Builder
    {
        $userId = $userOrId instanceof User ? $userOrId->id : $userOrId;

        return $query->where('broadcaster_id', $userId);
    }

    /**
     * Counts public votes as `public_votes`.
     */
    #[Scope]
    protected function withPublicVoteCount(Builder $query): Builder
    {
        return $query->withCount(
            [
                'votes as public_votes' => function (Builder $query) {
                    $query->where('type', ClipVoteType::Public);
                },
            ]
        );
    }

    /**
     * Counts jury votes as `jury_votes`.
     */
    #[Scope]
    protected function withJuryVoteCount(Builder $query): Builder
    {
        return $query->withCount(
            [
                'votes as jury_votes' => function (Builder $query) {
                    $query->where('type', ClipVoteType::Jury);
                },
            ]
        );
    }

    /**
     * Counts Votes
     * - Jury votes as `jury_votes`
     * - Public votes as `public_votes`
     */
    #[Scope]
    protected function withVoteCount(Builder $query): Builder
    {
        return $query
            ->withJuryVoteCount()
            ->withPublicVoteCount();
    }
}
