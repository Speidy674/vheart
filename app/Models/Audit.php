<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\AuditFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Audit extends Model
{
    /** @use HasFactory<AuditFactory> */
    use HasFactory;

    use MassPrunable;

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function causer(): MorphTo
    {
        return $this->morphTo();
    }

    // Because Audit logs can get massive over time, im just gonna prune everything >90 days old
    // we wont need anything older than this as audit logs usually only get used if something bad happened recently
    // and permanently storing them would explode the database in size
    public function prunable(): self
    {
        return static::where('created_at', '<=', now()->subDays(90));
    }

    protected function casts(): array
    {
        return [
            'old' => 'array',
            'new' => 'array',
            'tags' => 'array',
        ];
    }
}
