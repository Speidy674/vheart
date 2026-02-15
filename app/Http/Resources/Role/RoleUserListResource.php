<?php

declare(strict_types=1);

namespace App\Http\Resources\Role;

use App\Models\Role;
use Illuminate\Http\Request;

/**
 * Full Role data
 *
 * @mixin Role
 */
class RoleUserListResource extends MinimalRoleResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'weight' => $this->weight,
            'users' => $this->users->toResourceCollection(),
        ]);
    }
}
