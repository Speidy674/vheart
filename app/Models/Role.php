<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Permission;
use App\Http\Resources\Role\RoleResource;
use App\Models\Traits\Auditable;
use App\Policies\RolePolicy;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Spatie\Translatable\HasTranslations;

#[UseResource(RoleResource::class)]
#[UsePolicy(RolePolicy::class)]
class Role extends Model
{
    use Auditable;
    use HasFactory;
    use HasTranslations;

    public array $translatable = [
        'name',
        'desc',
    ];

    /**
     * @return BelongsToMany<User, $this, Pivot>
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles');
    }

    /**
     * @return HasMany<RolePermission, $this>
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(RolePermission::class);
    }

    public function getExtraAuditData(): array
    {
        return [
            'permissions' => $this->permissions()->pluck('permission')->map(fn (Permission $permission) => $permission->value)->toArray(),
        ];
    }

    protected function casts(): array
    {
        return [
            'name' => 'json:unicode',
            'desc' => 'json:unicode',
        ];
    }
}
