<?php

declare(strict_types=1);

namespace App\Models\Broadcaster;

use App\Enums\Broadcaster\BroadcasterConsent;
use App\Enums\Broadcaster\BroadcasterPermission;
use App\Models\Traits\Auditable;
use App\Models\User;
use Database\Factories\Broadcaster\BroadcasterFactory;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsEnumCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Broadcaster extends Model implements HasAvatar
{
    use Auditable;

    /** @use HasFactory<BroadcasterFactory> */
    use HasFactory;

    use SoftDeletes;

    public $incrementing = false;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    /**
     * @return HasMany<BroadcasterTeamMember, $this>
     */
    public function members(): HasMany
    {
        return $this->hasMany(BroadcasterTeamMember::class);
    }

    /**
     * @return HasMany<BroadcasterSubmissionFilter, $this>
     */
    public function filters(): HasMany
    {
        return $this->hasMany(BroadcasterSubmissionFilter::class);
    }

    public function proxiedContentUrl(): mixed
    {
        return $this->loadMissing('user')->user->proxiedContentUrl();
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->proxiedContentUrl();
    }

    protected static function booted(): void
    {
        // We have to manually sort them as json will preserve order of simple arrays rip
        // technically it doesnt matter but doesnt hurt either
        static::saving(static function (Broadcaster $broadcaster): void {
            if ($broadcaster->consent) {
                $broadcaster->consent = $broadcaster->consent
                    ->sortBy(fn (BroadcasterConsent $enum) => $enum->value)
                    ->values();
            }

            if ($broadcaster->twitch_mod_permissions) {
                $broadcaster->twitch_mod_permissions = $broadcaster->twitch_mod_permissions
                    ->sortBy(fn (BroadcasterPermission $enum) => $enum->value)
                    ->values();
            }
        });
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->loadMissing('user')->user->name,
        );
    }

    protected function casts(): array
    {
        return [
            'consent' => AsEnumCollection::of(BroadcasterConsent::class),
            'twitch_mod_permissions' => AsEnumCollection::of(BroadcasterPermission::class),
            'submit_user_allowed' => 'boolean',
            'submit_mods_allowed' => 'boolean',
            'submit_vip_allowed' => 'boolean',
            'onboarded_at' => 'datetime',
        ];
    }

    #[Scope]
    protected function whereGaveNoConsent(Builder $query): Builder
    {
        return $query->where(fn (Builder $query) => $query
            ->whereJsonLength('consent', '=', '0')
            ->orWhereNull('consent'));
    }

    /**
     * check if the broadcaster has given the consents or when no consents provided check if any consent is given
     */
    #[Scope]
    protected function whereGaveConsent(Builder $query, BroadcasterConsent|Collection|array|null $consents = null): Builder
    {
        if (! $consents) {
            return $query->whereJsonLength('consent', '>', '0');
        }

        if ($consents instanceof BroadcasterConsent) {
            $consents = [$consents];
        }

        return $query->whereJsonContains('consent', $consents);

    }
}
