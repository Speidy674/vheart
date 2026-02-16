<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    public static int $SuperAdminRole = 0;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can(Permission::ViewAnyRole);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        return $user->can(Permission::ViewRole);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can(Permission::CreateRole);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        if ($role->id === self::$SuperAdminRole) {
            return false;
        }

        if ($role->weight >= $user->getRole()?->weight) {
            return $user->getRole()?->id === 0;
        }

        return $user->can(Permission::UpdateAnyRole);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        if ($role->id === self::$SuperAdminRole) {
            return false;
        }

        if ($role->weight >= $user->getRole()?->weight) {
            return $user->getRole()?->id === 0;
        }

        return $user->can(Permission::DeleteAnyRole);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return false;
    }
}
