<?php

declare(strict_types=1);

namespace App\Models\Broadcaster;

use App\Enums\Broadcaster\RemovalRequestStatus;
use App\Models\Clip;
use App\Models\User;
use Database\Factories\Broadcaster\RemovalRequestFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RemovalRequest extends Model
{
    /** @use HasFactory<RemovalRequestFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Broadcaster, $this>
     */
    public function broadcaster(): BelongsTo
    {
        return $this->belongsTo(Broadcaster::class);
    }

    /**
     * @return BelongsTo<Clip, $this>
     */
    public function clip(): BelongsTo
    {
        return $this->belongsTo(Clip::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function claimer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'claimed_by');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function isResolved(): bool
    {
        return $this->status->isResolved();
    }

    protected function casts(): array
    {
        return [
            'status' => RemovalRequestStatus::class,
            'claimed_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }
}
