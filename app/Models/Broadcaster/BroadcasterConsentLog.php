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
            [$version] = explode('.', $this->checksum, 2);

            $key = collect([app('encrypter')->getKey(), ...config('app.previous_keys', [])])
                ->map(fn (string $k): string|false => str_starts_with($k, 'base64:') ? base64_decode(mb_substr($k, 7)) : $k)
                ->first(fn (string $k): bool => str_starts_with(hash('sha256', $k), $version));

            if ($key === null) {
                return false;
            }

            return hash_equals($this->checksum, self::computeChecksum($this, $key));
        } catch (JsonException $e) {
            report($e);

            return false;
        }
    }

    protected static function booted(): void
    {
        static::creating(static function (self $log): void {
            $log->checksum = self::computeChecksum($log, app('encrypter')->getKey());
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
    private static function computeChecksum(self $log, string $key): string
    {
        $version = mb_substr(hash('sha256', $key), 0, 8);

        $hmac = hash_hmac('sha256', implode('|', [
            $log->broadcaster_id,
            json_encode($log->state, JSON_THROW_ON_ERROR),
            $log->changed_by,
            $log->change_reason ?? '',
            $log->changed_at,
        ]), $key);

        return "{$version}.{$hmac}";
    }
}
