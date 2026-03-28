<?php

declare(strict_types=1);

namespace App\Models\Broadcaster;

use App\Models\Category;
use App\Models\Traits\Auditable;
use App\Models\User;
use App\Policies\Broadcaster\BroadcasterSubmissionFilterPolicy;
use Database\Factories\Broadcaster\BroadcasterSubmissionFilterFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[UsePolicy(BroadcasterSubmissionFilterPolicy::class)]
class BroadcasterSubmissionFilter extends Model
{
    use Auditable;

    /** @use HasFactory<BroadcasterSubmissionFilterFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Broadcaster, $this>
     */
    public function broadcaster(): BelongsTo
    {
        return $this->belongsTo(Broadcaster::class);
    }

    /**
     * @return MorphTo<User|Category|Model, $this>
     */
    public function filterable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'state' => 'boolean',
        ];
    }
}
