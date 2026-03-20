<?php

declare(strict_types=1);

namespace App\Models\Broadcaster;

use App\Enums\Broadcaster\BroadcasterConsent;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Model;
use JsonException;
use LogicException;

class BroadcasterConsentLog extends Model
{
    public $timestamps = false;

    public function isValid(): bool
    {
        try {
            return $this->checksum === self::computeChecksum($this);
        } catch (JsonException $e) {
            report($e);

            return false;
        }
    }

    protected static function booted(): void
    {
        static::creating(static function (self $log): void {
            $log->checksum = self::computeChecksum($log);
        });

        $immutable = static fn () => throw new LogicException('Consent logs are immutable.');

        static::updating($immutable);
        static::deleting($immutable);
    }

    protected function casts(): array
    {
        return [
            'state' => AsEnumCollection::of(BroadcasterConsent::class),
            'changed_at' => 'immutable_datetime',
        ];
    }

    /**
     * @throws JsonException
     */
    private static function computeChecksum(self $log): string
    {
        return hash_hmac('sha256', implode('|', [
            $log->broadcaster_id,
            json_encode($log->state, JSON_THROW_ON_ERROR),
            $log->changed_by,
            $log->change_reason,
            $log->changed_at,
        ]), config('app.key'));
    }
}
